<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;


class ProductDescription extends Model
{
    use SoftDeletes;
    
    protected $table = 'product_descriptions';
  
    protected $fillable = ['heading'];
    
      public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
