<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OTPVerificationController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\AffiliateController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Address;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\OtpConfiguration;
use App\Models\User;
use App\Models\Cart;
use App\Models\BusinessSetting;
use Auth;
use Session;
use DB;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Mail\UpdateDeliveryEmailStatus;
use App\Mail\DeliveryEmailStatus;
use App\Mail\AdminUpdateEmailManager;
use App\Mail\PlaceEmailStatus;
use App\Mail\SellerDeliveryEmailStatus;
use App\Mail\SellerUpdateDeliveryEmailStatus;
use App\Mail\SellerEmailManager;
use App\Mail\SellerCancelEmailStatus;
use App\Mail\CancelEmailStatus;
use App\Mail\SellerPaidEmailStatus;
use App\Brand;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', Auth::user()->id)
                    ->select('orders.id')
                    ->distinct();

        if ($request->payment_status != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }

        $orders = $orders->paginate(15);

       /* foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }*/

        return view('frontend.user.seller.orders', compact('orders','payment_status','delivery_status', 'sort_search'));
    }

    // All Orders
    public function all_orders(Request $request)
    {
         

         $date = $request->date;
         $sort_search = null;
         $orders = Order::orderBy('code', 'desc');
         if ($request->has('search')){
             $sort_search = $request->search;
             $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
         }
         if ($date != null) {
             $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
         }
         $orders = $orders->paginate(15);
         return view('backend.sales.all_orders.index', compact('orders', 'sort_search', 'date'));
    }

    public function all_orders_show($id)
    {
         $order = Order::findOrFail(decrypt($id));
         return view('backend.sales.all_orders.show', compact('order'));
    }

    // Inhouse Orders
    public function admin_orders(Request $request)
    {
        

        $date = $request->date;
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        /*$admin_user_id = User::where('user_type', 'admin')->first()->id;
		
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
				
                    ->where('order_details.seller_id', $admin_user_id)
					->where('order_details.delete_status',0)
                    ->select('orders.id')
                    ->distinct();*/
					$admin_user_id = User::where('user_type', 'admin')->first()->id;
		 $orders = DB::table('orders')
                    ->orderBy('orders.created_at', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('orders.payment_status', '!=', '')
					->where('order_details.delete_status',0)
					->select('orders.id')
                    ->distinct('orders.code')
                    ->get(15);

        if ($request->payment_type != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }
        if ($date != null) {
            $orders = $orders->where('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }

        //$orders = $orders->paginate(15);
        return view('backend.sales.inhouse_orders.index', compact('orders','payment_status','delivery_status', 'sort_search', 'admin_user_id', 'date'));
    }
	
	public function inactive_order_index(Request $request)
    {
       // dd($request->all());

        $date = $request->date;
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
		//dd($admin_user_id);
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')				
                    ->where('order_details.seller_id', $admin_user_id)
					->where('order_details.delete_status',1)
                    ->select('orders.id')
                    ->distinct();

        if ($request->payment_type != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }
        if ($date != null) {
            $orders = $orders->where('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }

        $orders = $orders->paginate(15);
        return view('backend.sales.inactive_order_index.index', compact('orders','payment_status','delivery_status', 'sort_search', 'admin_user_id', 'date'));
    }
	public function inactive_order_show($id)
    {
		$orders = OrderDetail::where('order_id',decrypt($id))->select('brand','order_id')->distinct()->get()->groupBy('brand');
		$order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('backend.sales.inactive_order_index.show', compact('order','orders'));
    }
	
	 public function inactive_order_history($id)
    {
		$orders = Order::where('payment_status','!=',null)->where('user_id',decrypt($id))->get();
		$order = OrderDetail::where('user_id',decrypt($id))->get();
		//dd(decrypt($id));
		//$orders = OrderDetail::where('user_id',decrypt($id))->select('brand','order_id')->distinct()->get()->groupBy('created_at');
		$order = OrderDetail::where('user_id',decrypt($id))->get();
		
        return view('backend.customer.customers.inactive_order_history_show', compact('order','orders'));
    }
    public function show($id)
    {
		$orders = OrderDetail::where('order_id',decrypt($id))->select('brand','order_id')->distinct()->get()->groupBy('brand');
		//dd($orders);
		
		
// 		$order1 = OrderDetail::where('order_id',decrypt($id))->groupBy('brand')->get()->first()->seller_commission;
        
        $order1 = OrderDetail::where('order_id', decrypt($id))
                ->get()
                ->groupBy('brand');
                
                
        $order_commissions = [];

            
            foreach ($order1 as $brand => $details) {
                foreach ($details as $orderCommissionDetail) {
                    $order_commissions[$brand] = $orderCommissionDetail->seller_commission;
                    break;

                }
            }

		$order_brand = OrderDetail::where('order_id',decrypt($id))->select('brand','order_id')->distinct()->groupBy('brand')->get();
		$order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('backend.sales.inhouse_orders.show', compact('order','orders','order_commissions','order_brand'));
    }

    // Seller Orders
    public function seller_orders(Request $request)
    {
        

        $date = $request->date;
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $admin_user_id = User::where('user_type', 'admin')->first()->id;
        $orders = DB::table('orders')
                    ->orderBy('code', 'desc')
                    ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('order_details.seller_id', '!=' ,$admin_user_id)
                    ->select('orders.id')
                    ->distinct();

        if ($request->payment_type != null){
            $orders = $orders->where('order_details.payment_status', $request->payment_type);
            $payment_status = $request->payment_type;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('order_details.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
        }
        if ($date != null) {
            $orders = $orders->where('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }

        $orders = $orders->paginate(15);
        return view('backend.sales.seller_orders.index', compact('orders','payment_status','delivery_status', 'sort_search', 'admin_user_id', 'date'));
    }

    public function seller_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('backend.sales.seller_orders.show', compact('order'));
    }


    // Pickup point orders
    public function pickup_point_order_index(Request $request)
    {
        $date = $request->date;
        $sort_search = null;

        if (Auth::user()->user_type == 'staff' && Auth::user()->staff->pick_up_point != null) {
            //$orders = Order::where('pickup_point_id', Auth::user()->staff->pick_up_point->id)->get();
            $orders = DB::table('orders')
                        ->orderBy('code', 'desc')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->where('order_details.pickup_point_id', Auth::user()->staff->pick_up_point->id)
                        ->select('orders.id')
                        ->distinct();

            if ($request->has('search')){
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
            }
            if ($date != null) {
                $orders = $orders->where('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            $orders = $orders->paginate(15);

            return view('backend.sales.pickup_point_orders.index', compact('orders'));
        }
        else{
            //$orders = Order::where('shipping_type', 'Pick-up Point')->get();
            $orders = DB::table('orders')
                        ->orderBy('code', 'desc')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->where('order_details.shipping_type', 'pickup_point')
                        ->select('orders.id')
                        ->distinct();

            if ($request->has('search')){
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%'.$sort_search.'%');
            }
            if ($date != null) {
                $orders = $orders->where('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            }

            $orders = $orders->paginate(15);

            return view('backend.sales.pickup_point_orders.index', compact('orders', 'sort_search', 'date'));
        }
    }

    public function pickup_point_order_sales_show($id)
    {
        if (Auth::user()->user_type == 'staff') {
            $order = Order::findOrFail(decrypt($id));
            return view('backend.sales.pickup_point_orders.show', compact('order'));
        }
        else{
            $order = Order::findOrFail(decrypt($id));
            return view('backend.sales.pickup_point_orders.show', compact('order'));
        }
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$order_id1 = Order::whereDate('created_at', Carbon::today())->count();
		//dd($order_id1 );
		if($order_id1 !== 0)
		{
			$order_id2 = Order::whereDate('created_at', Carbon::today())->count();
			$order_ids = $order_id2+1;
			
		}
		else
		{
			$order_ids = 1;
		}
		$order = new Order;
		if(Auth::check()){
            $order->user_id = Auth::user()->id;
        }
        else
		{
            $order->guest_id = mt_rand(100000, 999999);
        }
		$carts = Cart::where('user_id', Auth::user()->id)->where('status',1)->get();
		
		$shipping_info = Address::where('id', $carts[0]['address_id'])->first();
		$shipping_info->name = Auth::user()->name;
        $shipping_info->email = Auth::user()->email;
		//$order->shipping_address = json_encode($request->session()->get('shipping_info'));
		$order->shipping_address = json_encode($shipping_info);
		//dd($order->shipping_address);
        $order->payment_type = $request->payment_option;
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        //$order->code = date('Ymd-His').rand(10,99);
		$order->code = date('d-m-Y').'-'.$order_ids;
        $order->date = strtotime('now');
		$admin_user_id = User::where('user_type', 'admin')->first()->id;
        if($order->save()){
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;

            //calculate shipping is to get shipping costs of different types
            $admin_products = array();
            $seller_products = array();

            //Order Details Storing
            foreach ($carts as $key => $cartItem){
                $product = Product::find($cartItem['product_id']);

                if($product->added_by == 'admin'){
                    array_push($admin_products, $cartItem['product_id']);
                }
                else{
                    $product_ids = array();
                    if(array_key_exists($product->user_id, $seller_products)){
                        $product_ids = $seller_products[$product->user_id];
                    }
                    array_push($product_ids, $cartItem['product_id']);
                    $seller_products[$product->user_id] = $product_ids;
                }

                $subtotal += $cartItem['price']*$cartItem['quantity'];
                $tax += $cartItem['tax']*$cartItem['quantity'];

                $product_variation = $cartItem['variation'];

                if($product_variation != null){
                    $product_stock = $product->stocks->where('variant', $product_variation)->first();
                    if($product->digital != 1 &&  $cartItem['quantity'] > $product_stock->qty){
                        flash(translate('The requested quantity is not available for ').$product->getTranslation('name'))->warning();
                        $order->delete();
                        return redirect()->route('cart')->send();
                    }
                    
                }
                else {
                    if ($product->digital != 1 && $cartItem['quantity'] > $product->current_stock) {
                        flash(translate('The requested quantity is not available for ').$product->getTranslation('name'))->warning();
                        $order->delete();
                        return redirect()->route('cart')->send();
                    }
                    else {
                        $product->current_stock -= $cartItem['quantity'];
                        $product->save();
                    }
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id  =$order->id;
                $order_detail->seller_id = $product->user_id;
				$order_detail->mrp  =$cartItem['mrp'];
				$order_detail->existing_discount = $cartItem['products_discount_price'];
                $order_detail->product_id = $product->id;
				$order_detail->user_id = Auth::user()->id;
                $order_detail->variation = $product_variation;
				$order_detail->total_mrp = $cartItem['cart_total_mrp'];
				$order_detail->seller_commission = $cartItem['seller_commission'];				
				$order_detail->pre_commision_rate = $cartItem['pre_commission'];
				$order_detail->post_commision_rate = $cartItem['post_commission'];
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
               // $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
	            $order_detail->brand = $cartItem['brand'];
                //Dividing Shipping Costs
                $order_detail->shipping_cost = $cartItem['shipping_cost'] * $cartItem['quantity'];
                $shipping += $order_detail->shipping_cost;

                /*if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order_detail->pickup_point_id = $cartItem['pickup_point'];
                }*/
                //End of storing shipping cost
                
                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale++;
                $product->save();
            }
          //  dd($shipping);
            $order->grand_total = $subtotal + $tax + $shipping;
         
         

if ($carts->first()->coupon_code != '') {
                $order->grand_total -= $carts->sum('discount');
                if(Session::has('club_point')){
                    $order->club_point = Session::get('club_point');
                }
                $order->coupon_discount = $carts->sum('discount');

//                $clubpointController = new ClubPointController;
//                $clubpointController->deductClubPoints($order->user_id, Session::get('club_point'));

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $carts->first()->coupon_code)->first()->id;
                $coupon_usage->save();
            }
			
            /*if(Session::has('coupon_discount')){
                $order->grand_total -= Session::get('coupon_discount');
                $order->coupon_discount = Session::get('coupon_discount');

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Session::get('coupon_id');
                $coupon_usage->save();
            }*/

            $order->save();

           /* $array['view'] = 'emails.invoice';
            $array['subject'] = translate('Your order has been placed').' - '.$order->code;
            $array['from'] = env('MAIL_USERNAME');
            $array['order'] = $order;

            foreach($seller_products as $key => $seller_product){
                try {
                    Mail::to(\App\User::find($key)->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }*/

            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_order')->first()->value){
                try {
                    $otpController = new OTPVerificationController;
                    $otpController->send_order_code($order);
                } catch (\Exception $e) {

                }
            }

            //sends email to customer with the invoice pdf attached
            if(env('MAIL_USERNAME') != null){
                try {
                    Mail::to($request->session()->get('shipping_info')['email'])->queue(new InvoiceEmailManager($array));
                    Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new InvoiceEmailManager($array));
                } catch (\Exception $e) {

                }
            }

            $request->session()->put('order_id', $order->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if($order != null){
            foreach($order->orderDetails as $key => $orderDetail){
				//dd($orderDetail);
                try {
                    if ($orderDetail->variantion != null) {
                        $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variantion)->first();
                        if($product_stock != null){
                            $product_stock->qty += $orderDetail->quantity;
							//$orderDetail->delete_status = 1;
                            $product_stock->save();
                        }
                    }
                    else {
                        $product = $orderDetail->product;
                        $product->current_stock += $orderDetail->quantity;
						//$orderDetail->delete_status = 1;
                        $product->save();
                    }
                } catch (\Exception $e) {

                }
			$orderDetail->delete_status = 1;
                $orderDetail->save();
            }
            $order->save();
            flash(translate('Order has been deleted successfully'))->success();
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }
    public function temdestroy($id)
    {
        $order = Order::findOrFail($id);
        if($order != null){
            foreach($order->orderDetails as $key => $orderDetail){
                try {
                    if ($orderDetail->variantion != null) {
                        $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variantion)->first();
                        if($product_stock != null){
                            $product_stock->qty += $orderDetail->quantity;
                            $product_stock->save();
                        }
                    }
                    else {
                        $product = $orderDetail->product;
                        $product->current_stock += $orderDetail->quantity;
                        $product->save();
                    }
                } catch (\Exception $e) {

                }

                $orderDetail->delete();
            }
            $order->delete();
            flash(translate('Order has been deleted successfully'))->success();
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('frontend.user.seller.order_details_seller', compact('order'));
    }
	public function seller_update_order_status(Request $request)
    {
		//dd($request->all());
        $order = Order::findOrFail($request->order_id);
		$orders = $order->orderDetails->where('brand',$request->brand_id);
		$order1 = Brand::where('id',$request->brand_id)->first()->name;
        $order->delivery_viewed = '0';
		//print_r($order->orderDetails);exit();
        $order->save();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('brand',$request->brand_id) as $key => $orderDetail){
                $orderDetail->seller_order_status = $request->status;						
						$orderDetail->save();	
            }
						if($request->status === 'Canceled')
						{
							
				// 			$pdf = PDF::setOptions([
				// 				'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
				// 				'logOutputFile' => storage_path('logs/log.htm'),
				// 				'tempDir' => storage_path('logs/')
				// 			])->loadView('backend.invoices.customer_invoice', compact('order'));
				// 			$output = $pdf->output();
				// 			file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);
				// 			$array['view'] = 'emails.invoice';
				// 			$array['subject'] = ''.$order->code;
				// 			$array['from'] = env('MAIL_USERNAME');
				// 			$array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
				// 			$array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
				// 			$array['file_name'] = 'Order#'.$order->code.'.pdf';
				// 			$shipping_address = json_decode($order->shipping_address);
				// 			$shipping_address1 = User::where('brand_id', $request->brand_id)->first()->seller_email;
				// 			if($shipping_address1 !== null)
				// 			{
				// 				$myArray = explode(',', $shipping_address1);
				// 			    Mail::to($myArray)->queue(new SellerCancelEmailStatus($array,$order,$orders,$order1));
				// 			}
				// 			Mail::to($shipping_address->email)->queue(new CancelEmailStatus($array,$order,$orders));
				// 			Mail::to(User::where('brand_id', $request->brand_id)->first()->email)->queue(new SellerCancelEmailStatus($array,$order,$orders,$order1));  
				// 			unlink($array['file']);
				
				            $array['view'] = 'emails.invoice';
                            $array['subject'] = 'Order #' . $order->code;
                            $array['from'] = env('MAIL_USERNAME');
                            $array['content'] = 'Hi. your order has been cancelled.';
                            
                            $shipping_address = json_decode($order->shipping_address);
                            $shipping_address1 = User::where('brand_id', $request->brand_id)->first()->seller_email;
                            
                            if ($shipping_address1 !== null) {
                                $myArray = explode(',', $shipping_address1);
                                Mail::to($myArray)->queue(new SellerCancelEmailStatus($array, $order, $orders, $order1));
                            }
                            
                            // Send email to the customer
                            Mail::to($shipping_address->email)->queue(new CancelEmailStatus($array, $order, $orders));
                            
                            // Send email to the seller
                            Mail::to(User::where('brand_id', $request->brand_id)->first()->email)->queue(new SellerCancelEmailStatus($array, $order, $orders, $order1));

				
				
						}
						
						
						
        }
        else{
            foreach($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->activated){
            if($order->user != null && $order->user->phone != null){
                $otpController = new OTPVerificationController;
                $otpController->send_delivery_status($order);
            }
        }

        return 1;
    }
public function update_delivery_status(Request $request)
    {
		//dd($request->all());
        $order = Order::findOrFail($request->order_id);
		$orders = $order->orderDetails->where('brand',$request->brand_id);
		$order1 = Brand::where('id',$request->brand_id)->first()->name;
        $order->delivery_viewed = '0';
		//print_r($order->orderDetails);exit();
        $order->save();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('brand',$request->brand_id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;						
						$orderDetail->save();	
            }
						if($request->status === 'shipped')
						{
							
							$pdf = PDF::setOptions([
								'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
								'logOutputFile' => storage_path('logs/log.htm'),
								'tempDir' => storage_path('logs/')
							])->loadView('backend.invoices.customer_invoice', compact('order'));
							$output = $pdf->output();
							file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);
							$array['view'] = 'emails.invoice';
							$array['subject'] = ''.$order->code;
							$array['from'] = env('MAIL_USERNAME');
							$array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
							$array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
							$array['file_name'] = 'Order#'.$order->code.'.pdf';
							$shipping_address = json_decode($order->shipping_address);
							$shipping_address1 = User::where('brand_id', $request->brand_id)->first()->seller_email;
							if($shipping_address1 !== null)
							{
								$myArray = explode(',', $shipping_address1);
							    Mail::to($myArray)->queue(new SellerUpdateDeliveryEmailStatus($array,$order,$orders,$order1));
							}
							Mail::to($shipping_address->email)->queue(new UpdateDeliveryEmailStatus($array,$order,$orders));
							Mail::to(User::where('brand_id', $request->brand_id)->first()->email)->queue(new SellerUpdateDeliveryEmailStatus($array,$order,$orders,$order1));  
							unlink($array['file']);
						}
						if($request->status === 'placed')
						{
							//dd($request->all());
							$array['subject'] = ''.$order->code;
							$array['from'] = env('MAIL_USERNAME');
							$array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
							$array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
							$array['file_name'] = 'Order#'.$order->code.'.pdf';
							//$shipping_address = json_decode($order->shipping_address);
							//Mail::to($shipping_address->email)->queue(new InvoiceEmailManager($array,$order));

							$shipping_address = json_decode($order->shipping_address);
							$shipping_address1 = User::where('brand_id', $request->brand_id)->first()->seller_email;
							if($shipping_address1 !== null)
							{
								$myArray = explode(',', $shipping_address1);							
							    Mail::to($myArray)->queue(new SellerEmailManager($array,$order,$orders,$order1));
							}
							
							Mail::to($shipping_address->email)->queue(new InvoiceEmailManager($array,$order));
							Mail::to(User::where('brand_id', $request->brand_id)->first()->email)->queue(new SellerEmailManager($array,$order,$orders,$order1));  
							
							
							//Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new PlaceEmailStatus($array,$order));  
							//unlink($array['file']);
						}
						elseif($request->status === 'delivered')
						{
							$orders = $order->orderDetails->where('brand',$request->brand_id);
		
							$pdf = PDF::setOptions([
								'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
								'logOutputFile' => storage_path('logs/log.htm'),
								'tempDir' => storage_path('logs/')
							])->loadView('backend.invoices.customer_invoice', compact('order'));
							$output = $pdf->output();
							file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);
							$array['view'] = 'emails.invoice';
							$array['subject'] = ''.$order->code;
							$array['from'] = env('MAIL_USERNAME');
							$array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
							$array['file_name'] = 'Order#'.$order->code.'.pdf';
							$array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';							
							$shipping_address = json_decode($order->shipping_address);
							$shipping_address1 = User::where('brand_id', $request->brand_id)->first()->seller_email;
							if($shipping_address1 !== null)
							{
								$myArray = explode(',', $shipping_address1);							
							    Mail::to($myArray)->queue(new SellerDeliveryEmailStatus($array,$order,$orders,$order1));
							}
							
							Mail::to($shipping_address->email)->queue(new DeliveryEmailStatus($array,$order,$orders));
							Mail::to(User::where('brand_id', $request->brand_id)->first()->email)->queue(new SellerDeliveryEmailStatus($array,$order,$orders,$order1));  
							
						}
        }
        else{
            foreach($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->activated){
            if($order->user != null && $order->user->phone != null){
                $otpController = new OTPVerificationController;
                $otpController->send_delivery_status($order);
            }
        }

        return 1;
    }
	public function seller_update_payment_status(Request $request)
    {
		//dd($request->all());
		$order1 = Brand::where('id',$request->brand_id)->first()->name;
        $order = Order::findOrFail($request->order_id);
		$orders = $order->orderDetails->where('brand',$request->brand_id)->where('order_id',$request->order_id);
        $order->delivery_viewed = '0';
		//print_r($order->orderDetails);exit();
        $order->save();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('brand',$request->brand_id) as $key => $orderDetail){
				
                $orderDetail->seller_payment_status = $request->status;						
						$orderDetail->save();	
            }
			if($request->status === 'paid')
						{
							//dd($request->status);
							
							$pdf = PDF::setOptions([
								'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
								'logOutputFile' => storage_path('logs/log.htm'),
								'tempDir' => storage_path('logs/')
							])->loadView('backend.invoices.customer_invoice', compact('order'));
							$output = $pdf->output();
							file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);
							$array['view'] = 'emails.invoice';
							$array['subject'] = ''.$order->code;
							$array['from'] = env('MAIL_USERNAME');
							$array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
							$array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
							$array['file_name'] = 'Order#'.$order->code.'.pdf';
							$shipping_address = json_decode($order->shipping_address);
							$shipping_address1 = User::where('brand_id', $request->brand_id)->first()->seller_email;
							if($shipping_address1 !== null)
							{
								$myArray = explode(',', $shipping_address1);
							    //Mail::to($myArray)->queue(new SellerCancelEmailStatus($array,$order,$orders,$order1));
							}
							//Mail::to($shipping_address->email)->queue(new CancelEmailStatus($array,$order,$orders));
							Mail::to(User::where('brand_id', $request->brand_id)->first()->email)->queue(new SellerPaidEmailStatus($array,$order,$orders,$order1));  
							unlink($array['file']);
						}
					
        }
        else{
            foreach($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->activated){
            if($order->user != null && $order->user->phone != null){
                $otpController = new OTPVerificationController;
                $otpController->send_delivery_status($order);
            }
        }

        return 1;
    }
	
    /*public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->save();
        if(Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }
        else{
            foreach($order->orderDetails as $key => $orderDetail){
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_delivery_status')->first()->value){
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_delivery_status($order);
            } catch (\Exception $e) {
            }
        }

        return 1;
    }*/
public function update_payment_status(Request $request)
    {
		//dd($request->all());
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller'){
            foreach($order->orderDetails->where('brand',$request->brand_id)->where('order_id',$request->order_id) as $key => $orderDetail){
                $orderDetail->payment_status = $request->status;
				/*if($orderDetail->payment_status === 'paid')
				{
					$pdf = PDF::setOptions([
									'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
									'logOutputFile' => storage_path('logs/log.htm'),
									'tempDir' => storage_path('logs/')
								])->loadView('invoices.customer_invoice', compact('order'));
					$output = $pdf->output();
					file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);

					$array['view'] = 'emails.invoice';
					$array['subject'] = 'Order Placed - '.$order->code;
					$array['from'] = env('MAIL_USERNAME');
					$array['content'] = 'Hi. A new order has been placed. Please check the attached invoice.';
					$array['file'] = 'public/invoices/Order#'.$order->code.'.pdf';
					$array['file_name'] = 'Order#'.$order->code.'.pdf';

					$shipping_address = json_decode($order->shipping_address);
					Mail::to($shipping_address->email)->queue(new AdminUpdateEmailManager($array, $order));
					Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new AdminUpdateEmailManager($array, $order));
				}*/					
                $orderDetail->save();
            }
        }
        else{
            foreach($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail){
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
		
        foreach($order->orderDetails as $key => $orderDetail){
            if($orderDetail->payment_status != 'paid'){
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_paid_status')->first()->activated){
            if($order->user != null && $order->user->phone != null){
                $otpController = new OTPVerificationController;
                $otpController->send_payment_status($order);
            }
        }
        return 1;
    }
    public function update_payment_statusss(Request $request)
    {
		//dd($request->all());
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller')
		{
            foreach($order->orderDetails->where('seller_id', Auth::user()->id)->where('brand',$request->brand_id) as $key => $orderDetail){
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }
        else{
            foreach($order->orderDetails->where('brand',$request->brand_id) as $key => $orderDetail){
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach($order->orderDetails->where('brand',$request->brand_id) as $key => $orderDetail){
            if($orderDetail->payment_status != 'paid'){
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();


        if($order->payment_status == 'paid' && $order->commission_calculated == 0){
            if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() == null || !\App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
                if ($order->payment_type == 'cash_on_delivery') {
                    if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if($orderDetail->product->user->user_type == 'seller'){
                                $seller = $orderDetail->product->user->seller;
                                $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price*$commission_percentage)/100;
                                $seller->save();
                            }
                        }
                    }
                    else{
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if($orderDetail->product->user->user_type == 'seller'){
                                $commission_percentage = $orderDetail->product->category->commision_rate;
                                $seller = $orderDetail->product->user->seller;
                                $seller->admin_to_pay = $seller->admin_to_pay - ($orderDetail->price*$commission_percentage)/100;
                                $seller->save();
                            }
                        }
                    }
                }
                elseif($order->manual_payment) {
                    if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                        $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if($orderDetail->product->user->user_type == 'seller'){
                                $seller = $orderDetail->product->user->seller;
                                $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100 + $orderDetail->tax + $orderDetail->shipping_cost;
                                $seller->save();
                            }
                        }
                    }
                    else{
                        foreach ($order->orderDetails as $key => $orderDetail) {
                            $orderDetail->payment_status = 'paid';
                            $orderDetail->save();
                            if($orderDetail->product->user->user_type == 'seller'){
                                $commission_percentage = $orderDetail->product->category->commision_rate;
                                $seller = $orderDetail->product->user->seller;
                                $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100 + $orderDetail->tax + $orderDetail->shipping_cost;
                                $seller->save();
                            }
                        }
                    }
                }
            }

            if (\App\Addon::where('unique_identifier', 'affiliate_system')->first() != null && \App\Addon::where('unique_identifier', 'affiliate_system')->first()->activated) {
                $affiliateController = new AffiliateController;
                $affiliateController->processAffiliatePoints($order);
            }

            if (\App\Addon::where('unique_identifier', 'club_point')->first() != null && \App\Addon::where('unique_identifier', 'club_point')->first()->activated) {
                if ($order->user != null) {
                    $clubpointController = new ClubPointController;
                    $clubpointController->processClubPoints($order);
                }
            }

            $order->commission_calculated = 1;
            $order->save();
        }

        if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated && \App\OtpConfiguration::where('type', 'otp_for_paid_status')->first()->value){
            try {
                $otpController = new OTPVerificationController;
                $otpController->send_payment_status($order);
            } catch (\Exception $e) {
            }
        }
        return 1;
    }
}
