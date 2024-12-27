<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSettingProduct extends Model
{
   protected $fillable = [
      'home_page_setting_id','product_id','discount','discount_type','sort_order'
    ];
}
