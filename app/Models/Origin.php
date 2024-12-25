<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App;

class Origin extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $category_translation = $this->hasMany(OriginTranslation::class)->where('lang', $lang)->first();
        return $category_translation != null ? $category_translation->$field : $this->$field;
    }

    public function category_translations(){
    	return $this->hasMany(OriginTranslation::class);
    }

    public function products(){
    	return $this->hasMany(Product::class);
    }

    public function classified_products(){
    	return $this->hasMany(CustomerProduct::class);
    }

    public function categories()
    {
        return $this->hasMany(Origin::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Origin::class, 'parent_id')->with('categories');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Origin::class, 'parent_id');
    }
}
