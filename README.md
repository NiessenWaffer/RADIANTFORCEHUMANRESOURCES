# 🌟 Radiant Force Human Resources

A comprehensive web-based Human Resources management system built with PHP and MySQL. This application provides a complete solution for job posting, application management, candidate tracking, and HR administration.

![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

## 🚀 Features

### 🎯 **Core Functionality**
- **Job Management**: Create, edit, and manage job positions
- **Application Processing**: Handle job applications with resume parsing
- **Candidate Tracking**: Track applicants through the hiring process
- **Location-Based Jobs**: Organize jobs by cities and locations
- **Blog System**: Content management for company news and updates
- **Contact Management**: Handle inquiries and communications

### 👨‍💼 **Admin Panel**
- **Dashboard**: Overview of applications, jobs, and analytics
- **User Management**: Admin user authentication and management
- **Content Management**: Blog posts, FAQs, and testimonials
- **SEO Management**: Meta tags and search optimization
- **Newsletter**: Email campaign management
- **Analytics**: Track website performance and user engagement

### 🌐 **Public Features**
- **Job Search**: Browse available positions by location and category
- **Application Submission**: Online job application with file upload
- **Company Information**: About, services, and team pages
- **Blog**: Company news and industry insights
- **Contact Forms**: Multiple inquiry types and contact methods
- **Referral Program**: Employee referral system

## 📋 Requirements

- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache, Nginx, or PHP built-in server
- **Extensions**: PDO, MySQL, JSON, FileInfo

## 🛠️ Installation

### Option 1: Using Laragon (Windows - Recommended)

1. **Install Laragon**
   ```bash
   winget install LeNgocKhoa.Laragon
   ```

2. **Clone the Repository**
   ```bash
   git clone https://github.com/NiessenWaffer/RADIANTFORCEHUMANRESOURCES.git
   cd RADIANTFORCEHUMANRESOURCES
   ```

3. **Start Laragon Services**
   - Open Laragon
   - Start Apache and MySQL services

4. **Import Database**
   ```bash
   # Using Laragon's MySQL
   C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS dbronnie;"
   C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root dbronnie < radiant/database/radiant_complete.sql
   ```

5. **Configure Database**
   - Update `radiant/jobs/config.php` and `radiant/admin/config.php` with your database credentials

### Option 2: Using PHP Built-in Server

1. **Install PHP and MySQL**
   ```bash
   # Windows (using winget)
   winget install PHP.PHP.8.3
   
   # macOS (using Homebrew)
   brew install php mysql
   
   # Ubuntu/Debian
   sudo apt install php mysql-server php-mysql
   ```

2. **Clone and Setup**
   ```bash
   git clone https://github.com/NiessenWaffer/RADIANTFORCEHUMANRESOURCES.git
   cd RADIANTFORCEHUMANRESOURCES
   
   # Create database
   mysql -u root -p -e "CREATE DATABASE dbronnie;"
   mysql -u root -p dbronnie < radiant/database/radiant_complete.sql
   
   # Start PHP server
   php -S localhost:8000
   ```

### Option 3: Using Docker

1. **Clone Repository**
   ```bash
   git clone https://github.com/NiessenWaffer/RADIANTFORCEHUMANRESOURCES.git
   cd RADIANTFORCEHUMANRESOURCES
   ```

2. **Start with Docker Compose**
   ```bash
   docker-compose up -d
   ```

3. **Access Application**
   - Website: http://localhost:8080
   - Database will be automatically set up

## ⚙️ Configuration

### Database Configuration

Update the database credentials in both config files:

**`radiant/jobs/config.php`**
```php
$host = 'localhost';
$port = '3306';
$dbname = 'dbronnie';
$username = 'root';
$password = 'your_password';
```

**`radiant/admin/config.php`**
```php
$host = 'localhost';
$port = '3306';
$dbname = 'dbronnie';
$username = 'root';
$password = 'your_password';
```

### Environment Variables

Create a `.env` file in the root directory:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=dbronnie
DB_USERNAME=root
DB_PASSWORD=your_password
```

## 🎮 Usage

### Accessing the Application

- **Main Website**: `http://localhost:8000`
- **Admin Panel**: `http://localhost:8000/radiant/admin/`
- **Database Manager**: `http://localhost:8000/db-manager.php`

### Default Admin Credentials

- **Email**: `radiantforce@gmail.com`
- **Password**: `Youth2025`

### Key URLs

| Feature | URL |
|---------|-----|
| Home Page | `/` |
| Jobs Listing | `/radiant/jobs/jobs.php` |
| Cities | `/radiant/jobs/cities.php` |
| Blog | `/radiant/jobs/blog.php` |
| Contact | `/radiant/jobs/contact-form.php` |
| Admin Dashboard | `/radiant/admin/` |
| Job Applications | `/radiant/admin/pages/manage-job-applications.php` |

## 📁 Project Structure

```
RADIANTFORCEHUMANRESOURCES/
├── radiant/
│   ├── admin/                 # Admin panel
│   │   ├── assets/           # Admin CSS/JS
│   │   ├── auth/             # Authentication
│   │   ├── includes/         # Admin includes
│   │   └── pages/            # Admin pages
│   ├── database/             # SQL files
│   │   ├── radiant_complete.sql
│   │   └── insert_major_cities.sql
│   ├── design/               # CSS stylesheets
│   ├── includes/             # Common includes
│   ├── javascrpt/            # JavaScript files
│   ├── jobs/                 # Job-related pages
│   ├── pages/                # Static pages
│   └── uploads/              # File uploads
├── .env                      # Environment variables
├── .htaccess                 # Apache configuration
├── docker-compose.yml        # Docker setup
├── index.php                 # Entry point
├── manifest.json             # PWA manifest
└── radiantforcehumanresources.php
```

## 🗄️ Database Schema

The application uses 21 database tables:

| Table | Purpose |
|-------|---------|
| `admin_users` | Admin authentication |
| `job_positions` | Job postings |
| `job_applications` | Application submissions |
| `cities` | Location data |
| `locations` | Office locations |
| `blog_posts` | Blog content |
| `contact_inquiries` | Contact form submissions |
| `newsletter_subscribers` | Email subscribers |
| `testimonials` | Client testimonials |
| `faqs` | Frequently asked questions |
| `seo_meta` | SEO metadata |
| `analytics` | Website analytics |

## 🔧 Development

### Adding New Features

1. **Database Changes**: Update SQL files in `radiant/database/`
2. **Admin Pages**: Add to `radiant/admin/pages/`
3. **Public Pages**: Add to `radiant/jobs/` or `radiant/pages/`
4. **Styling**: Update CSS in `radiant/design/`
5. **JavaScript**: Add to `radiant/javascrpt/`

### File Upload Configuration

The application supports resume uploads. Configure upload limits in `.htaccess`:

```apache
php_value upload_max_filesize 10M
php_value post_max_size 12M
php_value max_execution_time 300
```

## 🚀 Deployment

### Production Deployment

1. **Server Requirements**
   - PHP 8.1+ with required extensions
   - MySQL 8.0+
   - Web server (Apache/Nginx)

2. **Security Configuration**
   - Update database credentials
   - Set proper file permissions
   - Configure SSL/HTTPS
   - Update `.htaccess` security headers

3. **Performance Optimization**
   - Enable PHP OPcache
   - Configure GZIP compression
   - Set up browser caching
   - Optimize database queries

## 🛡️ Security Features

- **SQL Injection Protection**: Prepared statements throughout
- **XSS Prevention**: Input sanitization and output escaping
- **CSRF Protection**: Form token validation
- **File Upload Security**: Type and size validation
- **Admin Authentication**: Session-based login system
- **Security Headers**: Configured in `.htaccess`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Support

For support and questions:

- **Email**: radiantforce@gmail.com
- **Issues**: [GitHub Issues](https://github.com/NiessenWaffer/RADIANTFORCEHUMANRESOURCES/issues)

## 🙏 Acknowledgments

- Built with PHP and MySQL
- Uses modern web technologies
- Responsive design for all devices
- SEO optimized structure

---

**Made with ❤️ by Radiant Force Human Resources Team**