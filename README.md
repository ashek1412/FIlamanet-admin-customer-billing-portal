# Customer billing portal 

A modern multitenant invoice management and tracking system for courier customer built with Laravel and Filament, designed for efficient billing operations and shipment tracking.

### Overview:
- **Name**: Customer - biiling / Invoice portal.
- **Purpose**: A multitenant invoice  and shipment tracking platform, built using Laravel and Filament.
- **Features**:
  - Invoice and shipment tracking management.
  - Advanced filtering options.
  - Responsive frontend using TailwindCSS.
  - Livewire-powered updates for real-time information.
  - Multi-movement and multi-billing-type support.
  - Document management to view/download relevant files.

### Recent Updates:
- Refactored session management for better performance using Livewire's reactive components and best practices.

### Requirements:
- PHP 8.2+, Composer, Node.js 18+, and MySQL/MariaDB or PostgreSQL.

### Installation & Setup:
The guide provides commands and steps to:
1. Clone the repository.
2. Install PHP and JavaScript dependencies.
3. Configure environment variables for database, ERP, and DMS.
4. Conduct migrations and build assets.
5. Run a development server.

### Usage:
- Admin Panel accessible at `/admin`.
- Default admin credentials: `admin/admin`.
- Navigate specific sections for invoice management and shipment tracking.

### Architecture:
- Key components include widgets and components for invoices and tracking.
- Data flow integrates user actions, Livewire updates, and API interactions.

### Plugins:
Uses various Filament plugins for media, user, resource preview, and logging functionality.


### Development & Testing:
- Use Laravel Pint for code formatting.
- Run tests via `php artisan test`.

### Contribution:
Encourages contributions with a process for forking and submitting pull requests.

### Licensing & Support:
- Licensed under the MIT License.
- Support options via GitHub issues.

### Acknowledgments:
Built with tools like Laravel, Filament, Livewire, and TailwindCSS.

The README is a detailed and well-structured document for developers, offering clear steps on setup, usage, and contributing to the project.
