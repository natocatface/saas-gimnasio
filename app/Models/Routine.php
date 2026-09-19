<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasTenant;

    protected $table = 'routines';

    protected $fillable = [
        'gymnasium_id', 'member_id', 'trainer_id', 'title', 'description', 'exercises', 'is_active',
    ];

    protected $casts = [
        'exercises' => 'array',
        'is_active' => 'boolean',
    ];

    public function member()  { return $this->belongsTo(Member::class); }
    public function trainer() { return $this->belongsTo(Trainer::class); }
}
