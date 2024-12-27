@extends('frontend.layouts.app')

@section('content')
<div class="hero">
	<div class="container">
		<div class="row">
			<div class="col-md-12"> 
							<h2 class="font-weight-normal">Our Categories</h2>
							<ul class="breadcrumb bg-transparent p-0 text-center inline-flex mb-0">
								<li class="breadcrumb-item opacity-50">
									<a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
								</li>
								<li class="breadcrumb-item opacity-50">
									<a class="text-reset" href="{{ route('categories.all') }}">Our Categories</a>
								</li>
							</ul>		
			</div>
		</div> 
	</div>
</div>

<section class="m-4">

<div class="container">
		<div class="">
			<div class="col-xs-12 d-none d-lg-block">
					<ul class="nav nav-tabs">
					@if(count($categories) > 12)
					   @for ($i = 0; $i < 11; $i++)
					   <li><a href="#nav-{{ $i }}" data-toggle="tab" class="nav-item nav-link @php if($i == 0) echo 'active' @endphp">{{ $categories[$i]->name }}</a></li>
					   @endfor
					@else
						@foreach ($categories as $key => $category)
						<li><a href="#nav-{{ $key }}" data-toggle="tab" class="nav-item nav-link @php if($key == 0) echo 'active' @endphp">{{ __($category->name) }}</a></li>
						@endforeach
                    @endif 
					</ul>
			
			
			
			<div class="tab-content">
			 @foreach ($categories as $key => $category)         
			   <div class="sub-category-menu tab-pane @php if($key == 0) echo 'active show' @endphp" id="nav-{{ $key }}">
			   <h3 class="category-name border-bottom p-3 text-center"><a href="{{ route('products.category', $category->slug) }}" style="font-size:24px;" >{{  __($category->name) }}</a></h3>
                        <div class="row">
						@foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                           <div class="col-lg-4 col-6 home-category border-bottom" >
                                <h6 class="mb-3 mt-2"><center><a href="{{ route('products.subcategory', \App\Models\Category::find($first_level_id)->slug) }}" class="fs-20">{{ \App\Models\Category::find($first_level_id)->getTranslation('name') }}</a></center></h6>
                                 <ul>                                         
                                        @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)
									        <li><a href="{{ route('products.subsubcategory', \App\Models\Category::find($second_level_id)->slug) }}" >{{ \App\Models\Category::find($second_level_id)->name }}</a></li>
                                    	@endforeach
                                 </ul>
                            </div>
						@endforeach
                        </div>
				</div>
			 @endforeach 
			</div>
			
			
			
			</div>
			<div class="col-lg-12 d-block d-lg-none">

			<div class="panel-group" id="accordion">
			
    <!-- First Panel -->
   
	@foreach ($categories as $key => $category)		
	 <div class="panel panel-default border">
        <div class="panel-heading border-bottom">
						
             <h6 class="panel-title mb-0 p-2" data-toggle="collapse" data-target="#navs-{{ $key }}">
                 {{  __($category->name) }}
             </h6>
        </div>

        <div id="navs-{{ $key }}" class="panel-collapse collapse">
            <div class="panel-body">
			 <div class="row">
        <div class="container">
		<h3 class="category-name border-bottom p-3 text-center"><a href="{{ route('products.category', $category->slug) }}" style="font-size:24px;" >{{  __($category->name) }}</a></h3>
           @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                <div class="mb-3 bg-white">
				
                    <div class="sub-category-menu @php if($key < 12) echo 'active'; @endphp">
                        <h6 class="pl-2"><a href="{{ route('products.subcategory', \App\Models\Category::find($first_level_id)->slug) }}">{{ \App\Models\Category::find($first_level_id)->getTranslation('name') }}</a></h6>
                        <div class="row">
                           
                            <div class="col-lg-12 col-12">                             
                                <ul class="mb-3">
                                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)
                                    <li class="w-100"><a href="{{ route('products.subcategory', \App\Models\Category::find($second_level_id)->slug) }}" >{{ \App\Models\Category::find($second_level_id)->getTranslation('name') }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            
                        </div>
                    </div>
                </div>
               
            @endforeach
        </div>
    </div>
                        
            </div>
        </div>
    </div>
   @endforeach 
</div>



			</div>
		</div>
</div>
   
</section>

@endsection
