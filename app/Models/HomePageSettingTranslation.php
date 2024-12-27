<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePageSettingTranslation extends Model
{
  protected $fillable = ['title', 'lang', 'home_page_setting_id'];

  public function flash_deal(){
    return $this->belongsTo(HomePageSetting::class);
  }

}
