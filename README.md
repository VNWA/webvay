# FinVay — Digital lending (findvay.net)

**Ngôn ngữ mặc định:** tiếng Việt (`APP_LOCALE=vi`, `lang/vi.json`). Filament dùng bản dịch sẵn có (`vendor/filament/.../lang/vi`). Múi giờ mặc định: `Asia/Ho_Chi_Minh`.

Ứng dụng cho vay trực tuyến xây trên **Laravel 13** (skeleton ships with `laravel/framework` ^13; behavior matches a Laravel 12–style app), **Blade**, **Tailwind CSS v4**, **Alpine.js**, **Filament v4** admin, **MySQL/SQLite**, **queues**, **Redis-ready** workers, **Resend** mail, và **DomPDF** hợp đồng.

Trang công khai và email hỗ trợ hiển thị theo cấu hình (mặc định gắn **findvay.net**):

```env
FINVAY_SUPPORT_EMAIL=support@findvay.net
FINVAY_PUBLIC_SITE=https://findvay.net
```

## Features

- Landing page with calculator, trust sections, FAQ, mobile sticky CTA
- Email **OTP** login (6-digit, 5-minute expiry, resend cooldown, rate limits, IP logging, audit trail)
- Multi-step wizard: personal → employment → CCCD + selfie uploads (preview, camera-friendly)
- Queued **AI-assisted** review (8–15s) with progress polling
- **LoanScoringService** (income, age, job title, documents) → score, risk band, approved amount, amortized monthly payment
- Result page with **confetti** + count-up animation
- **PDF contract** generation (queued) + **contract OTP** signing
- **Filament** admin: stats widget, users, applications (infolist, OCR JSON, downloads), contracts, read-only audit logs

## Requirements

- PHP **8.3+** with extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `gd`, **`intl`** (required by Filament at runtime — **install it for production**)

### PHP `intl` (Filament)

Filament declares `ext-intl`. **Recommended:** install the package for your PHP version, for example on Ubuntu/Debian:

```bash
sudo apt update && sudo apt install -y php8.4-intl   # match `php -v` (e.g. php8.3-intl)
sudo phpenmod intl  # if needed, then verify: php -m | grep intl
```

The `composer setup` script runs `composer install --ignore-platform-req=ext-intl` so a first-time install can finish **without** intl; you still need intl enabled before using the admin panel reliably.

- Node 20+ / npm
- MySQL 8+ (or use SQLite for local dev)
- Redis (optional but recommended for `QUEUE_CONNECTION=redis`)

## Quick start

```bash
cp .env.example .env
php artisan key:generate
# Set DB_* for MySQL, or leave sqlite for quick try
php artisan migrate --seed
npm install
npm run build
composer run dev   # serves app + queue + vite + logs (see composer.json)
```

### Admin

- URL: `/admin`
- Seeded user: `admin@gmail.com` / `admin@123`

### Filament admin (production)

Nếu đăng nhập `/admin` xong bị **tải lại trang login**, kiểm tra:

1. **`APP_URL`** trùng URL thật (gồm `https://` và host).
2. Bảng **`sessions`** đã migrate khi `SESSION_DRIVER=database`.
3. User có **`role` = `admin`** trong DB.

### Mail (Resend)

1. Tạo API key tại [Resend](https://resend.com) và thêm vào `.env`:

```env
RESEND_API_KEY=re_xxxx
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=onboarding@resend.dev
MAIL_FROM_NAME="FinVay"
```

Địa chỉ `onboarding@resend.dev` dùng được cho **thử nghiệm** theo tài liệu Resend. Khi lên production, xác minh domain của bạn trên Resend và đổi `MAIL_FROM_ADDRESS` sang email thuộc domain đó.

2. Gửi email thử (dùng cùng mailer với OTP):

```bash
php artisan mail:test you@example.com
```

Hoặc chạy `php artisan mail:test` rồi nhập email khi được hỏi.

For local dev without Resend, keep `MAIL_MAILER=log` and watch `storage/logs/laravel.log`.

### Queues

```env
QUEUE_CONNECTION=redis   # or database
REDIS_HOST=127.0.0.1
```

`composer run dev` đã chạy worker với `--queue=default,mail` (OTP email nằm trên queue **`mail`**).

Chạy worker tay:

```bash
php artisan queue:work redis --queue=default,mail
```

## Architecture

| Layer | Path |
|-------|------|
| HTTP | `app/Http/Controllers` |
| Services | `app/Services` (`OtpService`, `LoanScoringService`, `ContractService`, `UploadService`, `AuditLogService`) |
| Repositories | `app/Repositories/Contracts`, `app/Repositories/Eloquent` |
| Jobs | `app/Jobs` (OTP mail, AI verification, PDF generation) |
| Domain | `app/Models`, `app/Enums` |
| Admin | `app/Filament` |
| Views | `resources/views` |

## Security notes 

- OTP values are queued in job payloads briefly — for production, prefer synchronous mail or encrypted jobs.
- Uploaded files live on the `local` disk under `storage/app/private` (see `config/filesystems.php`).
- Enable `intl` and real SMTP/Resend before any public deployment.

## License

MIT (same as Laravel default).
