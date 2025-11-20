# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MAGNOR is a scrapyard management system (sistema de chatarrería) built with Laravel 12 and Filament 3.3 admin panel. It manages scrap materials inventory, suppliers (proveedores), clients, purchases, sales, and inventory movements. The system uses Spatie roles and permissions for user authorization.

## Development Commands

### Initial Setup
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database (default)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed database with test user
php artisan db:seed
```

### Development Server
```bash
# Run all services concurrently (server, queue, logs, vite)
composer dev

# Or run individual services:
php artisan serve              # Development server
npm run dev                    # Vite dev server for assets
php artisan queue:listen       # Queue worker
php artisan pail               # Real-time logs
```

### Testing
```bash
# Run all tests
composer test

# Or use artisan directly:
php artisan test

# Run specific test file
php artisan test --filter=TestClassName

# Run with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Run Laravel Pint (code style fixer)
./vendor/bin/pint

# Check code style without fixing
./vendor/bin/pint --test
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset and re-run all migrations
php artisan migrate:fresh

# Reset and seed
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_table_name
```

### Filament Commands
```bash
# Create a new Filament resource
php artisan make:filament-resource ModelName --generate

# Create a custom Filament page
php artisan make:filament-page PageName

# Create a Filament widget
php artisan make:filament-widget WidgetName

# Upgrade Filament (runs automatically after composer update)
php artisan filament:upgrade

# Create a Filament user
php artisan make:filament-user
```

### Build for Production
```bash
# Build frontend assets
npm run build

# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Architecture

### Filament Admin Panel

The application is built around Filament admin panel at `/admin` path:

- **Panel Provider**: `app/Providers/Filament/AdminPanelProvider.php` configures the admin panel, including:
  - Authentication
  - Navigation customization
  - User menu items (profile link)
  - Spatie roles/permissions plugin
  - Color scheme (Amber primary color)

- **Resources**: Located in `app/Filament/Resources/`
  - `MaterialResource` - Material/inventory management
  - `ProveedoresResource` - Supplier management
  - `UserResource` - User management with roles/permissions
  - Each resource has associated Pages for List/Create/Edit operations

- **Custom Pages**: Located in `app/Filament/Pages/`
  - `Dashboard.php` - Custom dashboard replacing default
  - `UserProfile.php` - User profile management
  - `GestionExtras.php` - Additional management page
  - Pages use custom Blade views in `resources/views/filament/pages/`

### Database Schema

Core entities for scrapyard operations (migrations in `database/migrations/`):
- `users` - Application users with Spatie roles/permissions
- `materiales` - Scrap materials/metals with stock tracking (copper, aluminum, steel, etc.)
- `proveedores` - Suppliers who sell scrap materials to the business
- `clientes` - Clients/customers who purchase scrap materials
- `compras` - Purchase orders for acquiring scrap materials
- `detalle_compras` - Purchase order line items (individual materials per purchase)
- `ventas` - Sales orders for selling scrap materials
- `detalle_ventas` - Sales order line items (individual materials per sale)
- `movimientos_inventario` - Inventory movements/adjustments for material tracking
- `permission_tables` - Spatie roles and permissions tables

### Models

- Models use Spanish table names (e.g., `Material` model uses `materiales` table)
- Specify `protected $table` property when table name doesn't follow Laravel conventions
- Decimal fields (prices, stock) are cast to `decimal:2`
- Models typically include relationship methods even if related models don't exist yet

### Frontend Stack

- **Vite** for asset bundling (`vite.config.js`)
- **Tailwind CSS 4.0** for styling
- **Axios** for HTTP requests
- Assets compiled from `resources/css/app.css` and `resources/js/app.js`

### Configuration Notes

- Default database: SQLite (`database/database.sqlite`)
- Queue connection: database
- Session driver: database
- Default locale: English (configurable via `APP_LOCALE`)
- Mail driver: log (for development)

## Common Patterns

### Creating a New Filament Resource

1. Create model: `php artisan make:model ModelName -m`
2. Define migration schema and run `php artisan migrate`
3. Generate Filament resource: `php artisan make:filament-resource ModelName --generate`
4. Customize form/table in resource class
5. Add any custom pages or widgets as needed

### Adding Custom Filament Pages

1. Create page class: `php artisan make:filament-page PageName`
2. Create corresponding Blade view in `resources/views/filament/pages/`
3. Set `protected static string $view` in page class
4. Configure navigation icon, title, and visibility with static properties
5. Page will auto-register if in `app/Filament/Pages/` (due to `discoverPages()`)

### Working with Roles & Permissions

- Uses `althinect/filament-spatie-roles-permissions` package
- Permission tables created via migration `2025_09_26_164818_create_permission_tables.php`
- Configure via Filament plugin in `AdminPanelProvider`
- Apply permissions to resources/pages using Filament's authorization methods
