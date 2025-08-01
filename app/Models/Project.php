<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'company_id',
        'title',
        'property',
        'contact_email',
        'date_requested',
        'status',
        'time_preference',
        'deadline',
        'service_type',
        'service_description',
        'latest_completion_date',
    ];

    protected $casts = [
        'date_requested' => 'datetime',
    ];

    // Custom accessors to ensure dates are returned as strings without timezone conversion
    public function getDeadlineAttribute($value)
    {
        return $value; // Return as-is from database (should be Y-m-d string)
    }

    public function getLatestCompletionDateAttribute($value)
    {
        return $value; // Return as-is from database (should be Y-m-d string)
    }

    // Custom mutators to ensure dates are stored as strings without timezone conversion
    public function setDeadlineAttribute($value)
    {
        $this->attributes['deadline'] = $value; // Store as-is (should be Y-m-d string)
    }

    public function setLatestCompletionDateAttribute($value)
    {
        $this->attributes['latest_completion_date'] = $value; // Store as-is (should be Y-m-d string)
    }

    /**
     * Get the client that owns the project.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the company that owns the project.
     */
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    /**
     * Get the messages for the project.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Get all attachments for the project via messages.
     */
    public function attachments()
    {
        return $this->hasManyThrough(\App\Models\Attachment::class, \App\Models\Message::class, 'project_id', 'message_id');
    }

    /**
     * Helper to check if project has any attachments.
     */
    public function getHasAttachmentsAttribute()
    {
        return $this->attachments()->exists();
    }
}
