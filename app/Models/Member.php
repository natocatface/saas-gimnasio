<?php

namespace App\Models;

use App\Traits\HasTenant;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasTenant;

    protected $fillable = ['gymnasium_id',
        'user_id','code','first_name','last_name','email','phone',
        'birth_date','gender','address','emergency_contact','emergency_phone',
        'photo','plan_id','membership_start','membership_end','status','notes','password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'birth_date'       => 'date',
        'membership_start' => 'date',
        'membership_end'   => 'date',
        'password'         => 'hashed',
    ];

    public function plan()        { return $this->belongsTo(Plan::class); }
    public function payments()    { return $this->hasMany(Payment::class); }
    public function attendance()  { return $this->hasMany(Attendance::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function routines()    { return $this->hasMany(Routine::class); }
    public function measurements(){ return $this->hasMany(MemberMeasurement::class); }
    public function enrollments() { return $this->hasMany(ClassEnrollment::class, 'member_id'); }

    public function getFullNameAttribute() { return $this->first_name . ' ' . $this->last_name; }
    public function getInitialsAttribute()  { return strtoupper(substr($this->first_name,0,1).substr($this->last_name,0,1)); }

    public function isMembershipActive(): bool
    {
        return $this->status === 'activo'
            && (!$this->membership_end || $this->membership_end->isFuture() || $this->membership_end->isToday());
    }
}
