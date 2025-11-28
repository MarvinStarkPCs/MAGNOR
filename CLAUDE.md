# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

MAGNOR is a scrapyard management system (sistema de chatarrería) built with Laravel 12 and React (using Inertia.js). It manages scrap materials inventory, suppliers (proveedores), clients, purchases, sales, and inventory movements. The system uses Laravel Breeze for authentication scaffolding.

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

### Inertia.js Commands
```bash
# Generate Ziggy route helpers (for use in React components)
php artisan ziggy:generate

# Clear Inertia cache
php artisan cache:clear
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

### React + Inertia.js Frontend

The application uses React with Inertia.js as the frontend framework:

- **Inertia Pages**: Located in `resources/js/Pages/`
  - React components that serve as pages
  - Automatically mapped to Laravel routes
  - Receive data as props from Laravel controllers
  - Examples: `Dashboard.jsx`, `Profile/Edit.jsx`, etc.

- **Shared Components**: Located in `resources/js/Components/`
  - Reusable React components
  - Layout components (`AuthenticatedLayout.jsx`, `GuestLayout.jsx`)
  - Form elements (`TextInput.jsx`, `PrimaryButton.jsx`, etc.)

- **Authentication**: Laravel Breeze provides:
  - Login, registration, password reset flows
  - Email verification
  - Profile management
  - Built with React + Inertia.js

- **Routing**:
  - Laravel routes defined in `routes/web.php`
  - Ziggy package provides Laravel routes to React components
  - Use `route()` helper in React for type-safe routing

### Database Schema

Core entities for scrapyard operations (migrations in `database/migrations/`):
- `users` - Application users
- `materiales` - Scrap materials/metals with stock tracking (copper, aluminum, steel, etc.)
- `proveedores` - Suppliers who sell scrap materials to the business
- `clientes` - Clients/customers who purchase scrap materials
- `compras` - Purchase orders for acquiring scrap materials
- `detalle_compras` - Purchase order line items (individual materials per purchase)
- `ventas` - Sales orders for selling scrap materials
- `detalle_ventas` - Sales order line items (individual materials per sale)
- `movimientos_inventario` - Inventory movements/adjustments for material tracking

### Models

- Models use Spanish table names (e.g., `Material` model uses `materiales` table)
- Specify `protected $table` property when table name doesn't follow Laravel conventions
- Decimal fields (prices, stock) are cast to `decimal:2`
- Models typically include relationship methods even if related models don't exist yet

### Frontend Stack

- **React** - JavaScript library for building user interfaces
- **Inertia.js** - Modern monolith framework connecting Laravel and React
  - No need for separate API endpoints
  - Server-side routing with client-side rendering
  - Shared data between backend and frontend via props
- **Vite** - Fast build tool for asset bundling (`vite.config.js`)
- **Tailwind CSS** - Utility-first CSS framework
- **Ziggy** - Laravel route helper for JavaScript
- **Axios** - HTTP client (used internally by Inertia)
- Entry point: `resources/js/app.jsx`
- Styles: `resources/css/app.css`

### Configuration Notes

- Default database: SQLite (`database/database.sqlite`)
- Queue connection: database
- Session driver: database
- Default locale: English (configurable via `APP_LOCALE`)
- Mail driver: log (for development)

## Common Patterns

### Creating a New Inertia Page

1. Create a controller: `php artisan make:controller PageNameController`
2. Create a React component in `resources/js/Pages/PageName.jsx`
3. Define route in `routes/web.php`:
   ```php
   Route::get('/page-name', [PageNameController::class, 'index'])->name('page.name');
   ```
4. Return Inertia response from controller:
   ```php
   return Inertia::render('PageName', [
       'data' => $data,
   ]);
   ```

### Creating Reusable Components

1. Create component in `resources/js/Components/ComponentName.jsx`
2. Export component:
   ```jsx
   export default function ComponentName({ prop1, prop2 }) {
       return <div>...</div>;
   }
   ```
3. Import and use in pages:
   ```jsx
   import ComponentName from '@/Components/ComponentName';
   ```

### Working with Forms

1. Use Inertia's form helper for form state and submission:
   ```jsx
   import { useForm } from '@inertiajs/react';

   const { data, setData, post, processing, errors } = useForm({
       field: '',
   });

   const submit = (e) => {
       e.preventDefault();
       post(route('route.name'));
   };
   ```

### Navigation with Inertia

- Use Inertia's `Link` component for navigation:
  ```jsx
  import { Link } from '@inertiajs/react';
  <Link href={route('route.name')}>Navigate</Link>
  ```
- Use Ziggy's `route()` helper to generate URLs from route names
