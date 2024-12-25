@extends('backend.layouts.app')

@section('content')
@php
    $refund_request_addon = \App\Models\Addon::where('unique_identifier', 'refund_request')->first();
@endphp

<div class="card">
  
      <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
          <h5 class="mb-md-0 h6">{{ translate('Stock Notification') }}</h5>
        </div>
        
         

         
         
          
      </div>
   
    <div class="card-body">
        <table class="table datatables mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th data-breakpoints="md">{{translate('Customer Email')}}</th>
                    <th data-breakpoints="md">{{translate('Product Name')}}</th>
                    <th data-breakpoints="md">{{translate('Size')}}</th>
                    <th data-breakpoints="md">{{translate('Brand Name')}}</th>
                    <th data-breakpoints="md">{{translate('Stock')}}</th>
                    <th data-breakpoints="md">{{translate('Status')}}</th>
                    <th data-breakpoints="md">{{translate('Date')}}</th>

                  
                </tr>
            </thead>
            
            <tbody>
                
            
                @foreach ($stockUpdates as $key => $stockUpdate)
                   
                        <tr>
                            <td>
                                {{ ($key+1) }}
                            </td>
                            <td>
                                {{ $stockUpdate->email }}
                            </td>
                            <td>
                                {{ $stockUpdate->product_name }}
                            </td>
                             <td>
                                {{ $stockUpdate->variant_name }}
                            </td>
                             <td>
                           
                                @php
                                    $brands = \App\Brand::all();
                                @endphp
                                
                                @foreach ($brands as $brand)
                                    @if ($stockUpdate->brand_name == $brand->id)
                                        {{ $brand->getTranslation('name') }}
                                    @endif
                                @endforeach

                            </td>
                            <td>
                               {{ $stockUpdate->stock_qty }}
                            </td>
                            <td>
                               
                               @if ($stockUpdate->status == '1')
                                  <span class="badge badge-inline badge-success">{{translate('Stock Fulfilled')}}</span>
                                @else
                                  <span class="badge badge-inline badge-danger">{{translate('Stock Request')}}</span>
                                @endif
                                    
                            </td>
                            <td>
                               {{ $stockUpdate->updated_at }}
                            </td>
                            
                           
                        
                        </tr>
                  
                @endforeach
            </tbody>
                

           
        </table>
        
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
