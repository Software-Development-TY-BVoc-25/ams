# Attendance Management System (ams)

A comprehensive web-based application built with PHP and MySQL to manage student records, attendance tracking, courses, and grades with role-based access control for administrators, teachers, and students.

## 📋 Table of Contents

-   [Features](#-features)
-   [Tech Stack](#-tech-stack)
-   [Prerequisites](#-prerequisites)
-   [Development Environment Setup](#-development-environment-setup)
-   [Installation Guide](#-installation-guide)
-   [Project Structure](#-project-structure)
-   [Configuration](#️-configuration)
-   [Usage](#-usage)
-   [Development Workflow](#-development-workflow)
-   [Troubleshooting](#-troubleshooting)
-   [Contributing](#-contributing)
-   [License](#-license)

## ✨ Features

-   **Role-based Access Control**: Admin, Teacher, and Student dashboards
-   **Attendance Management**: Upload, track, and view attendance records
-   **Student Management**: Comprehensive student information system
-   **Course Management**: Subject allocation and department organization
-   **Responsive Design**: Bootstrap-powered responsive UI
-   **Secure Authentication**: Session-based user authentication
-   **Database Integration**: MySQL with proper relational structure

## 🛠 Tech Stack

-   **Backend**: PHP 8.2+
-   **Database**: MySQL 8.0+ / MariaDB 10.4+
-   **Frontend**: HTML5, CSS3, Bootstrap 5.3.7
-   **Icons**: Bootstrap Icons (via CDN), Font Awesome 6.4.2
-   **Web Server**: Apache (via XAMPP)
-   **Development**: VS Code with PHP extensions

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

-   **Git**: [Download Git](https://git-scm.com/downloads)
-   **VS Code**: [Download VS Code](https://code.visualstudio.com/)
-   **XAMPP**: [Download XAMPP](https://www.apachefriends.org/download.html) (includes Apache, MySQL, PHP)

### System Requirements

-   **Operating System**: Windows 10/11, macOS 10.15+, or Linux
-   **RAM**: Minimum 4GB (8GB recommended)
-   **Storage**: At least 2GB free space
-   **Browser**: Chrome, Firefox, Safari, or Edge (latest versions)

## 🚀 Development Environment Setup

### 1. Install XAMPP

1. **Download XAMPP** from [apachefriends.org](https://www.apachefriends.org/download.html)
2. **Run the installer** and follow the setup wizard
3. **Choose components** (ensure Apache, MySQL, and PHP are selected)
4. **Install to default location** (usually `C:\xampp\`)
5. **Start XAMPP Control Panel** after installation

### 2. Configure XAMPP

1. **Open XAMPP Control Panel** as Administrator
2. **Start Apache** and **MySQL** services
3. Verify services are running (green status indicators)
4. **Access phpMyAdmin**: Navigate to `http://localhost/phpmyadmin`

### 3. Setup VS Code for PHP Development

1. **Install VS Code** from [code.visualstudio.com](https://code.visualstudio.com/)
2. **Install recommended extensions**:
   VS Code will automatically prompt you to install all recommended extensions for this project. Click "Install All" when prompted.

    ```
    - PHP Intelephense (bmewburn.vscode-intelephense-client)
    - Bootstrap IntelliSense (hossaini.bootstrap-intellisense)
    - Material Icon Theme (PKief.material-icon-theme)
    - Prettier - Code Formatter (esbenp.prettier-vscode)
    ```

3. **Configure PHP path**:
    - Open VS Code Settings `Ctrl + ,`
    - Search for "php executable"
    - Set path to: `C:\xampp\php\php.exe`

## 📥 Installation Guide

### Step 1: Clone the Repository

**Important**: The project must be cloned into the `htdocs` directory for XAMPP to serve it properly.

Execute these commands in **Command Prompt** or **PowerShell** (run as Administrator):

```bash
# Navigate to XAMPP htdocs directory
cd C:\xampp\htdocs

# Clone the repository (this creates an 'ams' folder)
git clone https://github.com/Software-Development-TY-BVoc-25/ams.git

# Navigate to project directory
cd ams

# open in vscode
code .
```

**Alternative method using File Explorer**:

1. Open File Explorer and navigate to `C:\xampp\htdocs\`
2. Right-click and select "Git Bash Here" (if you have Git Bash installed)
3. Run the git clone command

### Step 2: Database Setup

1. **Start XAMPP services** (Apache & MySQL)
2. **Open phpMyAdmin**: `http://localhost/phpmyadmin`
3. **Create database**:
    - Click "New" in the left sidebar
    - Database name: `attendance_system`
    - Collation: `utf8mb4_general_ci`
    - Click "Create"
4. **Import database schema**:
    - Select the `attendance_system` database
    - Click "Import" tab
    - Choose file: `attendance_system.sql`
    - Click "Go"

### Step 3: Configure Application

1. **Verify database configuration** in `config.php`:

    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'attendance_system');
    ```

### Step 4: Access the Application

1. **Ensure XAMPP services are running**
2. **Open your browser**
3. **Navigate to**: `http://localhost/ams`
4. **Verify installation**: You should see the ams dashboard

## 📁 Project Structure

```
ams/
├── .gitignore             # Git ignore rules
├── .htaccess              # URL rewriting rules
├── .prettierrc            # Code formatting configuration
├── .vscode/               # VS Code workspace settings
│   ├── extensions.json    # Recommended extensions
│   └── settings.json      # Editor configuration
├── assets/                # Static assets
│   ├── favicon.png        # Site favicon
│   └── global.css         # Global stylesheet
├── attendance_system.sql  # Database schema
├── config.php             # Database configuration
├── inc/                   # Reusable components
│   └── sidebar.php        # Navigation sidebar
├── index.php              # Application entry point
├── pages/                 # Application pages
│   ├── 404.php           # Error page
│   ├── dashboard.php     # Main dashboard
│   └── upload_attendance.php # Attendance upload
└── README.md             # This file
```

### Key Files Explained

-   **`index.php`**: Main router that handles URL routing and page rendering
-   **`config.php`**: Database connection and session management
-   **`.htaccess`**: Apache configuration for clean URLs and security
-   **`inc/sidebar.php`**: Dynamic navigation component
-   **`pages/`**: Individual page controllers and views
-   **`assets/`**: Static resources (CSS, images, JavaScript)

### Directory Guidelines

**Where to add new files:**

-   **New pages**: Create `.php` files in `pages/` directory
-   **Styles**: Use internal styles only
-   **Images**: Place images in `assets/` directory
-   **Reusable components**: Add PHP includes to `inc/` directory
-   **Configuration**: Modify `config.php` for database settings

## ⚙️ Configuration

### Database Configuration

Edit `config.php` to match your database setup:

```php
// Default XAMPP configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Empty for default XAMPP
define('DB_NAME', 'attendance_system');
```

### Apache Configuration

The `.htaccess` file provides:

-   Clean URL routing
-   Asset file protection
-   Directory listing prevention
-   Request forwarding to `index.php`

### VS Code Configuration

The project includes VS Code workspace settings for:

-   PHP syntax highlighting and IntelliSense
-   Code formatting with Prettier
-   File associations for PHP includes
-   Recommended extensions for optimal development experience

This will be set up automatically when you open the project in VS Code.

## 🎯 Usage

### Starting Development

1. **Start XAMPP Control Panel**
2. **Start Apache and MySQL services**
3. **Open project in VS Code**:
    ```bash
    code C:\xampp\htdocs\ams
    ```
4. **Access application**: `http://localhost/ams`

### Adding New Pages

1. **Create PHP file** in `pages/` directory
2. **Add navigation link** in `inc/sidebar.php`
3. **Test routing** via browser

Example new page (`pages/students.php`):

```php
<?php
$page_css = "<style>/* Page-specific styles */</style>";
?>

<h2>Student Management</h2>
<p>Student management content here...</p>
```

### Database Operations

Use the `$conn` variable (defined in `config.php`) for database operations:

```php
<?php
require_once 'config.php';

// Example query
$result = mysqli_query($conn, "SELECT * FROM student");
while ($row = mysqli_fetch_assoc($result)) {
    // Process data
}
?>
```

## 🔧 Development Workflow

### Version Control

```bash
# Create feature branch
git checkout -b feature/your-feature-name

# Make changes and commit
git add .
git commit -m "Add student management functionality"

# Push branch
git push origin feature/your-feature-name

# Create pull request via GitHub
```

important!: in `git checkout -b feature/your-feature-name` "feature" remains constant and "your-feature-name" will change - don't just copy paste it

### Code Style

-   Follow K&R style
-   Use meaningful variable and function names
-   Comment complex business logic
-   Format code with Prettier (automatic on save)
-   Use consistent indentation (4 spaces)

### Security Best Practices

-   Never commit sensitive information (passwords, API keys)
-   Use prepared statements for database queries
-   Validate and sanitize all user inputs
-   Enable HTTPS in production environments
-   Keep XAMPP and PHP updated to latest versions

## 🔍 Troubleshooting

### Common Issues

**XAMPP Services Won't Start**

-   Check if ports 80 (Apache) and 3306 (MySQL) are free
-   Run XAMPP Control Panel as Administrator
-   Check Windows Firewall settings

**Database Connection Failed**

-   Verify MySQL service is running
-   Check database credentials in `config.php`
-   Ensure database `attendance_system` exists

**Page Not Found (404)**

-   Verify `.htaccess` file exists and is readable
-   Check Apache mod_rewrite is enabled
-   Ensure file exists in `pages/` directory

**PHP Errors**

-   Check Apache error logs: `C:\xampp\apache\logs\error.log`
-   Enable PHP error display in development:
    ```php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    ```

### Getting Help

-   Check PHP documentation: [php.net](https://www.php.net/docs.php)
-   Bootstrap documentation: [getbootstrap.com](https://getbootstrap.com/docs/)
-   XAMPP documentation: [apachefriends.org](https://www.apachefriends.org/docs/)

## 🤝 Contributing

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** following the code style guide
4. **Test thoroughly** on local environment
5. **Commit your changes**: `git commit -m 'Add amazing feature'`
6. **Push to branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request** with detailed description

### Pull Request Guidelines

-   Include a clear description of changes
-   Reference any related issues
-   Ensure all tests pass locally
-   Follow the existing code style
-   Update documentation if needed

## 📄 License

This project is licensed under the MIT License – see the [LICENSE](./LICENSE) file for details.

---

**Need help?** Open an issue on GitHub or contact the development team.
