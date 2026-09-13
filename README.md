# Attendance System (Laravel 13 + Tailwind v4)

A clock in/out attendance web app for employees, built with **Laravel 13**, **Tailwind CSS v4**, and vanilla JavaScript.

## Features

- Employee registration & login (session-based auth)
- **Clock In / Clock Out** with a required **webcam selfie** captured at the moment of clocking in/out
- **Break tracking** (start/end breaks while clocked in)
- **Attendance history** with Daily / Weekly / Monthly filters
- **Late & overtime calculation** based on fixed company work hours (configurable via `.env`)
- **Leave / time-off requests** — self-logged, no approval workflow
  - Employees log leave from inside the app
  - **Guests** (no account needed) can submit a leave application via a public form (`/guest/leave`) using just their name and email
- **Equipment borrowing** ("Peminjaman Peralatan") — open to both employees and guests (`/borrow`)
  - Inventory: Notebooks `N-01`–`N-15`, Webcams `W-01`–`W-03`, LCD Projectors `LP-01`–`LP-03`, Portable PA/Printers `P-01`–`P-02`
  - Records borrower, borrow date, return date, and an optional reason
  - **Prevents double-booking**: an item can't be booked again for overlapping dates until it's returned
  - Logged-in employees can view their own borrow history and mark items as returned
- **Admin dashboard** (`/admin`, role-restricted) — full oversight and control:
  - Overview stats: total employees, clocked in today, pending leave/borrow counts, overdue equipment
  - **Approve or reject** every leave request (employee and guest) and every equipment booking
  - Browse/search all employees and drill into each one's full attendance history
  - Manually mark any approved equipment booking as returned (useful for guest bookings, since guests can't log back in)
- **Live chat** between logged-in employees — direct messages with a contact list, unread badges in the nav, and near-real-time updates via polling (no external chat service required)
- Profile management (update name/email, change password, delete account)
- **Forgot password via email verification code** — enter your email, get a 6-digit code (expires after 60 minutes), enter the code + new password on one screen to reset it
- Clean Tailwind v4 UI, mobile-friendly navigation
- Public landing page (`/`) with entry points for Employee Login, Guest Leave, and Borrow Equipment

There is no self-service approval anymore — leave and equipment requests from both employees and guests go to **pending** status and require an admin to approve or reject them. Guests only ever see the leave and borrow forms (no login, no dashboard access).

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ and npm
- SQLite extension for PHP (`pdo_sqlite`, usually enabled by default)

## Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Copy the environment file
cp .env.example .env

# 3. Generate the application key
php artisan key:generate

# 4. Create the SQLite database file (an empty one is already included,
#    but if it's missing or you deleted it, recreate it):
touch database/database.sqlite

# 5. Run migrations and seed demo data (2 demo employees + full equipment inventory)
php artisan migrate --seed

# 6. Link storage so uploaded selfie photos are publicly viewable
php artisan storage:link

# 7. Install JS dependencies and build assets
npm install
npm run build
# (or `npm run dev` while developing, alongside `php artisan serve`)

# 8. Serve the app
php artisan serve
```

Visit `http://localhost:8000`.

> **Upgrading from an earlier copy of this project?** The database schema changed again (new `role` column on `users`, `status`/`reviewed_by`/`reviewed_at` columns on `leave_requests` and `borrow_requests`). Run `php artisan migrate:fresh --seed` to rebuild the database cleanly.

### Demo logins

- **Admin:** `admin@example.com` / `password` — logs straight into `/admin`
- **Employee:** `demo@example.com` / `password`
- **Employee (for testing chat):** `aisyah@example.com` / `password`

Or register a new employee account from `/register` (new registrations are always `role = employee`; promote someone to admin by editing their `role` column in the database).

## Approval workflow

Leave requests and equipment bookings (from both employees and guests) are created with `status = pending`. Nothing is active until an admin approves it from `/admin/leave` or `/admin/borrow`:

- **Leave:** pending → approved / rejected. This is purely a status log — approving doesn't affect attendance calculations.
- **Equipment borrowing:** pending → approved / rejected. Overlap checking treats *pending and approved* bookings as "live" (so two people can't both have live requests approved for the same overlapping dates), but only **approved** bookings actually block availability shown on the public borrow page. The admin gets a safety check too: approving a booking that would overlap an already-approved one for the same item is blocked with an error.
- Only **approved** bookings can be marked as returned — by the employee themselves (from **My Borrow History**) or by an admin (useful for guest bookings, since guests have no login to return through).

## Configuring work hours (late / overtime rules)

Edit these values in your `.env` file:

```env
COMPANY_WORK_START=09:00
COMPANY_WORK_END=18:00
COMPANY_LATE_GRACE_MINUTES=10
```

- Clocking in after `COMPANY_WORK_START` + grace period is marked **late**, and the minutes late are recorded.
- Clocking out after `COMPANY_WORK_END` records **overtime minutes**.
- These are global (fixed) hours applied to every employee.

## Notes on the webcam capture

- The browser will prompt for camera permission the first time an employee clicks **Clock In** or **Clock Out**.
- The captured photo is sent to the server as a base64 JPEG and stored under `storage/app/public/attendance-photos/YYYY/MM/DD/...`, so make sure step 6 (`storage:link`) has been run.
- Camera access requires a **secure context** — `localhost` works fine for development, but in production you'll need **HTTPS**.

## Notes on password reset emails

By default `MAIL_MAILER=log`, so the verification code email is **not actually sent** — it's written to `storage/logs/laravel.log` instead. Open that file and search for "Your Password Reset Code" to find the 6-digit code while testing locally.

To actually receive the email, set real SMTP credentials in `.env` (e.g. Mailtrap, Gmail app password, your own SMTP server):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=hello@example.com
```

## Notes on chat

- Chat is polling-based (checks for new messages every 3 seconds, and the nav unread badge refreshes every 10 seconds) — no WebSocket server or third-party service required.
- Only logged-in employees can access `/chat`; every other registered employee appears as a contact automatically.

## Notes on equipment borrowing

- Editing the inventory: update `database/seeders/EquipmentSeeder.php` and re-run `php artisan db:seed --class=EquipmentSeeder`, or manage rows directly in the `equipment` table.
- Availability is calculated live from unreturned bookings whose date range overlaps the requested dates — there's no separate "approval" step, consistent with how Leave works in this system.
- Guests can book equipment but can't mark it as returned (no login) — only the equipment's own return date is used to determine availability for the next booking. Employees can mark their own bookings as returned early from **My Borrow History**.

## Tech notes

- Tailwind v4 is wired up via the official `@tailwindcss/vite` plugin — there's no `tailwind.config.js`; theme customization lives in `resources/css/app.css` using the `@theme` directive.
- No Alpine.js/Livewire dependency — mobile nav, webcam capture, and chat are all plain vanilla JS (`resources/js/webcam.js`, `resources/js/chat.js`).
- Default DB is SQLite for zero-config local setup; swap `DB_CONNECTION` in `.env` to `mysql` (config already included) if you'd rather use MySQL.

