<?php

require 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

// Load environment variables
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== Mail Configuration Test ===\n\n";

// Display configuration
echo "MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n\n";

// Build DSN
$encryption = env('MAIL_ENCRYPTION') === 'tls' ? 'tls' : 'ssl';
$dsn = sprintf(
    'smtp://%s:%s@%s:%d?encryption=%s',
    urlencode(env('MAIL_USERNAME')),
    urlencode(env('MAIL_PASSWORD')),
    env('MAIL_HOST'),
    env('MAIL_PORT'),
    $encryption
);

echo "Testing SMTP connection...\n";

try {
    $transport = Transport::fromDsn($dsn);
    echo "✓ Transport created successfully\n";
    
    // Try to start the transport
    $transport->start();
    echo "✓ SMTP connection successful!\n";
    $transport->stop();
    
} catch (\Exception $e) {
    echo "✗ Connection failed: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}

echo "\n=== Troubleshooting Tips ===\n";
echo "1. Verify Gmail credentials are correct\n";
echo "2. For Gmail, use an App Password (not your regular password)\n";
echo "3. Enable 2-factor authentication on your Gmail account\n";
echo "4. Check firewall/network settings for port 587\n";
echo "5. Verify MAIL_ENCRYPTION is set to 'tls' for port 587\n";

