# Email Setup Guide

This guide will help you configure PHP mail functionality for your eCommerce site.

## Quick Setup Steps

### For Gmail Users:

1. **Open the configuration file:**
   - Navigate to `includes/mail_config.php`

2. **Configure your Gmail settings:**
   - Fill in `MAIL_USERNAME` with your Gmail address (e.g., `yourname@gmail.com`)
   - Fill in `MAIL_PASSWORD` with your Gmail App Password (see below)
   - Fill in `MAIL_FROM_EMAIL` with your Gmail address
   - Fill in `MAIL_REPLY_TO` with your Gmail address

3. **Generate Gmail App Password:**
   - Go to your Google Account: https://myaccount.google.com/
   - Click on **Security** in the left sidebar
   - Enable **2-Step Verification** (if not already enabled)
   - Scroll down and click on **App passwords**
   - Select **Mail** and **Other (Custom name)**
   - Enter "PHP eCommerce" as the name
   - Click **Generate**
   - Copy the 16-character password and paste it in `MAIL_PASSWORD`

### For Other Email Providers:

#### Outlook/Hotmail:
```php
define('MAIL_HOST', 'smtp.office365.com');
define('MAIL_PORT', 587);
define('MAIL_SMTPSECURE', 'tls');
```

#### Yahoo Mail:
```php
define('MAIL_HOST', 'smtp.mail.yahoo.com');
define('MAIL_PORT', 587);
define('MAIL_SMTPSECURE', 'tls');
```

#### Custom SMTP Server:
```php
define('MAIL_HOST', 'smtp.yourdomain.com');
define('MAIL_PORT', 587);  // or 465 for SSL
define('MAIL_SMTPSECURE', 'tls');  // or 'ssl' for port 465
```

## Configuration File Location

All email settings are centralized in:
```
includes/mail_config.php
```

## What's Configured?

The following files use the email configuration:
- `register.php` - Sends activation emails to new users
- `reset.php` - Sends password reset links

## Testing Your Setup

1. Try registering a new account
2. Check if you receive the activation email
3. If you get an error, check:
   - Email credentials are correct
   - App Password is used (for Gmail)
   - SMTP settings match your email provider
   - Firewall/antivirus is not blocking the connection

## Troubleshooting

### Common Issues:

**"SMTP connect() failed"**
- Check your internet connection
- Verify SMTP host and port are correct
- Check if your firewall is blocking the connection

**"Authentication failed"**
- For Gmail: Make sure you're using App Password, not your regular password
- Verify username and password are correct
- Check if 2-Step Verification is enabled (required for App Passwords)

**"Could not instantiate mail function"**
- Make sure PHPMailer is installed (check `vendor/phpmailer` folder exists)
- Verify PHP has OpenSSL extension enabled

## Security Note

⚠️ **Important:** Never commit `mail_config.php` with real credentials to version control. Consider adding it to `.gitignore` if you're using Git.



