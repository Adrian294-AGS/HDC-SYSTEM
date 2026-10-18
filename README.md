# ULS HDC System

**Student Cumulative Record System for the Human Development Center (HDC) at the University of La Salette.**

A Laravel 11/12 application that digitizes student cumulative records, guidance appointments, and counseling workflows, with real-time updates and cloud-based file storage.

---

## Table of Contents

- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [SSL Certificate (Windows Only)](#ssl-certificate-windows-only)
- [Database Setup](#database-setup)
- [Default Accounts](#default-accounts)
- [Running the Application](#running-the-application)
- [Testing](#testing)
  - [Real-Time Notifications](#1-real-time-notifications)
  - [Real-Time Tables](#2-real-time-tables)
  - [Cloud Uploads (Backblaze B2)](#3-cloud-uploads-backblaze-b2)

---

## Features

- **Role-Based Access Control** — Separate experiences and permissions for three roles:
  - **Student**
  - **Counselor**
  - **Director**
- **Form 5 Wizard** — A guided, multi-step form for completing the student cumulative record.
- **SATA Encoding** — Encoding support for SATA records.
- **Real-Time Notifications & Tables** — Powered by Laravel Reverb (WebSockets) so alerts and table data update live without a page refresh.
- **Cloud Storage (Backblaze B2)** — Uploaded files such as student photos are stored in a Backblaze B2 bucket through its S3-compatible API.
- **Dark Mode / Glassmorphism UI** — A modern interface with a dark theme and frosted-glass styling.

---

## Prerequisites

Make sure the following are installed and available before you begin:

- **PHP 8.2+**
- **Composer**
- **Node.js & NPM**
- **MySQL**
- **Backblaze B2 account** (for cloud file storage)

---

## Installation

Run the following commands from the project root, in order:

**1. Install PHP dependencies**

```bash
composer install
```

**2. Install JavaScript dependencies**

```bash
npm install
```

**3. Create your environment file**

```bash
cp .env.example .env
```

**4. Generate the application key**

```bash
php artisan key:generate
```

---

## Environment Configuration

Open the `.env` file and configure the following sections.

### Application

```env
APP_NAME="ULS HDC"
APP_URL=http://localhost:8000
```

### Database (MySQL)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=HDC_DATABASE
DB_USERNAME=root
DB_PASSWORD=
```

> Create an empty MySQL database named `HDC_DATABASE` before running the migrations.

### Queue

```env
QUEUE_CONNECTION=database
```

### Cloud Storage (Backblaze B2)

```env
FILESYSTEM_DISK=b2

B2_KEY_ID=your_b2_key_id
B2_APPLICATION_KEY=your_b2_application_key
B2_BUCKET=your_b2_bucket_name
B2_REGION=us-east-005
B2_ENDPOINT=https://s3.us-east-005.backblazeb2.com
B2_USE_PATH_STYLE_ENDPOINT=false
```

Replace `B2_KEY_ID`, `B2_APPLICATION_KEY`, and `B2_BUCKET` with the values from your own Backblaze B2 account.

### Broadcasting (Laravel Reverb)

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your_reverb_app_id
REVERB_APP_KEY=your_reverb_app_key
REVERB_APP_SECRET=your_reverb_app_secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

The `VITE_REVERB_*` variables are mapped to the server-side `REVERB_*` variables so the frontend connects to the same WebSocket server.

---

## SSL Certificate (Windows Only)

On Windows, PHP may fail to verify SSL connections (for example, when talking to Backblaze B2). Download the CA certificate bundle by running:

```bash
curl -o cacert.pem https://curl.se/ca/cacert.pem
```

Then point PHP at the downloaded file by setting `curl.cainfo` and `openssl.cafile` in your `php.ini` to the full path of `cacert.pem`, and restart your PHP process.

---

## Database Setup

Run the migrations and seeders:

```bash
php artisan migrate:fresh --seed
```

> **Warning:** `migrate:fresh` drops all existing tables before recreating them. Do not run it against a database that contains data you want to keep.

---

## Default Accounts

All seeded accounts use the password **`password`**.

| Role      | Username    |
|-----------|-------------|
| Director  | `director`  |
| Counselor | `counselor` |
| Student   | `juan`      |
| Student   | `maria`     |
| Student   | `pedro`     |
| Student   | `ana`       |
| Student   | `jose`      |
| Student   | `liza`      |

---

## Running the Application

The application needs **4 terminals running simultaneously**. Open four terminal windows in the project root and run one command in each.

**Terminal 1 — Web server**

```bash
php artisan serve
```

**Terminal 2 — Frontend assets (Vite)**

```bash
npm run dev
```

**Terminal 3 — Queue worker**

```bash
php artisan queue:work
```

**Terminal 4 — Reverb WebSocket server**

```bash
php artisan reverb:start
```

Once all four are running, open [http://localhost:8000](http://localhost:8000) in your browser.

> **Important:** Closing Terminal 3 or Terminal 4 breaks the real-time features.
>
> - **Terminal 3 (`queue:work`)** processes the queued jobs that dispatch notifications and broadcasts. Without it, events are never sent.
> - **Terminal 4 (`reverb:start`)** is the WebSocket server that delivers those events to the browser. Without it, notifications and live tables stop updating.
>
> The rest of the app keeps working, but nothing updates in real time until both are running again.

---

## Testing

Keep all 4 terminals running while you test.

### 1. Real-Time Notifications

1. Open **two different browsers** (or one normal window and one private/incognito window).
2. In **Browser 1**, log in as a **Student** (for example, `juan` / `password`).
3. In **Browser 2**, log in as the **Counselor** (`counselor` / `password`).
4. In Browser 1, as the Student, **book an appointment**.
5. In Browser 2, the Counselor should receive a **real-time notification ping** without refreshing the page.

### 2. Real-Time Tables

1. Keep the same two browsers open: the Student in Browser 1 and the Counselor in Browser 2.
2. In Browser 2, as the Counselor, **approve** the Student's appointment.
3. In Browser 1, the Student's appointments table should **update instantly** (with a brief highlight effect on the status badge), with no manual refresh.

### 3. Cloud Uploads (Backblaze B2)

1. Log in as a **Student** (for example, `juan` / `password`).
2. **Upload a photo** through the student profile or form.
3. Open your **Backblaze B2 bucket** in the Backblaze web console.
4. Confirm that the uploaded file now appears in the bucket.

If the file does not appear, verify the following:

- `FILESYSTEM_DISK=b2` is set in `.env`.
- The `B2_*` credentials, bucket name, region, and endpoint are correct.
- On Windows, the SSL certificate step above has been completed.
- Run `php artisan config:clear` after changing `.env`, then try again.