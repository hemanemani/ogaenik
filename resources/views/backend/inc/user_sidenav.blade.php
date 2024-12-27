<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap border-bottom">
            <a href="{{ route('home') }}" class="d-block text-left">
                @if(get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" data-gumlet="false" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" data-gumlet="false" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap bg-white">           
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
			  
                    
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
			@auth
				<li class="aiz-side-nav-item border-bottom ">
                  <h3 class="aiz-side-nav-link" > {{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }} </h3>
                </li>
				@else
				 <li class="aiz-side-nav-item border-bottom">
                   <span class="aiz-side-nav-link" onclick="openRegisterModal();">{{__('Sign In | Registration')}}</span>
                </li>
				 @endauth
                <li class="aiz-side-nav-item ">
                    <a href="{{ route('home') }}" class="aiz-side-nav-link">                      
                        <span class="aiz-side-nav-text ">{{translate('Home')}}</span>
                    </a>
                </li>
				<li class="aiz-side-nav-item">
                    <a href="{{ route('categories.all') }}" class="aiz-side-nav-link">                      
                        <span class="aiz-side-nav-text ">{{translate('Categories')}}</span>
                    </a>
                </li>
				
				<li class="aiz-side-nav-item d-none">
					 @foreach(\App\Models\FlashDeal::where('status', 0)->get() as $key => $category)		
                    <a href="{{ url('flash-deal/'.$category->slug) }}" class="aiz-side-nav-link">                        
                        <span class="aiz-side-nav-text ">{{translate('Offers')}}</span>
                    </a>
					 @endforeach	
                </li>
				
				
				@if (Auth::check())
					<li class="aiz-side-nav-item">
                    <a href="{{ route('dashboard') }}" class="aiz-side-nav-link">                      
                        <span class="aiz-side-nav-text Oceanwide fs-14">DASHBOARD</span>
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
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
