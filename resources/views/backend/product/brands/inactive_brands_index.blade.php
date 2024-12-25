@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">Inactive Brands</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">Inactive Brands</h5>
				</div>
				<div class="col-md-4">
					
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table datatables mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>Brand Name</th>
		                    <th>{{translate('Logo')}}</th>
							<th>Status</th>
		                   
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($brands as $key => $brand)
		                    <tr>
		                        <td>{{ ($key+1) }}</td>
		                        <td>{{ $brand->name }}</td>
														<td>
		                            <img src="{{ uploaded_asset($brand->logo) }}" alt="{{translate('Brand')}}" class="h-50px">
		                        </td>
								<td>
							<label class="aiz-switch aiz-switch-success mb-0">
                              <input onchange="update_published(this)" value="{{ $brand->id }}" type="checkbox" <?php if($brand->status == 1) echo "checked";?> >
                              <span class="slider round"></span>
							</label>
						</td>
		                       
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		       
		    </div>
		</div>
	</div>
	
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
	@include('modals.delete_temmodal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
        $('#sort_brands').submit();
    }
	function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('brands.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
</script>
@endsection
