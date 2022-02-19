<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundsFlowType extends Model
{
    use HasFactory;

    protected $table = 'funds_flow_types';

    protected $guarded = ['id'];


}
