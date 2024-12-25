@extends('backend.layouts.app')

@section('content')
@php
    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
@endphp

<div class="card">
  
      <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
          <h5 class="mb-md-0 h6">{{ translate('Inhouse Orders') }}</h5>
        </div>
        
         

         
         
          
      </div>
   
    <div class="card-body">
        <table class="table datatables mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{translate('Order Code')}}</th>
                    <th data-breakpoints="md">{{translate('Num. of Products')}}</th>
                    <th data-breakpoints="md">{{translate('Customer')}}</th>
                    <th data-breakpoints="md">{{translate('Amount')}}</th>
                    <th data-breakpoints="md">{{translate('Delivery Status')}}</th>
                    <th data-breakpoints="md">{{translate('Payment Method')}}</th>
                    <th data-breakpoints="md">{{translate('Payment Status')}}</th>
                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                        <th>{{translate('Refund')}}</th>
                    @endif
                    <th class="text-right" width="15%">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key => $order_id)
                    @php
                        $order = \App\Order::find($order_id->id);
                    @endphp
                    @if($order != null)
                        <tr>
                            <td>
                                {{ ($key+1) }}
                            </td>
                            <td>
                                {{ $order->code }}@if($order->viewed == 0) <span class="badge badge-inline badge-info">{{translate('New')}}</span>@endif
                            </td>
                            <td>
                                {{ count($order->orderDetails) }}
                            </td>
                            <td>
                                @if ($order->user != null)
                                    {{ $order->user->name }} {{ $order->user->lastname }}
                                @else
                                    Guest ({{ $order->guest_id }})
                                @endif
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price') + $order->orderDetails->where('seller_id', $admin_user_id)->sum('tax')) }}
                            </td>
                            <td>
                                @php
                                    $status = $order->orderDetails->first()->delivery_status;
                                @endphp
                                {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                            </td>
                            <td>
                                {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                            </td>
                            <td>
                                @if ($order->payment_status == 'paid')
                                  <span class="badge badge-inline badge-success">{{translate('Paid')}}</span>
                                @else
                                  <span class="badge badge-inline badge-danger">{{translate('Unpaid')}}</span>
                                @endif
                            </td>
                            @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                <td>
                                    @if (count($order->refund_requests) > 0)
                                        {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                    @else
                                        {{ translate('No Refund') }}
                                    @endif
                                </td>
                            @endif

                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('inhouse_orders.show', encrypt($order->id))}}" title="{{ translate('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('customer.invoice.download', $order->id) }}" title="{{ translate('Download Invoice') }}">
                                    <i class="las la-download"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('orders.destroy', $order->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a> 
                                <a href="#" class="btn btn-soft-secondary btn-icon btn-circle btn-sm temporary-delete" data-href="{{route('orders.temdestroy', $order->id)}}" title="{{ translate('Delete') }}">
                              			<i class="las la-trash"></i>
                           			</a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
    @include('modals.delete_temmodal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
