<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasTenant;

    protected $table = 'support_tickets';

    protected $fillable = [
        'gymnasium_id', 'user_id', 'subject', 'priority', 'status', 'last_reply_at',
    ];

    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    public function gymnasium() { return $this->belongsTo(Gymnasium::class); }
    public function user()      { return $this->belongsTo(User::class); }
    public function messages()  { return $this->hasMany(SupportTicketMessage::class, 'ticket_id')->orderBy('created_at'); }

    public function isOpen(): bool { return $this->status !== 'closed'; }

    public function statusLabel(): string
    {
        return ['open' => 'Abierto', 'pending' => 'En espera', 'closed' => 'Cerrado'][$this->status] ?? $this->status;
    }

    public function priorityLabel(): string
    {
        return ['low' => 'Baja', 'normal' => 'Normal', 'high' => 'Alta'][$this->priority] ?? $this->priority;
    }
}
