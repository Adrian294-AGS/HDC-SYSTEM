# ULS HDC Cumulative Record System

Laravel 13 + MySQL + Blade system for the Human Development Center (HDC) of the University of La Salette, Santiago City.
It replaces paper forms filled out during enrollment (6,000+ students).

## Read these before building any feature
- docs/form-fields.md: exact fields of all 3 HDC forms, who fills each, sensitivity levels, privacy rules
- docs/needs-assessment.md: functional requirements FR-01 to FR-11, roles, open questions
- docs/interview-manuscript.md: interview with the HDC Director
- docs/forms/: the original scanned forms (PDF)

## Roles
- student: registers, fills out and edits ONLY their own Form 5 (Student Cumulative Record)
- counselor: sees students ONLY in their assigned college(s); encodes SATA entrance test results; fills Form 8 (Intake Interview)
- director: sees all colleges, manages counselors and college assignments, views reports

## The 3 forms
- Form 5, Student Cumulative Record: filled by the student online
- Entrance Test Result (SATA): encoded by staff
- Form 8, Intake Interview: filled by a counselor; holds the psychological concerns used in reports

## Core features
1. Login for students and staff, role-based middleware
2. Online Form 5 with data privacy consent
3. Records grouped by college via the student's program
4. Submission tracker: submitted vs missing records; staff notified on new submission
5. Search and filter by college and year level
6. Reports: COUNTS of psychological concerns from Form 8 per college and year level (counts under 5 shown as "fewer than 5")

## Rules
- Mental health, family and health data are confidential: enforce access by role AND assigned college on every query
- Students never see Form 8 or SATA results
- Keep the UI simple; no training should be needed
- Commit after each finished feature with a clear message (the prof checks GitHub progress)

---


<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>
