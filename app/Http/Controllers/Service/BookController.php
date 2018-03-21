<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Entity\Category;
use App\Models\MResult;

class BookController extends Controller
{
    public function getCategoryByParentId($parent_id)
    {
        $categorys = Category::where('parent_id', '=', $parent_id)->get();

        $m_result = new MResult();
        $m_result->status = 0;
        $m_result->message = '返回数据成功';
        $m_result->categorys = $categorys;

        return $m_result->toJson();
    }
}
