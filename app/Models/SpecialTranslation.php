<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialTranslation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $fillable = ['name', 'lang', 'special_id'];

  public function department(){
    return $this->belongsTo(Special::class);
  }
}
