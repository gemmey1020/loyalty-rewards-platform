<!-- footer begin -->
<footer class="text-light pt-3">
    <div class="container pb-2">
        <div class="row g-3 align-items-center">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="widget">
                    <h5 class="mb-2">{{ __('footer.contact_title') }}</h5>
                    <address class="s1 mb-0">
                        <span><i class="id-color fa fa-phone fa-lg"></i><a href="tel:+96560010054">60010054</a></span>
                        <span><i class="id-color fa fa-whatsapp fa-lg"></i><a href="https://wa.me/96560010053" target="_blank">60010053</a></span>
                        <span><i class="id-color fa fa-envelope-o fa-lg"></i><a href="mailto:contact@example.invalid">contact@example.invalid</a></span>
                    </address>
                </div>
            </div>

            <!-- Social Network -->
            <div class="col-lg-4">
                <div class="widget">
                    <h5 class="mb-2">{{ __('footer.social_title') }}</h5>
                    <div class="social-icons">
                        <a href="https://www.instagram.com/posh_center_kw/"><i class="fa fa-instagram fa-lg"></i></a>
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <img class="img-fluid" src="{{ asset('template/images/Posh_Center-1-removebg-preview.png') }}"
                     alt="Logo" style="max-width: 180px; height: auto;">
            </div>
        </div>
    </div>

    <!-- Subfooter -->
    <div class="subfooter py-2">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    {{ __('footer.copyright') }}
                </div>
            </div>
        </div>
    </div>
</footer>
