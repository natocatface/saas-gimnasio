<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class GymNotification extends Model
{
    use HasTenant;

    protected $table = 'gym_notifications';

    protected $fillable = [
        'gymnasium_id', 'user_id', 'type', 'title', 'body', 'icon', 'color', 'url', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeUnread($q) { return $q->whereNull('read_at'); }

    public function isUnread(): bool { return is_null($this->read_at); }

    /** Notificaciones visibles para un usuario: del gym (user_id null) o suyas. */
    public function scopeForUser($q, $userId)
    {
        return $q->where(function ($w) use ($userId) {
            $w->whereNull('user_id')->orWhere('user_id', $userId);
        });
    }
}
