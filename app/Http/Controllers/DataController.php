<?php

namespace App\Http\Controllers;

use App\Models\CategoryItem;
use Illuminate\Http\Request;
use App\Models\Category;

class DataController extends Controller
{
    public function getItems($id)
    {
        $item = CategoryItem::where("category_id",$id)->pluck("name","id");
        return json_encode($item);
    }
}
