# CM Sera Tool (Laravel)

Modern Laravel rebuild of the legacy CM Sera keyword competitiveness tool.

## Features

- Domain and multi-keyword SEO analysis
- Google search scraping for top-ranking pages per keyword
- PageRank, backlink, and anchor-text metrics
- Optional email delivery of report results
- Session persistence of form inputs

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ (for frontend assets)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

Configure mail settings in `.env` if you want email delivery:

```
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=...
```

Optional proxy list for Google requests (comma-separated `host:port` values):

```
SERA_HTTP_PROXIES=proxy1:8080,proxy2:8080
```

## Development

```bash
php artisan serve
npm run dev
```

Visit `http://127.0.0.1:8000`.

## Legacy Code

The original PHP application is preserved in `legacy/` for reference.

## Notes

Google scraping and PageRank lookup depend on external services that have changed significantly since the original tool was written. Results may vary or fail without working HTTP proxies. Configure `SERA_HTTP_PROXIES` for production use.
