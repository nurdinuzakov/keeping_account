<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = ['balanceable_id', 'balanceable_type', 'date', 'balance'];
//    protected $table = 'balances';
//
//    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function balanceable()
    {
        return $this->morphTo();
    }
}
