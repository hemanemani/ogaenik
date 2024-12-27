@if(get_setting('topbar_banner_content') != null)
<div class="aiz-carousel dots-inside-bottom mobile-img-auto-height position-relative top-banner removable-session d-lg-none d-block" data-key="top-banner" data-value="removed" data-arrows="false" data-dots="false" data-autoplay="true" data-infinite="true" style="background-color:#eeeeee">

    
     @php $slider_images_mobile = json_decode(get_setting('topbar_banner_content'), true);  @endphp
    @foreach ($slider_images_mobile as $key => $value)
        <div class="carousel-box item">
             <h6 class="fs-15 text-black text-center mb-0 p-2 Oceanwide">{!! str_replace(' ', '&nbsp;', $slider_images_mobile[$key]) !!}</h6>
             </div>
    @endforeach
    
</div>
@endif

<div class="d-block d-lg-none mobile-menu">
<div class="position-relative logo-bar-area  aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-xl-none d-flex">
        <div class="aiz-topbar-nav-toggler d-flex align-items-center justify-content-start mr-2 mr-md-3" data-toggle="aiz-mobile-nav">
            <button class="aiz-mobile-toggler">
                <span></span>
            </button>
        </div>
        <div class="aiz-topbar-logo-wrap d-flex align-items-center justify-content-start">
            @php
                $logo = get_setting('header_logo');
            @endphp
            <a href="{{ route('home') }}" class="d-block">
                @if($logo != null)
                    <img src="{{ uploaded_asset($logo) }}" data-gumlet="false" class="brand-icon" alt="{{ get_setting('website_name') }}" class="mw-100" width="130" height="75">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" data-gumlet="false" class="brand-icon" alt="{{ get_setting('website_name') }}" class="mw-100" width="130" height="75">
                @endif
            </a>
        </div>
    </div>
	
	<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left bg-white">
        <div class="aiz-side-nav-logo-wrap border-bottom">
            <a href="{{ route('home') }}" class="d-block text-center">
               <img src="{{ uploaded_asset($logo) }}" data-gumlet="false" class="brand-icon" alt="{{ get_setting('website_name') }}" class="mw-100" width="130" height="75">
            </a>
        </div>
        <div class=" bg-white position-relative" style="margin-bottom: 50px;">           

			
              
            <ul class="aiz-side-nav-list-home" id="main-menu" data-toggle="aiz-side-menu" >
			@auth
				<li class="aiz-side-nav-item border-bottom ">
                  <h3 class="aiz-side-nav-links fw-normal text-black Oceanwide mb-0" > {{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }} </h3>
                </li>
				@else
				 <li class="aiz-side-nav-item border-bottom">
                   <span class="aiz-side-nav-link fw-normal bg-gray Oceanwide text-black" onclick="openRegisterModal();"> <i class="fa fa-user" aria-hidden="true" style="
    font-size: 20px;
    padding-right: 8px;
"></i> {{__('Login | Sign Up')}}</span>
                </li>
				 @endauth
				
                <ul id="accordion" class="aiz-side-nav-list-home accordion">
				<li class="pt-3"><label class="links Oceanwide">SHOP</label></li>
				 <li>
  
   <div class="link  " id="heading-1"> <h5 class="mb1-0 mb-0">
        <a role="button" data-toggle="collapse" href="#collapse-2" class="Oceanwide" aria-expanded="false" aria-controls="collapse-1">
         SHOP BY ETHICS
        </a>
      </h5></div>
    
	<li>
    <div id="collapse-2" class="collapse" data-parent="#accordion" aria-labelledby="heading-1">      
@foreach (\App\Models\Department::all() as $key => $brand)
        <div id="accordion-1">
         
		  <div class="link bg-white" id="{{ ($brand->id) }}"> <h5 class="0">
       <a class="collapsed links p-0 Oceanwide" href="{{ route('department.category', $brand->slug) }}">
                 {{ ($brand->name) }}
                </a>
      </h5></div>
	  
	  
          
        </div>      
      @endforeach
      
    </div>
	</li>
  
  </li>
				 <li>
  
   <div class="link  " id="heading-1"> <h5 class="mb1-0 mb-0">
        <a role="button" data-toggle="collapse" href="#collapse-1" class="Oceanwide" aria-expanded="false" aria-controls="collapse-1">
         BRANDS
        </a>
      </h5></div>
    
	<li>
    <div id="collapse-1" class="collapse" data-parent="#accordion" aria-labelledby="heading-1">      
@foreach (\App\Models\Brand::where('alpha_order','!=','Others')->where('alpha_order','!=','')->where('slug','!=','')->where('status',1)->orderBy('alpha_order', 'ASC')->groupBy('alpha_order')->get() as $key => $brand)
        <div id="accordion-1">
         
		  <div class="link bg-white" id="heading-1-{{ ($brand->id) }}"> <h5 class="mb1-0">
       <a class="collapsed links p-0 Oceanwide" role="button" data-toggle="collapse" href="#collapse-1-{{ ($brand->id) }}" aria-expanded="false" aria-controls="collapse-1-{{ ($brand->id) }}">
                 {{ ($brand->alpha_order) }}
                </a>
      </h5></div>
	  
	  <ul class="submenu  aiz-side-nav-list-home accordions" id="collapse-1-{{ ($brand->id) }}" data-parent="#accordion-1" aria-labelledby="heading-1-{{ ($brand->id) }}">
          
            <li id="accordion-1-{{ ($brand->id) }}">
              
                
				   @foreach(\App\Models\Brand::where('status',1)->orderBy('name', 'asc')->where('alpha_order','like','%' . $brand->alpha_order . '%')->get() as $key => $s)
				   
				   <a href="{{ route('products.brand', $s->slug) }}"><div class="links"> {{ $s['name'] }}</div></a>
                    @endforeach
                   
                  </li>

             
            
         </ul>
          
        </div>      
      @endforeach
      
    </div>
	</li>
  
  </li>
			   @foreach (\App\Models\Category::where('level', 0)->where('featured',1)->orderBy('name', 'asc')->get()->take(11) as $key => $category)
				  <li>
					<div class="link Oceanwide">{{ strtoupper($category->name) }}<i class="fa fa-chevron-down"></i></div>
					
					<ul class="submenu  aiz-side-nav-list-home accordions" id="accordion1">
					@foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
					<li>
					<a href="{{ route('products.subcategory', \App\Models\Category::find($first_level_id)->slug) }}"><div class="links">{{ strtoupper(\App\Models\Category::find($first_level_id)->name) }}</div></a>
					 
					  </li>
						@endforeach 
					</ul>

				  </li>
  @endforeach 
 
</ul>

				
				<li class="aiz-side-nav-item accordion ">
                    <a href="{{route('customer_gift')}}">                      
                        <span class="link Oceanwide links" style="text-transform: uppercase">Gifts & Hampers</span>
                    </a>
                </li>
				<li class="aiz-side-nav-item accordion d-none">
					 @foreach(\App\Models\FlashDeal::where('status', 1)->get() as $key => $category)		
                    <a href="{{ url('flash-deal/'.$category->slug) }}">                        
                        <span class="link Oceanwide fs-14">OFFERS</span>
                    </a>
					 @endforeach	
					 @foreach(\App\Models\FlashDeal::where('status', 0)->get() as $key => $category)
							<a href="{{ url('flash-deal/'.$category->slug) }}">
								<span class="link Oceanwide fs-14">OFFERS</span>
							</a>
                            @endforeach
                </li>
				
				
				@if (Auth::check())
					<li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link">                      
                        <span class="aiz-side-nav-text Oceanwide fs-14 ">DASHBOARD</span>
                    </a>
                </li>

	<li class="aiz-side-nav-item">
                    <a href="{{ route('profile') }}" class="aiz-side-nav-link">                        
                        <span class="aiz-side-nav-text Oceanwide fs-14 ">MY PROFILE</span>
                    </a>
                </li>				
				<li class="aiz-side-nav-item">
                    <a href="{{ route('logout') }}" class="aiz-side-nav-link">                        
                        <span class="aiz-side-nav-text Oceanwide fs-14 ">SIGN OUT</span>
                    </a>
                </li>		
				@endif
				
            </ul><!-- .aiz-side-nav -->
		<div class="col-lg-4 col-xl-4 text-center sticky-social aiz-side-nav-link social-menu">
                    <div class="col">
                        <div class="d-inline-block d-md-block">
                          
					
					
                    						<a href="https://www.facebook.com/orgenikworld" class="ml-2 text-white"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                                						<a href="https://twitter.com/orgenikworld" class="ml-2 text-white"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                                						<a href="https://www.linkedin.com/company/orgenik" class="ml-2 text-white"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                        						                        <a href="https://www.instagram.com/orgenikworld/" class="ml-2 text-white"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        						<a href="https://www.youtube.com/@Orgenik" class="ml-2 text-white"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                        
					
				
                        </div>
                    </div>
                </div>
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->


  @php
if(auth()->user() != null) {
    $user_id = Auth::user()->id;
    $carts = \App\Models\Cart::where('user_id', $user_id)->get();
} else {
    $temp_user_id = Session()->get('temp_user_id');
    if($temp_user_id) {
        $carts = \App\Models\Cart::where('temp_user_id', $temp_user_id)->get();
		
    }
}

@endphp

	 <a href="{{ route('cart') }}" class="float-rigt py-2">
            <span class="d-inline-block position-relative px-2">
                <i class="las la-shopping-cart la-3x"></i>
                @if(isset($carts) && count($carts) > 0)
                    <span class="badge badge-circle badge-primary position-absolute absolute-top-right" id="cart_items_sidenav">{{ count($carts)}}</span>
                @else
                    <span class="badge badge-circle badge-primary position-absolute absolute-top-right" id="cart_items_sidenav">0</span>
                @endif
            </span>
        </a>
</div><!-- .aiz-topbar -->
<div class="flex-grow-1 d-flex align-items-center p-2">
                   <div class="position-relative flex-grow-1">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation" id="search_item">
                            <div class="d-flex position-relative align-items-center">
                               
								
							
                                <div class="input-group">
                                    
                                     <div class="input-group-append d-lg-block">
                                         
                                          <button class="btn search-box button search-box1" id="search-box1" type="submit" disabled="">
                                            <i class="la la-search la-flip-horizontal fs-18"></i>
                                        </button>
                                        
                                        
                                    </div>
                                    
                                   
                                        
                                  
                                    
                                    <input type="text" class="border-0 form-control search1"  id="search"  name="q" placeholder="Search for products, brands and more" autocomplete="off">
                                   
                                </div>
								 <!--<div class="d-lg-block" data-toggle="class-toggle" data-target=".front-header-search">-->
                                    <!--<button class="btn px-2" type="submit" id="search-box8"><i class="la la-2x la la-search color-white"></i></button>-->
                                <!--</div>-->
                            </div> 
                        </form>
                        
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white shadow-lg position-absolute left-0 top-100 w-100" style="z-index:99;height: 430px;overflow-x: hidden;overflow-y: auto;">
						 <div class="search-preloader absolute-top-center">
                                <div class="dot-loader"><div></div><div></div><div></div></div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content" class="text-left">

                            </div>
                        </div>
                    </div>
                </div>
</div>
                
                
				<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>



				<script>
$(document).ready(function(){
  $('#search-box8').prop('disabled',true);
    $('#search').keyup(function(){
        $('#search-box8').prop('disabled', this.value === "");     
    })
});
$(document).ready(function () {
  // Add minus icon for collapse element which is open by default
  $(".collapse.show").each(function () {
    $(this)
      .prev(".card-header")
      .find(".fa")
      .addClass("fa-minus")
      .removeClass("fa-plus");
  });

  // Toggle plus minus icon on show hide of collapse element
  $(".collapse")
    .on("show.bs.collapse", function () {
      $(this)
        .prev(".card-header")
        .find(".fa")
        .removeClass("fa-plus")
        .addClass("fa-minus");
    })
    .on("hide.bs.collapse", function () {
      $(this)
        .prev(".card-header")
        .find(".fa")
        .removeClass("fa-minus")
        .addClass("fa-plus");
    });
});
$(function() {
   var Accordion = function(el, multiple) {
      this.el = el || {};
      this.multiple = multiple || false;
       
      var links = this.el.find('.link');

      links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
   }

   Accordion.prototype.dropdown = function(e) {
      var $el = e.data.el;
         $this = $(this),
         $next = $this.next();

      $next.slideToggle();
      $this.parent().toggleClass('open');

      if (!e.data.multiple) {
         $el.find('.submenu').not($next).slideUp().parent().removeClass('open');
      };
   }  

   var accordion = new Accordion($('#accordion'), false);
});

</script>