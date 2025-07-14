<?php

// Simple storage check script for debugging production issues
echo "=== Storage Configuration Check ===\n";

// Check Laravel configuration
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Default filesystem disk: " . config('filesystems.default') . "\n";
echo "Storage path: " . storage_path() . "\n";
echo "Public storage path: " . storage_path('app/public') . "\n";
echo "Storage link exists: " . (file_exists(public_path('storage')) ? 'Yes' : 'No') . "\n";

// Check permissions
$storagePath = storage_path('app');
echo "Storage directory writable: " . (is_writable($storagePath) ? 'Yes' : 'No') . "\n";

$publicStoragePath = storage_path('app/public');
echo "Public storage directory exists: " . (is_dir($publicStoragePath) ? 'Yes' : 'No') . "\n";
echo "Public storage directory writable: " . (is_writable($publicStoragePath) ? 'Yes' : 'No') . "\n";

// Check if attachments directory exists
$attachmentsPath = storage_path('app/public/attachments');
echo "Attachments directory exists: " . (is_dir($attachmentsPath) ? 'Yes' : 'No') . "\n";

// Try to create a test file
try {
    $testFile = storage_path('app/test_write.txt');
    file_put_contents($testFile, 'test');
    echo "Test file creation: Success\n";
    unlink($testFile);
} catch (Exception $e) {
    echo "Test file creation: Failed - " . $e->getMessage() . "\n";
}

echo "\n=== Environment Info ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Laravel Version: " . app()->version() . "\n";
echo "Environment: " . config('app.env') . "\n";
echo "Upload max filesize: " . ini_get('upload_max_filesize') . "\n";
echo "Post max size: " . ini_get('post_max_size') . "\n";
echo "Max file uploads: " . ini_get('max_file_uploads') . "\n";

echo "\n=== Done ===\n";