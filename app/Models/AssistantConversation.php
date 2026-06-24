<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantConversation extends Model
{
    protected $fillable = [
        'user_id', 'guest_id', 'guest_name', 'guest_email',
        'status', 'handed_off', 'last_message_at',
    ];

    protected $casts = [
        'handed_off' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(AssistantMessage::class, 'conversation_id')->orderBy('id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Display name for the admin inbox. */
    public function getDisplayNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name ?? $this->user->username ?? ('User #' . $this->user_id);
        }
        return $this->guest_name ?: 'Guest';
    }
}
