<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

class PdtImages extends Model
{
    protected $table = 'pdt_image';

    protected $primaryKey = 'id';

    public $timestamps = true;
}
