@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Brand Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">
            <ul class="nav nav-tabs nav-fill border-light">
  				@foreach (\App\Language::all() as $key => $language)
  					<li class="nav-item">
  						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('brands.edit', ['id'=>$brand->id, 'lang'=> $language->code] ) }}">
  							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
  							<span>{{ $language->name }}</span>
  						</a>
  					</li>
	            @endforeach
  			</ul>
            <form class="p-4" action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="PATCH">
                <input type="hidden" name="lang" value="{{ $lang }}">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{ $brand->getTranslation('name', $lang) }}" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="company_name">{{translate('Company Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Company Name')}}" id="company_name" name="company_name" value="{{ $company_name }}" class="form-control" readonly>
                    </div>
                </div>
                
                
				<div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">Alpha Order <i class="las la-language text-danger" title="Alpha Order"></i></label>
                    <div class="col-sm-9">
					<select name="alpha_order" class="form-control" id="alpha_order">
  <option value="A-C" @if($brand->alpha_order == 'A-C' ) selected @endif >A-C</option>
  <option value="D-H" @if($brand->alpha_order == 'D-H' ) selected @endif>D-H</option>
  <option value="I-P" @if($brand->alpha_order == 'I-P' ) selected @endif>I-P</option>
  <option value="Q-Z" @if($brand->alpha_order == 'Q-Z' ) selected @endif>Q-Z</option>
  <option value="Others" @if($brand->alpha_order == 'Others' ) selected @endif>Others</option>
</select>

                      
                    </div>
                </div>
				
                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Logo')}} <small>({{ translate('120x80') }})</small></label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="logo" value="{{$brand->logo}}" class="selected-files">
                        </div>
                        <div class="file-preview box sm">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Meta Title')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="meta_title" value="{{ $brand->meta_title }}" placeholder="{{translate('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{translate('Meta Description')}}</label>
                    <div class="col-sm-9">
                        <textarea name="meta_description" rows="8" class="form-control">{{ $brand->meta_description }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Slug')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $brand->slug }}" class="form-control">
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-------------------------------------------------------------------------brand with products---------------------------------------------------------------------------->


<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('Brand Products')}}</h1>
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
                @foreach($brandProducts as $key => $product) 
				 
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

      

    </script>

@endsection
