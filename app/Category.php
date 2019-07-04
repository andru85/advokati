<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    protected $fillable = [
        'parent_id',
    ];

    use \Astrotomic\Translatable\Translatable;
    public $translatedAttributes = ['name'];

    public function Translations()
    {
        return $this->hasMany('App\CategoryTranslation');
    }
}
