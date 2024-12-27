<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];
    protected $fillable = ['status','shipping','products_discount_price','seller_commission','cart_total_mrp','mrp','post_commission','pre_commission','brand','address_id','user_id','price','tax','shipping_cost','discount','coupon_code','coupon_applied','quantity','user_id','temp_user_id','owner_id','product_id','variation'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
