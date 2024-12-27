@php
if(auth()->user() != null) {
    $user_id = Auth::user()->id;
    $carts = \App\Models\Cart::where('user_id', $user_id)->where('status',1)->get();
} else {
    $temp_user_id = Session()->get('temp_user_id');
    if($temp_user_id) {
        $carts = \App\Models\Cart::where('temp_user_id', $temp_user_id)->where('status',1)->get();
		
    }
}

@endphp

<button class="dropbtn2"> <a href="{{route('cart')}}" class="d-flex align-items-center text-reset h-100" data-display="static">
    <h5 class="mb-0"><i class="fa fa-shopping-cart d-inline-block nav-box-icon la-2x" style="font-size: 1.6rem !important;"></i></h5>
    <span class="flex-grow-1 ml-1">
       @if(isset($carts) && count($carts) > 0)
            <span class="badge badge-primary badge-inline badge-pill cartdetails">{{ count($carts)}}</span>
        @else
            <span class="badge badge-primary badge-inline badge-pill"></span>
        @endif
        <h5 class="nav-box-text d-none d-xl-block mb-0"></h5>
    </span>
</a></button>

