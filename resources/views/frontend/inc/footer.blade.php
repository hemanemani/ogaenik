<section class="bg-black footer-section">

    <div class="container-fluid">
        
    	<div class="row product--details">
    	    
    	    <div class="col-md-2 col-lg-2 col-xl-2">
    	            
    	            
    	            <h5 class="footer-heading">Customer Support</h5>

                    <ul class="list-unstyled mb-0">
                      <li>
                        <a href="{{ route('contact') }}" class="footer-items">Contact Us</a>
                      </li>
                      <li>
                        <a href="{{ url('returnpolicy') }}" class="footer-items">Returns & Exchange</a>
                      </li>
                      <li>
                        <a href="{{ url('faq') }}" class="footer-items">FAQs</a>
                      </li>
                    </ul>
    	
    	    </div>
    	    
    	    <div class="col-md-2 col-lg-2 col-xl-2">
    	            
    	            <h5 class="footer-heading">Orgenik</h5>

                    <ul class="list-unstyled mb-0">
                      <li>
                        <a href="{{ url('aboutus') }}" class="footer-items">About Orgenik</a>
                      </li>
                      <li>
                        <a href="{{ url('why-organic') }}" class="footer-items">Why Shop with Orgenik</a>
                      </li>
                       <li>
                        <a href="{{ url('ethical-product-tags') }}" class="footer-items">Ethical Product Tags</a>
                      </li>
                    </ul>

    	    </div>
    	    
    	    <div class="col-md-2 col-lg-2 col-xl-2">
    	        
    	        
    	         <h5 class="footer-heading">Policies</h5>

                    <ul class="list-unstyled mb-0">
                      <li>
                        <a href="{{ route('terms') }}" class="footer-items">Terms of Use</a>
                      </li>
                      <li>
                        <a href="{{ route('privacypolicy') }}" class="footer-items">Privacy Policy</a>
                      </li>
                    </ul>

    	    </div>
    	    
    	    <div class="col-md-3 col-lg-3 col-xl-3">
    	            
    	            
    	            <h5 class="footer-heading">Helpful Information</h5>

                    <ul class="list-unstyled mb-0">
                      <li>
                        <a href="{{ url('payment-shipping') }}" class="footer-items">Payments & Shipping</a>
                      </li>
                      <li>
                        <a href="https://www.seller.orgenik.com/" class="footer-items">Join as a Seller</a>
                      </li>
                    </ul>

    	    </div>
    	    
    	    <div class="col-md-2 col-lg-2 col-xl-2">
    	        
    	         <h5 class="footer-heading">Social</h5>

                    <ul class="list-unstyled mb-0">
                      <li>
                        <a href="https://www.instagram.com/orgenikworld/" target="_blank" class="footer-items">Instagram</a>
                      </li>
                      <li>
                        <a href="https://www.facebook.com/orgenikworld" target="_blank" class="footer-items">Facebook</a>
                      </li>
                      <li>
                        <a href="https://twitter.com/orgenikworld" target="_blank" class="footer-items">Twitter</a>
                      </li>
                    </ul>

    	    </div>
    	    
                
    	</div>
    	
    	<div class="row product--details2">
        	    <div class="col-md-3 col-lg-3 col-xl-3">
    	            
    	            
    	            <h5 class="footer-heading">Need Help?</h5>
    
                    <ul class="list-unstyled mb-0">
                      <li>
                          <span class="footer-items">
                                <i class="fa fa-envelope fs-17 mr-2" aria-hidden="true"></i> 
                                <a href="mailto:{{ get_setting('contact_email') }}" class="text-reset">{{ get_setting('contact_email')  }}</a>
                            </span>
                            
                      </li>
                    </ul>
    	
    	        </div>
    	        
    	        <div class="col-md-8 col-lg-8 col-xl-8">
            
                        <ul class="payment--option">
        
        						<li class="mr-1 list-inline-item">
         
                                    <img loading="lazy" alt="master card" data-src="{{ my_asset_image('assets/svg/master.svg')}}" class="card--data" height="50">
        
                                </li>
        
        						<li class="mr-1 list-inline-item">
        
                                    <img loading="lazy" alt="visa card" data-src="{{ my_asset_image('assets/svg/visa.svg')}}" class="card--data" height="50">
        
                                </li>
        
        						<li class="mr-1 list-inline-item">
        
                                    <img loading="lazy" alt="rupay payment" data-src="{{ my_asset_image('assets/svg/rupay.svg')}}" class="card--data" height="50">
        
                                </li>
        
        						<li class="mr-1 list-inline-item">
        
                                    <img loading="lazy" alt="rupay payment" data-src="{{ my_asset_image('assets/svg/upi.svg')}}" class="card--data" height="50">
        
                                </li>

        							<li class="mr-1 list-inline-item">
        
                                    <img loading="lazy" alt="paytm" data-src="{{ my_asset_image('assets/svg/paytm.svg')}}" class="card--data" height="35">
        
                                </li>
        
        				</ul>
        
                    </div>
    	    
    	</div>

    
    
        <div class="row bg-black footer--data">

            <div class="col-lg-11 footer--text">

                <div class="text-center text-md-center">

				Copyright © {{ date("Y") }}

                    All rights reserved. Orgenik E-commerce Pvt. Ltd.

                </div>

            </div>

        </div>



</section>





<!-- FOOTER -->



@if (Auth::check() && !isAdmin())

    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">

        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>

        <div class="collapse-sidebar bg-white">

            @include('frontend.inc.user_side_nav')

        </div>

    </div>

@endif

