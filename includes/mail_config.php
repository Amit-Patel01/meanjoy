<?php
/**
 * Email Configuration File
 * 
 * Configure your email settings here
 * For Gmail: You need to enable "Less secure app access" or use App Password
 * 
 * Steps to setup Gmail:
 * 1. Go to your Google Account settings
 * 2. Enable 2-Step Verification
 * 3. Generate an App Password: https://myaccount.google.com/apppasswords
 * 4. Use the generated App Password in MAIL_PASSWORD below
 */

// SMTP Configuration
define('MAIL_HOST', 'smtp.gmail.com');           // SMTP server (Gmail: smtp.gmail.com)
define('MAIL_SMTP_AUTH', true);                  // Enable SMTP authentication
define('MAIL_USERNAME', 'meanjoy.infocell@gmail.com');                     // Your email address (e.g., yourname@gmail.com)
define('MAIL_PASSWORD', 'elludbwlzgdissdc');                     // Your email password or App Password
define('MAIL_SMTPSECURE', 'ssl');                // Encryption: 'ssl' or 'tls'
define('MAIL_PORT', 465);                        // Port: 465 for SSL, 587 for TLS
define('MAIL_FROM_EMAIL', 'meanjoy.infocell@gmail.com');                   // From email address (usually same as MAIL_USERNAME)
define('MAIL_FROM_NAME', 'Meanjoy Infocell');      // From name
define('MAIL_REPLY_TO', 'meanjoy.infocell@gmail.com');                     // Reply-to email (usually same as MAIL_USERNAME)

// Alternative SMTP Providers (uncomment and configure as needed)
// For Outlook/Hotmail:
// define('MAIL_HOST', 'smtp.office365.com');
// define('MAIL_PORT', 587);
// define('MAIL_SMTPSECURE', 'tls');

// For Yahoo:
// define('MAIL_HOST', 'smtp.mail.yahoo.com');
// define('MAIL_PORT', 587);
// define('MAIL_SMTPSECURE', 'tls');

// For custom SMTP:
// define('MAIL_HOST', 'smtp.yourdomain.com');
// define('MAIL_PORT', 587);
// define('MAIL_SMTPSECURE', 'tls');

?>


