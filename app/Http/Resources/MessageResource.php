<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource; // Import User model if not already imported
use Illuminate\Support\Facades\URL; // Add this import for URL facade

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Eager load relationships if they aren't already
        $this->resource->loadMissing(['sender', 'receiver', 'labels', 'attachments', 'project']);

        // Determine folder based on status AND archive flag
        $folder = 'inbox'; // Default
        if ($this->is_archived) {
            $folder = 'archive';
        } elseif ($this->status === 'deleted') {
            $folder = 'trash';
        } elseif ($this->status === 'draft') { // Assuming sender perspective for draft/sent
            $folder = 'draft';
        } elseif ($this->status === 'sent') { // Assuming sender perspective for draft/sent
            // Sent items usually appear in inbox for receiver, but maybe 'sent' folder for sender view?
            // Let's keep it simple: if receiver sees it, it's inbox unless archived/deleted.
            // If sender is viewing 'sent' filter, the controller handles that.
            // So, resource doesn't need complex folder logic based on viewer.
        }

        return [
            'id' => $this->id,
            'from' => [
                'id' => $this->whenLoaded('sender', fn () => $this->sender->id ?? null),
                'fullName' => $this->whenLoaded('sender', fn () => $this->sender->name ?? 'Unknown Sender'),
                'email' => $this->whenLoaded('sender', fn () => $this->sender->email ?? 'unknown@example.com'),
                'avatar' => $this->whenLoaded('sender', fn () => $this->sender->avatar ?? '/images/avatars/avatar-1.png'), // Adjust default avatar path as needed
            ],
            'to' => $this->whenLoaded('receiver', function () {
                return $this->receiver ? [ // Check if receiver exists
                    [
                        'fullName' => $this->receiver->name ?? 'Unknown Receiver',
                        'email' => $this->receiver->email ?? 'unknown@example.com',
                    ],
                ] : []; // Return empty array if no receiver
            }),
            'subject' => $this->subject,
            'message' => $this->body, // Map body to message
            'time' => $this->created_at->toISOString(), // Map created_at to time
            'requestedDate' => $this->created_at->toISOString(), // Add requestedDate mapping
            'dueDate' => $this->due_date, // Already in Y-m-d format
            'labels' => $this->whenLoaded('labels', fn () => $this->labels->pluck('label_name')->toArray() ?? []),
            'attachments' => $this->whenLoaded('attachments', function () {
                return $this->attachments->map(function ($attachment) {
                    // Format file size in human-readable format
                    $sizeFormatted = $this->formatFileSize($attachment->size);

                    // Generate file icon based on mime type
                    $fileIcon = $this->getFileIcon($attachment->mime_type);

                    return [
                        'id' => $attachment->id,
                        'fileName' => $attachment->filename,
                        'thumbnail' => $fileIcon,
                        'url' => $attachment->path,
                        'size' => $sizeFormatted,
                        'mime_type' => $attachment->mime_type,
                        'download_url' => URL::temporarySignedRoute(
                            'attachments.download',
                            now()->addMinutes(60), // URL valid for 60 minutes
                            ['attachment' => $attachment->id]
                        ),
                    ];
                })->toArray() ?? [];
            }),
            'isRead' => $this->status === 'read',
            'isStarred' => (bool) $this->is_starred, // Use the actual value from the model
            'isArchived' => (bool) $this->is_archived, // Add archive flag
            'folder' => $folder, // Calculated folder
            'status' => $this->status, // Existing status (read/unread/deleted)
            'task_status' => $this->task_status, // New task status ('new'/'completed')
            'due_date' => $this->due_date,
            'company_id' => $this->company_id,
            'project' => $this->whenLoaded('project', function () {
                return $this->project ? [
                    'id' => $this->project->id,
                    'title' => $this->project->title,
                    'property' => $this->project->property,
                    'service_type' => $this->project->service_type,
                    'deadline' => $this->project->deadline,
                ] : null;
            }),

            // Include original fields if needed for debugging or specific logic
            // 'sender_id' => $this->sender_id,
            // 'receiver_id' => $this->receiver_id,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Format file size in human-readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        } elseif ($bytes > 1) {
            return $bytes.' bytes';
        } elseif ($bytes == 1) {
            return '1 byte';
        } else {
            return '0 bytes';
        }
    }

    /**
     * Get file icon based on mime type
     */
    private function getFileIcon($mimeType)
    {
        $iconMap = [
            'application/pdf' => '/images/icons/file-icons/pdf.png',
            'application/msword' => '/images/icons/file-icons/doc.png',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '/images/icons/file-icons/doc.png',
            'application/vnd.ms-excel' => '/images/icons/file-icons/xls.png',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '/images/icons/file-icons/xls.png',
            'application/vnd.ms-powerpoint' => '/images/icons/file-icons/ppt.png',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => '/images/icons/file-icons/ppt.png',
            'text/plain' => '/images/icons/file-icons/txt.png',
            'application/zip' => '/images/icons/file-icons/zip.png',
            'application/x-zip-compressed' => '/images/icons/file-icons/zip.png',
            'image/jpeg' => '/images/icons/file-icons/jpg.png',
            'image/png' => '/images/icons/file-icons/png.png',
            'image/gif' => '/images/icons/file-icons/gif.png',
        ];

        return $iconMap[$mimeType] ?? '/images/icons/file-icons/doc.png';
    }
}
