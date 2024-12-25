<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if(get_setting('system_logo_white') != null)
                    <img src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @else
                    <img src="{{ asset('assets/img/logo.svg') }}" class="brand-icon" alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-white border-0 form-control-sm text-white" type="text" name="" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">

                {{-- Dashboard --}}
                <li class="aiz-side-nav-item">
                    <a href="{{route('admin.dashboard')}}" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="_3d6902ec768df53cd9e274ca8a57e401" data-name="3d6902ec768df53cd9e274ca8a57e401" d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z" transform="translate(-2 -2)" fill="#575b6a" fill-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Dashboard')}}</span>
                    </a>
                </li>


                <!-- POS Addon-->
                @if (addon_is_activated('pos_system') && (auth()->user()->can('pos_manager') || auth()->user()->can('pos_configuration')))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13.79" height="16" viewBox="0 0 13.79 16">
                                    <g id="_371925cdd3f531725a9fa8f3ebf8fe9e" data-name="371925cdd3f531725a9fa8f3ebf8fe9e" transform="translate(-2.26 0)">
                                      <path id="Path_40673" data-name="Path 40673" d="M10.69,7H3.26a1.025,1.025,0,0,0-1,1V18.45a1.03,1.03,0,0,0,1,1.05h7.43a1.03,1.03,0,0,0,1.03-1.03V8A1.025,1.025,0,0,0,10.69,7ZM4.94,17.86H3.995v-.95H4.94Zm0-2.355H3.995v-.95H4.94Zm0-2.355H3.995V12.2H4.94Zm2.5,4.71H6.5v-.95h.955Zm0-2.355H6.5v-.95h.955Zm0-2.355H6.5V12.2h.955Zm2.5,4.71H8.99v-.95h.95Zm0-2.355H8.99v-.95h.95Zm0-2.355H8.99V12.2h.95Zm.325-3a.17.17,0,0,1-.165.17H3.835a.17.17,0,0,1-.165-.17V8.795a.165.165,0,0,1,.165-.165H10.13a.165.165,0,0,1,.165.165Zm5.09-1.45H15.13v9.09h.25a.67.67,0,0,0,.67-.67V9.375a.67.67,0,0,0-.695-.675Z" transform="translate(0 -3.5)" fill="#4e5767"/>
                                      <rect id="Rectangle_20842" data-name="Rectangle 20842" width="1.465" height="9.095" transform="translate(12.185 5.2)" fill="#4e5767"/>
                                      <rect id="Rectangle_20843" data-name="Rectangle 20843" width="0.63" height="9.095" transform="translate(14.06 5.2)" fill="#4e5767"/>
                                      <path id="Path_40674" data-name="Path 40674" d="M13.895.895a.89.89,0,0,0-.26-.635A.91.91,0,0,0,13,0a.895.895,0,0,0-.91.895v.53h1.79Zm-2.2,0a.76.76,0,0,1,0-.145.68.68,0,0,1,0-.1h.01A.5.5,0,0,1,11.755.5.43.43,0,0,1,11.79.4a1.2,1.2,0,0,1,.145-.26.5.5,0,0,1,.04-.055L12.045,0H7.995A.815.815,0,0,0,7.18.81V3.03h4.5Z" transform="translate(-2.46)" fill="#4e5767"/>
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('POS System')}}</span>
                            @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001" class="mx-2">
                                    <path id="Union_49" data-name="Union 49" d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z" transform="translate(19337 -3329)" fill="#f51350"/>
                                </svg>
                            @endif
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{route('poin-of-sales.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.index', 'poin-of-sales.create'])}}">
                                    <span class="aiz-side-nav-text">{{translate('POS Manager')}}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{route('poin-of-sales.activation')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('POS Configuration')}}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Product -->
                
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="13.714" viewBox="0 0 16 13.714">
                                <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -4)">
                                    <path id="Path_40719" data-name="Path 40719" d="M17.429,4H2.571A.571.571,0,0,0,2,4.571V8a.571.571,0,0,0,.571.571h.571v8.571a.571.571,0,0,0,.571.571H16.286a.571.571,0,0,0,.571-.571V8.571h.571A.571.571,0,0,0,18,8V4.571A.571.571,0,0,0,17.429,4ZM15.714,16.571H4.286v-8H15.714Zm1.143-9.143H3.143V5.143H16.857Z" fill="#575b6a"/>
                                    <path id="Path_40720" data-name="Path 40720" d="M12.571,15.143H16A.571.571,0,0,0,16,14H12.571a.571.571,0,0,0,0,1.143Z" transform="translate(-4.286 -4.286)" fill="#575b6a"/>
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Products')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                       
                        <li class="aiz-side-nav-item">
                            <a href="{{route('products.admin')}}" class="aiz-side-nav-link {{ areActiveRoutes(['products.admin', 'products.admin.edit']) }}" >
                                <span class="aiz-side-nav-text">{{ translate('In House Products') }}</span>
                            </a>
                        </li>
                  

                        <li class="aiz-side-nav-item">
                            <a href="{{route('product-description.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['product-description.index'])}}" >
                                <span class="aiz-side-nav-text">{{translate('Product Description')}} </span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('product.notifylist')}}" class="aiz-side-nav-link {{ areActiveRoutes(['product.notifylist'])}}" >
                                <span class="aiz-side-nav-text">{{translate('Stock Notifications')}} </span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('products.temporary_delete_product')}}" class="aiz-side-nav-link {{ areActiveRoutes(['products.temporary_delete_product'])}}" >
                                <span class="aiz-side-nav-text">{{translate('InActive Products')}}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('products.product_list_admin')}}" class="aiz-side-nav-link {{ areActiveRoutes(['products.product_list_admin'])}}" >
                                <span class="aiz-side-nav-text">{{translate('Products Listing ')}}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('products.editor')}}" class="aiz-side-nav-link {{ areActiveRoutes(['products.editor']) }}" >
                                <span class="aiz-side-nav-text">{{ translate('Editor') }}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('giftproducts.admin')}}" class="aiz-side-nav-link {{ areActiveRoutes(['giftproducts.admin', 'giftproducts.create', 'giftproducts.edit']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Gift Products') }}</span>
                            </a> 
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('origins.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['origins.index', 'origins.create', 'origins.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Origin')}}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('departments.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['departments.index', 'departments.create', 'departments.edit'])}}" >
                                <span class="aiz-side-nav-text">{{translate('Department')}}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('specials.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['specials.index', 'specials.create', 'specials.edit'])}}" >
                                <span class="aiz-side-nav-text">{{translate('On Special')}}</span>
                            </a>
                        </li>

                        <li class="aiz-side-nav-item">
                            <a href="{{route('brands.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}" >
                                <span class="aiz-side-nav-text">{{translate('Brand')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('attributes.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index','attributes.create','attributes.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Attribute')}}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{route('reviews.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Product Reviews')}}</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Sales -->
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-money-bill aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{translate('Sales')}} </span> 
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            

                            @if(Auth::user()->user_type == 'admin' || in_array('4', json_decode(Auth::user()->staff->role->permissions)))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('inhouse_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['inhouse_orders.index', 'inhouse_orders.show'])}}" >
                                        <span class="aiz-side-nav-text">{{translate('Active orders')}} </span> 
                                    </a>
                                </li>
                                
                            @endif
                        </ul>
                    </li>
                     

                
            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->