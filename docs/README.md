# Developer documentation

This documentation describes the current repository state. It was prepared by scanning the Laravel package manifests, route files, package providers, config files, Blade views, frontend API calls, Docker files, seeders, and test folders.

The goal is handover clarity. These docs document what the application uses now. They do not list inactive payment gateways, removed shipping carriers, unused admin APIs, or package features that are not registered in config.

## Documentation map

| File | Read when you need |
| --- | --- |
| [../README.md](../README.md) | A short project overview and quick start |
| [SETUP_AND_DEPLOYMENT.md](SETUP_AND_DEPLOYMENT.md) | Local setup, required services, environment variables, and build commands |
| [ARCHITECTURE.md](ARCHITECTURE.md) | How the application is structured and how requests move through it |
| [PACKAGES.md](PACKAGES.md) | The purpose of each package under `packages/Frooxi/` |
| [API_REFERENCE.md](API_REFERENCE.md) | Active web routes and API endpoints used by the UI |
| [api/shop.md](api/shop.md) | Details for shop AJAX and checkout endpoints |
| [api/admin.md](api/admin.md) | Details for admin AJAX calls and the small set of admin API calls used by the UI |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Production Docker and GitHub Actions deployment |

## Current feature surface

The storefront includes:

- Homepage, top banners, category browsing, search, and flash sale pages.
- Product listing and product detail pages.
- Simple and configurable products.
- Cart, coupon apply or remove, custom shipping selection, payment selection, and one-page checkout.
- Customer registration, OTP verification, login, password reset, profile, addresses, wishlist, reviews, orders, reorder, cancel, and invoice print.
- Payment redirects and callbacks for SSLCommerz and bKash.
- Cash on Delivery for eligible carts.

The admin panel includes:

- Dashboard statistics.
- Catalog management for products, categories, attributes, and attribute families.
- Sales management for orders, invoices, and payment methods.
- Customer management for customers, customer addresses, customer groups, customer notes, customer carts, wishlist items, and reviews.
- Storefront management for hero carousel, flash sale, and custom shipping methods.
- Settings for channels, currencies, exchange rates, locales, inventory sources, roles, users, themes, and configuration values exposed by the current admin UI.
- Admin account settings and two-factor authentication.

## Documentation boundary

The codebase contains stale tests, comments, and unused route definitions from a broader ecommerce base. These docs treat a feature as active only when the current config, routes, and views use it.

The active product types are `simple` and `configurable`. The active shipping carrier is `customshipping`. The active payment methods are `cashondelivery`, `sslcommerz`, and `bkash`.

The admin panel mainly uses protected web routes. The `/api/v1/admin` route file exists, but only the endpoints called by the current admin views are listed as active API usage.

## Known handover risks

The repository has no installed `vendor/` or `node_modules/` in this workspace, so route tables and tests were verified from source files rather than by running Artisan.

The test folders include useful Pest and Playwright coverage, but several test files reference inactive features. Add a root `phpunit.xml`, remove stale test references, and build a small checkout and payment smoke suite before treating tests as a release gate.

The logged-in storefront compare button posts to `/api/compare`, but no matching route exists in the current shop route files. Guest compare uses `localStorage`.

The admin API file is mounted at `/api/v1/admin` with Laravel's `api` middleware only. The current admin UI uses only a small subset of those routes. Do not expose the full admin API as an external contract without adding authentication and reviewing every endpoint.
