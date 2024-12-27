<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\SubSubCategory;
use App\Category;
use Session;
use App\Color;
use Cookie;
use Response;
use App\BusinessSetting;
use View;
use App\Cart;
use Auth;
use Illuminate\Support\Facades\Log;
use App\Seller;
use App\User;

class CartController extends Controller
{
    public function index(Request $request)
    {
        //dd($cart->all());
        $categories = Category::all();
		 if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            if($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user_id,
                                    'temp_user_id' => 0
                                ]
                );

                Session::forget('temp_user_id');
            }
            $carts = Cart::where('user_id', $user_id)->where('status',1)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
        }

       return view('frontend.view_cart', compact('categories', 'carts'));
    }

    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.partials.addToCart', compact('product'));
    }

    public function updateNavCart(Request $request)
    {
        return view('frontend.partials.cart');
    }

    public function addToCart(Request $request)
    {
		//dd($request->all());
        $product = Product::find($request->id);
		$carts = array();
        $data = array();
        
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
			$data['product_id'] = $product->id;
            $carts = Cart::where('user_id', $user_id)->where('status',1)->get();
        } else {
            if($request->session()->get('temp_user_id')) {
                $temp_user_id = $request->session()->get('temp_user_id');
            } else {
                $temp_user_id = bin2hex(random_bytes(10));
                $request->session()->put('temp_user_id', $temp_user_id);
            }
            $data['temp_user_id'] = $temp_user_id;
			$data['product_id'] = $product->id;
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
        }
		//dd($user_id);
		
        //$data = array();

		
        $str = '';
        $tax = 0;

        if($product->digital != 1 && $request->quantity < $product->min_qty) {
            return array('status' => 0, 'view' => view('frontend.partials.minQtyNotSatisfied', [
                'min_qty' => $product->min_qty
            ])->render());
        }


        //check the color enabled or disabled for the product
        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if ($product->digital != 1) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }

        $data['variation'] = $str;

        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;
            $quantity = $product_stock->qty;
			$mrp = $product_stock->price;
			$variant_discount = $product_stock->discount_price;
			$variant_discount_type = $product_stock->discount_type;
			$shipping = $product->shipping_cost * $request['quantity'] ;
			$brand_id = $product_stock->product->brand_id;
            if($quantity < $request['quantity']){
                return array('status' => 0, 'view' => view('frontend.partials.outOfStockCart')->render());
            }
        }
        else{
            $price = $product->unit_price;
			$mrp = $product->unit_price;
			$brand_id = $product->brand_id;
			$shipping = $product->shipping_cost * $request['quantity'];
        }
//dd($shipping);
        //discount calculation based on flash deal and regular discount
        //calculation of taxes
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
		
        $inFlashDeal = false;
        foreach ($flash_deals as $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1  && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) 
			{
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
					$discount_price_data = $flash_deal_product->discount;
					$data['products_discount_price'] = $discount_price_data;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
					$discount_price_data = $flash_deal_product->discount;
					$data['products_discount_price'] = $discount_price_data;
                }
				
                $inFlashDeal = true;
                break;
            }
			
        }
		
		
        if (!$inFlashDeal) {
			if($variant_discount_type == 'percent'){
                $price -= ($price*$variant_discount)/100;
				//dd($variant_discount);
            }
            elseif($variant_discount_type == 'amount'){
                $price -= $variant_discount;
            }
            /*if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount'){
                $price -= $product->discount;
            }*/
        }

        if($product->tax_type == 'percent'){
            $tax = ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $tax = $product->tax;
        }
        
        
        $seller = Seller::whereHas('user', function ($query) use ($product) {
            $query->where('user_type', 'seller')->where('brand_id', $product->brand_id);
        })->first();
        
        if ($seller && $seller->selected_plan) {
            $commission_percentage = $seller->selected_plan;
        } 
        //  Log::info('Selected Plan: ' . $commission_percentage);
// 		$commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
        $data['quantity'] = $request['quantity'];
        $data['price'] = $price;
        $data['tax'] = $tax;
		$data['mrp'] = $mrp;
		$data['brand'] = $brand_id;
        $data['shipping'] = $shipping;
        $data['product_referral_code'] = null;
        $data['digital'] = $product->digital;
		$data['seller_commission'] = $commission_percentage;
		$data['cart_total_mrp'] = $mrp * $request['quantity'];
		$data['pre_commission'] = (($mrp * $request['quantity']) * ($commission_percentage/100)) ;
		$data['post_commission'] = ($mrp * $request['quantity']) - (($mrp * $request['quantity']) * ($commission_percentage/100));
		
		//dd($data);
        if ($request['quantity'] == null){
            $data['quantity'] = 1;
        }

        if(Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $data['product_referral_code'] = Cookie::get('product_referral_code');
        }

        if($carts && count($carts) > 0){
            $foundInCart = false;
            //$cart = collect();

            foreach ($carts as $key => $cartItem){
                if($cartItem['id'] == $request->id){
                    if($cartItem['variant'] == $str && $str != null){
                        $product_stock = $product->stocks->where('variant', $str)->first();
                        $quantity = $product_stock->qty;

                        if($quantity < $cartItem['quantity'] + $request['quantity']){
                            return array('status' => 0, 'view' => view('frontend.partials.outOfStockCart')->render());
                        }
                        else{
                            $foundInCart = true;
                            $cartItem['quantity'] += $request['quantity'];
                        }
                    }
                }
               // $cart->push($cartItem);
            }

            if (!$foundInCart) {
				 Cart::create($data);
                //$cart->push($data);
            }
            //$request->session()->put('cart', $cart);
        }
        else{
			Cart::create($data);
           // $cart = collect([$data]);
          //  $request->session()->put('cart', $cart);
        }
		//dd($data);
        return array('status' => 1, 'view' => view('frontend.partials.addedToCart', compact('product', 'data'))->render());
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
		//dd($request->all());
       /* if($request->session()->has('cart')){
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        return view('frontend.partials.cart_details');*/
		 Cart::destroy($request->id);
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->where('status',1)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
			//dd($carts);
        }
        
        
        return view('frontend.partials.cart_details', compact('carts'));
    }
 
    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
		//dd($request->all());

        $object = Cart::findOrFail($request->id);
        
         $seller = Seller::whereHas('user', function ($query) use ($object) {
            $query->where('brand_id', $object->brand);
        })->first();
        
        
        Log::info('Selected Plan: ' . $seller);
        
        if ($seller && $seller->selected_plan) {
            $commission_percentage = $seller->selected_plan;
        } 
        
        
        
        
        
        
        // $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
        
        
        if($object['id'] == $request->id){
            $product = \App\Product::find($object['product_id']);
//                if($object['variant'] != null && $product->variant_product){
            
            $product_stock = $product->stocks->where('variant', $object['variation'])->first();
            $quantity = $product_stock->qty;
            if($quantity >= $request->quantity) {
                if($request->quantity >= $product->min_qty){
                    $object['quantity'] = $request->quantity;
					$object['seller_commission'] = $commission_percentage;
					$object['cart_total_mrp'] = $object['mrp'] * $request['quantity'];
					$object['pre_commission'] = (($object['mrp'] * $request['quantity']) * ($commission_percentage/100)) ;
					$object['post_commission'] = ($object['mrp'] * $request['quantity']) - (($object['mrp'] * $request['quantity']) * ($commission_percentage/100));
					
                }
            }
            
            $object->save();
        }
        
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->where('status',1)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
        }
        
        return view('frontend.partials.cart_details', compact('carts'));
    }
    public function updateQuantitys(Request $request)
    {
		$commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
		$object = Cart::findOrFail($request->id);        
        if($object['id'] == $request->id){
            $product = \App\Product::find($object['product_id']);
//                if($object['variant'] != null && $product->variant_product){
            
            $product_stock = $product->stocks->where('variant', $object['variation'])->first();
            $quantity = $product_stock->qty;
            if($quantity >= $request->quantity) {
                if($request->quantity >= $product->min_qty){
                    $object['quantity'] = $request->quantity;
					$object['seller_commission'] = $commission_percentage;
					$object['cart_total_mrp'] = $object['mrp'] * $request['quantity'];
					$object['pre_commission'] = (($object['mrp'] * $request['quantity']) * ($commission_percentage/100)) ;
					$object['post_commission'] = ($object['mrp'] * $request['quantity']) - (($object['mrp'] * $request['quantity']) * ($commission_percentage/100));
					
		
                }
            }
            
            $object->save();
        }
        
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->where('status',1)->get();
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
        }
        
        return view('frontend.partials.ajax_payment_select', compact('carts'));
		
    }
    public function removeFromCarts(Request $request)
    {
		//dd($request->id);
		 Cart::destroy($request->id);
        if(auth()->user() != null) {
            $user_id = Auth::user()->id;
            $carts = Cart::where('user_id', $user_id)->where('status',1)->get();
			if(count($carts) > 0)
			{
				return Response::json(['view' => View::make('frontend.partials.ajax_payment_select')->render(), 'status'=>1]);
			}
			else
			{
				return Response::json(['view' => View::make('frontend.partials.ajax_payment_select')->render(), 'status'=>0]);
			}
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
			//dd($carts);
        }
		
		  return view('frontend.partials.ajax_payment_select');
        /*if($request->session()->has('cart')){
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
			if(count($cart) > 0)
			{
				return Response::json(['view' => View::make('frontend.partials.ajax_payment_select')->render(), 'status'=>1]);
			}
			else
			{
				return Response::json(['view' => View::make('frontend.partials.ajax_payment_select')->render(), 'status'=>0]);
			}
        }
        return view('frontend.partials.ajax_payment_select');*/
    }
    

}
