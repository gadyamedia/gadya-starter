# Gadya Starter

The starting point for a new Gadya Media client website: Laravel 13, Filament 5 and [Gadya CMS](https://github.com/gadyamedia/gadya-cms), wired together and tested.

Already in place:

- **Public site from data.** Every page lives in the CMS site document and renders through `SiteController` and `pages/show.blade.php`. The client edits words and photos on the page and presses **Publish**.
- **The layout** (`layouts/site.blade.php`):
  - `@cmsSeo` in the head
  - `@cmsToolbar`
  - search and newsletter forms in the footer
  - the Gadya Media badge (`@gadyaBuiltBy`), in the site's own ink
- **The admin** at `/admin`, with user roles (contributor, editor, admin) and the two gates Gadya CMS needs.
- **Articles, events, search, the sitemap, robots.txt and llms.txt**, all on and using the site layout.
- **The scheduled jobs** in `routes/console.php`.
- **Brand colours and fonts** set from `.env` (`BRAND_*`).

## A new site

The `new-site` skill in the Gadya Claude Code plugin does all of this from a short interview. By hand:

```bash
gh repo create gadyamedia/client-site --private --template gadyamedia/gadya-starter --clone
cd client-site
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
php artisan gadya-cms:install --admin-name="Client Name" --admin-email=client@example.com --admin-password="..."
php artisan boost:install
```

Then:

1. Replace the placeholder business in `config/site.php` with the client's own: pages, menu, phone, address. `gadya-cms:install` seeds it once, and after that the client edits it in the admin.
2. Set `APP_NAME`, the `BRAND_*` colours and fonts, and `SITE_ENQUIRIES_EMAIL` in `.env`.
3. Restyle `layouts/site.blade.php` and `pages/show.blade.php` to the design. Keep the directives.
4. Run `php artisan gadya-cms:audit`. It lists anything not taken up.

## Hosting on Laravel Forge

A deploy script:

```bash
cd $FORGE_SITE_PATH
git pull origin $FORGE_SITE_BRANCH
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm ci && npm run build
$FORGE_PHP artisan migrate --force
$FORGE_PHP artisan filament:assets
$FORGE_PHP artisan optimize
$FORGE_PHP artisan queue:restart
```

- Add the scheduler (`php artisan schedule:run` every minute) and a queue worker.
- In the site's nginx configuration, delete the `location = /robots.txt` line so the generated robots.txt is served.
- Connect the site to the Gadya portal: **Sites → Connect a site** gives a code for `php artisan gadya:connect`.

## Tests

```bash
php artisan test --compact
```
