@extends('backend.layouts.app')

@section('content')

    <div class="card">
      <div class="card-header">
        <h1 class="h2">{{ translate('Order Details') }}</h1>
      </div>
	   <div class="card-header">
			<div class="col text-center text-md-left">
              <address>
                  <strong class="text-main">{{ json_decode($order->shipping_address)->name }}</strong><br>
                   {{ json_decode($order->shipping_address)->email }}<br>
                   {{ json_decode($order->shipping_address)->phone }}<br>
                   {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->postal_code }}<br>
                   {{ json_decode($order->shipping_address)->country }}
                </address>
                  @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                      <br>
                      <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                      Name: {{ json_decode($order->manual_payment_data)->name }}, Amount: {{ single_price(json_decode($order->manual_payment_data)->amount) }}, TRX ID: {{ json_decode($order->manual_payment_data)->trx_id }}
                      <br>
                      <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank"><img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt="" height="100"></a>
                  @endif
			</div>
			<div class="col-md-4 ml-auto">
              <table>
                  <tbody>
                    <tr>
                        <td class="text-main text-bold">{{translate('Order #')}}</td>
                        <td class="text-right text-info text-bold">{{ $order->code }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Status')}}</td>
                            @php
                              $status = $order->orderDetails->first()->delivery_status;
                            @endphp
                        <td class="text-right">
                            @if($status == 'delivered')
                                <span class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                            @else
                                <span class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Order Date')}}</td>
                        <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Total amount')}}</td>
                        <td class="text-right">
                            {{ single_price($order->grand_total) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-main text-bold">{{translate('Payment method')}}</td>
                        <td class="text-right">{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                    </tr>
                  </tbody>
              </table>
			</div>
		</div> 
		@foreach ($orders as $orderDetail1)
		@php 
							$test = \App\OrderDetail::where('brand',$orderDetail1[0]['brand'])->where('order_id',$orderDetail1[0]['order_id'])->first();
						@endphp
      
       

    	<div class="card-body">
    		
    		<div class="row">
			<div class="col-md-12">
			 @foreach (\app\models\brand::where('id',$orderDetail1[0]['brand'])->get() as $brand)
						<span><h4>{{ $brand['name'] }}</h4> </span>
						@endforeach
			 </div>
    			<div class="col-lg-12 table-responsive">
    				<table class="table table-bordered invoice-summary">
        				<thead>
            				<tr class="bg-trans-dark border">
                                <th class="min-col">#</th>
                                <th width="10%">{{translate('Photo')}}</th>
              					<th class="text-uppercase">{{translate('Description')}}</th>                                
              					<th class="min-col text-center text-uppercase">{{translate('Qty')}}</th>
              					<th class="min-col text-center text-uppercase">{{translate('Price')}}</th>
              					<th class="min-col text-right text-uppercase">{{translate('Total')}}</th>
            				</tr>
        				</thead>
        				<tbody>
                            @php
                            $admin_user_id = \App\User::where('user_type', 'admin')->first()->id;
                            @endphp
                            @foreach ($order->orderDetails as $key => $orderDetail)
							@if($orderDetail->brand == $orderDetail1[0]['brand'])
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                          <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"><img height="50px" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @else
                                          <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                          <strong><a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-muted">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                          <small>{{ $orderDetail->variation }}</small>
                                        @else
                                          <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                  
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price/$orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                                </tr>
                           @endif
						
			@endforeach
        				</tbody>
    				</table>
    			</div>
    		</div>
			</div>
			@endforeach
			<div class="card-body">
    		<div class="clearfix float-right">
    			<table class="table">
          			<tbody>
            			<tr>
            				<td><strong class="text-muted">{{translate('Sub Total')}} :</strong></td>
            				<td>
            					{{ single_price($order->orderDetails->sum('price')) }}
            				</td>
            			</tr>
            			<tr>
            				<td><strong class="text-muted">{{translate('Tax')}} :</strong></td>
            				<td>{{ single_price($order->orderDetails->sum('tax')) }}</td>
            			</tr>
                        <tr>
            				<td><strong class="text-muted"> {{translate('Shipping')}} :</strong></td>
            				<td>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
            			</tr>
                        <tr>
            				<td>
            					<strong class="text-muted">{{translate('Coupon')}} :</strong>
            				</td>
            				<td>
            					{{ single_price($order->coupon_discount) }}
            				</td>
            			</tr>
            			<tr>
            				<td><strong class="text-muted">{{translate('TOTAL')}} :</strong></td>
            				<td class="text-muted h5">
            					{{ single_price($order->grand_total) }}
            				</td>
            			</tr>
          			</tbody>
    			</table>
                <div class="text-right no-print">
                    <a href="{{ route('customer.invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
    		</div>
    	</div>
    </div>
@endsection

@section('script')
   <script>
function valuesOfAll(e)
{
	//var getthevalue = $(e).attr('id'); 
	var order_id = {{ $order->id }};
	var brand_id = $(e).data('brands');
	var status = $(e).val();
	  $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',brand_id:brand_id,order_id:order_id,status:status}, function(data){
                 AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
            });
}
function seller_update_payment_status(e)
{
	//var getthevalue = $(e).attr('id'); 
	var order_id = {{ $order->id }};
	var brand_id = $(e).data('brands');
	var status = $(e).val();
	  $.post('{{ route('orders.seller_update_payment_status') }}', {_token:'{{ @csrf_token() }}',brand_id:brand_id,order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Seller Payment status has been updated') }}');
            });
}
function customer_update_payment_status(e)
{
	//var getthevalue = $(e).attr('id'); 
	var order_id = {{ $order->id }};
	var brand_id = $(e).data('brands');
	var status = $(e).val();
	//alert(status);
	  $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',brand_id:brand_id,order_id:order_id,status:status}, function(data){
                AIZ.plugins.notify('success', '{{ translate('Customer Payment status has been updated') }}');
				
            });
}
</script>
@endsection
