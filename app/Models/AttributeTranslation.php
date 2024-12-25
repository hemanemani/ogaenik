<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeTranslation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $fillable = ['name', 'lang', 'attribute_id'];

  public function attribute(){
    return $this->belongsTo(Attribute::class);
  }

}
