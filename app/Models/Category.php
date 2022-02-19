<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $guarded = ['id'];

    public function categoryItem()
    {
        return $this->HasMany(CategoryItem::class);
    }

    public function expenses()
    {
           return $this->HasMany(Expense::class);
    }
}
