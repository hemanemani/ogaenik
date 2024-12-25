@extends('backend.layouts.app')

@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All products')}}</h1>
		</div>
        @if($type != 'Seller')
    		<div class="col-md-6 text-md-right">
    			<a href="{{ route('products.create') }}" class="btn btn-circle btn-info">
    				<span>{{translate('Add New Product')}}</span>
    			</a>
    		</div>
        @endif
	</div>
</div>
<br>

<div class="card">
	<form class="" id="sort_products" action="" method="GET">
		<div class="card-header row gutters-5">
			<div class="col text-center text-md-left">
				<h5 class="mb-md-0 h6">{{ translate('All Product') }}</h5>
			</div>
			
			<div class="col text-center text-md-right">
				
				<button type="button" class="btn btn-danger delete-all" data-toggle='confirmation'>Bulk Delete</button>
				
			</div>
			
		</div>
	</from>
    <div class="card-body">
        <table class="table mb-0 datatables">
            <thead>
                <tr>
				<th><input type="checkbox" id="check_all"></th>
                    <th>#</th>
                    <th width="20%">{{translate('Name')}}</th>
                    @if($type == 'Seller' || $type == 'All')
                        <th>{{translate('Added By')}}</th>
                    @endif
                    <th>{{translate('Num of Sale')}}</th>
                    <th>{{translate('Total Stock')}}</th>
                    <th>{{translate('Base Price')}}</th>
                  
                    <th>{{translate('Brand Name')}}</th>
                    <th>{{translate('Published')}}</th>
                   
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $key => $product) 
				 
                    <tr> 

					<td><input type="checkbox" class="checkbox" data-id="{{$product->id}}"></td>
                        <td>{{ ($key+1) }}</td>
                        <td>
                            <a href="{{ route('product', $product->slug) }}" target="_blank">
								<div class="form-group row">
									<div class="col-md-4">
										<img src="{{ uploaded_asset($product->thumbnail_img)}}" loading="lazy" alt="Image" class="w-50px">
									</div>
									<div class="col-md-8">
										<span class="text-muted">{{ $product['name']  }}</span>
									</div>
								</div>
							</a>
                        </td>
                        @if($type == 'Seller' || $type == 'All')
                            <td>{{ $product->user['name'] }}</td>
                        @endif
                        <td>{{ $product->num_of_sale }} {{translate('times')}}</td>
						
                        <td>
                            @php
                                $qty = 0;
                                if($product->variant_product){
                                    foreach ($product->stocks as $key => $stock) {
                                        $qty += $stock->qty;
                                    }
                                }
                                else{
                                    $qty = $product->current_stock;
                                }
                                echo $qty;
                            @endphp
                        </td>
                        <td>{{ number_format($product->unit_price,2) }}</td>
                       
                         <td>{{ $product->brand ? $product->brand->name : 'No Brand' }}</td> 
                        <td>
							<label class="aiz-switch aiz-switch-success mb-0">
                              <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                              <span class="slider round"></span>
							</label>
						</td>
                      
						<td class="text-right">
							@if ($type == 'Seller')
								<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
								   <i class="las la-edit"></i>
							   </a>
							@endif
							<a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{route('products.duplicate', ['id'=>$product->id, 'type'=>$type]  )}}" title="{{ translate('Duplicate') }}">
							   <i class="las la-copy"></i>
						   </a>
                            <a href="#" class="btn btn-soft-secondary btn-icon btn-circle btn-sm temporary-delete" data-href="{{route('products.temdestroy', $product->id)}}" title="{{ translate('Delete') }}">
                              <i class="las la-trash"></i>
                           </a>
                           
                      </td>
                  	</tr> 
                  	
                @endforeach
            </tbody>
        </table>
      
        
    </div>
    <!--<div id="pagination-links">-->
    <!--    {{ $products->appends(request()->input())->links() }}-->
    <!--</div>-->
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection



@section('script')



<script type="text/javascript">

    
$(document).ready(function () {
$('#check_all').on('click', function(e) {
if($(this).is(':checked',true))  
{
$(".checkbox").prop('checked', true);  
} else {  
$(".checkbox").prop('checked',false);  
}  
});
$('.checkbox').on('click',function(){
if($('.checkbox:checked').length == $('.checkbox').length){
$('#check_all').prop('checked',true);
}else{
$('#check_all').prop('checked',false);
}
});
$('.delete-all').on('click', function(e) {
var idsArr = [];  
$(".checkbox:checked").each(function() {  
idsArr.push($(this).attr('data-id'));
});  
if(idsArr.length <=0)  
{  
alert("Please select atleast one record to delete.");  
}  else {  
if(confirm("Are you sure, you want to delete the selected product?")){  
var strIds = idsArr.join(","); 
$.ajax({
url: "{{ route('product.multiple_delete')}}",
type: 'get',
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
data: 'ids='+strIds,
success: function (data) {
if (data['status']==true) {
$(".checkbox:checked").each(function() {  
$(this).parents("tr").remove();
});
alert(data['message']);
} else {
alert('Whoops Something went wrong!!');
}
},
error: function (data) {
alert(data.responseText);
}
});
}  
}  
});
 
});
</script>
    <script type="text/javascript">

        $(document).ready(function(){
            //$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
        });

        function update_todays_deal(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.todays_deal') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Todays Deal updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Published products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function sort_products(el){
            $('#sort_products').submit();
        }

    </script>
@endsection
