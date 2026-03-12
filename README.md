# Real Estate Website Platform 🏠

A comprehensive real estate management platform with multi-role support (Admin, Users, Agents), property listings, search functionality, and multilingual support.

## 📋 Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [User Roles](#user-roles)
- [Project Structure](#project-structure)
- [Usage](#usage)
- [Testing](#testing)
- [Security Notes](#security-notes)
- [Version Information](#version-information)
- [Troubleshooting](#troubleshooting)
- [Contributing](#contributing)
- [License](#license)

> **📢 Version 2.0 Released!** Major security update with bcrypt password hashing. See [VERSION_2.0_RELEASE_NOTES.md](VERSION_2.0_RELEASE_NOTES.md) for details.

## ✨ Features

### For Users (Buyers/Sellers)
- 🏡 Browse property listings with detailed information
- 🔍 Advanced search and filter functionality
- 💾 Save favorite properties
- 📝 Post and manage personal property listings
- 📧 Contact agents directly
- 🔐 Secure authentication system
- 🌐 Multi-language support (English, Hindi, Kannada)

### For Agents
- 👨‍💼 Professional agent dashboard
- 📊 Manage property listings
- 💼 Track requests and bookings
- 📈 View analytics

### For Administrators
- 👨‍💻 Complete admin dashboard
- 👥 Manage users and agents
- 🏘️ Manage all property listings
- 💬 Handle contact messages
- 📊 System-wide analytics

## 🛠️ Technologies Used

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL/MariaDB** - Database management
- **PDO** - Database abstraction layer

### Frontend
- **HTML5** - Structure
- **CSS3** - Styling
- **JavaScript** - Interactivity
- **Font Awesome** - Icons
- **SweetAlert** - Beautiful alerts

### Server
- **XAMPP** - Local development environment
- **Apache** - Web server
- **phpMyAdmin** - Database administration

## 📥 Prerequisites

Before installing, ensure you have:

- **XAMPP** (or WAMP/LAMP/MAMP) installed
  - Download: [https://www.apachefriends.org/](https://www.apachefriends.org/)
- **PHP 7.4 or higher**
- **MySQL 5.7 or higher**
- **Web Browser** (Chrome, Firefox, Edge, etc.)

## 🚀 Installation

### Step 1: Clone/Download the Repository

```bash
# If using Git
git clone <repository-url>

# Or download and extract the ZIP file
```

### Step 2: Place Project in XAMPP

1. Copy the entire project folder to your XAMPP `htdocs` directory:
   ```
   C:\xampp\htdocs\Major_project\
   ```

2. The project structure should be:
   ```
   C:\xampp\htdocs\Major_project\front-end%20real-estate%20website\project%20backend\
   ```

### Step 3: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Start **Apache** service
3. Start **MySQL** service

### Step 4: Database Setup

#### Automatic Setup (Recommended)
The application will automatically create the database and tables on first run.

Simply access the website and the database will be initialized automatically.

#### Manual Setup (Alternative)

1. Open **phpMyAdmin**: http://localhost/phpmyadmin
2. Click "New" to create a new database
3. Name it: `home_db`
4. Import the SQL file: `home_db.sql`
5. Click "Go"

## ⚙️ Configuration

### Database Configuration

The database configuration is in `components/connect.php`:

```php
$db_name = 'mysql:host=localhost;dbname=home_db';
$db_user_name = 'root';
$db_user_pass = ''; // Default XAMPP password
```

**⚠️ Important**: Change the database credentials if your setup differs from default XAMPP.

### Default Login Credentials

#### Admin
- **Username**: `admin`
- **Password**: `111`

#### Demo Agent
- **Email**: `agent@homehive.com`
- **Password**: `111`

## 👤 User Roles

### 1. Admin
- Full system control
- Manage all users and agents
- Approve/reject listings
- View all messages and requests

**Login**: http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/admin/login.php

### 2. Agent
- Create and manage property listings
- View booking requests
- Track performance

**Login**: http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/agent_login.php

### 3. User (Buyer/Seller)
- Browse and search properties
- Post property listings
- Save favorite properties
- Contact agents

**Login**: http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/login.php

## 📁 Project Structure

```
front-end real-estate website/
│
├── desgin/                           # Front-end design files
│   ├── css/
│   ├── js/
│   ├── images/
│   └── *.html
│
├── project backend/                  # Main application
│   ├── admin/                        # Admin panel
│   │   ├── dashboard.php
│   │   ├── users.php
│   │   ├── listings.php
│   │   └── ...
│   ├── components/                   # Reusable components
│   │   ├── connect.php              # Database connection
│   │   ├── user_header.php
│   │   ├── admin_header.php
│   │   └── ...
│   ├── css/                          # Stylesheets
│   ├── js/                           # JavaScript files
│   ├── images/                       # Images
│   ├── uploaded_files/               # User uploads
│   ├── home.php                      # Homepage
│   ├── listings.php                  # Property listings
│   ├── search.php                    # Search page
│   └── ...
│
├── home_db.sql                       # Database schema
├── README.md                         # This file
└── PROJECT_ANALYSIS.md               # Gap analysis
```

## 🎯 Usage

### Accessing the Application

#### Main Homepage
```
http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/home.php
```

Or shorter:
```
http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/
```

#### Admin Panel
```
http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/admin/
```

#### User Login
```
http://localhost/Major_project/front-end%20real-estate%20website/project%20backend/login.php
```

### Common Workflows

#### For Buyers:
1. Browse homepage for featured properties
2. Use search/filter to find specific properties
3. Save properties you like
4. View property details
5. Contact agent for inquiries

#### For Sellers:
1. Register/Login
2. Go to "Post Property"
3. Fill in property details
4. Upload property images
5. Submit listing (pending admin approval)

#### For Agents:
1. Register as agent
2. Wait for admin approval
3. Login to agent dashboard
4. Manage your listings
5. Track requests

## 🧪 Testing

### Current Test Status
⚠️ **No automated tests implemented yet**

### Manual Testing Checklist

#### User Functions
- [ ] User registration
- [ ] User login/logout
- [ ] Browse properties
- [ ] Search and filter
- [ ] Save properties
- [ ] Post property listing
- [ ] Contact form

#### Agent Functions
- [ ] Agent registration
- [ ] Agent login
- [ ] Create listing
- [ ] Edit listing
- [ ] View requests

#### Admin Functions
- [ ] Admin login
- [ ] Manage users
- [ ] Manage agents
- [ ] Manage listings
- [ ] View messages

### Recommended Testing Frameworks

- **PHPUnit** - For unit testing
- **Selenium** - For browser testing
- **PHPStan** - For static analysis

## 🔒 Security Notes

### ✅ Security Features Implemented

1. **Password Hashing**: ✅ **UPGRADED to bcrypt** - Secure password hashing with cost factor 12
2. **SQL Injection Prevention**: ✅ Using PDO prepared statements throughout
3. **Backward Compatibility**: ✅ Automatic migration from SHA1 to bcrypt on login
4. **Password Verification**: ✅ Secure password verification with hash timing attack prevention
5. **File Upload Handling**: Basic validation implemented, needs enhancement
6. **Input Sanitization**: Implemented basic filtering

### ⚠️ Security Improvements Needed

1. **CSRF Protection**: Forms need CSRF tokens
2. **File Upload Validation**: Enhanced type/size checking needed
3. **Input Sanitization**: Implement stricter input validation
4. **Rate Limiting**: Add brute force protection on login
5. **Session Security**: Enhanced session management needed

### Security Best Practices

1. **Never commit sensitive files** (.gitignore provided)
2. **Use strong passwords** in production
3. **Enable HTTPS** in production
4. **Regular backups** of database
5. **Keep dependencies updated**

## 📦 Version Information

### Current Version: 2.0

**What's New in v2.0:**
- 🔐 **Security Update:** Password hashing upgraded from SHA1 to bcrypt
- ✅ **Backward Compatible:** Existing users can still login
- ✅ **Auto-Migration:** Old passwords automatically upgraded
- 📝 **Developer Info:** Updated to Santosh Sanadi, 2025
- 📚 **New Documentation:** Version release notes added

**See Full Details:**
- [VERSION_2.0_RELEASE_NOTES.md](VERSION_2.0_RELEASE_NOTES.md) - Complete release notes
- [PROJECT_UPDATES.md](PROJECT_UPDATES.md) - Technical update documentation
- [REQUIREMENT_ANALYSIS.md](REQUIREMENT_ANALYSIS.md) - Comprehensive requirements

**⚠️ Known Issues:**
- CSRF protection not fully implemented
- File upload validation needs enhancement
- Automated backup system pending
- Rate limiting needed

**Next Version (2.1) Planned:**
- CSRF protection implementation
- Enhanced file upload validation
- Brute force protection
- Error logging system

## 🐛 Troubleshooting

### Issue: Page shows "Unknown database"

**Solution**: 
1. Make sure MySQL is running in XAMPP
2. Check if `home_db` database exists in phpMyAdmin
3. Database should auto-create on first run

### Issue: "Access denied" error

**Solution**:
1. Check database credentials in `components/connect.php`
2. Default XAMPP: user=`root`, password is empty
3. For WAMP: user=`root`, password=`''`

### Issue: Images not displaying

**Solution**:
1. Check upload permissions on `uploaded_files/` folder
2. Verify images exist in the folder
3. Check Apache is serving files

### Issue: "Cannot redeclare" errors

**Solution**:
1. Clear browser cache
2. Restart Apache in XAMPP
3. Check for duplicate include statements

### Issue: Language switcher not working

**Solution**:
1. Ensure sessions are enabled in PHP
2. Check `language_config.php` exists
3. Verify session directory is writable

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Add comments for complex logic
- Test thoroughly before submitting
- Update documentation for new features

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 📞 Support

**Developer**: Santosh Sanadi  
**Project**: Real Estate Website Platform  
**Version**: 2.0  
**Year**: 2025

For issues and questions:
- Open an issue on GitHub
- Contact: See project repository
- Documentation: See `REQUIREMENT_ANALYSIS.md`

## 🎓 Learning Resources

- PHP Documentation: [https://www.php.net/docs.php](https://www.php.net/docs.php)
- MySQL Documentation: [https://dev.mysql.com/doc/](https://dev.mysql.com/doc/)
- Bootstrap Documentation: [https://getbootstrap.com/docs/](https://getbootstrap.com/docs/)

---

## 📊 Project Status

**Current Version**: 2.0  
**Developer**: Santosh Sanadi  
**Last Updated**: 2025  
**Status**: Production Ready (Security Enhanced)

### Completed Features ✅
- User authentication system with bcrypt password hashing
- Property listing system with 30+ attributes
- Admin panel with full system control
- Agent dashboard with request tracking
- Advanced search and filter functionality
- Multi-language support (English, Hindi, Kannada)
- Secure password hashing (bcrypt)
- Responsive design for all devices

### Planned Features 🚧
- Unit testing framework (PHPUnit)
- REST API for mobile integration
- Payment gateway integration
- Advanced search with Google Maps
- Email notification system
- Mobile app development

### Known Issues 🐛
- CSRF protection (in progress)
- Automated testing framework needed
- Automated backup system needed

---

**Made with ❤️ by Santosh Sanadi**  
**© 2025 - All Rights Reserved**

