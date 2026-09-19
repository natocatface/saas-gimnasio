<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Database\Eloquent\Model;

class MemberMeasurement extends Model
{
    use HasTenant;

    protected $table = 'member_measurements';

    protected $fillable = [
        'gymnasium_id', 'member_id', 'measured_on',
        'weight', 'height', 'body_fat', 'chest', 'waist', 'hips', 'arm', 'thigh', 'notes',
    ];

    protected $casts = [
        'measured_on' => 'date',
    ];

    public function member() { return $this->belongsTo(Member::class); }
}
