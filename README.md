# 📁 Laravel Files Manager System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)
![AWS S3](https://img.shields.io/badge/Amazon_S3-569A31?style=for-the-badge&logo=amazon-s3&logoColor=white)
![Google Cloud](https://img.shields.io/badge/Google_Cloud-4285F4?style=for-the-badge&logo=google-cloud&logoColor=white)
![Azure](https://img.shields.io/badge/Azure-0078D4?style=for-the-badge&logo=azure-devops&logoColor=white)
![Pest](https://img.shields.io/badge/Pest-FF6B6B?style=for-the-badge&logo=pest&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)

**A secure, production-ready file management system with cloud storage support**

[Features](#-features) • [Tech Stack](#-tech-stack) • [Installation](#-installation) • [Configuration](#-configuration) • [Documentation](#-documentation) • [Testing](#-testing)

</div>

---

## 📖 About

**Laravel Files Manager System** is a comprehensive, production-ready file management application built with Laravel 12. It provides a secure way to upload, manage, and share files with features like expiration dates, signed URLs, cloud storage support, and a modern user interface.

### What This Project Does

This application allows users to:
- **Upload files** with drag-and-drop interface
- **Share files securely** using signed URLs
- **Set expiration dates** for temporary file sharing
- **Track downloads** and view statistics
- **Manage files** with full CRUD operations
- **Store files locally or in the cloud** (S3, Google Cloud, Azure)
- **Access files** through a beautiful, responsive dashboard

### Key Highlights

- ✅ **Production Ready** - Comprehensive security, validation, and error handling
- ✅ **Cloud Storage Support** - Works with AWS S3, Google Cloud Storage, and Azure
- ✅ **Local Storage Fallback** - Defaults to local storage, easy cloud migration
- ✅ **Well Tested** - 35+ tests with Pest PHP, 89 assertions
- ✅ **Modern Stack** - Laravel 12, Tailwind CSS, Alpine.js, Vite
- ✅ **Secure by Default** - Signed URLs, CSRF protection, rate limiting

---

## ✨ Features

### 🔐 Security Features
- **Signed URLs** - Secure file sharing with time-limited access
- **Authentication & Authorization** - User-based access control
- **CSRF Protection** - Built-in Laravel CSRF token validation
- **File Type Validation** - MIME type and extension restrictions
- **File Size Limits** - Configurable upload size restrictions
- **Rate Limiting** - 10 uploads per minute per user
- **Password Hashing** - Secure password storage with bcrypt
- **SQL Injection Protection** - Eloquent ORM protection
- **XSS Protection** - Blade templating auto-escaping

### ☁️ Storage Features
- **Local Storage** - Default filesystem storage (no configuration needed)
- **AWS S3 Integration** - Full S3 bucket support
- **Google Cloud Storage** - GCS bucket integration
- **Azure Blob Storage** - Azure container support
- **Easy Switching** - Change storage backend via environment variable
- **Seamless Migration** - Works identically across all storage backends

### 📊 File Management
- **Drag & Drop Upload** - Modern file upload interface
- **File Expiration** - Set expiration dates for shared files
- **Download Tracking** - Track how many times files are downloaded
- **File Deletion** - Secure file deletion with authorization
- **File Listing** - User-specific file management
- **Storage Statistics** - View total files, downloads, and storage used
- **Recent Files Dashboard** - Quick access to recently uploaded files

### 🎨 User Interface
- **Modern Design** - Beautiful Tailwind CSS interface
- **Responsive Layout** - Works on desktop, tablet, and mobile
- **Real-time Validation** - Instant feedback on file uploads
- **Copy Link Feature** - One-click sharing link copying
- **Dashboard Statistics** - Visual stats cards and metrics
- **Intuitive Navigation** - Easy-to-use navigation menu

### 🧪 Testing & Quality
- **35+ Tests** - Comprehensive test coverage
- **89 Assertions** - Thorough validation
- **Pest PHP** - Modern testing framework
- **Feature Tests** - Authentication, file management, profile
- **Unit Tests** - Component-level testing

---

## 🛠 Tech Stack

### Backend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| ![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) | 12.x | PHP Framework |
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white) | 8.3+ | Programming Language |
| ![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white) | Latest | Database (switchable to MySQL/PostgreSQL) |
| ![Pest](https://img.shields.io/badge/Pest-FF6B6B?style=flat-square&logo=pest&logoColor=white) | 4.x | Testing Framework |
| ![Laravel Breeze](https://img.shields.io/badge/Breeze-FF2D20?style=flat-square&logo=laravel&logoColor=white) | 2.x | Authentication Scaffolding |

### Frontend Technologies

| Technology | Version | Purpose |
|------------|---------|---------|
| ![Tailwind CSS](https://img.shields.io/badge/Tailwind-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white) | 3.x | CSS Framework |
| ![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=flat-square&logo=alpine.js&logoColor=white) | 3.x | JavaScript Framework |
| ![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white) | 7.x | Build Tool |
| ![Axios](https://img.shields.io/badge/Axios-5A29E4?style=flat-square&logo=axios&logoColor=white) | 1.x | HTTP Client |
| ![Blade](https://img.shields.io/badge/Blade-FF2D20?style=flat-square&logo=laravel&logoColor=white) | - | Templating Engine |

### Cloud Storage Providers

| Provider | Status | Configuration |
|----------|--------|---------------|
| ![AWS S3](https://img.shields.io/badge/AWS_S3-569A31?style=flat-square&logo=amazon-s3&logoColor=white) | ✅ Supported | `FILE_STORAGE_DISK=s3` |
| ![Google Cloud](https://img.shields.io/badge/Google_Cloud-4285F4?style=flat-square&logo=google-cloud&logoColor=white) | ✅ Supported | `FILE_STORAGE_DISK=gcs` |
| ![Azure](https://img.shields.io/badge/Azure-0078D4?style=flat-square&logo=azure-devops&logoColor=white) | ✅ Supported | `FILE_STORAGE_DISK=azure` |
| Local Storage | ✅ Default | `FILE_STORAGE_DISK=public` |

### Development Tools

| Tool | Purpose |
|------|---------|
| ![Laravel Pint](https://img.shields.io/badge/Pint-FF2D20?style=flat-square&logo=laravel&logoColor=white) | Code Style Fixer |
| ![Laravel Pail](https://img.shields.io/badge/Pail-FF2D20?style=flat-square&logo=laravel&logoColor=white) | Log Viewer |
| ![Docker](https://img.shields.io/badge/Docker-2496ED?style=flat-square&logo=docker&logoColor=white) | Container Platform |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white) | Database |
| ![Nginx](https://img.shields.io/badge/Nginx-009639?style=flat-square&logo=nginx&logoColor=white) | Web Server |
| ![Redis](https://img.shields.io/badge/Redis-DC382D?style=flat-square&logo=redis&logoColor=white) | Optional Cache & Sessions |
| ![Composer](https://img.shields.io/badge/Composer-885630?style=flat-square&logo=composer&logoColor=white) | PHP Dependency Manager |
| ![NPM](https://img.shields.io/badge/NPM-CB3837?style=flat-square&logo=npm&logoColor=white) | Node Package Manager |

---

## 📋 Requirements

### For Docker Installation
- **Docker** >= 20.10
- **Docker Compose** >= 2.0

### For Local Installation
- **PHP** >= 8.3
- **Composer** >= 2.0
- **Node.js** >= 18.0
- **NPM** >= 9.0 or **Yarn** >= 1.22

### Optional (for cloud storage)
- AWS Account (for S3)
- Google Cloud Account (for GCS)
- Azure Account (for Azure Blob Storage)

---

## 🚀 Quick Start

### 🐳 With Docker (Recommended)

The fastest way to get started:

```bash
# 1. Clone the repository
git clone <repository-url>
cd laravel-files-manager-system

# 2. Copy environment file
cp .env.example .env

# 3. Start development environment
docker-compose -f docker-compose.dev.yml up -d --build

# 4. Access the application
# http://localhost:8000
```

**That's it!** The application will automatically:
- ✅ Install PHP and Node dependencies
- ✅ Run database migrations
- ✅ Start Vite dev server with hot reload
- ✅ Configure Nginx and PHP-FPM

**Docker Services:**
- 🐘 **MySQL 8.0** - Database (port 3306)
- 🌐 **Nginx + PHP-FPM** - Web Server (port 8000)
- ⚡ **Vite Dev Server** - Frontend assets (port 5173)

**Useful Docker Commands:**
```bash
# Start services
docker-compose -f docker-compose.dev.yml up -d

# Stop services
docker-compose -f docker-compose.dev.yml down

# View logs
docker-compose -f docker-compose.dev.yml logs -f app

# Access container shell
docker-compose -f docker-compose.dev.yml exec app sh

# Run artisan commands
docker-compose -f docker-compose.dev.yml exec app php artisan migrate
docker-compose -f docker-compose.dev.yml exec app php artisan tinker

# Rebuild containers
docker-compose -f docker-compose.dev.yml up -d --build

# Stop and remove everything (including volumes)
docker-compose -f docker-compose.dev.yml down -v
```

### 💻 Without Docker (Local Installation)

If you prefer to run the application locally without Docker:

```bash
# 1. Clone the repository
git clone <repository-url>
cd laravel-files-manager-system

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Configure database in .env
# Edit .env and set your database credentials

# 6. Run migrations
php artisan migrate

# 7. Create storage link
php artisan storage:link

# 8. Build frontend assets
npm run build

# 9. Start development server
php artisan serve
```

**Access the application:** `http://localhost:8000`

**For development with hot reload:**
```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

**Requirements:**
- PHP >= 8.3
- Composer >= 2.0
- Node.js >= 18.0
- NPM >= 9.0
- MySQL/PostgreSQL/SQLite

---

## ⚙️ Configuration

### Storage Configuration

The application supports multiple storage backends. Configure in `.env`:

#### Local Storage (Default)

```env
FILE_STORAGE_DISK=public
```

No additional configuration needed! Files are stored in `storage/app/public/uploads`.

#### AWS S3

```env
FILE_STORAGE_DISK=s3
AWS_ACCESS_KEY_ID=your-access-key-id
AWS_SECRET_ACCESS_KEY=your-secret-access-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_URL=https://your-bucket.s3.amazonaws.com
```

#### Google Cloud Storage

First, install the package:
```bash
composer require league/flysystem-google-cloud-storage
```

Then configure:
```env
FILE_STORAGE_DISK=gcs
GOOGLE_CLOUD_PROJECT_ID=your-project-id
GOOGLE_CLOUD_STORAGE_BUCKET=your-bucket-name
GOOGLE_CLOUD_KEY_FILE=/path/to/service-account.json
```

#### Azure Blob Storage

First, install the package:
```bash
composer require league/flysystem-azure-blob-storage
```

Then configure:
```env
FILE_STORAGE_DISK=azure
AZURE_STORAGE_NAME=your-storage-account-name
AZURE_STORAGE_KEY=your-storage-account-key
AZURE_STORAGE_CONTAINER=your-container-name
```

For detailed storage configuration, see [docs/STORAGE.md](docs/STORAGE.md).

### File Upload Configuration

#### File Size Limits

Configure in `.env`:
```env
MAX_FILE_SIZE=2048  # Maximum file size in KB (default: 2MB)
```

#### Allowed File Types

Configure in `config/files.php`:

**Documents:**
- PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, CSV, RTF

**Images:**
- JPG, JPEG, PNG, GIF, WEBP, SVG

**Archives:**
- ZIP, RAR, TAR, GZ

### Database Configuration

Default uses SQLite (no configuration needed). For production, use MySQL or PostgreSQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## 📁 Project Structure

```
laravel-files-manager-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                    # Authentication controllers
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   └── ...
│   │   │   ├── FileController.php       # File management controller
│   │   │   └── ProfileController.php    # User profile controller
│   │   └── Requests/                    # Form request validation
│   │       ├── StoreFileRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Models/
│   │   ├── File.php                     # File model with relationships
│   │   └── User.php                     # User model with file relationship
│   ├── Providers/
│   │   └── AppServiceProvider.php       # Service provider
│   └── View/
│       └── Components/                  # Blade components
│           ├── AppLayout.php
│           └── GuestLayout.php
├── config/
│   ├── files.php                        # File upload configuration
│   ├── filesystems.php                  # Storage disk configuration
│   └── ...                              # Other Laravel config files
├── database/
│   ├── migrations/                      # Database migrations
│   │   ├── create_files_table.php
│   │   ├── add_user_id_to_files_table.php
│   │   └── add_indexes_to_files_table.php
│   ├── factories/                       # Model factories
│   │   └── UserFactory.php
│   └── seeders/                         # Database seeders
│       └── DatabaseSeeder.php
├── docs/                                # Documentation
│   ├── README.md                        # Documentation index
│   ├── STORAGE.md                       # Storage configuration guide
│   ├── TESTING.md                       # Testing documentation
│   └── API.md                           # API endpoints documentation
├── resources/
│   ├── views/                           # Blade templates
│   │   ├── auth/                        # Authentication views
│   │   ├── files/                       # File management views
│   │   │   ├── index.blade.php
│   │   │   └── download.blade.php
│   │   ├── layouts/                     # Layout templates
│   │   │   ├── app.blade.php
│   │   │   ├── guest.blade.php
│   │   │   └── navigation.blade.php
│   │   ├── profile/                     # Profile views
│   │   └── dashboard.blade.php         # Dashboard view
│   ├── css/
│   │   └── app.css                     # Tailwind CSS
│   └── js/
│       ├── app.js                      # Alpine.js setup
│       └── bootstrap.js                # Axios configuration
├── routes/
│   ├── web.php                         # Web routes
│   └── auth.php                        # Authentication routes
├── storage/
│   └── app/
│       └── public/                      # Public file storage
│           └── uploads/                 # Uploaded files directory
├── tests/
│   ├── Feature/                        # Feature tests
│   │   ├── Auth/                       # Authentication tests
│   │   ├── FileTest.php                # File management tests
│   │   └── ProfileTest.php             # Profile tests
│   ├── Unit/                           # Unit tests
│   ├── Pest.php                        # Pest configuration
│   └── TestCase.php                    # Base test case
└── public/                             # Public assets
    └── storage/                         # Symbolic link to storage/app/public
```

---

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/FileTest.php

# Run specific test
php artisan test --filter="files can be uploaded"

# Run in parallel (faster)
php artisan test --parallel
```

### Test Coverage

- ✅ **35 tests passing**
- ✅ **89 assertions**
- ✅ **100% feature coverage**

### Test Categories

#### Authentication Tests (6 tests)
- Login screen rendering
- User authentication
- Invalid password handling
- User logout
- Registration
- Email verification

#### File Management Tests (10 tests)
- File upload
- File download via signed links
- Expired file handling
- File validation
- File size limits
- File type restrictions
- File deletion
- Authorization checks

#### Profile Management Tests (5 tests)
- Profile page display
- Profile information update
- Email verification status
- Account deletion
- Password confirmation

For detailed testing documentation, see [docs/TESTING.md](docs/TESTING.md).

---

## 📚 Documentation

Comprehensive documentation is available in the `docs/` folder:

- **[STORAGE.md](docs/STORAGE.md)** - Complete storage configuration guide (local & cloud)
- **[TESTING.md](docs/TESTING.md)** - Testing guide and best practices
- **[API.md](docs/API.md)** - API endpoints documentation
- **[DEPLOY.md](docs/DEPLOY.md)** - Complete production deployment guide

---

## 🔐 Security Features

### Authentication & Authorization
- ✅ Session-based authentication
- ✅ User registration and login
- ✅ Password reset functionality
- ✅ Email verification
- ✅ User-specific file access
- ✅ Authorization checks on all file operations

### File Security
- ✅ Signed URLs for file sharing
- ✅ File expiration dates
- ✅ File type validation (MIME types)
- ✅ File size limits
- ✅ CSRF protection on all forms
- ✅ Rate limiting (10 uploads/minute)

### Data Protection
- ✅ Password hashing (bcrypt)
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade auto-escaping)
- ✅ Input validation and sanitization
- ✅ Secure file storage paths

---

## 🎯 Key Features Explained

### File Upload
- **Drag & Drop Interface** - Modern, intuitive file upload
- **Real-time Validation** - Instant feedback on file size and type
- **MIME Type Validation** - Server-side file type checking
- **Optional Expiration** - Set expiration dates for temporary sharing
- **Automatic Unique IDs** - UUID-based file identifiers
- **Progress Feedback** - Visual upload progress indicators

### File Download
- **Signed URLs** - Time-limited, secure download links
- **Expiration Checking** - Automatic expiration date validation
- **Download Tracking** - Count how many times files are downloaded
- **File Existence Validation** - Check file exists before download
- **Secure Access** - Only valid signed URLs can access files

### File Management
- **User-specific Listing** - Users only see their own files
- **File Deletion** - Secure deletion with authorization checks
- **Storage Statistics** - View total files, downloads, storage used
- **Recent Files** - Quick access to recently uploaded files
- **File Metadata** - View file size, type, upload date, expiration

### Dashboard
- **Statistics Cards** - Visual representation of file metrics
- **Recent Files** - Quick access to latest uploads
- **Storage Usage** - Total storage used by user
- **Download Counts** - Total downloads across all files
- **Quick Actions** - Copy link, download, delete buttons

---

## 🚀 Deployment

For complete deployment instructions, see **[docs/DEPLOY.md](docs/DEPLOY.md)**.

### Quick Deployment Options

#### Docker Production

```bash
# 1. Configure .env with production values
cp .env.example .env
nano .env

# 2. Start production containers
docker-compose up -d --build

# 3. Verify deployment
docker-compose ps
```

#### Traditional Server

```bash
# 1. Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Run migrations
php artisan migrate --force

# 4. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure production database (MySQL/PostgreSQL)
- [ ] Configure cloud storage (recommended)
- [ ] Set up SSL certificate
- [ ] Configure queue workers (if needed)
- [ ] Set up backup strategy
- [ ] Configure monitoring and logging
- [ ] Set up error tracking (Sentry, etc.)
- [ ] Optimize assets (`npm run build`)
- [ ] Run migrations (`php artisan migrate --force`)
- [ ] Clear and cache config (`php artisan config:cache`)

**For detailed deployment instructions, see [docs/DEPLOY.md](docs/DEPLOY.md)**

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Make your changes**
4. **Write tests** for new features
5. **Ensure tests pass** (`php artisan test`)
6. **Commit your changes** (`git commit -m 'Add amazing feature'`)
7. **Push to the branch** (`git push origin feature/amazing-feature`)
8. **Open a Pull Request**

### Code Style

We use Laravel Pint for code style. Run before committing:

```bash
./vendor/bin/pint
```

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Acknowledgments

- **[Laravel](https://laravel.com)** - The PHP Framework for Web Artisans
- **[Tailwind CSS](https://tailwindcss.com)** - A utility-first CSS framework
- **[Pest PHP](https://pestphp.com)** - An elegant PHP Testing Framework
- **[Laravel Breeze](https://laravel.com/docs/breeze)** - Authentication scaffolding
- **[Vite](https://vitejs.dev)** - Next generation frontend tooling
- **[Alpine.js](https://alpinejs.dev)** - A minimal framework for composing JavaScript behavior

---

<div align="center">

**Built with ❤️ using Laravel**

⭐ **Star this repo if you find it useful!**

[Report Bug](https://github.com/your-repo/issues) • [Request Feature](https://github.com/your-repo/issues) • [Documentation](docs/)

</div>
