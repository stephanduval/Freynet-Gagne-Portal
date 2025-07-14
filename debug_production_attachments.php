<?php

// Debug script for production attachment issues
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Message;
use App\Models\Attachment;
use App\Models\User;
use App\Http\Resources\MessageResource;
use Illuminate\Support\Facades\Storage;

echo "=== Production Attachment Debug ===\n";

// Check the latest message
$latestMessage = Message::orderBy('id', 'desc')->first();
echo "Latest message ID: {$latestMessage->id}\n";
echo "Subject: {$latestMessage->subject}\n";
echo "Sender ID: {$latestMessage->sender_id}\n";
echo "Receiver ID: {$latestMessage->receiver_id}\n";
echo "Status: {$latestMessage->status}\n";

// Check sender details
$sender = User::find($latestMessage->sender_id);
echo "Sender: {$sender->name} ({$sender->email})\n";

// Check attachments for this message
$attachmentCount = $latestMessage->attachments()->count();
echo "Attachments count: {$attachmentCount}\n";

if ($attachmentCount > 0) {
    echo "\n--- Attachment Details ---\n";
    foreach ($latestMessage->attachments as $index => $attachment) {
        $attachmentNum = $index + 1;
        echo "Attachment {$attachmentNum}:\n";
        echo "  ID: {$attachment->id}\n";
        echo "  Filename: {$attachment->filename}\n";
        echo "  Path: {$attachment->path}\n";
        echo "  Size: {$attachment->size} bytes\n";
        echo "  Mime Type: {$attachment->mime_type}\n";
        
        // Check file existence
        $defaultDisk = config('filesystems.default');
        echo "  Default disk: {$defaultDisk}\n";
        echo "  Exists on default disk: " . (Storage::disk($defaultDisk)->exists($attachment->path) ? 'Yes' : 'No') . "\n";
        echo "  Exists on public disk: " . (Storage::disk('public')->exists($attachment->path) ? 'Yes' : 'No') . "\n";
        
        if (Storage::disk($defaultDisk)->exists($attachment->path)) {
            echo "  Actual file size on disk: " . Storage::disk($defaultDisk)->size($attachment->path) . " bytes\n";
        }
        echo "\n";
    }
    
    // Test the API response
    echo "--- API Response Test ---\n";
    $messageWithRelations = Message::with(['sender', 'receiver', 'labels', 'attachments', 'project'])->find($latestMessage->id);
    $resource = new MessageResource($messageWithRelations);
    $apiResponse = $resource->toArray(request());
    
    echo "Attachments in API response: " . count($apiResponse['attachments']) . "\n";
    if (count($apiResponse['attachments']) > 0) {
        foreach ($apiResponse['attachments'] as $index => $attachment) {
            $attachmentNum = $index + 1;
            echo "API Attachment {$attachmentNum}:\n";
            echo "  fileName: {$attachment['fileName']}\n";
            echo "  size: {$attachment['size']}\n";
            echo "  mime_type: {$attachment['mime_type']}\n";
            echo "  download_url exists: " . (isset($attachment['download_url']) ? 'Yes' : 'No') . "\n";
            echo "\n";
        }
    }
} else {
    echo "No attachments found.\n";
}

// Check the last few messages by Sophie (user ID 2)
echo "\n--- Last 3 messages by Sophie (User ID 2) ---\n";
$sophieMessages = Message::where('sender_id', 2)->orderBy('id', 'desc')->take(3)->get();
foreach ($sophieMessages as $msg) {
    echo "Message ID {$msg->id}: {$msg->subject} - Attachments: {$msg->attachments()->count()}\n";
}

// Check storage configuration
echo "\n--- Storage Configuration ---\n";
echo "Default filesystem disk: " . config('filesystems.default') . "\n";
echo "Storage path: " . storage_path() . "\n";
echo "Storage directory exists: " . (is_dir(storage_path('app')) ? 'Yes' : 'No') . "\n";
echo "Storage directory writable: " . (is_writable(storage_path('app')) ? 'Yes' : 'No') . "\n";

echo "\n=== End Debug ===\n";