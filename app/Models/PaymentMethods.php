<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $guarded = ['id'];

    public function expenses()
    {
        return $this->HasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->HasMany(Income::class, 'paymentMethod_id');
    }
}
