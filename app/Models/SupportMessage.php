<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'sender_type',
        'sender_id',
        'message',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function sender()
    {
        if ($this->sender_type === 'admin') {
            return $this->belongsTo(\App\Models\Admin::class, 'sender_id');
        }
        return $this->belongsTo(User::class, 'sender_id');
    }
}
