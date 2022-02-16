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

    public function updateItem(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'item_name'        => 'required|string',
            'item_id'        => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $item = CategoryItem::find($inputs['item_id']);
        $item->name = $inputs['item_name'];
        $item->save();
        $id = $item->category_id;

        $categoryItem = CategoryItem::where('category_id', $id)->get();
        return view('item', ['categoryItem' => $categoryItem, 'id' => $id]);
    }

    public function categoryItemDelete($id)
    {
        $categoryItem = CategoryItem::find($id);

        if (!$categoryItem)
        {
            return $this->sendError(['message' => 'Item with this id was not found'],422);
        }


        $categoryItem->delete();
        $categoryItem->get();
        $id = $categoryItem->category_id;

        return redirect()->route('category-item', ['categoryItem' => $categoryItem, 'id' => $id]);
    }
}
