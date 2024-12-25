<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandTranslation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $fillable = ['name', 'lang', 'brand_id'];

  public function brand(){
    return $this->belongsTo(Brand::class);
  }
}
