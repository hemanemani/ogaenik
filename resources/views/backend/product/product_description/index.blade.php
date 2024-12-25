@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Product Heading') }}</h1>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('All Product Headings') }}</h6>
		<a href="{{ route('product-description.create') }}" class="btn btn-primary">{{ translate('Add New Heading') }}</a>

	</div>
	<div class="card-body">
		<table class="table datatables mb-0">
        <thead>
            <tr>
              <th>#.</th>
                <th>{{translate('Heading')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
            
        @php 
			$i=1;
		@endphp 
        	@foreach (\App\Models\ProductDescription::get() as $key => $desc)
	
        	<tr>
        	    <td>{{ $i++ }}</td>
        		<td>{{ $desc->heading }}</td>

        		<td class="text-right">
						
						
						 <a href="{{ route('product-description.edit', ['product_description' => $desc->id]) }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">



						<i class="las la-pen"></i>
					</a>
				
                        
                     <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" 
                      data-href="{{ route('product-description.destroy', ['product_description' => $desc->id]) }}" 
                       title="{{ translate('Delete') }}">
                        <i class="las la-trash"></i>
                    </a>

							
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
