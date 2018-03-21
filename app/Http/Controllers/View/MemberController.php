<?php

namespace App\Http\Controllers\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function toLogin(Request $request)
    {
        $return_url = $request->input('return_url', '');
        $data = [
            'return_url' => urldecode($return_url)
        ];
        return view('login', $data);
    }

    public function toRegister()
    {
        return view('register');
    }
}
