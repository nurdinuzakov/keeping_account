<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function categories()
    {
        $categories = Category::paginate(10);

        return view('category', ['categories' => $categories]);
    }

    public function categoryCreate(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'category_name'        => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $category = Category::create(['name' => $inputs['category_name']]);

        return redirect()->route('categories', ['categories' => $category]);
    }

    public function categoryUpdate(Request  $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs,[
            'category_name'        => 'required|string',
            'category_id'        => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(),422);
        }

        $categories = Category::all();
        $category = $categories->find($inputs['category_id']);

        $category->name = $inputs['category_name'];
        $category->save();

        return redirect()->route('categories', ['categories' => $category]);
    }

    public function categoryDelete($id)
    {
        $category = Category::find($id);

        if (!$category)
        {
            return $this->sendError(['message' => 'Category with this id was not found'],422);
        }
        $category->delete();
        $category->get();

        return redirect()->route('categories', ['categories' => $category]);
    }
}
