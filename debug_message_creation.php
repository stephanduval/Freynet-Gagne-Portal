<?php

// Debug script specifically for message creation and attachment processing
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Message;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "=== Message Creation Debug ===\n";

// Check the latest message details
$latestMessage = Message::orderBy('id', 'desc')->first();
echo "Latest message ID: {$latestMessage->id}\n";
echo "Created at: {$latestMessage->created_at}\n";
echo "Subject: {$latestMessage->subject}\n";

// Check recent log entries for this message
echo "\n--- Recent Log Entries ---\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $lines = explode("\n", $logContent);
    $recentLines = array_slice($lines, -200); // Last 200 lines
    
    $messageRelatedLogs = array_filter($recentLines, function($line) use ($latestMessage) {
        return strpos($line, "message: {$latestMessage->id}") !== false || 
               strpos($line, "Processing attachments for message: {$latestMessage->id}") !== false ||
               strpos($line, "MessageController::store") !== false ||
               (strpos($line, "attachments") !== false && strpos($line, date('Y-m-d')) !== false);
    });
    
    if (empty($messageRelatedLogs)) {
        echo "No attachment-related logs found for recent messages.\n";
        
        // Show last few MessageController logs
        $controllerLogs = array_filter($recentLines, function($line) {
            return strpos($line, "MessageController") !== false;
        });
        
        if (!empty($controllerLogs)) {
            echo "\nRecent MessageController logs:\n";
            foreach (array_slice($controllerLogs, -10) as $log) {
                echo $log . "\n";
            }
        }
    } else {
        foreach ($messageRelatedLogs as $log) {
            echo $log . "\n";
        }
    }
} else {
    echo "Log file not found at: {$logFile}\n";
}

// Check storage configuration
echo "\n--- Storage Debug ---\n";
$defaultDisk = config('filesystems.default');
echo "Default filesystem disk: {$defaultDisk}\n";

// Test file creation
$testPath = 'test_file_' . time() . '.txt';
try {
    Storage::disk($defaultDisk)->put($testPath, 'test content');
    echo "Test file creation: SUCCESS\n";
    echo "Test file exists: " . (Storage::disk($defaultDisk)->exists($testPath) ? 'Yes' : 'No') . "\n";
    Storage::disk($defaultDisk)->delete($testPath);
    echo "Test file cleanup: SUCCESS\n";
} catch (Exception $e) {
    echo "Test file creation: FAILED - " . $e->getMessage() . "\n";
}

// Check recent errors in logs
echo "\n--- Recent Errors ---\n";
if (file_exists($logFile)) {
    $lines = explode("\n", file_get_contents($logFile));
    $recentLines = array_slice($lines, -500); // Last 500 lines
    
    $errorLines = array_filter($recentLines, function($line) {
        return strpos($line, '[ERROR]') !== false || 
               strpos($line, 'Exception') !== false ||
               strpos($line, 'Error') !== false;
    });
    
    if (empty($errorLines)) {
        echo "No recent errors found.\n";
    } else {
        foreach (array_slice($errorLines, -10) as $error) {
            echo $error . "\n";
        }
    }
}

echo "\n=== End Debug ===\n";