<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
  
    protected $fillable = ['file_path','file_name','digital','subsubcategory_id','subcategory_id','barcode','rating','name','added_by','user_id','category_id','brand_id','department_id','origin_id','city_id',
	'tags','description','manufacturer','disclaimer','unit_price','purchase_price','variant_product','attributes','choice_options',
	'colors','variations','current_stock','unit','todays_deal','published','featured','min_qty','discount','discount_type','tax','tax_type','shipping_type',
	'shipping_cost','hsn_code','weight','dimensions','meta_title','meta_description','num_of_sale','video_provider','pdf','video_link','photos','thumbnail_img','meta_img','slug','ingredients','packaging'];

    public function getTranslation($field = '', $lang = false){
      $lang = $lang == false ? App::getLocale() : $lang;
      $product_translations = $this->hasMany(ProductTranslation::class)->where('lang', $lang)->first();
      return $product_translations != null ? $product_translations->$field : $this->$field;
    }

    public function product_translations(){
    	return $this->hasMany(ProductTranslation::class);
    }

    public function category(){
    	return $this->belongsTo(Category::class);
    }
    public function origin(){
    	return $this->belongsTo(Origin::class);
    }
    public function brand(){
    	return $this->belongsTo(Brand::class);
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function department(){
    	return $this->belongsTo(Department::class);
    }

    public function orderDetails(){
    return $this->hasMany(OrderDetail::class);
    }
   

    public function reviews(){
    return $this->hasMany(Review::class)->where('status', 1);
    }

    public function wishlists(){
    return $this->hasMany(Wishlist::class);
    }

    public function stocks(){
    return $this->hasMany(ProductStock::class);
    }
}
