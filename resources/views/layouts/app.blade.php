<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Sistem Keterlambatan Siswa</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            /* Poppins Font Classes */
            .poppins-regular {
                font-family: "Poppins", sans-serif;
                font-weight: 400;
                font-style: normal;
            }
            .poppins-semibold {
                font-family: "Poppins", sans-serif;
                font-weight: 600;
                font-style: normal;
            }
            .poppins-bold {
                font-family: "Poppins", sans-serif;
                font-weight: 700;
                font-style: normal;
            }
            /* Navbar with primary color */
            .navbar-primary {
                background-color: #160B6A !important;
                position: relative;
                z-index: 999;
            }
            /* Card with gradient from #231591 to #0A062B */
            .card-primary {
                background: linear-gradient(135deg, #231591 0%, #0A062B 100%) !important;
                color: white !important;
            }
            .card-primary * {
                color: white !important;
            }
            /* Header with gradient color */
            .header-primary {
                background: linear-gradient(135deg, #231591 0%, #0A062B 100%) !important;
                color: white !important;
            }
            .header-primary * {
                color: white !important;
            }
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .animate-gradient {
                background-size: 200% 200%;
                animation: gradient 15s ease infinite;
            }
            .glass-effect {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            .bg-custom-blue {
                background-color: #160B6A !important;
            }
            .text-custom-blue {
                color: #160B6A !important;
            }
            .bg-card-gray {
                background-color: #E5E5E5 !important;
            }

            .exit-permissions-bg {
                background:
                    radial-gradient(circle at 10% 10%, rgba(126, 123, 149, 0.14) 0%, rgba(126, 123, 149, 0) 42%),
                    radial-gradient(circle at 90% 15%, rgba(22, 11, 106, 0.10) 0%, rgba(22, 11, 106, 0) 45%),
                    #F4F2FF;
            }

            .late-attendance-bg {
                background:
                    radial-gradient(circle at 12% 12%, rgba(126, 123, 149, 0.14) 0%, rgba(126, 123, 149, 0) 42%),
                    radial-gradient(circle at 90% 14%, rgba(22, 11, 106, 0.10) 0%, rgba(22, 11, 106, 0) 45%),
                    #F4F2FF;
            }

            .late-attendance-hero {
                background: #160B6A;
                position: relative;
                overflow: hidden;
            }

            .late-attendance-hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at 86% 22%, rgba(126, 123, 149, 0.34) 0%, rgba(126, 123, 149, 0) 44%),
                    radial-gradient(circle at 12% 18%, rgba(255, 255, 255, 0.10) 0%, rgba(255, 255, 255, 0) 38%);
                pointer-events: none;
            }

            .late-attendance-hero-inner {
                position: relative;
                z-index: 1;
            }

            .late-attendance-hero-subtitle {
                color: rgba(255, 255, 255, 0.85);
            }

            .late-attendance-card {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 16px;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .late-attendance-card-header {
                background: #160B6A;
                padding: 16px 24px;
            }

            .late-attendance-card-title {
                color: #ffffff;
                font-weight: 900;
            }

            .late-attendance-card-body {
                padding: 24px;
            }

            .late-attendance-filters-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
                align-items: end;
            }

            .late-attendance-filter-actions {
                grid-column: 1 / -1;
                display: flex;
                gap: 10px;
                align-items: center;
                flex-wrap: wrap;
            }

            .late-attendance-toolbar-right {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .late-attendance-toolbar-link {
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .late-attendance-label {
                color: rgba(17, 24, 39, 0.78);
                font-weight: 700;
            }

            .late-attendance-input {
                width: 100%;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.20);
                border-radius: 12px;
                color: #111827;
                padding: 10px 12px;
            }

            .late-attendance-input:focus {
                outline: none;
                border-color: rgba(22, 11, 106, 0.65);
                box-shadow: 0 0 0 3px rgba(22, 11, 106, 0.16);
            }

            .late-attendance-primary-btn {
                background: #160B6A;
                color: #ffffff;
                border-radius: 12px;
                padding: 12px 18px;
                font-weight: 800;
                box-shadow: 0 12px 24px rgba(22, 11, 106, 0.22);
            }

            .late-attendance-primary-btn:hover {
                background: #120856;
            }

            .late-attendance-secondary-btn {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.25);
                color: #160B6A;
                border-radius: 12px;
                padding: 12px 18px;
                font-weight: 800;
            }

            .late-attendance-secondary-btn:hover {
                background: rgba(22, 11, 106, 0.04);
            }

            .late-attendance-secondary-btn-nohover {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.25);
                color: #160B6A;
                border-radius: 12px;
                padding: 12px 18px;
                font-weight: 800;
            }

            .late-attendance-table {
                width: 100%;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 14px;
                overflow: hidden;
            }

            .late-attendance-table thead {
                background: rgba(22, 11, 106, 0.06);
            }

            .late-attendance-table th {
                color: rgba(17, 24, 39, 0.66);
                font-weight: 900;
                letter-spacing: 0.06em;
                font-size: 11px;
                text-transform: uppercase;
            }

            .late-attendance-table-row:hover {
                background: rgba(22, 11, 106, 0.04);
            }

            .late-attendance-link {
                color: rgba(17, 24, 39, 0.82);
                font-weight: 700;
            }

            .late-attendance-link:hover {
                color: rgba(17, 24, 39, 0.92);
            }

            .class-show-bg {
                background:
                    radial-gradient(circle at 12% 12%, rgba(126, 123, 149, 0.14) 0%, rgba(126, 123, 149, 0) 42%),
                    radial-gradient(circle at 90% 14%, rgba(22, 11, 106, 0.10) 0%, rgba(22, 11, 106, 0) 45%),
                    #F4F2FF;
            }

            .class-show-header {
                background: #ffffff;
                border-bottom: 1px solid rgba(15, 23, 42, 0.10);
            }

            .class-show-header-inner {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 16px;
                padding: 20px 24px;
            }

            .class-show-title {
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }

            .class-show-title h2 {
                font-weight: 900;
                font-size: 22px;
                color: #0f172a;
                line-height: 1.15;
            }

            .class-show-subtitle {
                color: rgba(15, 23, 42, 0.62);
                font-weight: 600;
                font-size: 12px;
                margin-top: 2px;
            }

            .class-show-back-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.30);
                color: #160B6A;
                border-radius: 9999px;
                padding: 10px 14px;
                font-weight: 800;
            }

            .class-show-back-btn:hover {
                background: rgba(22, 11, 106, 0.04);
            }

            .class-show-card {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 16px;
                box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .class-show-card-header {
                background: #160B6A;
                padding: 14px 18px;
                color: #ffffff;
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
            }

            .class-show-card-header-title {
                font-weight: 900;
                letter-spacing: 0.01em;
            }

            .class-show-card-header-subtitle {
                color: rgba(255, 255, 255, 0.86);
                font-weight: 600;
                font-size: 12px;
                margin-top: 2px;
            }

            .class-show-submit-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(255, 255, 255, 0.15);
                border: 1px solid rgba(255, 255, 255, 0.32);
                color: #ffffff;
                padding: 10px 12px;
                border-radius: 12px;
                font-weight: 800;
                white-space: nowrap;
            }

            .class-show-submit-btn:hover {
                background: rgba(255, 255, 255, 0.22);
            }

            .class-show-student-row {
                background: #ffffff;
                border: 1px solid rgba(15, 23, 42, 0.10);
                border-radius: 14px;
                box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
                padding: 14px 16px;
            }

            .class-show-student-row:hover {
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.10);
            }

            .class-show-checkbox {
                width: 18px;
                height: 18px;
                border-radius: 6px;
                border: 1px solid rgba(15, 23, 42, 0.22);
                accent-color: #160B6A;
                cursor: pointer;
            }

            .class-show-avatar {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: #160B6A;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #ffffff;
                font-weight: 900;
            }

            .class-show-student-name {
                font-weight: 900;
                color: #0f172a;
                font-size: 14px;
            }

            .class-show-student-meta {
                color: rgba(15, 23, 42, 0.62);
                font-weight: 600;
                font-size: 12px;
                display: flex;
                gap: 14px;
                flex-wrap: wrap;
                margin-top: 2px;
            }

            .class-show-action-primary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #160B6A;
                color: #ffffff;
                border-radius: 12px;
                padding: 10px 12px;
                font-weight: 900;
                box-shadow: 0 10px 20px rgba(22, 11, 106, 0.22);
            }

            .class-show-action-primary:hover {
                background: #120856;
            }

            .class-show-action-secondary {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #ffffff;
                border: 1px solid rgba(15, 23, 42, 0.16);
                color: rgba(15, 23, 42, 0.82);
                border-radius: 12px;
                padding: 10px 12px;
                font-weight: 900;
            }

            .class-show-action-secondary:hover {
                background: rgba(15, 23, 42, 0.04);
            }

            @media (max-width: 768px) {
                .class-show-header-inner {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .class-show-card-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }

            @media (max-width: 768px) {
                .late-attendance-card-body {
                    padding: 18px;
                }

                .late-attendance-card-header {
                    padding: 14px 18px;
                }

                .late-attendance-primary-btn,
                .late-attendance-secondary-btn {
                    width: 100%;
                    justify-content: center;
                }

                .late-attendance-filters-grid {
                    grid-template-columns: 1fr;
                }

                .late-attendance-filter-actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .late-attendance-toolbar {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 12px;
                }

                .late-attendance-toolbar-right {
                    flex-direction: column;
                    align-items: stretch;
                }

                .late-attendance-table th,
                .late-attendance-table td {
                    padding: 12px 14px !important;
                }
            }

            @media (max-width: 480px) {
                .late-attendance-hero {
                    margin-left: -16px;
                    margin-right: -16px;
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }

            .exit-permissions-card {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 16px;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .exit-permissions-card-header {
                background: linear-gradient(90deg, #160B6A 0%, #160B6A 67%);
            }

            .exit-permissions-primary-btn {
                background: #160B6A;
                color: #ffffff;
            }

            .exit-permissions-secondary-btn {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.25);
                color: #160B6A;
            }

            .exit-permissions-header-btn {
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.45);
                color: #ffffff;
            }

            .exit-permissions-header-btn:hover {
                background: rgba(255, 255, 255, 0.20);
            }

            .exit-permissions-label {
                color: rgba(17, 24, 39, 0.78);
                font-weight: 600;
            }

            .exit-permissions-input {
                width: 100%;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.20);
                border-radius: 12px;
                color: #111827;
            }

            .exit-permissions-input::placeholder {
                color: rgba(17, 24, 39, 0.45);
            }

            .exit-permissions-input:focus {
                outline: none;
                border-color: rgba(22, 11, 106, 0.65);
                box-shadow: 0 0 0 3px rgba(22, 11, 106, 0.18);
            }

            .exit-permissions-table {
                width: 100%;
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 14px;
                overflow: hidden;
            }

            .exit-permissions-table-row:hover {
                background: rgba(22, 11, 106, 0.04);
            }

            .exit-permissions-table-head {
                background: #160B6A;
            }

            .exit-permissions-table-head th {
                color: #ffffff;
            }

            .exit-permissions-class-card {
                background:
                    radial-gradient(circle at 100% 0%, rgba(126, 123, 149, 0.98) 0%, rgba(126, 123, 149, 0.55) 22%, rgba(126, 123, 149, 0) 45%),
                    #160B6A;
                border-radius: 16px;
                box-shadow: 0 12px 30px rgba(22, 11, 106, 0.22);
                overflow: hidden;
            }

            .exit-permissions-class-desc {
                color: rgba(255, 255, 255, 0.80);
            }

            .exit-permissions-subtitle {
                color: rgba(255, 255, 255, 0.85);
            }

            .exit-status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 10px;
                border-radius: 9999px;
                font-size: 11px;
                font-weight: 700;
                line-height: 1;
                color: #ffffff;
            }

            .exit-status-approved {
                background: #22C55E;
            }

            .exit-status-rejected {
                background: #EF4444;
            }

            .exit-status-pending {
                background: #F59E0B;
            }

            .walas-selection-page {
                min-height: 100vh;
            }

            .walas-selection-hero {
                background: #160B6A;
                position: relative;
                overflow: hidden;
            }

            .walas-selection-hero::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                    radial-gradient(circle at 88% 22%, rgba(126, 123, 149, 0.34) 0%, rgba(126, 123, 149, 0) 44%),
                    radial-gradient(circle at 10% 16%, rgba(255, 255, 255, 0.10) 0%, rgba(255, 255, 255, 0) 38%);
                pointer-events: none;
            }

            .walas-selection-hero-inner {
                position: relative;
                z-index: 1;
            }

            .walas-selection-subtitle {
                color: rgba(255, 255, 255, 0.85);
            }

            .walas-info-card {
                background: rgba(255, 255, 255, 0.70);
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 16px;
                padding: 16px;
                box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
            }

            .walas-info-title {
                color: #160B6A;
                font-weight: 800;
            }

            .walas-info-text {
                color: rgba(17, 24, 39, 0.72);
            }

            .walas-section-card {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 16px;
                box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
                overflow: hidden;
            }

            .walas-section-header {
                padding: 18px 24px;
                background: linear-gradient(90deg, #160B6A 70%);
                color: #ffffff;
                font-weight: 900;
            }

            .walas-section-body {
                padding: 24px;
            }

            .walas-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .walas-class-card {
                background:
                    radial-gradient(circle at 100% 0%, rgba(126, 123, 149, 0.95) 0%, rgba(126, 123, 149, 0.55) 22%, rgba(126, 123, 149, 0) 45%),
                    #160B6A;
                border-radius: 16px;
                box-shadow: 0 14px 34px rgba(22, 11, 106, 0.22);
                overflow: hidden;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .walas-class-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 18px 44px rgba(22, 11, 106, 0.28);
            }

            .walas-class-card-body {
                padding: 20px;
            }

            .walas-class-title {
                color: #ffffff;
                font-weight: 900;
                font-size: 18px;
            }

            .walas-class-meta {
                color: rgba(255, 255, 255, 0.78);
            }

            .walas-class-desc {
                color: rgba(255, 255, 255, 0.72);
            }

            .walas-badge {
                display: inline-flex;
                align-items: center;
                padding: 6px 12px;
                border-radius: 9999px;
                font-size: 12px;
                font-weight: 800;
                line-height: 1;
                color: #ffffff;
                box-shadow: 0 10px 20px rgba(15, 23, 42, 0.16);
                white-space: nowrap;
            }

            .walas-badge-pending {
                background: #F59E0B;
            }

            .walas-badge-empty {
                background: rgba(255, 255, 255, 0.16);
                border: 1px solid rgba(255, 255, 255, 0.30);
                color: rgba(255, 255, 255, 0.85);
                box-shadow: none;
            }

            .walas-security-text {
                color: rgba(255, 255, 255, 0.80);
                font-weight: 700;
                font-size: 13px;
            }

            .walas-primary-btn {
                background: #160B6A;
                border: 1px solid rgba(255, 255, 255, 0.22);
                color: #ffffff;
                border-radius: 12px;
                padding: 10px 14px;
                font-weight: 800;
                box-shadow: 0 12px 26px rgba(22, 11, 106, 0.28);
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .walas-primary-btn:hover {
                background: #120856;
                transform: translateY(-1px);
                box-shadow: 0 16px 34px rgba(22, 11, 106, 0.34);
            }

            .walas-secondary-btn {
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.30);
                color: #ffffff;
                border-radius: 12px;
                padding: 10px 14px;
                font-weight: 800;
                transition: background 0.2s ease;
            }

            .walas-secondary-btn:hover {
                background: rgba(255, 255, 255, 0.20);
            }

            .walas-alert {
                border-radius: 16px;
                padding: 14px 16px;
                border: 1px solid;
                box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
            }

            .walas-alert-success {
                background: rgba(34, 197, 94, 0.12);
                border-color: rgba(34, 197, 94, 0.30);
                color: #166534;
            }

            .walas-alert-error {
                background: rgba(239, 68, 68, 0.10);
                border-color: rgba(239, 68, 68, 0.28);
                color: #7F1D1D;
            }

            .walas-empty {
                padding: 44px 14px;
                text-align: center;
                color: rgba(17, 24, 39, 0.62);
            }

            .walas-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(2, 6, 23, 0.55);
                z-index: 50;
                padding: 16px;
            }

            .walas-modal-wrap {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .walas-modal {
                width: 100%;
                max-width: 520px;
                background: #ffffff;
                border-radius: 18px;
                overflow: hidden;
                box-shadow: 0 22px 60px rgba(2, 6, 23, 0.38);
                border: 1px solid rgba(22, 11, 106, 0.14);
            }

            .walas-modal-header {
                padding: 16px 18px;
                background: linear-gradient(90deg, #160B6A 70%);
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .walas-modal-title {
                font-weight: 900;
                font-size: 16px;
            }

            .walas-modal-close {
                background: rgba(255, 255, 255, 0.14);
                border: 1px solid rgba(255, 255, 255, 0.28);
                color: #ffffff;
                border-radius: 10px;
                padding: 6px;
                transition: background 0.2s ease;
            }

            .walas-modal-close:hover {
                background: rgba(255, 255, 255, 0.20);
            }

            .walas-modal-body {
                padding: 18px;
            }

            .walas-modal-hint {
                background: rgba(245, 158, 11, 0.12);
                border: 1px solid rgba(245, 158, 11, 0.25);
                border-radius: 14px;
                padding: 12px;
                color: rgba(120, 53, 15, 0.92);
                font-weight: 700;
            }

            .walas-modal-label {
                font-weight: 800;
                color: rgba(17, 24, 39, 0.86);
                font-size: 13px;
                margin-bottom: 8px;
                display: block;
            }

            .walas-modal-input {
                width: 100%;
                padding: 12px 14px;
                border-radius: 12px;
                border: 1px solid rgba(22, 11, 106, 0.22);
                outline: none;
                transition: box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .walas-modal-input:focus {
                border-color: rgba(22, 11, 106, 0.65);
                box-shadow: 0 0 0 3px rgba(22, 11, 106, 0.16);
            }

            .walas-modal-footnote {
                color: rgba(17, 24, 39, 0.62);
                font-size: 12px;
                margin-top: 8px;
            }

            .walas-modal-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                padding-top: 8px;
            }

            .walas-modal-cancel {
                background: rgba(148, 163, 184, 0.18);
                border: 1px solid rgba(148, 163, 184, 0.40);
                color: rgba(15, 23, 42, 0.78);
                border-radius: 12px;
                padding: 10px 14px;
                font-weight: 800;
            }

            .walas-modal-cancel:hover {
                background: rgba(148, 163, 184, 0.26);
            }

            .walas-modal-submit {
                background: #160B6A;
                color: #ffffff;
                border-radius: 12px;
                padding: 10px 14px;
                font-weight: 900;
                box-shadow: 0 12px 26px rgba(22, 11, 106, 0.28);
            }

            .walas-modal-submit:hover {
                background: #120856;
            }

            @media (min-width: 768px) {
                .walas-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (min-width: 1024px) {
                .walas-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }

            @media (max-width: 768px) {
                .walas-section-body {
                    padding: 18px;
                }

                .walas-section-header {
                    padding: 16px 18px;
                }

                .walas-modal-actions {
                    flex-direction: column;
                    align-items: stretch;
                }
            }

            .exit-create-page-bg {
                background:
                    radial-gradient(circle at 10% 10%, rgba(126, 123, 149, 0.14) 0%, rgba(126, 123, 149, 0) 42%),
                    radial-gradient(circle at 90% 15%, rgba(22, 11, 106, 0.10) 0%, rgba(22, 11, 106, 0) 45%),
                    #F4F2FF;
            }

            .exit-create-page-header {
                background: #160B6A;
            }

            .exit-create-page-header-subtitle {
                color: rgba(255, 255, 255, 0.85);
            }

            .exit-create-back-btn {
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.45);
                color: #ffffff;
            }

            .exit-create-back-btn:hover {
                background: rgba(255, 255, 255, 0.20);
            }

            .exit-create-form-card {
                background: #ffffff;
                border: 1px solid rgba(22, 11, 106, 0.12);
                border-radius: 24px;
                box-shadow: 0 18px 48px rgba(15, 23, 42, 0.12);
                overflow: hidden;
            }

            .exit-create-form-header {
                background: #160B6A;
                padding: 44px 28px;
                text-align: center;
            }

            .exit-create-form-header-img {
                width: 100px;
                height: 100px;
                display: block;
                margin: 0 auto 14px;
            }

            .exit-create-form-header-title {
                color: #ffffff;
                font-size: 34px;
                font-weight: 800;
                letter-spacing: 0.5px;
                margin: 0;
            }

            .exit-create-form-header-subtitle {
                color: rgba(255, 255, 255, 0.85);
                font-size: 18px;
                font-weight: 600;
                margin-top: 10px;
            }

            .exit-create-form-body {
                background: linear-gradient(180deg, #ffffff 0%, #F8FAFC 100%);
                padding: 32px;
            }

            .exit-create-submit-btn {
                background: #22C55E;
                color: #ffffff;
                border-radius: 16px;
                padding: 16px 40px;
                font-size: 18px;
                font-weight: 800;
                box-shadow: 0 14px 28px rgba(34, 197, 94, 0.25);
                transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            }

            .exit-create-submit-btn:hover {
                background: #16A34A;
                transform: translateY(-1px);
                box-shadow: 0 18px 38px rgba(34, 197, 94, 0.32);
            }

            @media (max-width: 768px) {
                .exit-create-form-header {
                    padding: 34px 20px;
                }

                .exit-create-page-header-inner {
                    display: flex;
                    flex-direction: column;
                    align-items: stretch;
                    gap: 14px;
                }

                .exit-create-page-header-inner .exit-create-back-btn {
                    width: 100%;
                    justify-content: center;
                }

                .exit-create-form-body {
                    padding: 20px;
                }

                .exit-create-form-header-title {
                    font-size: 28px;
                }

                .exit-create-form-header-subtitle {
                    font-size: 16px;
                }

                .exit-create-submit-btn {
                    width: 100%;
                    justify-content: center;
                }

                .exit-create-form-actions {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 14px;
                }

                .exit-create-form-actions a {
                    justify-content: center;
                }
            }

            @media (max-width: 480px) {
                .exit-create-page-header {
                    margin-left: -16px;
                    margin-right: -16px;
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }

            @media (max-width: 1024px) {
                .exit-permissions-page-hero-inner {
                    gap: 14px;
                }

                .exit-permissions-class-card-body {
                    padding: 20px;
                }
            }

            @media (max-width: 768px) {
                .exit-permissions-page {
                    padding-top: 24px;
                    padding-bottom: 24px;
                }

                .exit-permissions-page-hero {
                    padding-top: 20px;
                    padding-bottom: 20px;
                }

                .exit-permissions-page-hero-inner {
                    display: flex;
                    flex-direction: column;
                    align-items: stretch;
                }

                .exit-permissions-page-title {
                    font-size: 28px;
                    line-height: 1.2;
                }

                .exit-permissions-page-hero-inner .exit-permissions-header-btn {
                    width: 100%;
                    justify-content: center;
                }

                .exit-permissions-class-card-body {
                    padding: 16px;
                }

                .exit-permissions-table th,
                .exit-permissions-table td {
                    padding: 12px 14px !important;
                }
            }

            @media (max-width: 480px) {
                .exit-permissions-page-hero {
                    margin-left: -16px;
                    margin-right: -16px;
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .exit-status-badge {
                    font-size: 10px;
                    padding: 4px 8px;
                }
            }

            @media (max-width: 639px) {
                .mobile-page-spacing {
                    padding-left: 16px;
                    padding-right: 16px;
                }

                .mobile-page-spacing .\-mx-6 {
                    margin-left: -16px;
                    margin-right: -16px;
                }

                .mobile-page-spacing .\-mx-6.px-6 {
                    padding-left: 16px;
                    padding-right: 16px;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="shadow-lg">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="{{ request()->routeIs('dashboard', 'walas.dashboard') ? '' : 'mobile-page-spacing' }}">
                {{ $slot }}
            </main>
        </div>
        
        <!-- Floating Background Elements -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute top-20 left-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-40 right-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-20 left-40 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        
        <style>
            @keyframes blob {
                0%, 100% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
        
        @stack('scripts')
    </body>
</html>
