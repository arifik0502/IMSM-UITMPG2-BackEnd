# Deploying the API to Render

## 0. Push this folder to a GitHub repo

Render deploys from a git repo, not a zip upload.

```bash
cd attendance-api        # this folder
git init
git add .
git commit -m "Laravel attendance API"
git branch -M main
git remote add origin https://github.com/<you>/attendance-api.git
git push -u origin main
```

## 1. Create the Postgres database

In the Render dashboard: **New +** → **PostgreSQL**.
- Name: `attendance-db`, plan: Free is fine to start.
- Once created, open it and copy the **Internal Database URL** (starts with
  `postgres://...`) — you'll paste this into `DB_URL` below.

(If you'd rather not click through this by hand, `render.yaml` in this repo
is a Blueprint — use **New +** → **Blueprint** and point it at your repo to
provision the database and web service together in one step, then skip to
step 3.)

## 2. Create the web service

**New +** → **Web Service** → connect your GitHub repo.

- **Runtime**: Docker (Render will detect the `Dockerfile` automatically)
- **Plan**: Free is fine to start (note: free services spin down after
  inactivity and take ~30s to wake up on the next request)
- **Environment variables** — add these under the service's "Environment" tab:

| Key | Value |
|---|---|
| `APP_NAME` | `Attendance System` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | leave blank — the entrypoint script generates one on first boot, but see the note below |
| `DB_CONNECTION` | `pgsql` |
| `DB_URL` | the Internal Database URL from step 1 |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `FILESYSTEM_DISK` | `public` |
| `ATTENDANCE_PHOTOS_DISK` | `public` (see the storage note below) |
| `MAIL_MAILER` | `log` (see the mail note below) |
| `COMPANY_WORK_START` | `09:00` |
| `COMPANY_WORK_END` | `18:00` |
| `COMPANY_LATE_GRACE_MINUTES` | `10` |
| `FRONTEND_URL` | your Vercel URL once you have it, e.g. `https://your-app.vercel.app` |

**Generate a real APP_KEY instead of relying on the auto-generated one:**
locally run `php artisan key:generate --show` (needs PHP; or use an online
Laravel key generator) and paste the `base64:...` value into `APP_KEY`.
Without this, every container restart on a free plan invalidates existing
sessions and encrypted data.

Click **Create Web Service**. Render will build the Docker image and start
the container. Watch the deploy logs — `entrypoint.sh` runs migrations
automatically on boot.

Your API is now live at `https://attendance-api-xxxx.onrender.com`. Test it:

```bash
curl https://attendance-api-xxxx.onrender.com/up            # health check
curl -X POST https://attendance-api-xxxx.onrender.com/api/auth/login \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

## 3. Seed demo data (optional, first deploy only)

Render's dashboard has a **Shell** tab on the web service. Open it and run:

```bash
php artisan db:seed
```

This creates the same demo accounts as the original app:
- Admin: `admin@example.com` / `password`
- Employee: `demo@example.com` / `password`
- Equipment inventory for the borrow feature

## 4. Important limitation: selfie photo storage

Render's free/starter web services use an **ephemeral filesystem** — any
file written to local disk (including clock-in/out selfies saved on the
`public` disk) is **wiped on every redeploy and every time the container
restarts**, and isn't shared if you ever scale to multiple instances.

For a demo/portfolio project this is often fine to accept as-is. For
anything real, switch to object storage:

1. Create a free bucket on **Cloudflare R2** (10GB free) or use AWS S3 /
   DigitalOcean Spaces.
2. Add the bucket credentials as `AWS_*` env vars (see `.env.example`).
3. Set `ATTENDANCE_PHOTOS_DISK=s3`.
4. Add the S3 driver package: add `"league/flysystem-aws-s3-v3": "^3.0"`
   to `composer.json` and redeploy (Render's build step installs it).

No application code changes needed beyond that — `AttendanceController`
already reads the disk name from `ATTENDANCE_PHOTOS_DISK`.

Alternatively, Render offers **persistent disks** as a paid add-on you can
mount at `storage/app/public` — simpler, but ties you to a single instance.

## 5. Email (forgot-password codes) — Gmail SMTP

The forgot-password flow (both `/forgot-password` on the web app and
`/api/auth/forgot-password` on the API) is fully implemented already — it
just needs real mail credentials to actually deliver the 6-digit code
instead of writing it to the log.

**Generate a Gmail App Password** (this is different from your normal Gmail
password — Google blocks plain-password SMTP logins):

1. Turn on **2-Step Verification** on the Gmail account, if it isn't
   already: https://myaccount.google.com/security
2. Go to https://myaccount.google.com/apppasswords, sign in again if asked.
3. Under "App name" type something like `Attendance System` and click
   **Create**. Google shows a 16-character password (spaces don't matter) —
   copy it now, it's shown only once.

**Set the environment variables** on the Render web service (Environment
tab):

| Key | Value |
|---|---|
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_ENCRYPTION` | `tls` |
| `MAIL_USERNAME` | your full Gmail address, e.g. `you@gmail.com` |
| `MAIL_PASSWORD` | the 16-character App Password from step 3 (no spaces) |
| `MAIL_FROM_ADDRESS` | same Gmail address |
| `MAIL_FROM_NAME` | `Attendance System` |

If you deploy via the `render.yaml` Blueprint, `MAIL_USERNAME`,
`MAIL_PASSWORD`, and `MAIL_FROM_ADDRESS` are marked `sync: false`, which
means Render will prompt you to type them into the dashboard rather than
storing them in the file/git — fill them in there.

Save, and Render restarts the service automatically. Test it:

```bash
curl -X POST https://attendance-api-xxxx.onrender.com/api/auth/forgot-password \
  -H "Content-Type: application/json" -H "Accept: application/json" \
  -d '{"email":"demo@example.com"}'
```

You should get the code in your inbox within a few seconds. Gmail's normal
account sending limit is 500 emails/day, which is plenty for this kind of
app. If Render's outbound network ever blocks port 587 (uncommon, but some
hosts do), switch `MAIL_PORT` to `465` and `MAIL_ENCRYPTION` to `ssl`.

For local development, leave `MAIL_MAILER=log` in your local `.env` — the
code will just print to your terminal/log instead of sending a real email,
which is faster for testing.

## 6. Updating FRONTEND_URL after deploying the frontend

Once your Vercel frontend is live, come back to the Render service's
environment variables and set `FRONTEND_URL` to its real URL, then
**Manual Deploy → Deploy latest commit** (or just save — Render restarts
automatically on env var changes) to pick up the new CORS setting.
