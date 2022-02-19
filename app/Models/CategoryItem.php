<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Model
{
    use HasFactory;

    protected $table = 'category_item';

    protected $guarded = ['id'];

    public function category()
    {
        return $this->HasOne(Category::class);
    }

    public function expenses()
    {
        return $this->HasMany(Expense::class);
    }
}
