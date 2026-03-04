<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use SimpleXMLElement;
use ZipArchive;

class StudentImportParser
{
    private const XLSX_MAIN_NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
    private const XLSX_REL_NS = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';
    private const PACKAGE_REL_NS = 'http://schemas.openxmlformats.org/package/2006/relationships';

    public function parse(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return match ($extension) {
            'csv' => $this->parseCsv($file->getRealPath()),
            'xlsx' => $this->parseXlsx($file->getRealPath()),
            'pdf' => $this->parsePdf($file->getRealPath()),
            default => throw ValidationException::withMessages([
                'import_file' => 'Format file tidak didukung. Gunakan CSV, XLSX, atau PDF.',
            ]),
        };
    }

    private function parseCsv(string $path): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw ValidationException::withMessages([
                'import_file' => 'File CSV tidak bisa dibaca.',
            ]);
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);

            throw ValidationException::withMessages([
                'import_file' => 'File CSV kosong.',
            ]);
        }

        $delimiter = $this->detectCsvDelimiter($firstLine);
        rewind($handle);

        $headerRow = fgetcsv($handle, 0, $delimiter);
        if ($headerRow === false) {
            fclose($handle);

            throw ValidationException::withMessages([
                'import_file' => 'Header CSV tidak ditemukan.',
            ]);
        }

        $headers = $this->normalizeHeaders($headerRow);
        $rows = [];

        while (($values = fgetcsv($handle, 0, $delimiter)) !== false) {
            $row = $this->combineRow($headers, $values);

            if ($this->isEmptyRow($row)) {
                continue;
            }

            $rows[] = $row;
        }

        fclose($handle);

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    private function parseXlsx(string $path): array
    {
        if (!class_exists(ZipArchive::class)) {
            throw ValidationException::withMessages([
                'import_file' => 'Fitur impor XLSX membutuhkan ekstensi PHP ZipArchive (php-zip).',
            ]);
        }

        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw ValidationException::withMessages([
                'import_file' => 'File XLSX tidak bisa dibaca.',
            ]);
        }

        $sharedStrings = $this->extractSharedStrings($zip);
        $worksheetPath = $this->resolveFirstWorksheetPath($zip);
        $worksheetContent = $zip->getFromName($worksheetPath);

        if ($worksheetContent === false) {
            $zip->close();

            throw ValidationException::withMessages([
                'import_file' => 'Sheet pertama pada XLSX tidak ditemukan.',
            ]);
        }

        $worksheet = $this->loadXml($worksheetContent, 'File XLSX tidak valid.');
        $worksheet->registerXPathNamespace('main', self::XLSX_MAIN_NS);

        $rowNodes = $worksheet->xpath('//main:sheetData/main:row') ?: [];
        $matrix = [];

        foreach ($rowNodes as $rowNode) {
            $indexedCells = [];

            foreach ($rowNode->c as $cellNode) {
                $reference = (string) $cellNode['r'];
                $columnLetters = preg_replace('/\d/', '', $reference);
                $columnIndex = $this->columnLettersToIndex($columnLetters);
                $indexedCells[$columnIndex] = $this->extractCellValue($cellNode, $sharedStrings);
            }

            if ($indexedCells === []) {
                continue;
            }

            ksort($indexedCells);
            $maxColumnIndex = max(array_keys($indexedCells));
            $line = [];

            for ($i = 0; $i <= $maxColumnIndex; $i++) {
                $line[$i] = $this->sanitizeCell($indexedCells[$i] ?? '');
            }

            $matrix[] = $line;
        }

        $zip->close();

        if ($matrix === []) {
            throw ValidationException::withMessages([
                'import_file' => 'File XLSX kosong.',
            ]);
        }

        $headers = $this->normalizeHeaders(array_shift($matrix));
        $rows = [];

        foreach ($matrix as $values) {
            $row = $this->combineRow($headers, $values);

            if ($this->isEmptyRow($row)) {
                continue;
            }

            $rows[] = $row;
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    private function parsePdf(string $path): array
    {
        $content = file_get_contents($path);

        if ($content === false || $content === '') {
            throw ValidationException::withMessages([
                'import_file' => 'File PDF tidak bisa dibaca.',
            ]);
        }

        $text = $this->extractTextFromPdf($content);
        $lines = preg_split('/\R/u', $text) ?: [];
        $lines = array_values(array_filter(array_map([$this, 'sanitizeCell'], $lines), static fn ($line) => $line !== ''));

        if (count($lines) < 2) {
            throw ValidationException::withMessages([
                'import_file' => 'PDF tidak memiliki struktur tabel yang bisa dibaca. Gunakan PDF text-based dengan header kolom.',
            ]);
        }

        return $this->parseDelimitedLines($lines);
    }

    private function parseDelimitedLines(array $lines): array
    {
        $headerIndex = $this->detectHeaderIndex($lines);
        $headerLine = $lines[$headerIndex];
        $delimiter = $this->detectLineDelimiter($headerLine);
        $headerValues = $this->splitLineByDelimiter($headerLine, $delimiter);
        $headers = $this->normalizeHeaders($headerValues);
        $rows = [];

        for ($i = $headerIndex + 1; $i < count($lines); $i++) {
            $values = $this->splitLineByDelimiter($lines[$i], $delimiter);
            $row = $this->combineRow($headers, $values);

            if ($this->isEmptyRow($row)) {
                continue;
            }

            $rows[] = $row;
        }

        if ($rows === []) {
            throw ValidationException::withMessages([
                'import_file' => 'Data baris siswa pada file tidak ditemukan.',
            ]);
        }

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    private function detectCsvDelimiter(string $line): string
    {
        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $bestCount = -1;

        foreach ($delimiters as $delimiter) {
            $count = substr_count($line, $delimiter);
            if ($count > $bestCount) {
                $bestCount = $count;
                $bestDelimiter = $delimiter;
            }
        }

        return $bestDelimiter;
    }

    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];

        foreach ($headers as $index => $header) {
            $headerValue = $this->sanitizeCell((string) $header);
            if ($index === 0) {
                $headerValue = preg_replace('/^\xEF\xBB\xBF/u', '', $headerValue) ?? $headerValue;
            }

            $normalizedHeader = $this->normalizeKey($headerValue);
            if ($normalizedHeader === '') {
                $normalizedHeader = 'column_' . ($index + 1);
            }

            $normalized[] = $normalizedHeader;
        }

        return $normalized;
    }

    private function combineRow(array $headers, array $values): array
    {
        $row = [];

        foreach ($headers as $index => $header) {
            $row[$header] = $this->sanitizeCell((string) ($values[$index] ?? ''));
        }

        return $row;
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }

    private function sanitizeCell(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $value = str_replace("\xC2\xA0", ' ', $value);
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);

        return $value;
    }

    private function normalizeKey(string $value): string
    {
        $value = mb_strtolower($this->sanitizeCell($value));
        $value = str_replace(['-', '_'], ' ', $value);
        $value = preg_replace('/[^a-z0-9 ]/u', ' ', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);

        return $value;
    }

    private function resolveFirstWorksheetPath(ZipArchive $zip): string
    {
        $workbookContent = $zip->getFromName('xl/workbook.xml');
        $relsContent = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbookContent === false || $relsContent === false) {
            return 'xl/worksheets/sheet1.xml';
        }

        $workbook = $this->loadXml($workbookContent, 'Struktur workbook XLSX tidak valid.');
        $workbook->registerXPathNamespace('main', self::XLSX_MAIN_NS);

        $sheetNodes = $workbook->xpath('//main:sheets/main:sheet');
        if (!$sheetNodes) {
            return 'xl/worksheets/sheet1.xml';
        }

        $firstSheet = $sheetNodes[0];
        $relationId = (string) $firstSheet->attributes(self::XLSX_REL_NS)['id'];

        if ($relationId === '') {
            return 'xl/worksheets/sheet1.xml';
        }

        $rels = $this->loadXml($relsContent, 'Relasi workbook XLSX tidak valid.');
        $rels->registerXPathNamespace('rel', self::PACKAGE_REL_NS);
        $relationNodes = $rels->xpath("//rel:Relationship[@Id='{$relationId}']");

        if (!$relationNodes) {
            return 'xl/worksheets/sheet1.xml';
        }

        $target = (string) $relationNodes[0]['Target'];
        $target = ltrim($target, '/');

        if ($target === '') {
            return 'xl/worksheets/sheet1.xml';
        }

        if (!str_starts_with($target, 'xl/')) {
            $target = 'xl/' . $target;
        }

        return $target;
    }

    private function extractSharedStrings(ZipArchive $zip): array
    {
        $sharedStringsContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsContent === false) {
            return [];
        }

        $sharedStringsXml = $this->loadXml($sharedStringsContent, 'Shared strings XLSX tidak valid.');
        $sharedStringsXml->registerXPathNamespace('main', self::XLSX_MAIN_NS);

        $strings = [];
        $items = $sharedStringsXml->xpath('//main:si') ?: [];

        foreach ($items as $item) {
            $textNodes = $item->xpath('.//main:t') ?: [];
            $text = '';
            foreach ($textNodes as $textNode) {
                $text .= (string) $textNode;
            }

            $strings[] = $this->sanitizeCell($text);
        }

        return $strings;
    }

    private function columnLettersToIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $index = 0;

        for ($i = 0; $i < strlen($letters); $i++) {
            $index = ($index * 26) + (ord($letters[$i]) - ord('A') + 1);
        }

        return max(0, $index - 1);
    }

    private function extractCellValue(SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) $cell['t'];

        if ($type === 's') {
            $sharedIndex = (int) ($cell->v ?? 0);

            return $this->sanitizeCell($sharedStrings[$sharedIndex] ?? '');
        }

        if ($type === 'inlineStr') {
            return $this->sanitizeCell((string) ($cell->is->t ?? ''));
        }

        if ($type === 'b') {
            return ((string) ($cell->v ?? '0')) === '1' ? '1' : '0';
        }

        return $this->sanitizeCell((string) ($cell->v ?? ''));
    }

    private function loadXml(string $content, string $errorMessage): SimpleXMLElement
    {
        $useErrors = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);
        libxml_clear_errors();
        libxml_use_internal_errors($useErrors);

        if (!$xml instanceof SimpleXMLElement) {
            throw ValidationException::withMessages([
                'import_file' => $errorMessage,
            ]);
        }

        return $xml;
    }

    private function extractTextFromPdf(string $content): string
    {
        $textParts = [];
        preg_match_all('/stream[\r\n]+(.*?)endstream/s', $content, $streamMatches);

        foreach ($streamMatches[1] ?? [] as $stream) {
            $decoded = $this->decodePdfStream($stream);
            if ($decoded === '') {
                continue;
            }

            foreach ($this->extractPdfTextTokens($decoded) as $token) {
                $cleanToken = $this->sanitizeCell($token);
                if ($cleanToken !== '') {
                    $textParts[] = $cleanToken;
                }
            }
        }

        return implode("\n", $textParts);
    }

    private function decodePdfStream(string $stream): string
    {
        $stream = ltrim($stream, "\r\n");
        $stream = rtrim($stream, "\r\n");

        $decoded = @gzuncompress($stream);
        if ($decoded !== false) {
            return $decoded;
        }

        $decoded = @zlib_decode($stream);
        if ($decoded !== false) {
            return $decoded;
        }

        return $stream;
    }

    private function extractPdfTextTokens(string $content): array
    {
        $tokens = [];

        preg_match_all('/\((.*?)\)\s*Tj/s', $content, $directTextMatches);
        foreach ($directTextMatches[1] ?? [] as $match) {
            $tokens[] = $this->decodePdfText($match);
        }

        preg_match_all('/\[(.*?)\]\s*TJ/s', $content, $arrayTextMatches);
        foreach ($arrayTextMatches[1] ?? [] as $sequence) {
            preg_match_all('/\((.*?)\)/s', $sequence, $groupMatches);
            $line = '';

            foreach ($groupMatches[1] ?? [] as $piece) {
                $line .= $this->decodePdfText($piece);
            }

            if ($line !== '') {
                $tokens[] = $line;
            }
        }

        return $tokens;
    }

    private function decodePdfText(string $value): string
    {
        $value = str_replace(['\\(', '\\)', '\\\\'], ['(', ')', '\\'], $value);

        $value = preg_replace_callback('/\\\\([0-7]{1,3})/', static function ($matches) {
            return chr(octdec($matches[1]));
        }, $value) ?? $value;

        $value = str_replace(
            ['\\r', '\\n', '\\t', '\\b', '\\f'],
            ["\r", "\n", "\t", "\b", "\f"],
            $value
        );

        return $value;
    }

    private function detectHeaderIndex(array $lines): int
    {
        $bestIndex = 0;
        $bestScore = -1;

        foreach ($lines as $index => $line) {
            $normalized = $this->normalizeKey($line);
            $score = 0;

            if (str_contains($normalized, 'nama') || str_contains($normalized, 'name')) {
                $score++;
            }
            if (str_contains($normalized, 'kelas') || str_contains($normalized, 'class')) {
                $score++;
            }
            if (str_contains($normalized, 'ortu') || str_contains($normalized, 'parent')) {
                $score++;
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestIndex = $index;
            }
        }

        return $bestIndex;
    }

    private function detectLineDelimiter(string $line): string
    {
        foreach (['|', ';', ',', "\t"] as $delimiter) {
            if (substr_count($line, $delimiter) > 0) {
                return $delimiter;
            }
        }

        return 'spaces';
    }

    private function splitLineByDelimiter(string $line, string $delimiter): array
    {
        if ($delimiter === 'spaces') {
            return preg_split('/\s{2,}/u', trim($line)) ?: [];
        }

        return str_getcsv($line, $delimiter);
    }
}
