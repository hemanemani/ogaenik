@if(get_setting('topbar_banner_content') != null)
<div class="aiz-carousel dots-inside-bottom mobile-img-auto-height position-relative top-banner removable-session d-lg-block d-none" data-key="top-banner" data-value="removed" data-arrows="false" data-dots="false" data-autoplay="true" data-infinite="true" style="background-color:#eeeeee">

    
     @php $slider_images_mobile = json_decode(get_setting('topbar_banner_content'), true);  @endphp
    @foreach ($slider_images_mobile as $key => $value)
        <div class="carousel-box item">
             <h6 class="fs-15 text-black text-center mb-0 p-2 Oceanwide"> {!! str_replace(' ', '&nbsp;', $slider_images_mobile[$key]) !!}</h6>
             </div>
    @endforeach
    
</div>
@endif
<style>
.mega-dropdown {
  position: static !important;
}
.mega-dropdown-menu {
    padding: 20px 0px;
    width: 100%;
    box-shadow: none;
    -webkit-box-shadow: none;
}
.mega-dropdown-menu > li > ul {
  padding: 0;
  margin: 0;
}
.mega-dropdown-menu > li > ul > li {f
  list-style: none;
}
.mega-dropdown-menu > li > ul > li > a {
  display: block;
  color: #222;
  padding: 3px 5px;
}
.mega-dropdown-menu > li ul > li > a:hover,
.mega-dropdown-menu > li ul > li > a:focus {
  text-decoration: none;
}
.mega-dropdown-menu .dropdown-header {
  font-size: 18px;
  color: #ff3546; 
  border:1px solid #000;
  padding: 5px 60px 5px 5px;
  line-height: 30px;
}

.carousel-control {
  width: 30px;
  height: 30px;
  top: -35px;

}
.left.carousel-control {
  right: 30px;
  left: inherit;
}
.carousel-control .glyphicon-chevron-left, 
.carousel-control .glyphicon-chevron-right {
  font-size: 12px;
  background-color: #fff;
  line-height: 30px;
  text-shadow: none;
  color: #333;
  border: 1px solid #ddd;
}
.vertical-scrollable>.row {
            position: absolute;
            top: 120px;
            bottom: 100px;
            left: 180px;
            width: 50%;
            overflow-y: scroll;
        }
		.scrollbar
{	
	
	overflow-y: scroll;
	margin-bottom: 25px;
}
.force-overflow
{
	min-height: 450px;
}
#style-3::-webkit-scrollbar-thumb {
  background-color: #000;
  border: 4px solid transparent;
  border-radius: 20px;
  background-clip: padding-box !important;  
}

#style-3::-webkit-scrollbar {
  width: 16px;
  background:#e4e4e4;
   border-top-right-radius: 20px;
   border-bottom-right-radius:20px;
}
#style-3
{
	height:350px; border-radius:20px;
                
                
}

ul.menus > li active a{
 text-transform:uppercase !important;
}

li.active > a
{
	background:#000;
	color:#fff;
}

.inactive{
	background:#fff;
	color:#000;
}
.flameU {
    width: 100%;
    height: 5px;
    position: absolute;  
}
</style>
<!-- Top Bar -->
<div class="top-navbar bg-white border-soft-secondary z-1035">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col">
                
            </div>

            <div class="col-5 text-right d-none d-lg-block">
                <ul class="list-inline mb-0">
				
					

                        <li class="list-inline-item mr-2">
                            <a href="https://www.seller.organysk.com" class="text-reset py-1 d-inline-block fs-15 fw-bold" style="font-family: 'Oceanwide';padding-top:0.5rem;padding-bottom:0.5rem;">Join as a Seller</a>
                        </li>
						@if ( get_setting('facebook_link') !=  null )
                    <li class="list-inline-item">
                        <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook fs-16"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                    </li>
                    @endif
                    @if ( get_setting('twitter_link') !=  null )
                    <li class="list-inline-item">
                        <a href="{{ get_setting('twitter_link') }}" target="_blank" class="twitter fs-16"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                    </li>
                    @endif
                    @if ( get_setting('instagram_link') !=  null )
                    <li class="list-inline-item">
                        <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram fs-16"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                    </li>
                    @endif
                   
                    @if ( get_setting('linkedin_link') !=  null )
                    <li class="list-inline-item">
                        <a href="{{ get_setting('linkedin_link') }}" target="_blank" class="linkedin fs-16"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
                    </li>
                    @endif
					 @if ( get_setting('youtube_link') !=  null )
                    <li class="list-inline-item">
                        <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube fs-16"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                    </li>
                    @endif
				</ul>
            </div>
        </div>
    </div>
</div>
<!-- END Top Bar -->
<header class="@if(get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 bg-white d-none d-lg-block">

    <div class="position-relative logo-bar-area">
        <div class="container">
            <div class="d-flex align-items-center">

                <div class="col-auto col-xl-2 pl-0 pr-3 d-flex align-items-center">
                    <a class="d-block mr-3 ml-0" href="{{ route('home') }}">
                        @php
                            $header_logo = get_setting('header_logo');
                        @endphp
                        @if($header_logo != null)
                            <img src="{{ uploaded_asset($header_logo) }}" data-gumlet="false" alt="{{ uploaded_alt($header_logo) }}" class="mw-100" width="155" height="75">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" data-gumlet="false" alt="{{ env('APP_NAME') }}" class="mw-100 " width="155" height="75">
                        @endif
                    </a>

                    @if(Route::currentRouteName() != 'home')
                        
                    @endif
                </div>
                <div class="d-lg-none ml-auto mr-0">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle" data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>
				<div class="col-lg-7  position-static">
                <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white">
                    <div class="position-relative flex-grow-1">
                        <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                            <div class="d-flex position-relative align-items-center">
                                <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                    <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                                </div>
								
								
                                <div class="input-group">
                                     <div class="input-group-append d-none d-lg-block">
                                    <button class="btn search-box button search-box1" id="search-box1" type="submit">
                                        <i class="la la-search la-flip-horizontal fs-18" id="search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" class="border-0 form-control search1" id="search1" name="q" placeholder="Search for products, brands and more" autocomplete="off">

                                   
                                </div>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute top-100 w-98" style="z-index:99;height: 430px;overflow-x: hidden;overflow-y: auto;left:16px">
                           
                            <div class="search-nothing d-none p-3 text-center fs-16">

                            </div>
                            <div id="search-content1" class="text-left" style="z-index:99">

                            </div>
                        </div>
                    </div>
                </div>
				</div>
				<div class="col-xl-3 d-none d-lg-block text-center" style="margin-left:-1%;margin-right:-2%">
				@auth
							  <div class="text-center clearfix d-none d-lg-block" >
					<div class="dropdown">
  <button class="dropbtn2"><h5 class="fs-16 mb-0">Welcome, <br>{{ Auth::user()->name }} <i class="fa fa-angle-down" aria-hidden="true"></i></h5></button>
	<div class="dropdown-content text-left">  
		<a href="{{ route('dashboard') }}">My Account</a>
		<a href="{{ route('orders.track') }}" target="_blank">{{__('Track Order')}}</a>
		<a href="{{ route('logout') }}">Logout</a>
		
						
  </div>
</div>
	</div>

					
					
					@else
					<div class="buttonSet d-flex w-100 clearfix d-none d-lg-block">
				
						<span class="buttonSet btn btn-outline-secondary-login text-center lu1pGp " data-toggle="modal" href="javascript:void(0)" onclick="openRegisterModal();">Login / Signup</span>
	 </div> 
					@endauth
				</div>
                <div class="d-none d-lg-none ml-3 mr-0">
                    <div class="nav-search-box">
                        <a href="#" class="nav-box-link">
                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                        </a>
                    </div>
                </div>
				 
				<div class="col-xl-0 p-0">
                <div class="d-none d-lg-block  align-self-stretch mr-0" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items">
                        @include('frontend.partials.cart')
                    </div>
                </div>
</div>
            </div>
        </div>
        
        
          
    </div>
	<div class="top-navbar bg-white z-1035 shadow-sm d-none d-lg-block" style="border-top:1px solid #cdcaca">
    <div class="container">
        <div class="row">
		
            <div class="col-lg-12 col pl-0 pr-0 text-center">
			 <ul class="menus list-inline d-flex justify-content-between justify-content-lg-start mb-0 clearfix d-none d-lg-block">
			      <li class="dropdown1 mega-dropdown fw-normal fs-14   orgenikbold " value="red">
 
			   <a href="#" class="Oceanwide dropdown-toggle dropdown-tog p-2 disabled" >SHOP BY ETHICS</a>
			  <div class="flameU"></div>		
			  <ul class="dropdown-menu mega-dropdown-menu scrollbar bg-gray-menu " id="style-3" style="margin-top:3px"> 
	  
		
<li class="col-sm-12 row force-overflow" > 


        <div class="col-sm-3 card bg-gray-menu shadow-none border-0 vertical-scrollable" >
            <ul class="list-unstyled">
               
                @foreach(\App\Models\Department::all() as $key => $s)
                    <li> 
					  
                        <a class="data-link grays fw-normal fs-13 text-center" href="{{ route('department.category', $s->slug) }}">{{ $s['name'] }}</a>
						
                    </li>
                @endforeach
            </ul>
        </div> 

</li>
				</ul>			   
			   </li>
			    <li class="dropdown1 mega-dropdown fw-normal fs-14   orgenikbold " value="red">
 
			   <a href="#" class="Oceanwide dropdown-toggle dropdown-tog p-2 disabled" >BRANDS</a>
			  <div class="flameU"></div>		
			  <ul class="dropdown-menu mega-dropdown-menu scrollbar bg-gray-menu " id="style-3" style="margin-top:3px"> 
	  
			  <h3 class="text-center bg-gray-menu Oceanwide fs-22">Brands</h3>
<li class="col-sm-12 row force-overflow" > 

 @foreach (\app\models\brand::where('alpha_order','!=','Others')->where('alpha_order','!=','')->where('slug','!=','')->where('status',1)->orderBy('alpha_order', 'ASC')->groupBy('alpha_order')->get() as $key => $brand)
        <div class="col-sm-3 card bg-gray-menu shadow-none border-0 vertical-scrollable" >
            <ul class="list-unstyled text-center ">
                <li>
                   <h5 class="text-center Oceanwide fs-16">Brands {{ ($brand->alpha_order) }}</h5>
                </li>
                @foreach(\app\models\brand::where('status',1)->orderBy('name', 'asc')->where('alpha_order','like','%' . $brand->alpha_order . '%')->get() as $key => $s)
                    <li> 
					  
                        <a class="data-link grays fw-normal fs-13 text-center" href="{{ route('products.brand', $s->slug) }}">{{ $s['name'] }}</a>
						
                    </li>
                @endforeach
            </ul>
        </div> 
    @endforeach
	 @foreach (\app\models\brand::where('alpha_order','=','Others')->where('alpha_order','!=','')->where('slug','!=','')->where('status',1)->orderBy('alpha_order', 'ASC')->groupBy('alpha_order')->get() as $key => $brand)
        <div class="col-sm-3 card shadow-none border-0 vertical-scrollable bg-gray-menu">
            <ul class="list-unstyled text-center bg-gray-menu">
                <li>
                   <h5 class="text-center bg-gray Oceanwide fs-16">{{ ($brand->alpha_order) }}</h5>
                </li>
                @foreach(\app\models\brand::where('status',1)->orderBy('name', 'asc')->where('alpha_order','like','%' . $brand->alpha_order . '%')->get() as $key => $s)
                    <li > 
					  
                        <a class="data-link grays fw-normal fs-13" href="{{ route('products.brand', $brand->slug) }}">{{ $s['name'] }}</a>
						
                    </li>
                @endforeach
            </ul>
        </div> 
    @endforeach
</li>
				</ul>			   
			   </li> 
			   
			   @foreach (\App\Models\Category::where('level', 0)->where('featured',1)->orderBy('name', 'asc')->get()->take(11) as $key => $category)
			   <li class="dropdown1 mega-dropdown fw-normal   fs-14 orgenikbold ">
 
			   <a href="{{ route('products.category', $category->slug) }}" class="Oceanwide dropdown-toggle dropdown-tog p-2 disabled">{{ strtoupper($category->name) }}</a>
			     <div class="flameU"></div>	
			  <ul class="dropdown-menu mega-dropdown-menu scrollbar bg-gray-menu" id="style-3" style=" margin-top:3px; height:350px;
                overflow: scroll
                overflow-y: auto;
               "> 
<li class="col-sm-12 row"> 
@foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
        <div class="col-sm-2 card shadow-none border-0 bg-gray-menu vertical-scrollable">
            <ul class="list-unstyled  ">
                <li style="border-bottom:1px solid #e0e0e0;">
                    <a class="data-link text-reset  fs-14 Oceanwide" href="{{ route('products.subcategory', \App\Models\Category::find($first_level_id)->slug) }}" >{{ strtoupper(\App\Models\Category::find($first_level_id)->name) }}</a>
                </li>
                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)
                    <li >
                        <a class="data-link grays fw-normal fs-13" href="{{ route('products.subsubcategory', \App\Models\Category::find($second_level_id)->slug) }}">{{ \App\Models\Category::find($second_level_id)->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div> 
    @endforeach
</li>
				</ul>			  
			   </li>
			    @endforeach
					<li class="dropdown mega-dropdown fw-normal fs-14">
                    <a href="{{route('customer_gift')}}" class="dropdown-toggle dropdown-tog p-2 disabled">                      
                        <span class="aiz-side-nav-text fw-normal Oceanwide" style="text-transform: uppercase">Gifts & Hampers</span>
                    </a>
                </li>
				<li class="dropdown mega-dropdown  fw-normal fs-14 d-none">
					 @foreach(\App\Models\FlashDeal::where('status', 1)->get() as $key => $category)		
                    <a href="{{ url('flash-deal/'.$category->slug) }}" class="dropdown-toggle dropdown-tog p-2 disabled">                        
                        <span class="aiz-side-nav-text fw-normal Oceanwide">OFFERS</span>
                    </a>
					 @endforeach	
					 @foreach(\App\Models\FlashDeal::where('status', 0)->get() as $key => $category)
							<a href="{{ url('flash-deal/'.$category->slug) }}" class="dropdown-toggle dropdown-tog p-2 disabled">
								<span class="aiz-side-nav-text fw-normal Oceanwide">OFFERS</span>
							</a>
                            @endforeach
                </li>
     
                   
                  
					
					

                   		</ul>
                <ul class="list-inline d-flex justify-content-between justify-content-lg-start mb-0">
                    @if(get_setting('show_language_switcher') == 'on')
                    <li class="list-inline-item dropdown mr-3" id="lang-change">
                        @php
                            if(Session::has('locale')){
                                $locale = Session::get('locale', Config::get('app.locale'));
                            }
                            else{
                                $locale = 'en';
                            }
                        @endphp
                        <a href="javascript:void(0)" class="dropdown-toggle text-reset py-2" data-toggle="dropdown" data-display="static">
                            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ static_asset('assets/img/flags/'.$locale.'.png') }}" class="mr-2 lazyload" alt="{{ \App\Models\Language::where('code', $locale)->first()->name }}" height="11">
                            <span class="opacity-60">{{ \App\Models\Language::where('code', $locale)->first()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            @foreach (\App\Models\Language::all() as $key => $language)
                                <li>
                                    <a href="javascript:void(0)" data-flag="{{ $language->code }}" class="dropdown-item @if($locale == $language) active @endif">
                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                                        <span class="language">{{ $language->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif

                    @if(get_setting('show_currency_switcher') == 'on')
                    <li class="list-inline-item dropdown" id="currency-change">
                        @php
                            if(Session::has('currency_code')){
                                $currency_code = Session::get('currency_code');
                            }
                            else{
                                $currency_code = \App\Models\Currency::findOrFail(\App\Models\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
                            }
                        @endphp
                        <a href="javascript:void(0)" class="dropdown-toggle text-reset py-2 opacity-60" data-toggle="dropdown" data-display="static">
                            {{ \App\Models\Currency::where('code', $currency_code)->first()->name }} {{ (\App\Models\Currency::where('code', $currency_code)->first()->symbol) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">
                            @foreach (\App\Models\Currency::where('status', 1)->get() as $key => $currency)
                                <li>
                                    <a class="dropdown-item @if($currency_code == $currency->code) active @endif" href="javascript:void(0)" data-currency="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>

          
        </div>
    </div>
</div>
<div class="hover-category-menu category-align ml-0 position-absolute w-100 top-100 left-0 right-0 d-none z-3" style="margin-top: -8px;"id="hover-category-menu">
            <div class="container">
                <div class="row gutters-10 position-relative">
                    <div class="col-lg-3 position-static">
                        @include('frontend.partials.category_menu')
                    </div>
                </div>
            </div>
        </div>
</header>
<script>
$(document).ready(function(){
    $(".dropdown1").hover(            
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(false,false).fadeIn(100);
			$(this).addClass('active');
			$(this).removeClass('inactive');
            //$(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).not('.in .dropdown-menu').stop(false,false).fadeOut(100);
			$(this).removeClass('active');
           $(this).addClass('inactive'); 
        }
    );
});

$('.data-link').click(function (e) {   
    e.stopPropagation()
    $('.menus').show();
});


$(document).click(function () {
     $('.dropdown-menu1', this).not('.in .dropdown-menu').stop(false,false).fadeOut();
});
</script>