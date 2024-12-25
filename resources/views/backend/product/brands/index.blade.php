@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Brands')}}</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Brands') }}</h5>
				</div>
				<div class="col-md-4">
					
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table datatables mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>{{translate('Name')}}</th>
		                    <th>{{translate('Company Name')}}</th>
		                    <th>{{translate('No. of Products')}}</th>
							<th>Status</th>
		                    <th>{{translate('Logo')}}</th>
							
		                    <th class="text-right">{{translate('Options')}}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($brands as $key => $brand)
		                    <tr>
		                        <td>{{ ($key+1) }}</td>
		                        <td>{{ $brand->getTranslation('name') }}</td>
		                        <td>{{ $brand->company_name }}</td>
		                         <td>{{ $brand->products_count }}</td>
								 <td>
							<label class="aiz-switch aiz-switch-success mb-0">
                              <input onchange="update_published(this)" value="{{ $brand->id }}" type="checkbox" <?php if($brand->status == 1) echo "checked";?> >
                              <span class="slider round"></span>
							</label>
						</td>
														<td>
		                            <img src="{{ uploaded_asset($brand->logo) }}" alt="{{translate('Brand')}}" class="h-50px">
		                        </td>
		                        <td class="text-right">
		                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('brands.edit', ['id'=>$brand->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
		                                <i class="las la-edit"></i>
		                            </a>
		                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('brands.destroy', $brand->id)}}" title="{{ translate('Delete') }}">
		                                <i class="las la-trash"></i>
		                            </a>
									<a href="#" class="btn btn-soft-secondary btn-icon btn-circle btn-sm temporary-delete" data-href="{{route('brands.temdestroy', $brand->id)}}" title="{{ translate('Delete') }}">
                              			<i class="las la-trash"></i>
                           			</a>
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		       
		    </div>
		</div>
	</div>
<!--	<div class="col-md-5">-->
<!--		<div class="card">-->
<!--			<div class="card-header">-->
<!--				<h5 class="mb-0 h6">{{ translate('Add New Brand') }}</h5>-->
<!--			</div>-->
<!--			<div class="card-body">-->
<!--				<form action="{{ route('brands.store') }}" method="POST">-->
<!--					@csrf-->
<!--					<div class="form-group mb-3">-->
<!--						<label for="name">{{translate('Name')}}</label>-->
<!--						<input type="text" placeholder="{{translate('Name')}}" name="name" class="form-control" required>-->
<!--					</div>-->
<!--					<div class="form-group mb-3">-->
<!--						<label for="name">Alpha Order</label>-->
<!--						<select name="alpha_order" class="form-control" id="alpha_order">-->
<!--  <option value="A-C">A-C</option>-->
<!--  <option value="D-H">D-H</option>-->
<!--  <option value="I-P">I-P</option>-->
<!--  <option value="Q-Z">Q-Z</option>-->
<!--  <option value="Others">Others</option>-->
<!--</select> -->
<!--					</div>-->
<!--					<div class="form-group mb-3">-->
<!--						<label for="name">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>-->
<!--						<div class="input-group" data-toggle="aizuploader" data-type="image">-->
<!--							<div class="input-group-prepend">-->
<!--									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>-->
<!--							</div>-->
<!--							<div class="form-control file-amount">{{ translate('Choose File') }}</div>-->
<!--							<input type="hidden" name="logo" class="selected-files">-->
<!--						</div>-->
<!--						<div class="file-preview box sm">-->
<!--						</div>-->
<!--					</div>-->
<!--					<div class="form-group mb-3">-->
<!--						<label for="name">{{translate('Meta Title')}}</label>-->
<!--						<input type="text" class="form-control" name="meta_title" placeholder="{{translate('Meta Title')}}">-->
<!--					</div>-->
<!--					<div class="form-group mb-3">-->
<!--						<label for="name">{{translate('Meta Description')}}</label>-->
<!--						<textarea name="meta_description" rows="5" class="form-control"></textarea>-->
<!--					</div>-->
<!--					<div class="form-group mb-3 text-right">-->
<!--						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>-->
<!--					</div>-->
<!--				</form>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
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
                    AIZ.plugins.notify('success', '{{ translate('Successfully Updated Brands') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
</script>
@endsection
