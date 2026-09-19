<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'avatar', 'phone', 'status', 'gymnasium_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ── Roles ──────────────────────────────────────────────────
    public function isSuperAdmin()  { return $this->role === 'superadmin'; }
    public function isAdmin()       { return $this->role === 'admin'; }
    public function isTrainer()     { return $this->role === 'trainer'; }
    public function isMember()      { return $this->role === 'member'; }
    public function isReceptionist(){ return $this->role === 'receptionist'; }

    // ── Relaciones ─────────────────────────────────────────────
    public function gymnasium()
    {
        return $this->belongsTo(Gymnasium::class);
    }
}
