<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App;

class Attribute extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $attribute_translation = $this->hasMany(AttributeTranslation::class)->where('lang', $lang)->first();
      return $attribute_translation != null ? $attribute_translation->$field : $this->$field;
    }

    public function attribute_translations(){
      return $this->hasMany(AttributeTranslation::class);
    }

}
