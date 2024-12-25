<div class="aiz-topbar px-15px px-lg-25px d-flex align-items-stretch justify-content-between">
    <div class="d-flex justify-content-between align-items-stretch flex-grow-1">
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <!-- Browse Website -->
             <div class="d-md-flex justify-content-around align-items-center align-items-stretch">
                <div class="aiz-topbar-item">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-icon btn-circle btn-light" href="{{ route('home')}}" target="_blank" title="{{ translate('Browse Website') }}">
                            <i class="las la-globe"></i>
                        </a>
                    </div>
                </div>
            </div>
          
            <!-- Clear Cache -->
           <div class="d-flex justify-content-around align-items-center align-items-stretch ml-3">
                <div class="aiz-topbar-item">
                    <div class="d-flex align-items-center">
                        <a class="btn btn-soft-danger btn-sm d-flex align-items-center" href="{{ route('cache.clear')}}">
                            <i class="las la-hdd fs-20" style="margin-top: 0;"></i>
                            <span class="fw-500 ml-1 mr-0 d-md-block">{{ translate('Clear Cache') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-around align-items-center align-items-stretch">
            <!-- Notifications -->
            @php
                $orders = DB::table('orders')
                            ->orderBy('code', 'desc')
                            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                            ->where('order_details.seller_id', \App\Models\User::where('user_type', 'admin')->first()->id)
                            ->where('orders.viewed', 0)
                            ->select('orders.id')
                            ->distinct()
                            ->count();
                $sellers = \App\Models\Seller::where('verification_status', 0)->where('verification_info', '!=', null)->count();
            @endphp

            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-icon p-1">
                            <span class=" position-relative d-inline-block">
                                <i class="las la-bell la-2x"></i>
                                @if($orders > 0 || $sellers > 0)
                                    <span class="badge badge-dot badge-circle badge-primary position-absolute absolute-top-right"></span>
                                @endif
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-lg py-0">
                        <div class="p-3 bg-light border-bottom">
                            <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                        </div>
                        <ul class="list-group c-scrollbar-light overflow-auto" style="max-height:300px;">

                            @if($orders > 0)
                            <li class="list-group-item">
                                <a href="{{ route('inhouse_orders.index') }}" class="text-reset">
                                    <span class="ml-2">{{ $orders }} {{translate('new orders')}}</span>
                                </a>
                            </li>
                            @endif
                            @if($sellers > 0)
                            <li class="list-group-item">
                                <a href="{{ route('sellers.index') }}" class="text-reset">
                                    <span class="ml-2">{{translate('New verification request(s)')}}</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- language -->
            @php
                if (Session::has('locale')) {
                    $locale = Session::get('locale', Config::get('app.locale'));
                } else {
                    $locale = env('DEFAULT_LANGUAGE');
                }
            @endphp
            <div class="aiz-topbar-item mr-3">
                <div class="align-items-stretch d-flex dropdown" id="lang-change">
                     <a class="dropdown-toggle no-arrow" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="btn btn-icon">
                            <img src="{{ asset('assets/img/flags/'.$locale.'.png') }}" height="11">
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-xs">

                        @foreach (\App\Models\Language::where('status', 1)->get() as $key => $language)
                            <li>
                                <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                    class="dropdown-item @if ($locale == $language->code) active @endif">
                                    <img src="{{ asset('assets/img/flags/' . $language->code . '.png') }}"
                                        class="mr-2">
                                    <span class="language">{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- User -->
            <div class="aiz-topbar-item ml-2">
                <div class="align-items-stretch d-flex dropdown">
                    <a class="dropdown-toggle no-arrow text-dark" data-toggle="dropdown" href="javascript:void(0);" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <span class="avatar avatar-sm mr-md-2">
                                <img
                                    src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/avatar-place.png') }}';"
                                >
                            </span>
                            <span class="d-none d-md-block">
                                <span class="d-block fw-500">{{Auth::user()->name}}</span>
                                <span class="d-block small opacity-60">{{Auth::user()->user_type}}</span>
                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-menu-md">
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="las la-user-circle"></i>
                            <span>{{translate('Profile')}}</span>
                        </a>

                        <a href="{{ route('logout')}}" class="dropdown-item">
                            <i class="las la-sign-out-alt"></i>
                            <span>{{translate('Logout')}}</span>
                        </a>
                    </div>
                </div>
            </div><!-- .aiz-topbar-item -->
        </div>
    </div>
</div><!-- .aiz-topbar -->
