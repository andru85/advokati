<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use \Astrotomic\Translatable\Translatable;
    public $translatedAttributes = ['title'];
}
