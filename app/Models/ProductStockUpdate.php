<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductStockUpdate extends Model
{
    protected $table = 'product_stock_update';

    protected $fillable = ['email', 'product_id','status','stock_qty'];
}
