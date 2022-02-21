<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 * @mixin Builder
 */
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
