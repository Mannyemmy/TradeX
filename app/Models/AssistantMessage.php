<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantMessage extends Model
{
    protected $fillable = [
        'conversation_id', 'sender_type', 'sender_id', 'message',
    ];

    public function conversation()
    {
        return $this->belongsTo(AssistantConversation::class, 'conversation_id');
    }
}
