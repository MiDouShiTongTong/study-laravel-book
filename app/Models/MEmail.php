<?php

namespace App\Models;
use Mail;

Class MEmail {

    public $from;       // 发件人
    public $to;         // 收件人
    public $cc;         // 抄送
    public $attach;     // 附件
    public $subject;    // 主题
    public $content;    // 内容

    public function sendEmail()
    {
        Mail::send('component.email_register', [
            'm_email' => $this
        ], function($email) {
            $email->from('i@yc66.me', '[laravel-book]邮件注册验证');
            $email->to($this->to)->cc($this->cc)->subject($this->subject);
        });
    }

}