<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    use HasFactory;

    protected $table = 'funds_flow_types';

    protected $guarded = ['id'];

    public function expenses()
    {
        return $this->HasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->HasMany(Income::class);
    }
}
