<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use \Astrotomic\Translatable\Translatable;
    public $translatedAttributes = ['title'];
}
