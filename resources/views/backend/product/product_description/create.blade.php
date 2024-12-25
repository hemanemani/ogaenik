@extends('backend.layouts.app')
@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Add New Heading') }}</h1>
		</div>
	</div>
</div>
<div class="card">
	<form action="{{ route('product-description.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="card-header">
			<h6 class="fw-600 mb-0">{{ translate(' Heading') }}</h6>
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-sm-2 col-from-label" for="name">{{translate('Heading')}} <span class="text-danger">*</span></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" placeholder="Heading" name="heading" required>
				</div>
			</div>
		</div>

			<div class="text-right">
				<button type="submit" class="btn btn-primary">{{ translate('Save Page') }}</button>
			</div>
		</div>
	</form>
</div>
@endsection
