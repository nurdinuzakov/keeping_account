<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Income extends Model
{
    use HasFactory;
    use CascadesDeletes;

    protected $table = 'incomes';

    protected $guarded = ['id'];

    protected $cascadeDeletes = ['paymentHistory'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function paymentHistory()
    {
        return $this->morphOne(PaymentHistory::class, 'balanceable');
    }

    public function paymentMethods()
    {
        return $this->belongsTo(PaymentMethods::class, 'paymentMethod_id');
    }
}
