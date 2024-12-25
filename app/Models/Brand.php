<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App;
use App\Models\Product;

class Brand extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $brand_translation = $this->hasMany(BrandTranslation::class)->where('lang', $lang)->first();
      return $brand_translation != null ? $brand_translation->$field : $this->$field;
  }

  public function brand_translations(){
    return $this->hasMany(BrandTranslation::class);
  }
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
 