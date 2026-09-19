<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassEnrollment extends Model
{
    protected $table = 'class_enrollments';

    public $timestamps = false;

    protected $fillable = ['gymnasium_id', 'class_id', 'member_id', 'status', 'enrolled_at'];

    public function gymClass() { return $this->belongsTo(GymClass::class, 'class_id'); }
    public function member()   { return $this->belongsTo(Member::class); }
}
