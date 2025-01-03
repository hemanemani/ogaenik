<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Color;
use App\Models\Order;
use App\Models\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use Mail;
use App\Utility\TranslationUtility;
use App\Utility\CategoryUtility;
use App\Models\Department;
use App\Models\Origin;
use App\Models\Special; 
use App\Models\HomePageSetting;
use App\Models\OrderDetail;
use App\Models\Cart;
class HomeController extends Controller
{
    public function login()
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        return redirect()->route('home');
    }

    public function registration(Request $request)
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        if($request->has('referral_code')){
            Cookie::queue('referral_code', $request->referral_code, 43200);
        }
        return back();
    }

    public function cart_login(Request $request)
    {
		//dd($request->all());
		if($request->login_types == 0)
		{
			$user = User::whereIn('user_type', ['customer'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
			if($user != null)
			{
				if(Hash::check($request->password, $user->password)){
					if($request->has('remember')){
						auth()->login($user, true);
					}
					else{
						auth()->login($user, false);
					}
				}
				if($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user->id,
                                    'temp_user_id' => 0
                                ]
                );

                Session::forget('temp_user_id');
            }
				
			}
			flash(translate('Logged in'))->success();
        return redirect()->route('home');
		}
		elseif($request->login_types == 1)
		{
			$user = User::whereIn('user_type', ['customer'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
			if($user != null)
			{
				if(Hash::check($request->password, $user->password)){
					if($request->has('remember')){
						auth()->login($user, true);
					}
					else{
						auth()->login($user, false);
					}
				}
				if($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user->id,
                                    'temp_user_id' => 0
                                ]
                );

                Session::forget('temp_user_id');
            }
				
			}
			flash(translate('Logged in'))->success();
        return redirect()->route('checkout.shipping_info');
		}
        $user = User::whereIn('user_type', ['customer'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        if($user != null){
            if(Hash::check($request->password, $user->password)){
                if($request->has('remember')){
                    auth()->login($user, true);
                }
                else{
                    auth()->login($user, false);
                }
            }
           
        }
		if($request->session()->get('temp_user_id')) {
                Cart::where('temp_user_id', $request->session()->get('temp_user_id'))
                        ->update(
                                [
                                    'user_id' => $user->id,
                                    'temp_user_id' => null
                                ]
                );

                Session::forget('temp_user_id');
            }
       return redirect()->route('home');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard()
    {
        return view('backend.dashboard');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::user()->user_type == 'seller'){
            return view('frontend.user.seller.dashboard');
        }
        elseif(Auth::user()->user_type == 'customer'){
            return view('frontend.user.customer.dashboard');
        }
        else {
            abort(404);
        }
    }

    public function profile(Request $request)
    {
		
        if(Auth::user()->user_type == 'customer'){
			$order = OrderDetail::where('user_id',Auth::user()->id)->get();
			$result = array();
			foreach($order as $key=> $val)
			{
			  $result[$val['delivery_status']] = $val['delivery_status'];
			}
			$a = array_key_exists("pending",$result);
			$b = array_key_exists("dispatched",$result);
			$c = array_key_exists("shipped",$result);
			$product = null;
			//dd($a);
			if($a == true || $b == true || $c == true)
			{
				$users = User::findOrFail(Auth::user()->id);
				$users->product_status = 0;
				$users->save();
				
			}
			else
			{
				$users = User::findOrFail(Auth::user()->id);
				$users->product_status = 1;
				$users->save();
			}	
			//$c = $product;
            return view('frontend.user.customer.profile');
        }
        elseif(Auth::user()->user_type == 'seller'){
            return view('frontend.user.seller.profile');
        }
    }

    public function customer_update_profile(Request $request)
    {
        

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }
        $user->avatar_original = $request->photo;

        if($user->save()){
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }


    public function seller_update_profile(Request $request)
    {
        

        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }
        $user->avatar_original = $request->photo;

        $seller = $user->seller;
        $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
        $seller->bank_payment_status = $request->bank_payment_status;
        $seller->bank_name = $request->bank_name;
        $seller->bank_acc_name = $request->bank_acc_name;
        $seller->bank_acc_no = $request->bank_acc_no;
        $seller->bank_routing_no = $request->bank_routing_no;

        if($user->save() && $seller->save()){
            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
		$gitags = HomePageSetting::where('product_location', '53')->first();
		$gitag = HomePageSetting::where('slug', $gitags->slug)->first();

		$seller = HomePageSetting::where('product_location', '55')->first();
		$seller_week = HomePageSetting::where('slug', $seller->slug)->first();	
		$seller_brand = Brand::where('id', $seller->brands)->first();

		
		$bestproduct = HomePageSetting::where('product_location', '55')->first();	
		$gitag1 = HomePageSetting::where('slug', $bestproduct->slug)->first();		
        return view('frontend.index',compact('gitag','gitag1','gitags','bestproduct','seller','seller_week','seller_brand'));
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
		
        if($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }
	 public function home_page_design_details($slug)
    {
        $flash_deal = HomePageSetting::where('slug', $slug)->first();		
        if($flash_deal != null)
            return view('frontend.home_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }
	public function special_deal_details($slug)
    {
       $flash_deal  = HomePageSetting::where('slug', $slug)->first();
	   if($flash_deal  != null)
            return view('frontend.home_page_setting_details', compact('flash_deal'));
        else {
            abort(404);
        }	 
    }

    public function load_featured_section(){
        return view('frontend.partials.featured_products_section');
    }

    public function load_best_selling_section(){
        return view('frontend.partials.best_selling_section');
    }

    public function load_home_categories_section(){
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section(){
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug,$category_id = null)
    {
        $detailedProduct  = Product::where('slug', $slug)->first();

        if($detailedProduct!=null && $detailedProduct->published){
            //updateCartSetup();
            if($request->has('product_referral_code')){
                Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
            }
            if($category_id != null){
                $category_ids = CategoryUtility::children_ids($category_id);
                $category_ids[] = $category_id;
    
                $products = $products->whereIn('category_id', $category_ids);
            }
            if($detailedProduct->digital == 1){
                return view('frontend.digital_product_details', compact('detailedProduct'));
            }
            else {
                return view('frontend.product_details', compact('detailedProduct'));
            }
            // return view('frontend.product_details', compact('detailedProduct'));
        }
        abort(404);
    }

    public function shop($slug)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null){
            $seller = Seller::where('user_id', $shop->user_id)->first();
            if ($seller->verification_status != 0){
                return view('frontend.seller_shop', compact('shop'));
            }
            else{
                return view('frontend.seller_shop_without_verification', compact('shop', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop', compact('shop', 'type'));
        }
        abort(404);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::where('level', 0)->where('featured',1)->orderBy('name', 'asc')->get();
        return view('frontend.all_category', compact('categories'));
    }
	public function all_categories_admin(Request $request)
    {
        $categories = Category::where('level', 0)->where('featured',1)->orderBy('name', 'asc')->get();
        return view('frontend.admin_all_category', compact('categories'));
    }
	
    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        if(\App\Models\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Models\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_uploads > 0){
                $categories = Category::all();
                return view('frontend.user.seller.product_upload', compact('categories'));
            }
            else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_upload', compact('categories'));
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('frontend.user.seller.product_edit', compact('product', 'categories', 'tags', 'lang'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%'.$search.'%');
        }
        $products = $products->paginate(10);
        return view('frontend.user.seller.products', compact('products', 'search'));
    }

    public function ajax_search(Request $request)
    {		
		
        $keywords = array();
		$product = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
		$a = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
			if($a->isEmpty())
			{ 			
				$products = filter_products(Product::where('published', 1)->where('category_id','!=', 46)->where('brand_id', 1))->get()->take(10);		
			}
			else
			{
				$products = filter_products(Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%'))->get()->take(10);		
				
				
			}
		/*if($request->category != null)
		{
			$main_category = Category::where('slug',$request->category)->first()->id;
		$category_ids = CategoryUtility::children_ids($main_category);
        $category_ids[] = $main_category; 		
        $category_menu =  Category::whereIn('parent_id', $category_ids)->where('level','2')->pluck('id');		
		$product = Product::whereIn('category_id',$category_menu)->where('published', 1)->where('name', 'like', '%'.$request->search.'%')->get();
		//dd($category_menu);
		$products = filter_products(Product::whereIn('category_id',$category_menu)->where('published', 1)->where('name', 'like', '%'.$request->search.'%'))->get()->take(10);
		
		}
		else
		{
			$product = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
		$products = filter_products(Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%'))->get()->take(10);
		}*/
        foreach ($products as $key => $product) {
            foreach (explode(',',$product->tags) as $key => $tag) {
                if(stripos($tag, $request->search) !== false){
                    if(sizeof($keywords) > 5){
                        break;
                    }
                    else{
                        if(!in_array(strtolower($tag), $keywords)){
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        
		//dd($products);
        $categories = Category::where('name', 'like', '%'.$request->search.'%')->get()->take(10);
		
		$brands = Brand::where('slug','!=',null)->where('status',1)->where('name', 'like', '%'.$request->search.'%')->get()->take(5);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%'.$request->search.'%')->get()->take(3);
		//dd($brands);
        if(sizeof($keywords)>0 || sizeof($brands)>0 || sizeof($categories)>0 || sizeof($products)>0 || sizeof($shops) >0){
            return view('frontend.partials.search_content', compact('brands','products', 'categories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function listing(Request $request)
    {
        return $this->search($request);
    }

   /* public function listingByCategory(Request $request, $category_slug)
    {
		//dd($category_slug);
        $category = Category::where('slug', $category_slug)->first();
        if ($category != null) {
			//dd($category->id);
            return $this->search($request, $category->id);
        }
        abort(404);
    }*/
    public function main_category_search(Request $request,$id)
    {  
        $category = Category::where('slug', $id)->first();       
        $category_ids = CategoryUtility::children_ids($category->id);
        $category_ids[] = $category->id;        
        $products =  Category::whereIn('parent_id', $category_ids)->where('level','2')->get()->toArray();
		if($products != null)
		{		
			return view('frontend.main_category_listing', compact('products','category'));
		}
		abort(404);
    }
    public function category_search(Request $request, $id)
    {	
        $category = Category::where('slug', $id)->first();
        $products =  Category::where('parent_id', $category->id)->get();
        return view('frontend.category_listing', compact('products','category'));
    }
    
public function search_bar(Request $request, $category_id = null, $brand_id = null)
    {
		//dd($request->all());
        $query = $request->q;
		$a = Product::where('published', 1)->where('tags', 'like', '%'.$query.'%')->get();
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;

        $conditions = ['published' => 1];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }elseif ($request->brand != null) {
            $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }

        if($seller_id != null){
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }

        $products = Product::where($conditions);

        if($category_id != null){
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;

            $products = $products->whereIn('category_id', $category_ids);
        }

        if($min_price != null && $max_price != null){
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }
		if($a->isEmpty())
			{ 			
				$products = $products->where('brand_id', 1)->where('category_id','!=', 46);
			}
			else
				{
					if($query != null){
				$searchController = new SearchController;
				$searchController->store($request);
				$products = $products->where('name', 'like', '%'.$query.'%')->orWhere('tags', 'like', '%'.$query.'%');
				}	
			}

        /*if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);
            $products = $products->where('name', 'like', '%'.$query.'%')->orWhere('tags', 'like', '%'.$query.'%');
        }
		else
		{
			 $products = $products->where('name', 'like', '%tea%');
		}*/

        switch ($sort_by) {
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $products->orderBy('unit_price', 'desc');
                break;
            default:
                $products->orderBy('created_at', 'desc');
                break;
        }


        $non_paginate_products = filter_products($products)->get();

        //Attribute Filter

        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if($product->attributes != null && is_array(json_decode($product->attributes))){
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if($attribute['id'] == $value){
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if(!$flag){
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    }
                    else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                foreach ($choice_option->values as $key => $value) {
                                    if(!in_array($value, $attributes[$pos]['values'])){
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('choice_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if(!in_array($color, $all_colors)){
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if($request->has('color')){
            $str = '"'.$request->color.'"';
            $products = $products->where('colors', 'like', '%'.$str.'%');
            $selected_color = $request->color;
        }


        $products = filter_products($products)->paginate(100000)->appends(request()->query());

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color'));
    }
	
    public function search(Request $request,  $id, $special_id_list = null, $department = null,$origin_id = null)
    {
		
		
        $query = $request->q;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $seller_id = $request->seller_id;
		//$department = $request->department;
        
		
		
		
		$department = (Department::where('slug', $request->department)->first() != null) ? Department::where('slug', $request->department)->first()->id : null;
        $origin_id = (Origin::where('slug', $request->origin)->first() != null) ? Origin::where('slug', $request->origin)->first()->id : null;
		$special_id_list = (Special::where('slug', $request->special)->first() != null) ? Special::where('slug', $request->special)->first()->id : null;
		$category_id = (Category::where('slug', $id)->first() != null) ? Category::where('slug', $id)->first()->id : null;
		$brand_id = (Brand::where('slug', $id)->first() != null) ? Brand::where('slug', $id)->first()->id : null;
		 // dd($brand_id);
		 
// 		 dd($department);  
		  
		if($query !='' || $category_id != '' || $department != null || $brand_id !=null ||  $origin_id != '' || $special_id_list !='' )
		{	
		$conditions = ['published' => 1];	
		if($brand_id != null)
		{ 
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }
// 		if($department != null)
// 		{
//             $conditions = array_merge($conditions, ['department_id' => $department]);
// 			//dd($conditions);
//         } 
// 		elseif ($request->department != null) 
// 		{            
//             $conditions = array_merge($conditions, ['department_id' => $department]);
			
//         }
		if($origin_id != null){
            $conditions = array_merge($conditions, ['origin_id' => $origin_id]);
        }
		if($seller_id != null){ 
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }
        $products = Product::where($conditions);
        
   
		
		//dd($category_id);
        if($category_id != null){
            $category_ids = CategoryUtility::children_ids($category_id);
            $category_ids[] = $category_id;
			
            $products = $products->whereIn('category_id', $category_ids);
			
        }
		if($special_id_list != null)
        {        
            $searchController = new SearchController;           
            $searchController->store1($special_id_list);
			// $conditions = array_merge($conditions, [$products->where('special_id', 'like', '%'.$special_id_list.'%')]);
			// dd($conditions);
            $products = $products->where('special_id', 'like', '%'.$special_id_list.'%');
			
        }
        
        if($department != null)
        {        
            $searchController = new SearchController;           
            $searchController->store1($department);
			
            $products = $products->where('department_id', 'like', '%'.$department.'%');
			
		
        }
        
        
        
        if($min_price != null && $max_price != null){
            $products = $products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);
            $products = $products->where('name', 'like', '%'.$query.'%')->orWhere('tags', 'like', '%'.$query.'%');
        }

        if($sort_by != null){
            switch ($sort_by) {
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $products->orderBy('created_at', 'asc');
                    break;
                case 'price-asc':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case 'price-desc':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }


        $non_paginate_products = filter_products($products)->get();

        //Attribute Filter

        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if($product->attributes != null && is_array(json_decode($product->attributes))){
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if($attribute['id'] == $value){
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if(!$flag){
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    }
                    else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                foreach ($choice_option->values as $key => $value) {
                                    if(!in_array($value, $attributes[$pos]['values'])){
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('choice_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if(!in_array($color, $all_colors)){
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if($request->has('color')){
            $str = '"'.$request->color.'"';
            $products = $products->where('colors', 'like', '%'.$str.'%');
            $selected_color = $request->color;
        }
        

        $products = filter_products($products)->get();

        return view('frontend.product_listing', compact('products','origin_id','special_id_list','query', 'department','category_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color'));
    }
		else
			{
				abort(404);	
			}
	}
	
	
	
	
	
	
	public function department_list()
    {
        return view('frontend.department_listing');
    }
	public function origin_list()
    {
        return view('frontend.origin_list');
    }
    public function product_content(Request $request){
        $connector  = $request->connector;
        $selector   = $request->selector;
        $select     = $request->select;
        $type       = $request->type;
        productDescCache($connector,$selector,$select,$type);
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if(is_array($request->top_categories) && in_array($category->id, $request->top_categories)){
                $category->top = 1;
                $category->save();
            }
            else{
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if(is_array($request->top_brands) && in_array($brand->id, $request->top_brands)){
                $brand->top = 1;
                $brand->save();
            }
            else{
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(translate('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;

        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if(json_decode(Product::find($request->id)->choice_options) != null){
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= '-'.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }
 


        if($str != null && $product->variant_product){
            $product_stock = $product->stocks->where('product_id',$request->id)->where('variant', $str)->first();
            $price = $product_stock->price;
			$actual_price = $product_stock->price;
			$variant_discount = $product_stock->discount_price;
			$variant_discount_type = $product_stock->discount_type;
            $quantity = $product_stock->qty;
			//dd($product_stock);
        }
        else{
            $price = $product->unit_price;
            $quantity = $product->current_stock;
			$actual_price = $product->unit_price;
        }

        //discount calculation
        $flash_deals = \App\Models\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
           
			if($product->discount_type != null)
			{
				 if($product->discount_type == 'percent'){
					$price -= ($price*$product->discount)/100;
				}
				elseif($product->discount_type == 'amount'){
					$price -= $product->discount;
				}
			}
			else
			{
				 if($variant_discount_type == 'percent'){
                $price -= ($price*$variant_discount)/100;
				//dd($variant_discount);
            }
            elseif($variant_discount_type == 'amount'){
                $price -= $variant_discount;
            }
			}
			
        }

      /*  if($product->tax_type == 'percent'){
            $price += ($price*$product->tax)/100;
        }
        elseif($product->tax_type == 'amount'){
            $price += $product->tax;
        }*/
		 if($variant_discount_type == 'percent'){
                $price -= ($price*$variant_discount)/100;
				//dd($variant_discount);
            }
            elseif($variant_discount_type == 'amount'){
                $price -= $variant_discount;
            }
		//dd($price);
        return array('price' => single_price($price),'actual_price' => single_price($actual_price), 'quantity' => $quantity, 'digital' => $product->digital);
    }

    public function sellerpolicy(){
        return view("frontend.policies.sellerpolicy");
    }

    public function returnpolicy(){
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy(){
        return view("frontend.policies.supportpolicy");
    }

    public function terms(){
        return view("frontend.policies.terms");
    }

    public function privacypolicy(){
        return view("frontend.policies.privacypolicy");
    }

    public function get_pick_ip_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request){
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

   

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        if(\App\Models\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Models\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_digital_uploads > 0){
                $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
                $categories = Category::where('digital', 1)->get();
                return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
            }
            else {
                flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                return back();
            }
        }

        $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
        $categories = Category::where('digital', 1)->get();
        return view('frontend.user.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $lang = $request->lang;
        $product = Product::find($id);
        return view('frontend.user.seller.digitalproducts.product_edit', compact('categories', 'product', 'lang'));
    }

    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;
        if(isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        $response = $this->send_email_change_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if(isUnique($email)) {
            $this->send_email_change_verification_mail($request, $email);
            flash(translate('A verification mail has been sent to the mail you provided us with.'))->success();
            return back();
        }

        flash(translate('Email already exists!'))->warning();
        return back();
    }

    public function send_email_change_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = 'Verify your account';
        $array['link'] = route('email_change.callback').'?new_email_verificiation_code='.$verification_code.'&email='.$email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");

        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function email_change_callback(Request $request){
        if($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $user = User::where('new_email_verificiation_code', $verification_code_of_url_param)->first();

            if($user != null) {

                $user->email = $request->input('email');
                $user->new_email_verificiation_code = null;
                $user->save();

                auth()->login($user, true);

                flash(translate('Email Changed successfully'))->success();
                return redirect()->route('dashboard');
            }
        }

        flash(translate('Email was not verified. Please resend your mail!'))->error();
        return redirect()->route('dashboard');

    }
}
