@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Category Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
  			
            <form class="p-4" action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PATCH">
	           
            	@csrf
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-md-9">
                        <input type="text" name="name" value="{{ $category->name }}" class="form-control" id="name" placeholder="{{translate('Name')}}" required>
                    </div>
                </div>
				 <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Specification')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-md-9">
                        <input type="text" name="specification" value="{{ $category->specification }}" class="form-control" id="specification" placeholder="{{translate('Specification')}}">
                    </div>
                </div>
				
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Parent Category')}}</label>
                    <div class="col-md-9">
                        <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" data-selected="{{ $category->parent_id }}">
                            <option value="0">{{ translate('No Parent') }}</option>
                            @foreach ($categories as $acategory)
                                <option value="{{ $acategory->id }}">{{ $acategory->name }}</option>
                                @foreach ($acategory->childrenCategories as $childCategory)
                                    @include('categories.child_category', ['child_category' => $childCategory])
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Type')}}</label>
                    <div class="col-md-9">
                        <select name="digital" required class="form-control aiz-selectpicker mb-2 mb-md-0">
                            <option value="0" @if ($category->digital == '0') selected @endif>{{translate('Physical')}}</option>
                            <option value="1" @if ($category->digital == '1') selected @endif>{{translate('Digital')}}</option>
							<option value="2" @if ($category->digital == '2') selected @endif>{{translate('Gift')}}</option>
							<option value="3" @if ($category->digital == '3') selected @endif>{{translate('Settings')}}</option>
						</select>
                    </div>
                </div> 
	              <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Banner')}} <small>({{ translate('200x200') }})</small></label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="banner" class="selected-files" value="{{ $category->banner }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Icon')}} <small>({{ translate('32x32') }})</small></label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="icon" class="selected-files" value="{{ $category->icon }}">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Meta Title')}}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="meta_title" value="{{ $category->meta_title }}" placeholder="{{translate('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Meta Description')}}</label>
                    <div class="col-md-9">
                        <textarea name="meta_description" rows="5" class="form-control">{{ $category->meta_description }}</textarea>
                    </div>
                </div>
               
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{{translate('Slug')}}</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $category->slug }}" class="form-control">
                    </div>
                </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Would you like to modify the Slug? <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-md-9">
                        <input type="hidden" name="change_slug" value="0" />
                        <input type="checkbox" name="change_slug" class="form-control" id="change_slug" value="1"  >
                    </div>
                </div>
                @if (\App\BusinessSetting::where('type', 'category_wise_commission')->first()->value == 1)
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{translate('Commission Rate')}}</label>
                        <div class="col-md-9 input-group">
                            <input type="number" lang="en" min="0" step="0.01" id="commision_rate" name="commision_rate" value="{{ $category->commision_rate }}" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-------------------------------------------------------------------------category with products---------------------------------------------------------------------------->


<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('Category Products')}}</h1>
		</div>
	</div>
</div>
<br>

<div class="card">
	<form class="" id="sort_products" action="" method="GET">
	</from>
    <div class="card-body">
        <table class="table mb-0 datatables">
            <thead>
                <tr>
				<!--<th><input type="checkbox" id="check_all"></th>-->
                    <th>#</th>
                    <th width="20%">{{translate('Name')}}</th>
                    <th>{{translate('Num of Sale')}}</th>
                    <th>{{translate('Total Stock')}}</th>
                    <th>{{translate('Base Price')}}</th>
                    <th>{{translate('Brand Name')}}</th>
                    <th>{{translate('Published')}}</th>
                   
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allProducts as $key => $product) 
				 
                    <tr> 

					<!--<td><input type="checkbox" class="checkbox" data-id="{{$product->id}}"></td>-->
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
		                      <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.admin.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
		                          <i class="las la-edit"></i>
		                      </a>
						
						
                      </td>
                  	</tr> 
                  	
                @endforeach
            </tbody>
        </table>
      
        
    </div>
</div>

<script type="text/javascript">

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

      
    document.addEventListener('DOMContentLoaded', e => {
    for (let checkbox of document.querySelectorAll('input[type=checkbox]')) {
        checkbox.value = checkbox.checked ? 1 : 0;
        checkbox.addEventListener('change', e => {
                e.target.value = e.target.checked ? 1 : 0;
           });
        }
    });
</script>

@endsection
