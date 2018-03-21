<?php

namespace App\Tool\Validate;

//验证码类
class ValidateCode
{
    private $width = 139;
    private $height = 44;
    private $counts = 4;
    private $disturbCode = '123456789wertyuipasdfghjklzxcvbWERTYUIPASDFGHJKLZXCVB';
    private $fontUrl = 'fonts/AdobeGothicStd-Bold.otf';
    private $fontSize = 26;
    private $code;

    // 构造方法初始化
    function __construct()
    {
        $this->code = $this->getDisturbCode();
    }

    public function getCode()
    {
        return $this->code;
    }

    // 创建图像资源句柄
    private function createImageSource()
    {
        return imagecreate($this->width, $this->height);
    }

    // 生成随机背景
    private function setBackgroundColor($img){
        $bgColor = ImageColorAllocate($img, rand(200, 255), rand(200, 255), rand(200, 255));
        imagefill($img, 0, 0, $bgColor);
    }

    // 生成随机 小的干扰字符串
    private function setSubDisturbCode($img)
    {
        $count_h = $this->height;
        $cou = floor($count_h * 2);
        for ($i = 0; $i < $cou; $i++) {
            $x = rand(0, $this->width);
            $y = rand(0, $this->height);
            $range = rand(0, 360);
            $fontSize = $this->fontSize / 6;
            $fontUrl = $this->fontUrl;
            $originCode = $this->disturbCode;
            $countDisturb = strlen($originCode);
            $disturbCode = $originCode[rand(0, $countDisturb - 1)];
            $color = ImageColorAllocate($img,  rand(40, 140), rand(40, 140), rand(40, 140));
            imagettftext($img, $fontSize, $range, $x, $y, $color, $fontUrl, $disturbCode);
        }
    }

    // 生成随机干扰字符串
    private function setCode($img)
    {
        $width = $this->width;
        $height = $this->height;
        $y = floor($height / 1.6) + floor($height / 6);
        $fontSize = $this->fontSize;
        $counts = $this->counts;
        for($i = 0; $i < $counts; $i++) {
            $char = $this->code[$i];
            $x = floor($width / $counts) *$i + 8;
            $range = rand(-13, 23);
            $color = ImageColorAllocate($img, rand(0, 50), rand(50, 100), rand(100, 140));
            imagettftext($img, $fontSize, $range, $x, $y, $color, $this->fontUrl, $char);
        }
    }

    // 获取随机干扰字符串
    private function getDisturbCode()
    {
        $originCode = $this->disturbCode;
        $countDisturb = strlen($originCode);
        $_disturbCode = '';
        $counts = $this->counts;
        for ($j = 0; $j < $counts; $j++) {
            $disturbCode = $originCode[rand(0, $countDisturb - 1)];
            $_disturbCode .= $disturbCode;
        }
        return $_disturbCode;
    }

    // 输出图像
    function doimg()
    {
        header('Content-type:image/gif');
        $img = $this->createImageSource();
        $this->setBackgroundColor($img);
        $this->setCode($img);
        $this->setSubDisturbCode($img);
        imagepng($img);
        imagedestroy($img);
    }
}