# Cosmixify Database Setup

## Requirements

- XAMPP with MySQL/MariaDB running
- PHP CLI (optional, for password hash generation)

## Quick Install

### Option 1: Command line

```bash
cd c:\xampp\htdocs\space
c:\xampp\mysql\bin\mysql.exe -u root < database\schema.sql
Get-Content database\reseed-content.sql | c:\xampp\mysql\bin\mysql.exe -u root
php database\sync-apod.php 14
```

### Refresh content only (keeps users/admin)

```bash
Get-Content database\reseed-content.sql | c:\xampp\mysql\bin\mysql.exe -u root
php database\sync-apod.php 14
```

### Option 2: phpMyAdmin

1. Open http://localhost/phpmyadmin
2. Import `database/schema.sql`
3. Import `database/seed.sql`

## Default Admin Account

| Field    | Value                    |
|----------|--------------------------|
| Email    | admin@cosmixify.local    |
| Password | admin123                 |

**Change this password immediately after first login.**

## Database Tables

| Table                 | Purpose                          |
|-----------------------|----------------------------------|
| site_visits           | Visit counter                    |
| admin                 | Admin panel users                |
| users                 | Site registered users            |
| photos                | Main photo gallery               |
| photos_tags           | Tags for photos                  |
| apod                  | NASA APOD/EPIC images            |
| astronomical_events   | Astronomical calendar events     |
| user_photos           | Community submitted photos       |
| favs                  | User favorites                   |

## Upload Directories

Ensure these folders exist and are writable by Apache:

```
uploads/
uploads/events/
uploads/user_photos/
```

## Connection Settings

Default XAMPP settings in `db-conn.php`:

- Host: localhost
- User: root
- Password: (empty)
- Database: cosmixify

## Regenerate Admin Password Hash

```bash
php -r "echo password_hash('your_password', PASSWORD_DEFAULT);"
```
