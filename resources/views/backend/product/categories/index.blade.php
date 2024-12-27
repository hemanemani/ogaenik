@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-3">
			<h1 class="h3">{{translate('All categories')}}</h1>
		</div>
		<div class="col-md-9" style="display: flex;justify-content: right;align-items: end;gap:10px">
			<a href="{{ route('categories.create') }}" class="btn btn-primary">
				<span>{{translate('Add New category')}}</span>
			</a>
			
			<button type="button" class="text-center btn badge badge-inline badge-success bg-black text-white pt-3 pb-3 pl-3 pr-3 mt-4 fs-14 fw-600" data-toggle="modal" data-target="#bulk_category_download">Download Category</button>

			
			<form method="POST" action="{{ route('export.bulk.category.products') }}">
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
                    <th>{{translate('Name')}}</th>
					<th>{{ translate('Parent Category') }}</th>
					<th>{{translate('No. of Products')}}</th>
                    <th>{{translate('Banner')}}</th>
                    <th>{{translate('Icon')}}</th>
                    <th>{{translate('Featured')}}</th>
                    <th>{{translate('Commission')}}</th>
                    <th width="10%" class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $key => $category)
                    <tr>
                        <td>{{ ($key+1)}}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @php
                                $parent = \App\Models\Category::where('id', $category->parent_id)->first();
                            @endphp
                            @if ($parent != null)
                                {{ $parent->name }}
                            @else
                                —
                            @endif
                        </td>
                         <td>{{ $category->total_products_count }}</td>
                        <td>
                            @if($category->banner != null)
                                <img src="{{ uploaded_asset($category->banner) }}" alt="{{translate('Banner')}}" class="h-50px">
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($category->icon != null)
                                <span class="avatar avatar-square avatar-xs">
                                    <img src="{{ uploaded_asset($category->icon) }}" alt="{{translate('icon')}}">
                                </span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="update_featured(this)" value="{{ $category->id }}" <?php if($category->featured == 1) echo "checked";?>>
                                <span></span>
                            </label>
                        </td>
                        <td>{{ $category->commision_rate }} %</td>
                        <td class="text-right">
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('categories.edit', $category->id )}}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('categories.destroy', $category->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                            <a href="#" class="btn btn-soft-secondary btn-icon btn-circle btn-sm temporary-delete" data-href="{{route('categories.temdestroy', $category->id)}}" title="{{ translate('Delete') }}">
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
    @include('modals.delete_temmodal')
	<div class="modal fade" id="bulk_category_download" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
               
                  <h6 class="modal-title">Download Selected Category Product</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                		 <div class="col-sm-8">
                	       	<b>Download Excel File</b>
                		 </div>
                		 <div class="col-sm-4 text-center">
                  
                             <form method="POST" action="{{ route('export.selected.category.products') }}">
                                @csrf
                                
                                
                                
                                <select name="product_category" id="product_category" class="form-control mt-3 aiz-selectpicker" data-live-search="true">
                                    <option value="">--Select Category Name--</option> 
                                        @foreach (\App\Models\Category::whereNotNull('parent_id')->with('childrenCategories')->get() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach

                                </select>


                                
                                <button type="submit" class="btn btn-primary mt-3">Export to Excel</button>
                            </form>
                		 </div>
            		 </div>
        		 </div>
            </div>
        </div>
    </div>
  </div>
@endsection


@section('script')
    <script type="text/javascript">
        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('categories.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Featured categories updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
