<?php

namespace App\Models;

class MResult
{
    public $status;
    public $message;
    public $categorys;

    public function toJson()
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
}