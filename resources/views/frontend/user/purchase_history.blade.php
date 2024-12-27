@extends('frontend.layouts.app')

@section('content')

<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-start">
            @include('frontend.inc.user_side_nav')
            <div class="aiz-user-panel">
			<div class="text-center mb-3">
	<div class="container">
		<div class="row">
			<div class="col-md-12"> 
							<h2 class="font-weight-normal">Order History</h2>
							<ul class="breadcrumb bg-transparent p-0 text-center inline-flex mb-0">
								<li class="breadcrumb-item opacity-50">
									<a class="text-reset" href="{{ route('home') }}">Home</a>
								</li>
								<li class="breadcrumb-item opacity-50">
									<a class="text-reset" href="{{ route('purchase_history.index') }}">Order History</a>
								</li>
							</ul>		
			</div>
		</div> 
	</div>
</div>


                <div class="card datatables">
				
					
                   <h5 class="mb-0 h6"></h5>
					

					 @if (count($orders) > 0)
							@foreach ($orders as $key1 => $order)
						
						
								  <div class="card-body border ">
								  <div class="row">
								<div class="col-md-12"><span class="p-2 bg-green color-white">{{ ($key1+1) }}</span></div>
								<div class="col-md-9 col-xs-9 p-2 mt-2">
							<b>{{__('Order Id')}}: <a href="#{{ $order->code }}" onclick="show_purchase_history_details({{ $order->id }})">
							{{ $order->code }}</a></b>
							<a href="{{ route('seller.invoice.download', $order->id) }}" class="badge badge-inline badge-success d-none"> Download Invoice</a> 
							</div>	
								<div class="col-md-3 col-xs-3 p-2 mt-2">
							<b>{{__('Order Date')}} : {{ date('d-m-Y', $order->date) }}</b><br>
						
							</div>	
							<div class="col-md-9 col-xs-9 p-2 mt-2">
							<b>Total Amount:  {{ single_price($order->grand_total) }}</b>
							</div>
							<div class="col-md-3 col-xs-3 p-2 mt-2">
							<b>{{__('Payment Status')}} : @if ($order->payment_status == 'paid')
                                                        <span class="badge badge-inline badge-success">{{('Paid')}}</span>
                                                    @else
                                                        <span class="badge badge-inline badge-danger">{{('Unpaid')}}</span>
                                                    @endif</b>
						
							</div>	
							<div class="col-sm-12 p-0 mt-2" >
								<button type="button" class="btn btn-outline-secondary" data-toggle="collapse" data-target="#collapse{{$order->id}}" aria-expanded="true" aria-controls="collapse{{$order->id}}">View Details</button>               
							</div>
								</div>  
								@foreach ($order->orderDetails as $key => $orderDetail)
							
							
								<div class="row mt-1 p-0 collapse {{ $orderDetail->order_id }}" id="collapse{{ $orderDetail->order_id }}">
								<div class="col-md-2 col-sm-3 col-4 col-xs-3 p-0">
								<img src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}" alt="{{ uploaded_alt($orderDetail->product->thumbnail_img) }}" class="p-3" height="200">	
								</div>
								<div class="col-md-10 col-sm-9 col-xs-9 col-8">
								<div class="row">
									<div class="col-md-8 col-xs-6 col-12">
									<span style="line-height:3">  @if ($orderDetail->product != null)
                                            {{ $orderDetail->product->name }} {{ $orderDetail->variation }}
                                        @else
                                            <strong>{{ __('Product Unavailable') }}</strong>
                                        @endif <Br>
										Qty : {{ $orderDetail->quantity }} | Amount : {{ single_price($orderDetail->price) }} </span>
									</div>	
									<div class="col-md-4  mt-2 col-xs-6 col-12">
									<span><b>Brand :</b> {{ $orderDetail->product->brand->name }}</span><br><br>
									<span><b>Delivery Status :</b> <span class="badge badge-inline badge-success">{{ $orderDetail->delivery_status }}</span></span><br><br>
									<span><b>Order Status :</b> <span class="badge badge-inline badge-success">{{ $orderDetail->seller_order_status }}</span></span>
									</div>										
								</div>								
								</div>
								</div>
							@endforeach
								</div>
							
								
							@endforeach
					@endif
					
                </div>
				<div class="aiz-pagination float-right">
                                	{{ $orders->links() }}
                          	</div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('modal')
    @include('modals.delete_modal')

    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $('#order_details').on('hidden.bs.modal', function () {
            location.reload();
        })
    </script>

@endsection
