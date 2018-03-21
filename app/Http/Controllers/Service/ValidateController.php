<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Entity\Member;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use App\Models\MResult;
use Validator;

class ValidateController extends Controller
{
    public function createCode(Request $request)
    {
        $validate = new ValidateCode();
        $request->session()->put('validate_code', $validate->getCode());
        $validate->doimg();
    }

    public function getRandomNumber($length = 5)
    {
        $charset = '123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $charset[mt_rand(0, strlen($charset) - 1)];
        }
        return $code;
    }

    public function sendSMS(Request $request)
    {
        // 实例化 回复类
        $m_result = new MResult;
        // 参数
        $phone = $request->input('phone', '');
        // 逻辑处理
        $v_phone = Validator::make($request->all(), [
            'phone' => 'required|min:11|regex:/^1[34578][0-9]{9}$/'
        ]);
        if ($v_phone->fails()) {
            $m_result->status = 1;
            $m_result->message = '手机号格式不正确';
            return $m_result->toJson();
        }
        // 发送短信
        $sendTemplateSMS = new SendTemplateSMS;
        $length = 6;
        $code = $this->getRandomNumber($length);
        $m_result =  $sendTemplateSMS->sendTemplateSMS($phone, [$code, 60], 1);
        // 保存验证码
//        $m_result->status = 0;
//        $m_result->message = '短信发送成功';
        if ($m_result->status == 0) {
            $tempPhone = new TempPhone();
            $_tempPhone = TempPhone::where('phone', '=', $phone)->get()->first();
            if ($_tempPhone == null) {
                $tempPhone->phone = $phone;
                $tempPhone->code = $code;
                $tempPhone->deadline = date('Y-m-d H:i:s', time() + 60 * 60);
                $tempPhone->save();
            } else {
                $_tempPhone->code = $code;
                $_tempPhone->deadline = date('Y-m-d H:i:s', time() + 60 * 60);
                $_tempPhone->save();
            }

        }
        return $m_result->toJson();
    }

    public function validateEmail(Request $request)
    {
        $member_id = $request->input('member_id', '');
        $code = $request->input('code', '');

        if ($member_id == '' || $code == '') return '服务验证异常';

        $tempEmail = tempEmail::where('member_id', '=', $member_id)->get()->first();

        if ($tempEmail == null) return '验证异常';

        if ($tempEmail->code == $code) {
            if (time() > strtotime($tempEmail->deadline)) return '链接已失效';
            $member = Member::find($member_id);
            $member->active = 1;
            $member->save();
            return redirect('/login');
        } else {
            return '链接已失效了';
        }

    }
}
