@php
    $footerLogo = $setting->logo ?: $setting->logo_footer;
    $footerCompany = $setting->company ?: 'Entdecke Travel';
    $footerDesc = $setting->webname ?: $footerCompany;
    $footerPhone = trim((string) ($setting->phone1 ?? ''));
    $footerTel = preg_replace('/\s+/', '', $footerPhone);
    $footerPhone2 = trim((string) ($setting->phone2 ?? ''));
    $footerTel2 = preg_replace('/\s+/', '', $footerPhone2);
    $footerEmail = trim((string) ($setting->email ?? ''));
    $footerAddress = trim((string) ($setting->address1 ?? ''));
    $footerAddress2 = trim((string) ($setting->address2 ?? ''));
    $footerFacebook = trim((string) ($setting->fbPixel ?? ''));
    if ($footerFacebook !== '' && !preg_match('#^https?://#i', $footerFacebook)) {
        $footerFacebook = 'https://' . ltrim($footerFacebook, '/');
    }
    $footerDestinations = collect($categoryhome ?? [])->take(6);
    $footerYear = date('Y');
@endphp
 <!-- Footer Start -->
 <footer class="main-footer">
    <div class="sisf-page-footer-inner-area sisf-page-background sis-br-radius mb-3">
        <div class="sisf-page-footer-middle-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="footer-logo mb-4 pb-4" data-aos="fade-right" data-aos-delay="100">
                            <a href="{{ route('home') }}">
                                <img src="{{ url('' . $footerLogo) }}" alt="{{ $footerCompany }}">
                            </a>
                        </div>
                        <div class="sisf-e-text-bottom">
                            <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="300">
                                <p class="text-white">{{ $footerDesc }}</p>
                            </div>
                        </div>
                        @if($footerAddress !== '')
                        <div class="subscribe-newsletter-form" data-aos="fade-up" data-aos-delay="500">
                            <div class="form-group position-relative d-flex align-items-start gap-2 mb-0">
                                <span class="text-white pt-1"><i class="fa-solid fa-location-dot"></i></span>
                                <p class="text-white mb-0">{{ $footerAddress }}@if($footerAddress2 !== '')<br>{{ $footerAddress2 }}@endif</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6">
                        <!-- Links Start -->
                        <div class="footer-links page" data-aos="fade-up" data-aos-delay="100">
                            <h3>Quick Links</h3>
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('aboutUs') }}">About Us</a></li>
                                <li><a href="{{ route('destination') }}">Destinations</a></li>
                                <li><a href="{{ route('gallery') }}">Gallery</a></li>
                                <li><a href="{{ route('allListBlog') }}">Latest Blogs</a></li>
                                <li class="mb-0"><a href="{{ route('lienHe') }}">Contact Us</a></li>
                            </ul>
                        </div>
                        <!-- Links End -->
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <!-- Links Start -->
                        <div class="footer-links page" data-aos="fade-up" data-aos-delay="300">
                            <h3>Popular Destinations</h3>
                            <ul>
                                @forelse ($footerDestinations as $item)
                                    <li @if($loop->last) class="mb-0" @endif>
                                        <a href="{{ route('allListProCate', ['danhmuc' => $item->slug]) }}">{{ languageName($item->name) }}</a>
                                    </li>
                                @empty
                                    <li class="mb-0"><a href="{{ route('destination') }}">View all destinations</a></li>
                                @endforelse
                            </ul>
                        </div>
                        <!-- Links End -->
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <!-- Links Start -->
                        <div class="footer-links page mb-0" data-aos="fade-up" data-aos-delay="300">
                            <h3>Support</h3>
                            <ul>
                                <li><a href="{{ route('fags') }}">FAQs</a></li>
                                <li><a href="{{ route('feedback') }}">Feedback</a></li>
                                <li><a href="{{ route('bookATrrip') }}">Book a Trip</a></li>
                                @if($footerPhone !== '')
                                <li><a href="tel:{{ $footerTel }}">Hotline: {{ $footerPhone }}</a></li>
                                @endif
                                @if($footerEmail !== '')
                                <li class="mb-0"><a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a></li>
                                @endif
                            </ul>
                        </div>
                        <!-- Links End -->
                    </div>
                    <div class="col-12">
                        <div class="sisf-page-footer-middle-area-bottom">
                            <div class="row align-items-center">
                                <div class="col-xl-6 col-lg-4">
                                    @if($footerFacebook !== '')
                                    <!-- Links Start -->
                                    <div class="footer--links page d-flex align-items-center flex-wrap gap-3" data-aos="fade-right" data-aos-delay="100">
                                        <div class="sisf-e-social-title">
                                            <h3 class="text-white">Follow Us On Socials:</h3>
                                        </div>
                                        <div class="footer-social-icons-link page">
                                            <ul class="list-unstyled d-flex align-items-center gap-3 p-0 m-0">
                                                <li class="mb-0"><a href="{{ $footerFacebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Links End -->
                                    @endif
                                </div>
                                <div class="col-xl-6 col-lg-8">
                                    <div class="sisf-sis-contact-footer-information" data-aos="fade-left" data-aos-delay="100">
                                        <div class="sisf-sis-contact-information d-flex align-items-center justify-content-end gap-4 flex-wrap">
                                            @if($footerPhone !== '')
                                            <div class="sisf-contact-box d-flex align-items-center gap-3">
                                                <div class="sisf-icon">
                                                    <a href="tel:{{ $footerTel }}"><i class="fa-solid fa-phone-volume"></i></a>
                                                </div>
                                                <div class="sisf-sis-e-content">
                                                    <span class="sis-title text-white d-block">Call us on</span>
                                                    <a href="tel:{{ $footerTel }}" class="sis-title text-white d-block">{{ $footerPhone }}</a>
                                                    @if($footerPhone2 !== '')
                                                    <a href="tel:{{ $footerTel2 }}" class="sis-title text-white d-block">{{ $footerPhone2 }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                            @if($footerEmail !== '')
                                            <div class="sisf-contact-box d-flex align-items-center ps-4 gap-3">
                                                <div class="sisf-icon">
                                                    <a href="mailto:{{ $footerEmail }}"><i class="fa-regular fa-envelope"></i></a>
                                                </div>
                                                <div class="sisf-sis-e-content">
                                                    <span class="sis-title text-white d-block">Reach out</span>
                                                    <a href="mailto:{{ $footerEmail }}" class="sis-title text-white d-block">{{ $footerEmail }}</a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sisf-page-footer-bottom-area">
            <div class="container">
                <!-- Footer Copyright Section Start -->
                <div class="footer-copyright">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Footer Copyright Start -->
                            <div class="footer-copyright-text">
                                <p class="mb-0 text-white">&copy; {{ $footerYear }} {{ $footerCompany }}. All Rights Reserved.</p>
                            </div>
                            <!-- Footer Copyright End -->
                        </div>
                        <div class="col-lg-6">
                            <div class="footer-privacy-policy">
                                <ul class="list-unstyled d-flex align-items-center justify-content-end gap-4 p-0 m-0 flex-wrap">
                                    <li><a href="{{ route('pagecontent', ['slug' => 'huong-dan-thanh-toan']) }}" class="text-white">Payment Guide</a></li>
                                    <li><a href="{{ route('pagecontent', ['slug' => 'chinh-sach-bao-hanh']) }}" class="text-white">Warranty Policy</a></li>
                                    <li><a href="{{ route('lienHe') }}" class="text-white">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Footer Copyright Section End -->
            </div>
        </div>
    </div>
</footer>
<!-- Footer End -->
