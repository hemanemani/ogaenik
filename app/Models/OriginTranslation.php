<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OriginTranslation extends Model
{
    protected $fillable = ['name', 'lang', 'origin_id'];

    public function category(){
    	return $this->belongsTo(Origin::class);
    }
}
