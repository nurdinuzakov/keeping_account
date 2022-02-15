<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryItemController extends BaseController
{
    public function categoryItem($id)
    {
        $categoryItem = CategoryItem::where('category_id', $id)->get();

        return view('item', ['categoryItem' => $categoryItem, 'id' => $id]);
    }

    public function createItem(Request $request, $id)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'item_name'        => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $item = CategoryItem::create(['category_id' => $id, 'name' => $inputs['item_name']]);

        return redirect()->route('category-item', ['categoryItem' => $item, 'id' => $id]);
    }

    public function categoryItemDelete()
    {
        dd('in');
    }
}
