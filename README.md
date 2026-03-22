# Multi-Panel

A Laravel 12 application with multiple Filament admin panels (Default, Blog, Travel) featuring role-based access control.

## Features

- **Multiple Panels**: Default, Blog, and Travel admin panels
- **Authentication**: Filament authentication with Spatie Permission (Filament Shield)
- **RBAC**: Role-based access control with panel-specific permissions
- **Blog Management**: Categories and Posts management
- **Travel Management**: Customers and Trips management

## Panels

| Panel | URL | Permission |
|-------|-----|-------------|
| Default | `/default` | `access_default_panel` |
| Blog | `/blog` | `access_blog_panel` |
| Travel | `/travel` | `access_travel_panel` |

## Installation

### Prerequisites

- PHP 8.3+
- Composer
- Node.js & NPM

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/codei93/multi-panel.git
   cd multi-panel
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database** in `.env` file, then run:
   ```bash
   php artisan migrate
   ```

6. **Seed the database**
   ```bash
   php artisan db:seed
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the development server**
   ```bash
   php artisan serve
   ```

## Usage

- Access the default panel at `http://localhost:8000/default`
- Login with seeded super_admin credentials (check `DatabaseSeeder`)
- Users with appropriate permissions will see panel links in the user menu

## Permissions

Panel access permissions are defined in `database/data/permissions.json`:
- `access_default_panel` - Access to Default panel
- `access_blog_panel` - Access to Blog panel
- `access_travel_panel` - Access to Travel panel

## Tech Stack

- Laravel 12
- Filament 4
- Livewire 3
- Spatie Permission (Filament Shield)

## Author

[Aggrey Mutagaywa](https://www.linkedin.com/in/aggrey-mutagaywa/) - [GitHub](https://github.com/codei93)

## License

MIT