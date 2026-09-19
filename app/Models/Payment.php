<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class Payment extends Model
{
    use HasTenant;

    protected $fillable = ['gymnasium_id',
        'member_id','plan_id','amount','payment_date','payment_method',
        'reference','period_start','period_end','status','notes','created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'period_start' => 'date',
        'period_end'   => 'date',
    ];

    public function member()    { return $this->belongsTo(Member::class); }
    public function plan()      { return $this->belongsTo(Plan::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
