# eBilling - Invoice Management System

A modern invoice management and tracking system built with Laravel and Filament, designed for efficient billing operations and shipment tracking.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![Filament](https://img.shields.io/badge/Filament-3.x-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)

## Features

- 📋 **Invoice Management** - Comprehensive invoice listing and management
- 📦 **Shipment Tracking** - Real-time tracking of shipments with detailed information
- 🔍 **Advanced Filtering** - Filter invoices by movement type, billing type, and date ranges
- 📊 **Dynamic Widgets** - Interactive widgets for invoice details and shipment information
- 🔐 **User Authentication** - Secure authentication with role-based access control
- 📱 **Responsive Design** - Mobile-friendly interface built with TailwindCSS
- ⚡ **Real-time Updates** - Livewire-powered reactive components
- 📄 **Document Management** - View and download invoices, Musak documents, and AWB scans
- 🌐 **Multi-Movement Support** - Handle both Export and Import operations
- 🏢 **Multiple Billing Types** - Support for EPP, IFC, CNF, and EFD billing

## Recent Updates

### Session Refactoring (Latest)
- ✅ Removed all session-based state management
- ✅ Implemented Livewire reactive properties for better performance
- ✅ Added static property pattern for Sushi model data passing
- ✅ Improved widget reactivity with `#[Reactive]` attributes
- ✅ Enhanced code maintainability and follows Laravel/Livewire best practices

## Requirements

Make sure all dependencies have been installed before moving on:

- [PHP](https://secure.php.net/manual/en/install.php) >= 8.2
- [Composer](https://getcomposer.org/download/)
- [Node.js](http://nodejs.org/) >= 18
- [Yarn](https://yarnpkg.com/en/docs/install) or npm
- MySQL/MariaDB or PostgreSQL

## Installation

1. **Clone the repository**
```sh
git clone https://github.com/ashek1412/ebilling.git
cd ebilling
```

2. **Install PHP dependencies**
```sh
composer install
```

3. **Install JavaScript dependencies**
```sh
yarn install
# or
npm install
```

4. **Configure environment**
```sh
cp .env.example .env
php artisan key:generate
```

5. **Configure your `.env` file with:**
   - Database credentials
   - ERP API URL and credentials
   - DMS (Document Management System) credentials
   - Application settings

6. **Run migrations**
```sh
php artisan migrate --seed
```

7. **Build assets**
```sh
yarn build
# or for development
yarn dev
```

8. **Start the development server**
```sh
php artisan serve
```

## Usage

### Admin Panel

Access the admin panel at `/admin` with your credentials.

**Default Admin User:**
```yaml
Username: admin
Password: admin
```

### Invoice Management

1. Navigate to **Invoices** in the sidebar
2. Use filters to search by:
   - Movement (Export/Import)
   - Type (EPP/IFC/CNF/EFD)
   - Date range
3. Click **shipments** to view detailed shipment information
4. Use action buttons to:
   - View invoice PDFs
   - View Musak documents
   - Access shipment details

### Tracking

1. Navigate to **Tracking** page
2. Enter tracking number
3. View shipment details including:
   - Date and movement type
   - DWS (Dimensional Weight Scan)
   - AWB Scan documents

## Architecture

### Key Components

- **InvoiceListPage** - Main invoice listing with filters and actions
- **InvoiceTableWidget** - Reactive widget displaying shipment details
- **Invoice Model** - Sushi-based model for dynamic invoice data
- **AccountController** - Handles API communication with ERP and DMS systems
- **Tracking System** - Real-time shipment tracking functionality

### Data Flow

```
User Action → Livewire Component → Controller → External API
                    ↓
            Reactive Widget Update
                    ↓
            Display Updated Data
```

## Plugins Used

The following [Filament plugins](https://filamentphp.com/plugins) are implemented:

| **Plugin**                                                          | **Description**                                    |
| :------------------------------------------------------------------ | :------------------------------------------------- |
| [Curator](https://github.com/awcodes/filament-curator)              | Media library management                           |
| [Breezy](https://github.com/jeffgreco13/filament-breezy)            | User profile pages and 2FA support                 |
| [Peek](https://github.com/pboivin/filament-peek)                    | Front-end previews of resources                    |
| [Logger](https://github.com/z3d0x/filament-logger)                  | Resource activity logging                          |

## Configuration

### ERP Integration

Configure ERP connection in `.env`:
```env
ERP_URL=your_erp_api_url
EBIL_ID=your_ebill_id
EBIL_KEY=your_ebill_key
```

### DMS Integration

Configure Document Management System in `.env`:
```env
DMS_URL=your_dms_api_url
DMS_USER=your_dms_username
DMS_PASS=your_dms_password
```

## Development

### Code Style

The project uses Laravel Pint for code formatting:
```sh
./vendor/bin/pint
```

### Testing

Run tests with:
```sh
php artisan test
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.

## Support

For support, please open an issue on GitHub or contact the development team.

## Acknowledgments

- Built with [Laravel](https://laravel.com/)
- Admin panel powered by [Filament](https://filamentphp.com/)
- Frontend reactivity by [Livewire](https://livewire.laravel.com/)
- Styling with [TailwindCSS](https://tailwindcss.com/)
