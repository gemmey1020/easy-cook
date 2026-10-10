# Easy Cook

Easy Cook is a Laravel application for a healthy meal planning and delivery service. It combines the public food and plan catalog with customer accounts, subscriptions, checkout, recipes, tips, assessments, and staff dashboards.

## Main areas

- Meal plans, menus, recipes, and nutrition content
- Customer profiles, addresses, and subscription workflows
- Checkout and Paymob payment integration
- Admin tools for discounts, coupons, invitations, and subscriptions
- Public content pages, search, and SEO routes

## Stack

- PHP 8.1 or newer and Laravel 10
- Composer
- Node.js and npm for Vite assets
- MySQL or MariaDB by default

## Local setup

```bash
cp .env.example .env
composer install
npm ci
php artisan key:generate
```

Set the local database connection and any optional mail or Paymob sandbox settings in `.env`. Then run:

```bash
php artisan migrate
npm run build
php artisan serve
```

The optional `php artisan db:seed` command seeds public meal, menu, plan, recipe, and tips catalog content only. This export does not include account, assessment, payment, subscription, or coupon seed data, production database dumps, environment secrets, or domain ownership verification files.

Runtime assets are under `public/assets`. The duplicate source asset tree and dashboard demo pages have been omitted from this standalone export.

## License

No project license has been selected. Public visibility does not grant permission to reuse or redistribute this code; all rights remain reserved by the copyright holder unless a license is added. Bundled third-party components remain subject to their own terms.
