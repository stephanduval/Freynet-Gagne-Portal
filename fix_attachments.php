<?php

// Script to fix attachment storage issues
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

echo "=== Attachment Storage Fix Script ===\n";

$defaultDisk = config('filesystems.default');
echo "Default disk: {$defaultDisk}\n";

$attachments = Attachment::all();
echo "Total attachments to check: " . $attachments->count() . "\n\n";

$fixed = 0;
$errors = 0;
$skipped = 0;

foreach ($attachments as $attachment) {
    echo "Checking attachment ID {$attachment->id}: {$attachment->filename}\n";
    echo "  Current path: {$attachment->path}\n";
    
    $existsOnDefault = Storage::disk($defaultDisk)->exists($attachment->path);
    $existsOnPublic = Storage::disk('public')->exists($attachment->path);
    
    echo "  Exists on '{$defaultDisk}': " . ($existsOnDefault ? 'Yes' : 'No') . "\n";
    echo "  Exists on 'public': " . ($existsOnPublic ? 'Yes' : 'No') . "\n";
    
    if (!$existsOnDefault && !$existsOnPublic) {
        echo "  ERROR: File not found on any disk!\n";
        $errors++;
    } elseif ($existsOnDefault) {
        echo "  OK: File exists on default disk\n";
        $skipped++;
    } elseif ($existsOnPublic && !$existsOnDefault) {
        echo "  FIXING: Moving file from 'public' to '{$defaultDisk}'\n";
        try {
            // Get file content from public disk
            $content = Storage::disk('public')->get($attachment->path);
            
            // Create directory structure on default disk if needed
            $pathInfo = pathinfo($attachment->path);
            $directory = $pathInfo['dirname'];
            Storage::disk($defaultDisk)->makeDirectory($directory);
            
            // Write file to default disk
            Storage::disk($defaultDisk)->put($attachment->path, $content);
            
            // Verify the file was written correctly
            if (Storage::disk($defaultDisk)->exists($attachment->path)) {
                echo "  SUCCESS: File moved successfully\n";
                $fixed++;
                
                // Optionally remove from public disk
                // Storage::disk('public')->delete($attachment->path);
            } else {
                echo "  ERROR: File move failed\n";
                $errors++;
            }
        } catch (Exception $e) {
            echo "  ERROR: " . $e->getMessage() . "\n";
            $errors++;
        }
    }
    
    echo "\n";
}

echo "=== Summary ===\n";
echo "Fixed: {$fixed}\n";
echo "Skipped (already correct): {$skipped}\n";
echo "Errors: {$errors}\n";
echo "Total: " . ($fixed + $skipped + $errors) . "\n";