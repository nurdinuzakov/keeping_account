<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;

    protected $table = 'balances';

    protected $guarded = ['id'];

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
}
