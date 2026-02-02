# Gym Management System

A simple, aesthetically pleasing PHP-based Gym Management System designed to help gym owners manage members, track memberships, and monitor active/expired statuses.

## 🔑 Login Credentials

The system comes with a default administrator account created automatically on the first run.

- **Username**: `admin`
- **Password**: `admin123`

## 🚀 Setup Instructions

### Prerequisites
- **Web Server**: Apache (XAMPP, WAMP, MAMP, etc.)
- **PHP**: Version 7.4 or higher
- **Database**: MySQL or MariaDB

### Installation
1. **Deploy**:
   - Copy the `gym_system` folder to your web server's root directory (e.g., `htdocs`).

2. **Database Configuration**:
   - The system is pre-configured for standard local environments:
     - **Host**: `localhost`
     - **User**: `root`
     - **Password**: (empty)
   - If you need to change this, edit `config/db.php`.

3. **First Run**:
   - Open your browser and go to: `http://localhost/gym_system/`
   - **Auto-Setup**: The application will automatically create the database (`assmt`) and the necessary tables (`users`, `members`) if they don't exist. It will also seed the default admin account.

## ✨ Features

- **Authentication**: Secure Admin Login protected by sessions.
- **Dashboard**: 
  - Real-time stats counting Total and Active members.
  - Quick access to member search and registration.
- **Member Management**:
  - **Register New**: Add members with photo, phone, and membership dates.
  - **Edit/Delete**: Update member details or remove records.
  - **List & Card Views**: Toggle between a table list or a visual card grid for member browsing.
- **Search & Filter**: 
  - Instant AJAX search by name.
  - Filter members by status (Active, Expired, All).
- **Auto-Status**: System automatically calculates if a member is Active or Expired based on their end date.
- **Security**: 
  - Password Hashing (BCRYPT).
  - Protected Routes (cannot access dashboard without login).

## ⚠️ Notes / Known Issues

- **Uploads**: Member photos are stored in `public/uploads/`. The system attempts to create this directory with write permissions (`0777`) automatically. If image uploads fail, please manually check the permissions of this folder.
- **Redirect**: The root `index.html` simply redirects to `public/index.php`.
