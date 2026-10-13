# ULS HDC Cumulative Record System

Laravel + MySQL + Blade system for the school's Human Development Center (HDC).
It replaces paper cumulative record forms filled out during enrollment (6,000+ students).

## Roles
- student: registers, fills out and edits ONLY their own cumulative record
- counselor: views records ONLY of their assigned college(s)
- hdc_head (admin): views all colleges, manages counselors and college assignments, sees reports

## Core features
1. Auth for students and staff (role-based middleware)
2. Cumulative record form (personal info, family background, education, psychological concerns checklist e.g. depression, anxiety)
3. Records auto-routed by the student's program -> college
4. Staff notification when a new record is submitted
5. Reports: counts of students per psychological concern, per college
6. Confidential data: no public access, other departments request data by letter

## Rules
- Mental health and family data are confidential: always enforce access by role and college
- Keep the UI simple; users should not need training
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
