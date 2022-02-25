<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Expense extends Model
{
    use HasFactory;
    use CascadesDeletes;

    protected $table = 'expenses';

    protected $guarded = ['id'];

    protected $cascadeDeletes = ['paymentHistory'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function item()
    {
        return $this->belongsTo(CategoryItem::class);
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
