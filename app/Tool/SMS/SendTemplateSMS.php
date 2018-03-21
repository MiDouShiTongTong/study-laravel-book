<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 */




namespace App\Tool\SMS;
use App\Models\MResult;



class SendTemplateSMS
{
    //主帐号,对应开官网发者主账号下的 ACCOUNT SID
    private $accountSid = '8a216da857dc1f6a0157ea2fab8e0f5a';

    //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    private $accountToken = 'b313ca63f1fb4681a9d0afe1d0ea7819';

    //应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    //在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    private $appId = '8a216da857dc1f6a0157ea2fae660f61';

    //请求地址
    //沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    //生产环境（用户应用上线使用）：app.cloopen.com
    private $serverIP = 'app.cloopen.com';


    //请求端口，生产环境和沙盒环境一致
    private $serverPort = '8883';

    //CCPRestSmsSDK版本号，在官网文档CCPRestSmsSDK介绍中获得。
    private $softVersion = '2013-12-26';


    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
     */
    public function SendTemplateSMS($to, $datas, $tempId)
    {
        $m_result = new MResult();
        // 初始化CCPRestSmsSDK SDK
        $rest = new CCPRestSmsSDK($this->serverIP, $this->serverPort, $this->softVersion);
        $rest->setAccount($this->accountSid, $this->accountToken);
        $rest->setAppId($this->appId);
        $result = $rest->sendTemplateSMS($to, $datas, $tempId);
        if ($result == NULL) {
            $m_result->status = 3;
            $m_result->message = 'reuslt error';
        }
        if ($result->statusCode != 0) {
            $m_result->status = $result->statusCode;
            $m_result->message = $result->statusMsg;
        } else {
            $m_result->status = 0;
            $m_result->message = '短信发送成功';
        }
        return $m_result;
    }
}