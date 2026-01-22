Feature Objective

Improve the late attendance recording flow so teachers can select multiple late students first, review them in a separate page, and then submit all records at once with minimal input effort.

This feature aims to reduce repetitive input and speed up daily usage.

🧭 Updated User Flow

Teacher selects a class

Teacher checks (checkbox) students who are late

Teacher clicks Submit Selection

System opens a Review & Confirmation Page

Teacher fills one shared late attendance form

Teacher submits once

System:

Saves all records

Sends notification to Telegram automatically

1️⃣ Student Selection Page (Updated)

Display a list of students by class

Each student has a checkbox

No late data is entered at this stage

A button is provided:

“Submit Selection”

This page is only used to collect late students.

2️⃣ Late Student Review Page (New)

After clicking Submit Selection, show a new page containing:

A. Late Student List

Table/list of selected students:

Student Name

Class

Ability to:

Remove a student from the list (optional)

B. Shared Late Attendance Form

One form that applies to all selected students:

Late Reason (dropdown)

Arrival Time (time picker)

Late Date (date picker, default: current date)

This design avoids repetitive data entry.

3️⃣ Final Submission Behavior

When the teacher clicks Final Submit:

The system saves multiple late attendance records in one action

Each selected student gets:

The same reason

The same arrival time

The same date

No additional confirmation page is required.

4️⃣ Telegram Notification Integration

After successful submission:

The system automatically sends a Telegram message

No extra “Send” button is needed

Telegram Message Content Example:

Class: 10 PPLG

Date: 2026-01-17

Arrival Time: 07:25

Reason: Transportation issue

Late Students:

Student A

Student B

Student C

⚙️ System Rules

Telegram notification is sent only after successful database save

If database save fails, Telegram message must NOT be sent

Message is sent automatically in the background

🛠️ Technical Notes

Use batch insert for performance

Use transactions to ensure data consistency

Telegram Bot API integration

Reuse existing class and student data

🎯 UX Principles

Minimal clicks

Minimal typing

Fast daily operation for teachers

Clear separation between:

Student selection

Data confirmation

Final submission