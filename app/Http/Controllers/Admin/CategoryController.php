<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Entity\Category;
use Illuminate\Http\Request;
use App\Models\MResult;

class CategoryController extends Controller
{
    public function toCategory()
    {
        $categories = Category::all();
        foreach ($categories as $_key => $category) {
            if ($category->parent_id != null && !empty($category->parent_id)) {
                $category->parent = Category::find($category->parent_id);
            }
        }
        $data = [
            'categories' => $categories
        ];
        return view('admin.category', $data);
    }

    public function toCategoryAdd()
    {
        $categories = Category::where('parent_id', '=', 0)->get();
        $data = [
            'categories' => $categories
        ];
        return view('admin.category_add', $data);
    }

    public function toCategoryEdit($id)
    {
        $category = Category::find($id);
        $categories = Category::where('parent_id', '=', 0)->get();
        $data = [
            'category'      => $category,
            'categories'    => $categories
        ];
        return view('admin.category_edit', $data);
    }


    /***************Service***********************/
    public function categoryAdd(Request $request)
    {

        $name = $request->input('name', '');
        $category_no = $request->input('category_no', '');
        $parent_id = $request->input('parent_id', '');
        $preview = $request->input('preview', '');
        $category = new Category();
        $category->name = $name;
        $category->category_no = $category_no;
        $category->parent_id = $parent_id;
        if (!empty($preview)) $category->preview = $preview;
        $category->save();

        $m_result = new MResult();
        $m_result->status = 0;
        $m_result->message = '添加成功';
        return $m_result->toJson();
    }

    public function categoryEdit(Request $request)
    {
        $id = $request->input('id');

        $category = Category::find($id);

        $name = $request->input('name', '');
        $category_no = $request->input('category_no', '');
        $parent_id = $request->input('parent_id', '');
        $preview = $request->input('preview', '');

        $category->name = $name;
        $category->category_no = $category_no;
        $category->parent_id = $parent_id;
        if (!empty($preview )) $category->preview = $preview = $request->input('preview', '');
        $category->save();

        $m_result = new MResult();
        $m_result->status = 0;
        $m_result->message = '修改成功';
        return $m_result->toJson();
    }

    public function categoryDel(Request $request)
    {
        $id = $request->input('id', '');
        Category::find($id)->delete();

        $m_result = new MResult();
        $m_result->status = 0;
        $m_result->message = '删除成功';
        return $m_result->toJson();
    }
}
