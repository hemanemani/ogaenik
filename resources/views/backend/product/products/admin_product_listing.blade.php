@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2">
	<div class="row card-body align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('Seller Product Listing')}}</h1>
		</div>
      <div class="col-md-6 text-md-right">
    			 <button type="button" class="text-center btn badge badge-inline badge-success bg-black text-white pt-3 pb-3 pl-3 pr-3 mt-4 fs-14 fw-600" data-toggle="modal" data-target="#bulk_import">Bulk Import</button>
    		</div>
	</div>
</div>


<div class="card">
	
		
	
    <div class="card-body">
        <table class="table mb-0 datatables">
            <thead>
                <tr>
                   
                    <th class="text-center">#</th>
											<th class="text-center" >Sheet Name</th>
											<th class="text-center" >Image URL</th>
											 <th class="text-center">Upload ID</th>
                                            <th class="text-center">Upload Date</th>
											 <th class="text-center">Upload Status</th>
                                            <th class="text-center">Live Status</th>
											 <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
			@php 
										$i= 1;
										@endphp
			@if (count($data) > 0)
                                        @foreach ($data as $key => $product_stock)                                           
                                            
                                                <tr style="
    background: #f2f2f2;
    border:10px solid #ffffff !important; 
">                                                    
													 <td class="text-center">
                                                        {{ $i++ }}
                                                    </td>
													<td class="text-center">
													<a href="https://seller.organysk.com/public/uploads/{{ $product_stock->path }} ">
													 {{ $product_stock->path }} 
                                                    </td>
													<td class="text-center">
													<a href="{{ $product_stock->image_location }}">
													 <button class="text-center btn badge badge-inline badge-success bg-black text-white p-3 fs-12 fw-600">Click here</button>
                                                    </td>
                                                    <td class="text-center Oceanwide fs-13">  
                                                       {{ $product_stock->upload_id }} 
                                                    </td>
													 <td class="text-center Oceanwide  fs-13"> 
                                                       {{ $product_stock->created_at->format('Y-m-d') }} 
                                                    </td>
                                                    <td class="text-center Oceanwide  fs-13">
													
													@if( $product_stock->upload_status == 'Uploaded')
                                                        <span style="color:green">Uploaded</span>
													@endif
                                                    </td>
                                                    <td class="text-center Oceanwide  fs-13">
	<select class="form-control  fs-13" name="upload_status{{ $product_stock->id }}" data-upload_id="{{ $product_stock->id }}" id="{{ $product_stock->id }}" 
	onchange="upload_status(this)">  
  <option value="Live" @if( $product_stock->live_status == "Live") selected @endif>Live</option>
  <option value="Under Review" @if( $product_stock->live_status == "Under Review") selected @endif>Under Review</option>
  <option value="Rejected" @if( $product_stock->live_status == "Rejected") selected @endif>Rejected</option>
</select>  
                                                    </td>                                                    
                                                      <td class="text-center">
													 <button onclick='UpdateData(this)' data-id="{{ $product_stock->id }}" value="{{ $product_stock->id }}"  class="btn btn-soft-info btn-icon text-white btn-circle btn-sm bg-black"> <i class="las la-trash" style="margin-top:0px !important;"></i></button>
                                                      
                                                       
                                                    </td>
                                                    
                                                </tr>
                                          
                                        @endforeach
										@endif
            </tbody>
        </table>
        
    </div>
</div>

@endsection
@section('script')
 <script>
 function UpdateData(el)
    {
		var id = el.value;		
         $.ajax({
            type: 'POST',
            url: '{{ route('product_listing.delete') }}',   
			data: { id: id,"_token": "{{ csrf_token() }}"},      			
			success: function (data) { 
                if(data == 1){
					location.reload();
                    AIZ.plugins.notify('success', '{{ translate('Deleted successfully') }}');
                }
                else{
					location.reload();
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            },            
        });
    }
function upload_status(e)
{	
	var getthevalue = $(e).val();
	var upload_id = $(e).data('upload_id');
	 $.ajax({
            type: 'POST',
            url: '{{ route('product.upload_status') }}',           
			data: { getthevalue: getthevalue,upload_id:upload_id,"_token": "{{ csrf_token() }}"},           
            success: function (data) { 
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            },            
        });
	
}
</script>

@endsection