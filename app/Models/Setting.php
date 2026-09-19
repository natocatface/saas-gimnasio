<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class Setting extends Model
{
    use HasTenant;

    protected $fillable = ['gymnasium_id','key','value','group'];
}
