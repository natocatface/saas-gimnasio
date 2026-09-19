<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    protected $table = 'support_ticket_messages';

    protected $fillable = [
        'ticket_id', 'user_id', 'is_staff', 'body',
    ];

    protected $casts = [
        'is_staff' => 'boolean',
    ];

    public function ticket() { return $this->belongsTo(SupportTicket::class, 'ticket_id'); }
    public function user()   { return $this->belongsTo(User::class); }
}
