@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Edit Description Heading Information') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	
	<form class="p-4" action="{{ route('product-description.update', $descheading->id) }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="hidden" name="_method" value="PATCH">

	  <h6 class="fw-600 mb-0">{{ translate(' Heading') }}</h6>
		<hr>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Heading')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="Heading" name="heading" value="{{ old('heading', $descheading->heading) }}" required>
				</div>
			</div>

				<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Update Heading') }}</button>
			</div>
		
		</div>

	</form>
</div>
@endsection

