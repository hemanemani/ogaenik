@extends('backend.layouts.app')

@section('content')



<div class="card">
	
		<div class="card-header row gutters-5">
			<div class="col text-center text-md-left">
				<h5 class="mb-md-0 h6">{{ translate('All Product') }}</h5>
			</div>
			<div class="col text-center text-md-right">
				
				<button type="button" class="btn btn-danger delete-all" data-toggle='confirmation'>Bulk Recover</button>
				
			</div>
			
			 
		</div>
	
    <div class="card-body">
        <table class="table mb-0 datatables">
            <thead>
                <tr>
				<th><input type="checkbox" id="check_all"></th>
                    <th>#</th>
					<th>{{translate('Image')}}</th>
                    <th class="text-center">Name</th>
					
                    <th>{{translate('Total Stock')}}</th>
					<th>{{translate('Qty')}}</th>
                    
                    <th>{{translate('Brand Name')}}</th>
                    
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
										<img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="Image" class="w-50px">
									</div>
									
								</div>
							</a>
                        </td>
                     
                        <td>{{ $product['name']  }}</td>
						
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
                       
                         <td>
                            @if(isset($product->brand['name']))
                                {{ $product->brand['name'] }}
                            @else
                                No Brand
                            @endif
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
alert(strIds);
$.ajax({
url: "{{ route('product.multiple_update')}}",
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

$('.delete-permanaent').on('click', function(e) {
var idsArr = [];  
$(".checkbox:checked").each(function() {  
idsArr.push($(this).attr('data-id'));
});  
if(idsArr.length <=0)  
{  
alert("Please select atleast one record to delete.");  
}  else {  
if(confirm("Are you sure, you want to Permanent delete the selected product?")){  
var strIds = idsArr.join(","); 
alert(strIds);
$.ajax({
url: "#",
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
