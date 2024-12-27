@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Product Categories')}}</h1>
		</div>
		<div class="col-md-6" style="display: flex;justify-content: right;align-items: center;">
		
			<form method="POST" action="{{ route('export.bulk.product.categories') }}">
                @csrf
             <button type="submit" class="btn btn-primary ml-2">Export to Excel</button>
            </form>

		</div>
	</div>
</div>
<div class="card">
    
    <div class="card-body">
        <table class="table mb-0 datatables">
            <thead>
                <tr>
                    <th>#</th>
					<th>{{ translate('Product Name') }}</th>
					<th>{{translate('Sub Sub Category')}}</th>
                    <th>{{translate('Sub Category')}}</th>
                    <th>{{translate('Main Category')}}</th>
                </tr>
            </thead>
            <tbody>
                
                 @foreach($products as $key => $product)
                    <tr>
                        <td>{{ ($key+1)}}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ optional($product->category)->name }}</td>
                        <td>{{ optional($product->category->parentCategory)->name }}</td>
                        <td>{{ optional(optional(optional($product->category)->parentCategory)->parentCategory)->name}}</td>
                    </tr>
                        
                    @endforeach

                
            </tbody>
        </table>
     
    </div>
</div>
@endsection

