<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Member;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Tool\UUID;
use App\Models\MResult;
use App\Models\MEmail;
use Validator;

class MemberController extends Controller
{
    public function register(Request $request)
    {
        $register_type = $request->input('register_type');
        $m_result = new MResult();
        switch ($register_type) {
            case 'phone' :
                $phone = $request->input('phone');
                $password = $request->input('password');
                $phone_code = $request->input('phone_code');
                // Validate
                $v_phone = Validator::make($request->all(), [
                    'phone' => 'required|regex:/^1[34578][0-9]{9}$/'
                ]);
                if ($v_phone->fails()) {
                    $m_result->status = 1;
                    $m_result->message = '手机号格式不正确';
                    return $m_result->toJson();
                }
                $v_password = Validator::make($request->all(), [
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6'
                ]);
                if ($v_password->fails()) {
                    $m_result->status = 2;
                    $m_result->message = '密码不得小于六位 或者 两次密码不一致';
                    return $m_result->toJson();
                }
                $v_phone_code = Validator::make($request->all(), [
                    'phone_code' => 'required|min:6'
                ]);
                if ($v_phone_code->fails()) {
                    $m_result->status = 3;
                    $m_result->message = '短信验证码不得小于6位';
                    return $m_result->toJson();
                }
                // 判断验证码
                $tempPhone = TempPhone::where('phone', '=', $phone)->get()->first();
                if ($tempPhone == null) {
                    $m_result->status = 4;
                    $m_result->message = '手机号与发送验证码手机号不匹配';
                    return $m_result->toJson();
                }
                if ($phone_code == $tempPhone->code) {
                    // 判断验证码是否过期
                    if (time() > strtotime($tempPhone->deadline)) {
                        $m_result->status = 4;
                        $m_result->message = '短信验证码不正确';
                        return $m_result->toJson();
                    }

                    // 注册成功
                    $member = new Member();
                    $member->phone = $phone;
                    $member->password = md5('bk'.$password);
                    $member->save();

                    $m_result->status = 0;
                    $m_result->message = '注册成功';
                    return $m_result->toJson();
                } else {
                    $m_result->status = 5;
                    $m_result->message = '短信验证码不正确';
                    return $m_result->toJson();
                }
                break;
            case 'email' :
                $email = $request->input('email');
                $password = $request->input('password');
                $v_email = Validator::make($request->all(), [
                    'email' => 'required|email'
                ]);
                if ($v_email->fails()) {
                    $m_result->status = 1;
                    $m_result->message = '邮箱格式不正确';
                    return $m_result->toJson();
                }
                $v_password = Validator::make($request->all(), [
                    'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|min:6'
                ]);
                if ($v_password->fails()) {
                    $m_result->status = 2;
                    $m_result->message = '密码不得小于六位 或者 两次密码不一致';
                    return $m_result->toJson();
                }
                $v_validate_code = Validator::make($request->all(), [
                    'validate_code' => 'required|min:4'
                ]);
                if ($v_validate_code->fails()) {
                    $m_result->status = 3;
                    $m_result->message = '验证码不得小于4位';
                    return $m_result->toJson();
                }
                // 验证验证码
                $validate_code = strtolower($request->input('validate_code'));
                if ($validate_code != strtolower($request->session()->get('validate_code', ''))) {
                    $m_result->status = 4;
                    $m_result->message = '验证码不正确';
                    return $m_result->toJson();
                }
                // 注册成功
                $member = new Member;
                $member->email = $email;
                $member->password = md5('bk'.$password);
                $member->save();

                $uuid = UUID::create();

                // 发送邮件验证
                $m_email = new MEmail();
                $m_email->to = $email;
                $m_email->cc = '1092879991@qq.com';
                $m_email->subject = '邮件激活验证';
                $m_email->content = '请于24小时点击该链接完成验证. '.url('/service/validate_email').'?member_id='.$member->id.'&code='.$uuid;
                $m_email->sendEmail();

                // 保存UUID
                $tempEmail = new TempEmail();
                $tempEmail->member_id = $member->id;
                $tempEmail->code = $uuid;
                $tempEmail->deadline = date('Y-m-d H:i:s', time() + 60 * 60 * 24);
                $tempEmail->save();

                $m_result->status = 0;
                $m_result->message = '注册成功';
                return $m_result->toJson();
                break;
            default :
                return 'register_type Error';
        }
    }

    public function login(Request $request)
    {
        $m_result = new MResult();

        // 验证
        $v_username = Validator::make($request->all(), [
            'username' => 'required'
        ]);
        if ($v_username->fails()) {
            $m_result->status = 1;
            $m_result->message = '请输入用户名';
            return $m_result->toJson();
        }
        $v_password = Validator::make($request->all(), [
            'password' => 'required|min:6'
        ]);
        if ($v_password->fails()) {
            $m_result->status = 2;
            $m_result->message = '密码不得小于六位';
            return $m_result->toJson();
        }
        $v_password = Validator::make($request->all(), [
            'validate_code' => 'required'
        ]);
        if ($v_password->fails()) {
            $m_result->status = 3;
            $m_result->message = '验证码必须是4位';
            return $m_result->toJson();
        }

        // 验证验证码
        $username = $request->input('username', '');
        $password = $request->input('password', '');
        $validate_code = $request->input('validate_code', '');

        $validate_code_session = $request->session()->get('validate_code');
        if (strtolower($validate_code) != strtolower($validate_code_session)) {
            $m_result->status = 4;
            $m_result->message = '验证码不正确';
            return $m_result->toJson();
        }

        if (strpos($username, '@') == true) {
            $member = Member::where('email', '=', $username)->get()->first();
        } else {
            $member = Member::where('phone', '=', $username)->get()->first();
        }
        if ($member == null) {
            $m_result->status = 2;
            $m_result->message = '该用户不存在';
            return $m_result->toJson();
        } else {
            if (md5('bk'.$password) != $member->password) {
                $m_result->status = 6;
                $m_result->message = '用户名密码不正确';
                return $m_result->toJson();
            }
        }

        // 保存用户数据
        $data = [
            'member' => $member
        ];
        $request->session()->put($data);

        $m_result->status = 0;
        $m_result->message = '登陆成功';
        return $m_result->toJson();
    }
}
