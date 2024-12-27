<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class HomePageSetting extends Model
{
    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $flash_deal_translation = $this->hasMany(HomePageSettingTranslation::class)->where('lang', $lang)->first();
        return $flash_deal_translation != null ? $flash_deal_translation->$field : $this->$field;
    }

    public function homesetting_translations(){
      return $this->hasMany(HomePageSettingTranslation::class);
    }

    public function home_settings_products()
    {
        return $this->hasMany(HomePageSettingProduct::class);
    }
}
