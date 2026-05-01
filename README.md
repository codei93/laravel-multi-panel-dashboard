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

6. **Set up Filament Shield**

   Shield powers the role/permission system across all three panels. Run the
   commands below in order — `shield:setup` is required once for the project,
   while `shield:install` must be run for **each** panel.

   ```bash
   # One-time core setup (publishes config + migrations, runs migrations)
   php artisan shield:setup

   # Install Shield into each panel (creates the Role resource, etc.)
   php artisan shield:install default
   php artisan shield:install blog
   php artisan shield:install travel

   # Generate policies & permissions for all discovered Filament resources
   php artisan shield:generate --all
   ```

7. **Seed the database**

   The seeders create the panel-access permissions, the `super_admin` role,
   and a default super admin user.

   ```bash
   php artisan db:seed
   ```

   To promote an existing user to super admin instead, use:

   ```bash
   php artisan shield:super-admin --user={user_id}
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Start the development server**
   ```bash
   php artisan serve
   ```

### Useful Shield commands

| Command | Purpose |
|---------|---------|
| `php artisan shield:setup` | Publishes Shield config and migrates core tables |
| `php artisan shield:install {panel}` | Registers Shield (Role resource etc.) on a panel |
| `php artisan shield:generate --all` | Generates permissions/policies for all resources |
| `php artisan shield:generate --resource=PostResource` | Generate for a single resource |
| `php artisan shield:super-admin --user={id}` | Assign the `super_admin` role to a user |
| `php artisan shield:seeder` | Build a seeder from current roles/permissions |
| `php artisan shield:publish` | Publish Shield's Role resource for customisation |

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