<!DOCTYPE html>
@if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>  
	<meta name="title" content ="@yield('meta_title', get_setting('website_name').' - '.get_setting('site_motto'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
   

    <title>@yield('meta_title', get_setting('website_name').' - '.get_setting('site_motto'))</title>
    

    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description') )" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords') )">
<link rel="manifest" href="/manifest.json">
    @yield('meta')

    @if(!isset($detailedProduct) && !isset($shop) && !isset($page))
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ config('app.name', 'Laravel') }}">
    <meta itemprop="description" content="{{ get_setting('meta_description') }}">
    <meta itemprop="image" content="{{ uploaded_asset(get_setting('meta_image')) }}">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1"/>
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ config('app.name', 'Laravel') }}">
    <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset(get_setting('meta_image')) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ uploaded_asset(get_setting('meta_image')) }}" />
    <meta property="og:description" content="{{ get_setting('meta_description') }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" href="{{ uploaded_asset(get_setting('site_icon')) }}">

   <script>
  document.write('<link rel="stylesheet" type="text/css" href="{{ static_asset("assets/css/vendors.css?v=").time() }}">');
  document.write('<link rel="stylesheet" type="text/css" href="{{ static_asset("assets/css/aiz-core.css?v=").time() }}">');
  document.write('<link rel="stylesheet" type="text/css" href="{{ static_asset("assets/css/custom-style.css?v=").time() }}">');
  document.write('<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">');
  document.write('<link rel="stylesheet" type="text/css" href="{{ static_asset("assets/css/footer-style.css?v=").time() }}">');
	</script>	
    @yield('content_script')
   
    
    <script>
        var AIZ = AIZ || {};
    </script>
    
     <script type="text/javascript">
        window.GUMLET_CONFIG = {
            hosts: [{
                current: "orgenik.com",
                gumlet: "org-img.gumlet.io"
            }],
            lazy_load: true,
auto_quality:false,
auto_webp: true
        };
        (function(){d=document;s=d.createElement("script");s.src="https://cdn.jsdelivr.net/npm/gumlet.js@2.1/dist/gumlet.min.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();
    </script>
    


	
	<script>if ($(window).width() < 600) { $('meta[name=viewport]').attr('content','initial-scale=0.54, maximum-scale=0.54, user-scalable=no'); }</script>
	
    <style>
        body{
            font-family: OrgenikLight;
            font-weight: 400;

        }
        :root{
            --primary: {{ get_setting('base_color', '#e62d04') }};
            --hov-primary: {{ get_setting('base_hov_color', '#187B22') }};
            --soft-primary: {{ hex2rgba(get_setting('base_color','#e62d04'),.15) }};
        }
		.error
		{
			color:red !important;
			font-size:15px;
		}	


.loginpassword {
  padding-right: 35px;
   font-family : fantasy;
}

.custom-wrapper {
  position: relative;
}	

.input-grid {
    position: relative;
    display: grid;
    grid-template-columns: auto;
}

.input-icon {
    position: absolute;
    right: 35px;
    top: 13px;
    cursor:pointer;
}

    </style>


<script async src="https://www.googletagmanager.com/gtag/js?id=UA-176149824-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-176149824-1');
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "WebSite",
  "name": "Orgenik",
  "url": "https://www.orgenik.com",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://www.orgenik.com/search?q{search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
</head>
<body>

    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column">
<div class="aiz-main-wrapper ">
<div class="d-block d-lg-none">
        @include('backend.inc.user_sidenav')
		</div>		
	
            @include('backend.inc.user_nav')
		
			<div class="aiz-main-content">
			 
			@include('frontend.inc.nav')
					@yield('content')

				 @include('frontend.inc.footer')
				
			</div><!-- .aiz-main-content -->
		
	</div><!-- .aiz-main-wrapper d-none d-lg-block  --> 
        <!-- Header -->
       

    </div>

    @if (get_setting('show_cookies_agreement') == 'on')
        <div class="aiz-cookie-alert shadow-xl">
            <div class="p-3 bg-gray">
			 <div class="row">
               <div class="col-12 col-sm-8">
			    <div class="text-white mb-3 color-white">
				<span class="color-white">{!! get_setting('cookies_agreement_text') !!}</span>
				
                </div>
			   </div>
			   <div class="col-12 col-sm-4">
                <button class="btn btn-primary aiz-cookie-accepet float-right">
                    {{ translate('Ok. I Understood') }}
                </button>
				</div>
				</div>
            </div>
        </div>
    @endif


    @include('frontend.partials.modal')

    <div class="modal fade" id="addToCart">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="c-preloader text-center p-3">
                    <i class="las la-spinner la-spin la-3x"></i>
                </div>
                <button type="button" class="close absolute-top-right btn-icon close z-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="la-2x">&times;</span>
                </button>
                <div id="addToCart-modal-body">

                </div>
            </div>
        </div>
    </div>
    <div class="modal hide" id="GuestCheckout" role="dialog">
    
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">       
        <div class="">
                
            <div class="box">
                <div class="text-left loginBox" id="createaccounts">
                <div class="text-center pt-4">
                                    <h1 class="h1 heading Oceanwide fs-24">
                                       CREATE NEW ACCOUNT
                                    </h1>
                                </div>

                       
                            <div class="card-body modal-body">
                                <form method="POST" action="{{ route('register') }}" id="createaccount">
                                    @csrf
                                <div class="row">
                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label class="Oceanwide fs-14">First Name</label>
									<input type="text" name="email_data" value="" style="display:none;">
                                        <input id="firstname" type="text" class="form-control border-radius-5px" id="firstname" name="name" value="{{ old('name') }}" >
                                         <input class="txtBox" type="hidden" name="login_types"/>
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div> 
                                   
                                    <div class="form-group col-12 col-sm-6 col-md-6 col-lg-6">
                                    <label class="Oceanwide fs-14">Last Name</label>
                                        <input type="text" id="lastname" class="form-control border-radius-5px" id="lastname" name="lastname" value="{{ old('lastname') }}"  >

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-12">
                                    <label class="Oceanwide fs-14">Mobile Number</label>
                                        <input  type="tel"  autocomplete="off" maxlength="10" onkeypress="return onlyNumberKey(event)" style="border-radius:5px;font-size:15px" class="form-control padding-lefts" name="phone" id="tel" value="{{ old('phone') }}" >
                                         <span class="placeholderAlternative mobileNumber phonenumber" >+91<span style="padding: 0px 10px; position: relative; bottom: 1px;">|</span><span class="mobileNumberPlacholder"></span> </span>@if ($errors->has('phone'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('phone') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-12">
                                    <label class="Oceanwide fs-14">Email ID</label>
                                        <input id="email" type="email" class="form-control" style="border-radius:5px;font-size:15px" name="email" value="{{ old('email') }}">

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif 
                                    </div>
                                    <div class="form-group col-12">
                                    <label class="Oceanwide fs-14">Password</label>
                                    
                                        <div class="input-grid">
                                        <input autocomplete="off" id="password" type="password" class="form-control txtInputpw border-radius-5px loginpassword" name="password">
                                         <i class="fa fa-eye-slash input-icon eye" id="eye"></i>
    	                                </div>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif  
                                    </div>
                                    <div class="form-group col-12">
                                    <label class="Oceanwide fs-14">Confirm Password</label>
                                        <div class="input-grid">
                                        <input autocomplete="off" id="confirm_password" type="password" class="border-radius-5px txtInputcpw form-control loginpassword" name="password_confirmation">
                                         <i class="fa fa-eye-slash input-icon eye" id="eye"></i>
    	                                </div>
                                        
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="checkbox pad-btm text-left col-12 mb-3" style="display:flex;align-items:center;">

                                        <input name="checkbox_example_1" id="checkboxExample_1a" class="magic-checkbox" type="checkbox" >
                                        <label for="demo-form-checkbox" style="vertical-align:text-bottom">I agree with the <a href="https://www.orgenik.com/terms" target="_blank" class="Oceanwide" style="font-size:13px">Terms of Use </a> & <a href="https://orgenik.com/privacypolicy" target="_blank" class="Oceanwide" style="font-size:13px">Privacy Policy</a></label>
                                    </div>
									<div class="col-12 col-sm-12 col-md-12 col-lg-12 p-2 text-center">
                                    <button type="submit" id="Hensgiwkg2" class="border-radius-10px submit btn bg-black text-white text-center text-center pl-5 pr-5 fs-16">
                                       Create Account <span id="createloading"></span>
                                    </button>
                                    </div>
                                    <div class="col-10 col-sm-11 col-md-11 col-lg-11 p-2 text-center">
									<span class="float-left close fs-13" data-dismiss="modal" style="line-height:1.7">x Close</span>
                                    <a href="javascript: showRegisterForm();" style="text-align:center;cursor: pointer;color:#7F7F7F">Already a Member? <span class="Oceanwide fs-13 text-black">Log in</b></a>
                                    </div>
                                   
                                    
                                    </div>
                                </form>
                               
                            </div>
							
                        </div>
                </div>
            </div>
            <div class="box">
                <div class="registerBox" style="display:none;">
                                <div class="text-center pt-4">
                                    <h1 class="h1 heading Oceanwide fs-24" style="font-size:28px !Important;">
                                       LOGIN
                                    </h1>
                                </div>

                                <div class="px-4 py-3 py-lg-4" style="padding-top:1.3rem !Important">
                                    <div class="">
                                        <form class="form-default" role="form" action="{{ route('cart.login.submit') }}" id="loginboxs" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label class="Oceanwide fs-14">Email</label>
                                                @if (\App\Models\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Models\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                                    <input type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email Or Phone')}}" name="email" id="email" style="border-radius:5px;">
                                                 <input class="txtBox" type="hidden" name="login_types"/>
												 @else
                                                    <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" id="loginemail" name="email" style="border-radius:5px;">
                                                 <input class="txtBox" type="hidden" name="login_types"/>
												 @endif
                                                @if (\App\Models\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Models\Addon::where('unique_identifier', 'otp_system')->first()->activated)
                                                    <span class="opacity-60">{{  translate('Use country code before number') }}</span>
                                                @endif  
                                            </div>

                                            <div class="form-group custom-wrapper">
                                                <label class="Oceanwide fs-14">Password</label>
                                                
    												<a href="{{ route('password.request') }}" class="text-black text-reset float-right fs-12" style="text-decoration: underline;">{{ translate('Forgot password?')}}</a>
                                                    <div class="input-grid">
                                                    <input type="password" class="txtInput form-control {{ $errors->has('password') ? 'is-invalid' : '' }} loginpassword"  style="border-radius:5px;" name="password" id="loginpassword">
    												<i class="fa fa-eye-slash input-icon eye" id="eye"></i>
                                                </div>
                                            </div>
                                          

                                            <div class="mb-3 text-center" style="margin-top:1.8rem !Important">
                                          
                                                <button type="submit" class="border-radius-10px login_submit btn bg-black text-white text-center pl-5 pr-5 fs-16">Sign In</button>
                                            </div>
                                        </form>
                                       
                                    </div>
                                    <div class="text-center login-footer">
                                        <p class="text-muted mb-0">{{ translate('Dont have an account?')}}</p>
                                        <a href="javascript: showRegisterForm();">{{ translate('Register Now')}}</a>
                                    </div>
                                    <div class="forgot register-footer text-center" style="display:none">

                            

                             <a href="javascript: showLoginForm();" style="text-align:center;cursor: pointer;color:#7F7F7F">New to Orgenik? <span class="Oceanwide fs-13 text-black">Join Now</b></a>

                        </div>
                                </div>
                            </div>
                </div>
            </div>   
      
    </div>
    </div>
    <div class="modal fade" id="new-address-modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="">
                <h6 class="modal-title text-center mt-4 Oceanwide fs-24" id="exampleModalLabel">ADDRESS DETAILS</h6>
              
            </div>
            <form class="form-default" role="form"  action="{{ route('addresses.store') }}" method="POST">
                @csrf
				
                <div class="modal-body">
                  
                    <div class="row">
                    <div class="col-md-6 mb-3">
                               <label class="Oceanwide fs-14">{{ translate('First Name')}}</label>
                                <input type="text" class="form-control  rounded"  name="firstname" required>
								<input type="text" name="avoid_spam" value="" style="display:none;">
							</div>
                            
                            <div class="col-md-6 mb-3">
                               <label class="Oceanwide fs-14">{{ translate('Last Name')}}</label>
                                <input type="text" class="form-control rounded"  name="order_lastname" required>
                            </div>  
                    </div>
                        <div class="row">                            
                            <div class="col-md-12 mb-3">
                            <label class="Oceanwide fs-14">Full Address</label>
                                <textarea class="form-control rounded textarea-autogrow textarea-scrollbar" rows="3" name="address" required></textarea>
                            </div>
                        </div>
                        <div class="row"> 
                       
                        <div class="col-md-6 mb-3">
                               <label class="Oceanwide fs-14">Mobile Number</label>
                                <input type="text" class="form-control rounded padding-lefts" onkeypress="return onlyNumberKey(event)" maxlength="10"  name="phone" value="{{ auth()->check() ? auth()->user()->phone : '' }}"  required>
                            <span class="placeholderAlternative mobileNumber phonenumber" >+91<span style="padding: 0px 10px; position: relative; bottom: 1px;">|</span><span class="mobileNumberPlacholder"></span> </span></div>
                            <div class="col-md-6 mb-3">
                               <label class="Oceanwide fs-14">Email ID</label>
                                <input type="email" class="form-control rounded" name="email" value="{{ auth()->check() ? auth()->user()->email : '' }}"  readonly>
                            </div>                        
                            <div class="col-md-6 mb-3">
                            <label class="Oceanwide fs-14">{{ translate('Country')}}</label>
                            <input type="text" class="form-control rounded"  name="country" value="India" readonly>
                               
                            </div>
							 <div class="col-md-6 mb-3">
                            <label class="Oceanwide fs-14">{{ translate('State')}}</label>
							 <select class="form-control text-center form-control-sm rounded aiz-selectpicker" name="state" required>
                                                <option value="">{{__('Select Your State')}}</option>
                                                @foreach (\App\Models\Origin::orderBy('name', 'asc')->get() as $key => $category)
                                                <option value="{{ __($category->name) }}">{{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                            
                               
                            </div>
                            <div class="col-md-6 mb-3">
                            <label class="Oceanwide fs-14">{{ translate('City')}}</label>
							<select class="form-control  text-center  form-control-sm rounded aiz-selectpicker" name="city" required>
                                                <option value="">{{__('Select Your City')}}</option>
                                                @foreach (\App\Models\City::where('top', 1)->orderBy('name', 'asc')->get() as $key => $category)
                                                <option value="{{ __($category->name) }}">{{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                </div>
                            <div class="col-md-6 mb-3">
                            <label class="Oceanwide fs-14">{{ translate('Postal code')}}</label>
                                <input type="number"  class="rounded form-control" onKeyPress="if(this.value.length==6) return false;"  name="postal_code" value="" required>
                            </div>
							<div class="col-md-11 text-center">
							<span class="float-left close fs-13" data-dismiss="modal" style="line-height:2">x Close</span>
                             <button type="submit" class="rounded btn btn-primary pl-5 pr-5 submit-address">{{  translate('Save') }}</button>
							 </div>
                        </div>
                        
                      
                       
                    
                </div>
               
            </form>
        </div>
    </div>
</div>  


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="text-center pt-3">
	   <img src="https://www.orgenik.com/public/assets/img/contact-us.png" class="text-center w-150px">
        <h4 class="modal-title text-center">Contact us and we will get back to you</h4>       
      </div>
       
     
      
     
    </div>
  </div>
</div>

    @yield('modal')

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js') }}"></script>
	 <script src="{{ static_asset('assets/js/lozad.min.js') }}"></script>
	  <!--<script src="{{ static_asset('assets/js/network-status.js') }}"></script>-->
	 
	        @if (session('status'))
        <script>
                AIZ.plugins.notify('success', '{{ session('status') }}');
        </script>
     @endif
   
   
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script>
	function onlyNumberKey(evt) {
              
            // Only ASCII character in that range allowed
            var ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }
		$(document).ready(function(){ 
				$(".buttonSet").click(function () {
					$(".txtBox").val(["0"]); 
				});
				$(".buttonbuynow").click(function () {
					$(".txtBox").val(["1"]);
				});
         });
        $(document).ready(function() {
            $('.category-nav-element').each(function(i, el) {
                $(el).on('mouseover', function(){
                    if(!$(el).find('.sub-cat-menu').hasClass('loaded')){
                        $.post('{{ route('category.elements') }}', {_token: AIZ.data.csrf, id:$(el).data('id')}, function(data){
                            $(el).find('.sub-cat-menu').addClass('loaded').html(data);
                        });
                    }
                });
            });
            if ($('#lang-change').length > 0) {
                $('#lang-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var locale = $this.data('flag');
                        $.post('{{ route('language.change') }}',{_token: AIZ.data.csrf, locale:locale}, function(data){
                            location.reload();
                        });

                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change .dropdown-menu a').each(function() {
                    $(this).on('click', function(e){
                        e.preventDefault();
                        var $this = $(this);
                        var currency_code = $this.data('currency');
                        $.post('{{ route('currency.change') }}',{_token: AIZ.data.csrf, currency_code:currency_code}, function(data){
                            location.reload();
                        });

                    });
                });
            }
        });

        $('#search').on('keyup', function(){
            search();
        });
		 $('#search1').on('keyup', function(){
            search1();
        });

        $('#search').on('focus', function(){
            search1();
        });
		 $('#search1').on('focus', function(){
            search();
        });
		
		function search1(){
            var searchKey = $('#search1').val();
			var categoryKey = $('#resizing_select').val();
			
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey,category:categoryKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content1').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content1').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: AIZ.data.csrf, search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }

        function updateNavCart(){
            $.post('{{ route('cart.nav_cart') }}', {_token: AIZ.data.csrf }, function(data){
                $('#cart_items').html(data);
            });
        }
         function removeFromCart(key){
            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf, 
                id      :  key 
            }, function(data){
				console.log(data);
                updateNavCart();
                $('.cart-summary').html(data);
				$('#cart-summary_desktop').html(data.view);
				$('#payments').html(data);
                AIZ.plugins.notify('success', 'Item has been removed from cart');
                $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
            });
        }
		function removeFromCarts(key){

$('.removes').prop('disabled', true);

        $.post('{{ route('cart.removeFromCarts') }}', {_token:'{{ csrf_token() }}', id:key }, function(data){
		if(data.status == 1)
		{
			 updateNavCart();
					$('.cart-summary').html(data.view);
					$('#cart-summary_desktop').html(data.view);
					$('#payments').html(data.view);
					$('#page-content').html(data.view);
					AIZ.plugins.notify('success', 'Item has been removed from cart');
					$('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);					
					return false;
		}
		else
		{
			$('#page-content-return-value').html(data.view);
			updateNavCart();
            $('.cart-summary').html(data.view);
			$('#cart-summary_desktop').html(data.view);
			$('#payments').html(data.view);			
            AIZ.plugins.notify('success', 'Item has been removed from cart');
            $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);			
			return false;
		}    
        });

    }
       
        function removeFromCartsdfs(key){
			$('.removes').prop('disabled', true);
			
            $.post('{{ route('cart.removeFromCarts') }}', {
				_token: AIZ.data.csrf, key:key}, function(data){
				if(data.status == 1)
{
	 updateNavCart();

            $('.cart-summary').html(data.view);
$('#cart-summary_desktop').html(data.view);
			$('#payments').html(data.view);

			//$('#payment_details').html(data);	
 
			$('.c-preloader').show();			

			$('#page-content').html(data.view);
			
           AIZ.plugins.notify('success', 'Item has been removed from cart');

            $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
			
			return false;
}
else
{
	$('#page-content-return-value').html(data.view);
	 updateNavCart();

            $('.cart-summary').html(data.view);
			$('#cart-summary_desktop').html(data.view);
			$('#payments').html(data.view);

			//$('#payment_details').html(data);	
 
			$('.c-preloader').show();			

			//$('#page-content').html(data.view);
			
            
AIZ.plugins.notify('success', 'Item has been removed from cart');
            $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);
			
			return false;
}

               /* updateNavCart();
                $('#cart-summary').html(data);
			    $('#payments').html(data);
                console.log(data);
                AIZ.plugins.notify('success', 'Item has been removed from cart');
                
                $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())-1);*/
            });
        }
        
        
		function submitOrder_new(el)
		{
			 $(el).prop('disabled', true);
			 if($('#agree_checkbox').is(":checked"))
			 {
				 $("#payment_select").prop('disabled', true);
               // $('#checkout-form').submit();
					$.ajax({
                   type:"POST",
                   url: '{{ route('payment.checkout') }}',
                   data: $('#checkout-form').serializeArray(),
                   success: function(data){
					   if (data) {
            // data.redirect contains the string URL to redirect to
            window.location.href = data;
        }
		
					   // $('#payment-gateway-page').html(data);
						//$('#payment_gateway').hide();
				   }
					});
			 }else{
                AIZ.plugins.notify('danger','{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
			 
		}
        function submitOrder(el){
			var payment_options = $('input[name=payment_option]:checked', '#checkout-form').val()
			
            $(el).prop('disabled', true);
			
            if($('#agree_checkbox').is(":checked")){
				$("#payment_select").prop('disabled', true);
				$("#loaders").html("<i class='fa fa-spinner fa-spin' style='margin-left: 7px;'></i>");
				if(payment_options == 'Online')
				{
					$.ajax({
                   type:"POST",
                   url: '{{ route('payment.checkout') }}',				   
                   data: $('#checkout-form').serializeArray(),
                   success: function(data){	
					    $('#payment-gateway-page').html(data);
						$('.payment_gateway_online').hide();
				   }
					});
				}
				else if(payment_options == 'cash_on_delivery')
				{
					$('#checkout-form').submit();
				}
                
            }else{
                AIZ.plugins.notify('danger','{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
        }
        

        function showAddToCartModal(id){
            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.post('{{ route('cart.showCartModal') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data);
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            });
        }

        $('#option-choice-form input').on('change', function(){
			//alert('test');
            getVariantPrice();
        });

        function getVariantPrice(){
			$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
           if(checkAddToCartValidity()){
                $.ajax({
                   type:"POST",
                   url: '{{ route('products.variant_price') }}',
                   data: $('#option-choice-form').serializeArray(),
                   success: function(data){
                    //alert(data.price);
					   $('.product-gallery-thumb .carousel-box').each(function (i,val) {						   
                            if($(this).data('variation') && data.variation == $(this).data('variation')){
								$('.product-gallery-thumb').slick('slickGoTo', i);                                
                            }
                        })
                       $('#option-choice-form #chosen_price_div').removeClass('d-none');
                       $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                       $('#actual-quantity').html(data.actual_price); 
					   $('#discount-price').html(data.price);  					   
                       $('#available-quantity').html(data.quantity);
                       $('.input-number').prop('max', data.quantity);
					   
						
					   var limit = 11;
                       console.log(data);
						if(data.actual_price != data.price)
						{
							 $('.discount-price-variant').show();
						}
						else  
						{
							 $('.discount-price-variant').hide();
						}
                       if(parseInt(data.quantity) < 1 && data.digital  == 0){
						 
                           $('.buy-now').hide();
                           $('.add-to-cart').hide();
						   $('.outofstock').show();
						   $('.show-qty').hide();
						   $('.myDivIdos').html('Out Of Stock').show();  
						   $('.myDivIdis').html('In Stock').hide();
							
                       }
					   else if(parseInt(data.quantity) < 1 && data.digital  == 2){
						   var s = ''; 					
						   for (var i = 1; i <= data.quantity; i++) { 
							if(i < limit) 
							{
							   s += '<option value="' + i + '" >' + i + '</option>'; 		 
							}
						   }  
						   $(".departmentsDropdown").html(s);						   
						   $('.show-qty').show();
                           $('.buy-now').show();
                           $('.add-to-cart').show();
						   $('.outofstock').hide();
						   $('.myDivIdos').html('Out Of Stock').show(); 
							$('.myDivIdis').html('In Stock').hide();
							
                       }
                       else{						   
						   var s = ''; 					
						   for (var i = 1; i <= data.quantity; i++) { 
							if(i < limit) 
							{
							   s += '<option value="' + i + '" >' + i + '</option>'; 		 
							}
						   }  
						   $(".departmentsDropdown").html(s);						   
						   $('.show-qty').show();
                           $('.buy-now').show();
                           $('.add-to-cart').show();
						   $('.outofstock').hide();
						   $('.myDivIdos').html('Out Of Stock').hide(); 
							$('.myDivIdis').html('In Stock').show();
                       }
                   }
               });
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() { // find unique names
                  names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                  count++;
            });

            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }

            return false;
        }

        function addToCart(){
            if(checkAddToCartValidity()) {
               // $('#addToCart').modal();
                //$('.c-preloader').show();
                $.ajax({
                   type:"POST",
                   url: '{{ route('cart.addToCart') }}',
                   data: $('#option-choice-form').serializeArray(),
                   success: function(data){
                      // $('#addToCart-modal-body').html(null);
                      // $('.c-preloader').hide();
                       $('#modal-size').removeClass('modal-lg');
                       $('#addToCart-modal-body').html(data.view);
                       AIZ.plugins.notify('success', 'Successfully Added product in cart');
                       updateNavCart();
                       $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
                   }
               });
               return false;
            }
            else{
                AIZ.plugins.notify('warning', 'Please choose all the options');
            }
        }
	function home_carts(id)
    {
		var ids = $(id).attr('data-id');
		var id = $(id).val();
      if(checkAddToCartValidity()) 

	  {
            $.ajax({
               type:"POST",
               url: '{{ route('cart.addToCart') }}',
               data: $('#option-choice-form').serializeArray(),
               success: function(data){				   
			   if(ids == 2)  
				   { 
					   $('#addToCart-modal-body').html(null);
					   $('.c-preloader').hide();
					   $('#modal-size').removeClass('modal-lg');
					   $('#addToCart-modal-body').html(data);
					   $('#cart').hide();
					   updateNavCart();
					   openRegisterModal();
					   //showFrontendAlert('success', 'Product has been Added in your cart');
					   $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);  					   
				   }
				   else
				   {					   
					    $('#addToCart-modal-body').html(null);
					   $('.c-preloader').hide();
					   $('#modal-size').removeClass('modal-lg');
					   $('#addToCart-modal-body').html(data);
					   $('#cart').hide();
					   updateNavCart();
					   window.location.replace("{{ route('checkout.shipping_info') }}");
					  // showFrontendAlert('success', 'Product has been Added in your cart');
					   $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
				   }
               }
           });
        }
        else{
			AIZ.plugins.notify('warning', 'Please choose all the options');   
        }

    }
        function buyNow(){
            if(checkAddToCartValidity()) {
                $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                   type:"POST",
                   url: '{{ route('cart.addToCart') }}',
                   data: $('#option-choice-form').serializeArray(),
                   success: function(data){
                       if(data.status == 1){
                            updateNavCart();
                            $('#cart_items_sidenav').html(parseInt($('#cart_items_sidenav').html())+1);
                            window.location.replace("{{ route('cart') }}");
                       }
                       else{
                            $('#addToCart-modal-body').html(null);
                            $('.c-preloader').hide();
                            $('#modal-size').removeClass('modal-lg');
                            $('#addToCart-modal-body').html(data.view);
                       }
                   }
               });
            }
            else{
                AIZ.plugins.notify('warning', 'Please choose all the options');
            }
        }
       
        function show_purchase_history_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('purchase_history.details') }}', { _token : AIZ.data.csrf, order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
            });
        }

        function show_order_details(order_id)
        {
            $('#order-details-modal-body').html(null);

            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', { _token : AIZ.data.csrf, order_id : order_id}, function(data){
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
            });
        }
      
        function cartQuantityInitialize(){
          
            $('.btn-number').click(function(e) {
                e.preventDefault();
               
                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
                
            });

            $('.input-number').focusin(function() {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function() {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());
				alert(valueCurrent);
                name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the minimum value was reached');
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the maximum value was reached');
                    $(this).val($(this).data('oldValue'));
                }


            });
            $(".input-number").keydown(function(e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }

         function imageInputInitialize(){
             $('.custom-input-file').each(function() {
                 var $input = $(this),
                     $label = $input.next('label'),
                     labelVal = $label.html();

                 $input.on('change', function(e) {
                     var fileName = '';

                     if (this.files && this.files.length > 1)
                         fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
                      else if (e.target.value)
                         fileName = e.target.value.split('\\').pop();
 
                     if (fileName)
                         $label.find('span').html(fileName);
                     else
                         $label.html(labelVal);
                 });

                 // Firefox bug fix
                 $input
                     .on('focus', function() {
                         $input.addClass('has-focus');
                     })
                     .on('blur', function() {
                         $input.removeClass('has-focus');
                     });
             });
         }

function showRegisterForm()
{
$('.loginBox').fadeOut('fast',function(){
    $('.registerBox').fadeIn('fast');
    $('.login-footer').fadeOut('fast',function(){
        $('.register-footer').fadeIn('fast');
    });
    $('.modal-title').html('Register with');
}); 

$('.error').removeClass('alert alert-danger').html('');

$('label.error').remove();
}

function showLoginForm()
{
$('#GuestCheckout .registerBox').fadeOut('fast',function(){
    $('.loginBox').fadeIn('fast');
    $('.register-footer').fadeOut('fast',function(){
        $('.login-footer').fadeIn('fast');    
    });
    $('.modal-title').html('Login with');
});       

 $('.error').removeClass('alert alert-danger').html(''); 
}

function openRegisterModal()
{
    showRegisterForm();
    setTimeout(function(){
        $('#GuestCheckout').modal('show'); 
    }, 230);
}

function openLoginModal()
{
    showLoginForm();
    setTimeout(function()
    {
        $('#GuestCheckout').modal('show');   
    }, 230);
}

    </script>

<script type="text/javascript">	
	$("#createaccount").validate({ 
   
				rules: {
					name: {
					lettersonly: true,
					minlength: 2,
					maxlength: 20,
					required : true,
					},
					
					
					lastname:{
					lettersonly: true,
					minlength: 2,
					maxlength: 20,
					required : true,
					},
					phone: {
					required: true,
		  number: true,
		  minlength: 10,
		  maxlength: 10,
					remote: {
                                url: '{{ route('auth.verifyphone') }}',
                                type: "get",
                                data: {phone: $("input[phone='phone']").val(), _token: $('input[name=_token]').val()},
                                dataFilter: function (data) {
                                    var json = JSON.parse(data);
									console.log(data);
                                    if (json.msg == "true") {
                                        return "\"" + "Phone Number already in use" + "\"";
                                    } else {
                                        return 'true';
                                    }
                                }
                            }
							
				
					},
					password: {
						required: true,
						minlength: 5
					},
					password_confirmation: {
						required: true,
						minlength: 5,
						equalTo: "#password"
					},
					email: {
						required: true,
						email: true,
						remote: {
							url: '{{url("create_varifyemail")}}',
                                //url: '{{url("varifyemail")}}',
                                type: "get",
                                data: {phone: $("input[email='email']").val(), _token: $('input[name=_token]').val()},
                                dataFilter: function (data) {
                                    var json = JSON.parse(data);
									console.log(data);
                                    if (json.msg == "true") {
                                        return "\"" + "This Email ID already Exist" + "\"";
                                    }
								    else if (json.msg == "getvalue") {
                                        return "\"" + "System Terminated your account" + "\"";
                                    } 
									else {
                                        return 'true';
                                    }
                                }
                            }
					},
					checkbox_example_1: "required"
				},
				messages: { 
				hiddencode: "Please click here",
					name: "Please enter your Firstname",
					middlename: "Please enter your Middlename",
					lastname: "Please enter your Lastname",	
					phone: {
						required:"Please enter your Mobile Number",
						remote: "Mobile Number id already registred",
						minlength: "Please enter a valid Mobile number",
						minlength: "Please enter a valid Mobile number",
					},						
					password: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long"
					},
					password_confirmation: {
						required: "Please provide a password",
						minlength: "Your password must be at least 5 characters long",
						equalTo: "Please enter the same password as above"
					},
					email: "Please enter a valid email address",
					checkbox_example_1: "Please accept our policy"
				},
				submitHandler: function(form) { // <- pass 'form' argument in
            $(".submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        },				
   
	});

	$("#new_address").validate({ 
   
				rules: {
					firstname: {
					lettersonly: true,
					minlength: 2,
					maxlength: 20,
					required : true,
					},					
					order_lastname:{
					lettersonly: true,
					minlength: 2,
					maxlength: 20,
					required : true,
					},
					phone: {
					required: true,
					  number: true,
					  minlength: 10,
					  maxlength: 10
					},
					email: {
						required: true,
						email: true,
					},
					address: {
						required: true,						
					},
					postal_code: {
						maxlength: 6,
						required: true					
					},
					state: {
						required: true,						
					},
					city: {
						required: true,						
					},
					
				},
				messages: { 				
					firstname: "Please enter your Firstname",
					order_lastname: "Please enter your Lastname",	
					phone: {
						required:"Please enter your Mobile Number",						
						minlength: "Please enter a valid Mobile number",
						maxlength: "Please enter a valid Mobile number",
					},
					email: "Please enter a valid email address",
					address: "Please enter a valid email address",
					postal_code: {
						required: "Please enter your Postal Code",
						maxlength: "Please enter your Postal Code",
					},
					state: "Please Select Dropdown List",
					city: "Please Select Dropdown List",
					
					
				},
				submitHandler: function(form) { // <- pass 'form' argument in
            $(".submit-address").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        },				
   
	});	

		jQuery.validator.addMethod("lettersonly", function(value, element) 
		{
			return this.optional(element) || /^[a-z]+$/i.test(value);
		}, "Letters only please"); 
	</script>
    <script type="text/javascript">	
	
	function show_purchase_history_details(order_id)

    {

        $('#order-details-modal-body').html(null);



        if(!$('#modal-size').hasClass('modal-lg')){

            $('#modal-size').addClass('modal-lg');

        }



        $.post('{{ route('purchase_history.details') }}', { _token : '{{ @csrf_token() }}', order_id : order_id}, function(data){

            $('#order-details-modal-body').html(data);

            $('#order_details').modal();

            $('.c-preloader').hide();

        });

    }
	
	$("#loginboxs").validate({     
				
				rules: {	   	 			
					    
					password: {  
						required: true,						
						remote: {
                                url: '{{url("varifypassword")}}',
                                type: "get",
								
                                data: {email: function() {
            return $('#loginemail').val();
         }, _token: $('input[name=_token]').val()},
                                dataFilter: function (data) {
                                    var json = JSON.parse(data);
									console.log(data);
                                    if (json.msg == "true") {
                                         return 'true';
                                    } else {
										return "\"" + "The password is incorrect. Try again" + "\"";
                                       
                                    }
                                }
                            }			
					},
					
					email: {
						required: true,
						email: true,
						remote: {
                                url: '{{url("varifyemail")}}',
                                type: "get",
                                data: {phone: $("input[email='email']").val(), _token: $('input[name=_token]').val()},
                                dataFilter: function (data) {
                                    var json = JSON.parse(data);
									console.log(data);
                                    if (json.msg == "true") {
                                         return 'true';
                                    } else {
										return "\"" + "This Email ID is not valid" + "\"";
                                       
                                    }
                                } 
                            }
					},
					
				},
				messages: { 
						
					password: {
						required: "Please provide a password",						
					},					
					email: "Please enter a valid email address",
					
				},				
				submitHandler: function(form) { // <- pass 'form' argument in
            $(".login_submit").attr("disabled", true);
            form.submit(); // <- use 'form' argument here.
        },
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `invalid-feedback` class to the error element
					error.addClass( "invalid-feedback" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.next( "label" ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
				}
});

	</script>
    @yield('script')
    <script type="text/javascript"> 
    function add_new_address(){
        $('#new-address-modal').modal('show');
    }
    function already_requested(){
        $('#already-requested-modal').modal('show');
    }
</script>
<script>

var $contents = $('.tab-content');
$contents.slice(1).hide();
$('.tab').click(function() {
  var $target = $('#' + this.id + 'show').show();
  $contents.not($target).hide();
});
</script>
<script> 
$('.valid123').on('input', function(){
    var filteredValue = this.value.replace('+91 ', '').match(/\d*/g).join('');
    $(this).val(filteredValue
      .replace(/(\d{0,3})\-?(\d{0,3})\-?(\d{0,4}).*/,'$1$2$3')
      .replace(/\-+$/, '')
      .replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'+91 $1$2$3'))
});
function goBack() {
  window.history.back();
}
$('#search_item1').on("keyup keypress", function(e) {
    var code = e.keyCode || e.which; 
    if (code === 13) {               
        e.preventDefault();
        return false;
    }
});
</script>
<script>
$(document).ready(function(){
  $('#search-box1').prop('disabled',true);
    $('#search1').keyup(function(){
        $('#search-box1').prop('disabled', this.value === "");     
    })
});
</script>
<script>
$(document).ready(function(){
  $('#search-box').prop('disabled',true);
    $('#search').keyup(function(){
        $('#search-box').prop('disabled', this.value === "");     
    })
});
</script>
<script>

navigator.serviceWorker.register('/sw.js', { scope: '/' })

        .then(function (registration)

        {

         // console.log('Service worker registered successfully');

        }).catch(function (e)

        {

         // console.error('Error during service worker registration:', e);

        });
// 	$('.txtInput').focus(function () {
//         if ($('.txtInput').val() == "") {
//              $('.txtInput').css('font-size', '18px');
//          }
//         //  else {
//         //      $('.txtInput').css('font-size', '24px');
//         //  }
//      });

//      $('.txtInput').blur(function () {
//          if ($('.txtInput').val() == "") {
//              $('.txtInput').css('font-size', '18px');
//          }
//         //  else {
//         //      $('.txtInput').css('font-size', '24px');
//         //  }
//      }); 
	 
// 	 $('.txtInputpw').focus(function () {
//         if ($('.txtInputpw').val() == "") {
//               $('.txtInputpw').css('font-size', '18px');
//               $('.txtInputpw').css('font-family', 'fantasy');
//          }
//         //  else {
//         //      $('.txtInputpw').css('font-size', '24px');
//         //  }
//      });

//      $('.txtInputpw').blur(function () {
//          if ($('.txtInputpw').val() == "") {
//              $('.txtInputpw').css('font-size', '18px');
//               $('.txtInputpw').css('font-family', 'fantasy');
//          }
//         //  else {
//         //      $('.txtInputpw').css('font-size', '24px');
//         //  }
//      }); 
// 	  $('.txtInputcpw').focus(function () {
//         if ($('.txtInputcpw').val() == "") {
//             $('.txtInputcpw').css('font-size', '18px');
//               $('.txtInputcpw').css('font-family', 'fantasy');
//          }
//         //  else {
//         //      $('.txtInputcpw').css('font-size', '24px');
//         //  }
//      });

//      $('.txtInputcpw').blur(function () {
//          if ($('.txtInputcpw').val() == "") {
//             $('.txtInputcpw').css('font-size', '18px');
//               $('.txtInputcpw').css('font-family', 'fantasy');
//          }
//         //  else {
//         //      $('.txtInputcpw').css('font-size', '24px');
//         //  }
//      }); 
	 

// function CheckModification(){
//          if ($('.txtInput').val() == "") {
//              $('.txtInput').css('font-size', '18px');
//          }
//         //  else {
//         //      $('.txtInput').css('font-size', '24px');
//         //  }
//      }
// 	 function CheckPasswordModification(){
//          if ($('.txtInputpw').val() == "") {
//              $('.txtInputpw').css('font-size', '18px');
//              $('.txtInputpw').css('font-family', 'fantasy');
//          }
//         //  else {
//         //      $('.txtInputpw').css('font-size', '24px');
//         //  }
//      }
// 	  function CheckConformPasswordModification(){
//          if ($('.txtInputcpw').val() == "") {
//               $('.txtInputcpw').css('font-size', '18px');
//              $('.txtInputcpw').css('font-family', 'fantasy');
//          }
//         //  else {
//         //      $('.txtInputcpw').css('font-size', '24px');
//         //  }
//      }
// 	  $(function(){
  
//   $('#eye').click(function(){
       
//         if($(this).hasClass('fa-eye-slash')){
           
//           $(this).removeClass('fa-eye-slash');
          
//           $(this).addClass('fa-eye');
//           $('.txtInput').css('font-size', '15px');
//           $('.txtInput').css('font-family', 'inherit');
//           $('#loginpassword').attr('type','text');
            
//         }else{
         
//           $(this).removeClass('fa-eye');
          
//           $(this).addClass('fa-eye-slash');  
//           $('.txtInput').css('font-size', '18px');
//          $('.txtInput').css('font-family', 'fantasy');
//           $('#loginpassword').attr('type','password');
//         }
//     });
//  });

	$(document).ready(function() {
    function applyFontStyles() {
        $('.loginpassword').each(function() {
            if ($(this).attr('type') === 'text') {
                $(this).css({
                    'font-size': '15px',
                    'font-family': 'inherit'
                });
            } else {
                $(this).css({
                    'font-size': '18px',
                    'font-family': 'fantasy'
                });
            }
        });
    }

    $('.eye').click(function() {
        var passwordField = $(this).siblings('.loginpassword');
        if ($(this).hasClass('fa-eye-slash')) {
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
            passwordField.attr('type', 'text');
        } else {
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
            passwordField.attr('type', 'password');
        }
        applyFontStyles();
    });

    applyFontStyles();
});


		</script>
</body>
</html>
