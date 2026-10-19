@extends('layouts.main.master')
@section('title')
Contact
@endsection
@section('description')
Contact Us
@endsection
@section('image')
{{ url('' . $setting->logo) }}
@endsection
@section('css')
@endsection
@section('js')
<script>
    function refreshContactCaptcha() {
        var img = document.getElementById('contact-captcha-img');
        if (img) img.src = '{{ route('contactCaptcha') }}?t=' + Date.now();
    }
    @if(session('error'))
    document.addEventListener('DOMContentLoaded', refreshContactCaptcha);
    @endif
</script>
@endsection
@section('content')
@php
    $contactPhone = $setting->phone1 ?? '';
    $contactTel = preg_replace('/\s+/', '', $contactPhone);
    $contactEmail = $setting->email ?? '';
    $contactEmailAlt = $setting->fax ?? '';
    $contactAddress = $setting->address1 ?? '';
    $bannerImage = $setting->linkpopup ?: url('frontend/images/page-banner.jpg');
    $contactImage = file_exists(public_path('frontend/images/contact-img.png'))
        ? url('frontend/images/contact-img.png')
        : ($setting->logo_footer ?: url('frontend/images/page-banner.jpg'));
@endphp
<div class="sisf-banner sis-br-radius mt-3 position-relative">
    <div class="banner-img">
        <figure>
            <img src="{{ url('' . $bannerImage) }}" alt="{{ $setting->company }}">
        </figure>
    </div>
    <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
        <div class="sisf-m-inner container">
            <div class="sisf-breadcrumbs mb-2">
                <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}">
                    <span>Home</span>
                </a>
                <span class="sisf-breadcrumbs-separator text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                <span class="sisf-breadcrumbs-current text-white">Contact Us</span>
            </div>
            <div class="sisf-m-content sisf-content-grid">
                <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">Contact Us</h1>
            </div>
        </div>
    </div>
</div>

<div class="sisf-contact-us-section section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sisf-sis-section-title text-center sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">Contact Us Today</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3">Your <span class="sisf-e-colored">Dream Vacation</span> Begins with<br> a Conversation</h2>
                    <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                        <p>Whether you're planning an international getaway, a relaxing family vacation, or a once-in-a-lifetime adventure,<br> our travel experts are here to help.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="sisf-contact-left-part position-relative">
                    <div class="sisf-contact-left-image">
                        <figure class="sis-image-anime sis-reveal">
                            <img src="{{ $contactImage }}" class="w-100" alt="{{ $setting->company }}">
                        </figure>
                    </div>
                    <div class="sisf-contact-left-bottom" data-aos="zoom-in-left" data-aos-delay="500">
                        <div class="sisf-sis-contact-information">
                            @if($contactPhone !== '')
                            <div class="sisf-contact-box mb-3 pb-3 d-flex align-items-center gap-3">
                                <div class="sisf-icon">
                                    <a href="tel:{{ $contactTel }}"><i class="fa-solid fa-phone-volume"></i></a>
                                </div>
                                <div class="sisf-sis-e-content">
                                    <span class="sis-title text-white d-block">Call us on</span>
                                    <a href="tel:{{ $contactTel }}" class="sis-title text-white sis-comman-text d-block">{{ $contactPhone }}</a>
                                </div>
                            </div>
                            @endif
                            @if($contactEmail !== '')
                            <div class="sisf-contact-box mb-3 pb-3 d-flex align-items-center gap-3">
                                <div class="sisf-icon">
                                    <a href="mailto:{{ $contactEmail }}"><i class="fa-regular fa-envelope"></i></a>
                                </div>
                                <div class="sisf-sis-e-content">
                                    <span class="sis-title text-white d-block">Reach out</span>
                                    <a href="mailto:{{ $contactEmail }}" class="sis-title text-white sis-comman-text d-block">{{ $contactEmail }}</a>
                                    @if($contactEmailAlt !== '' && $contactEmailAlt !== $contactEmail)
                                    <a href="mailto:{{ $contactEmailAlt }}" class="sis-title text-white sis-comman-text d-block">{{ $contactEmailAlt }}</a>
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($contactAddress !== '')
                            <div class="sisf-contact-box d-flex align-items-center gap-3">
                                <div class="sisf-icon">
                                    <a href="#sis-contact-map"><i class="fa-solid fa-location-dot"></i></a>
                                </div>
                                <div class="sisf-sis-e-content">
                                    <span class="sis-title text-white d-block">Our Location</span>
                                    <a href="#sis-contact-map" class="sis-title text-white sis-comman-text d-block">{{ $contactAddress }}</a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="sis-contect-right bg-white p-4 sis-radius" data-aos="fade-up" data-aos-delay="100">
                    <div class="sisf-sis-section-title sis-section-title">
                        <h2 class="sisf-m-title sis-text-anime-style-3">Send a <span class="sisf-e-colored">Message</span></h2>
                        <div class="sisf-m-text">
                            <p class="mt-3">Have a question or need help planning your next trip? Fill out the contact form, and our travel experts will get back to you with personalized recommendations.</p>
                        </div>
                    </div>
                    <div class="form-section">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <form id="enquiryForm" class="p-0 m-0" action="{{ route('postcontact') }}" method="POST">
                            @csrf
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="sis-td-honeypot" aria-hidden="true">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-col">
                                            <input type="text" class="form-control mb-4" id="contact-name" name="name" placeholder="Your name" required maxlength="191" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-col">
                                            <input type="tel" class="form-control mb-4" id="contact-phone" name="phone" placeholder="Phone Number" required maxlength="30" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-col">
                                            <input type="email" class="form-control mb-4" id="contact-email" name="email" placeholder="Email Address" required maxlength="191" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-col position-relative">
                                            <textarea id="contact-message" class="form-control mb-4 rounded-3" name="mess" placeholder="Message" rows="6" required>{{ old('mess') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <div class="simple-captcha-box d-flex flex-wrap align-items-center gap-3 mb-2">
                                            <img id="contact-captcha-img" class="simple-captcha-image" src="{{ route('contactCaptcha') }}" alt="Captcha">
                                            <button type="button" class="simple-captcha-refresh" onclick="refreshContactCaptcha()">Refresh</button>
                                        </div>
                                        <input class="form-control" name="captcha_code" type="text" required maxlength="4" pattern="[0-9]{4}" inputmode="numeric" autocomplete="off" placeholder="Enter 4 digits in the image" value="{{ old('captcha_code') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="sisf-m-btn pt-3">
                                <button type="submit" class="sis-btn-default">Submit Message<i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($setting->iframe_map))
<div class="sis-contact-location-section pt-0 section" id="sis-contact-map">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <div class="sis-contact-map" data-aos="fade-up" data-aos-delay="300">
                    {!! $setting->iframe_map !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
