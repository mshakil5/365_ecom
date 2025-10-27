<footer class="footer-section section-top-gap-100 no-print">
    <div class="footer-top section-inner-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="footer-widget footer-widget-contact" data-aos="fade-up" data-aos-delay="0">
                        <div class="footer-logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('images/company/' . $company->company_logo) }}"
                                    alt="{{ $company->company_name }}" class="img-fluid"
                                    style="width: 150px; height: 50px; object-fit: contain; display: block;">
                            </a>
                        </div>
                        <div class="footer-contact">
                            <div class="customer-support">
                                <div class="customer-support-icon">
                                    <img src="{{ asset('frontend/images/icon/support-icon.png') }}" alt="">
                                </div>
                                <div class="customer-support-text">
                                    <span>Customer Support</span>
                                    <a class="customer-support-text-phone"
                                        href="tel:{{ $company->phone1 }}">{{ $company->phone1 }}</a>
                                </div>
                            </div>
                        </div>
                        <ul class="footer-social">
                            @if ($company->facebook)
                                <li>
                                    <a href="{{ $company->facebook }}" class="facebook" target="_blank"
                                        aria-label="Visit {{ $company->company_name }} on Facebook" title="Facebook">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                        <span class="sr-only">Visit {{ $company->company_name }} on Facebook</span>
                                    </a>
                                </li>
                            @endif
                            @if ($company->twitter)
                                <li>
                                    <a href="{{ $company->twitter }}" class="svg-round ebay" target="_blank"
                                        aria-label="Visit our eBay store" title="eBay">
                                        <img src="{{ asset('evay.png') }}" alt="eBay logo" class="img-fluid"
                                            style="max-height: 42px; object-fit: contain;">
                                        <span class="sr-only">Visit our eBay store</span>
                                    </a>
                                </li>
                            @endif
                            @if ($company->youtube)
                                <li><a href="{{ $company->youtube }}" class="youtube" target="_blank"><i
                                            class="fa fa-youtube"></i></a></li>
                            @endif
                            @if ($company->instagram)
                                <li><a href="{{ $company->instagram }}" class="instagram" target="_blank"><i
                                            class="fa fa-instagram"></i></a></li>
                            @endif
                        </ul>

                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="footer-widget" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="footer-widget-title">LINKS</h3>
                        <div class="footer-menu row">
                            <div class="col-6 col-sm-6">
                                <ul class="footer-menu-nav">
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('frontend.shop') }}">Shop</a></li>
                                    <li><a href="{{ route('cart.index') }}" class="cartBtn">Cart</a></li>
                                    <li><a href="{{ route('wishlist.index') }}" class="wishlistBtn">Wish List</a></li>
                                    @auth
                                        <li><a href="{{ route('orders.index') }}">Orders</a></li>
                                    @else
                                        <li><a href="{{ route('login') }}">Login</a></li>
                                    @endauth
                                </ul>
                            </div>
                            <div class="col-6 col-sm-6">
                                <ul class="footer-menu-nav">
                                    <li><a href="{{ route('privacy-policy') }}">Privacy</a></li>
                                    <li><a href="{{ route('terms-and-conditions') }}">Terms and conditions</a>
                                    </li>
                                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                                    <li><a href="{{ route('about-us') }}">About Us</a></li>
                                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 mb-4">
                    <div class="footer-widget footer-widget-menu" data-aos="fade-up" data-aos-delay="600">
                        <h3 class="footer-widget-title">ADDRESS</h3>
                        <div class="footer-menu">

                            <div class="contact-details-single-item">
                                <div class="contact-details-icon">
                                    <a href="https://www.google.com/maps/dir//Quatrone+-+Catering+Equipment+-+Manchester+Unit+7,+Orchard+sew+mill+Langley+Road+South,+Salford+M6+6SD/@53.4990438,-2.2898415,16z/data=!4m8!4m7!1m0!1m5!1m1!1s0x487bafcaa36da13d:0xbaa3261462266097!2m2!1d-2.2898415!2d53.4990438?entry=ttu&g_ep=EgoyMDI1MDIyNi4xIKXMDSoASAFQAw%3D%3D"
                                        target="blank" aria-label="View our address on Google Maps"
                                        title="Google Maps"><i class="fa fa-map-marker text-white"></i></a>
                                </div>
                                <div class="contact-details-content contact-phone">
                                    <span>{{ $company->address1 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="copyright-area">
                        {!! $company->copyright !!}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="footer-payment">
                        <img class="img-fluid" src="{{ asset('resources/frontend/images/payment-icon.png') }}"
                            alt="Payment gateways">
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<button class="material-scrolltop" type="button" aria-label="Scroll to top"></button>

<a href="https://wa.me/{{ $company->whatsapp }}" target="_blank" class="material-whatsapp" aria-label="WhatsApp">
    <i class="fa fa-whatsapp"></i>
</a>

<a href="{{ route('cart.index') }}" class="material-addtocart offcanvas-toggle cartBtn">
    <i class="fa fa-shopping-cart"></i>
    <span class="header-action-icon-item-count cartCount">0</span>
</a>

<script>
    document.addEventListener('scroll', function() {
        const addToCartButton = document.querySelector('.material-addtocart');
        const whatsappButton = document.querySelector('.material-whatsapp');
        if (window.scrollY > 100) {
            addToCartButton.style.display = 'flex';
            whatsappButton.style.display = 'flex';
        } else {
            addToCartButton.style.display = 'none';
            whatsappButton.style.display = 'none';
        }
    });
</script>

<style>
    .material-addtocart,
    .material-whatsapp {
        position: fixed;
        width: 55px;
        height: 55px;
        font-size: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease-in-out;
        cursor: pointer;
        z-index: 1000;
    }

    .material-addtocart {
        bottom: 83px;
        right: 27px;
        background-color: #ea1c26;
        color: white;
    }

    .material-addtocart:hover {
        background-color: #ff3b2f;
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
    }

    .material-whatsapp {
        bottom: 140px;
        right: 27px;
        background-color: #25D366;
        color: white;
    }

    .material-whatsapp:hover {
        background-color: #1ebe5d;
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
    }

    .svg-round {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 1px solid #000;
        background-color: white;
    }

    .map-icon {
        font-size: 22px;
        margin-right: 20px;
        color: #fff;
        background: #ea1c26;
        width: 60px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        border-radius: 50%
    }
</style>