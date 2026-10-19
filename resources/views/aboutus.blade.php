@extends('layouts.main.master')
@section('title')
About {{ $setting->company }}
@endsection
@section('description')
About {{ $setting->company }}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
@php
    $bannerImage = $setting->logo_footer ?? (isset($banner[0]->image) ? $banner[0]->image : '');
    $aboutTitle = optional($gioithieu)->title ?: $setting->company;
    $aboutBody = optional($gioithieu)->content ?: (optional($gioithieu)->description ?? '');
    $aboutImages = [];
    $rawAboutImage = optional($gioithieu)->image;
    if (is_array($rawAboutImage)) {
        $aboutImages = $rawAboutImage;
    } elseif (is_string($rawAboutImage) && $rawAboutImage !== '') {
        $decodedAboutImage = json_decode($rawAboutImage, true);
        $aboutImages = is_array($decodedAboutImage) ? $decodedAboutImage : [$rawAboutImage];
    }
    $aboutImages = array_values(array_filter($aboutImages));
    $aboutImageSmall = $aboutImages[0] ?? null;
    $aboutImageRight = $aboutImages[1] ?? ($aboutImages[0] ?? null);
@endphp
<div class="page-content sis-about-page">
    <div class="sisf-banner position-relative sis-product-list-banner"@if(!empty($bannerImage)) style="background-image: url({{ url('' . $bannerImage) }});"@endif>
        <div class="sisf-page-title">
            <div class="container">
                <div class="sisf-m-inner">
                    <h1 class="sisf-m-title text-white">About Us</h1>
                    <div class="sisf-breadcrumbs mt-3">
                        <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}"><span>Home</span></a>
                        <span class="text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                        <span class="text-white">About Us</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="sis-about-story sis-page-background section">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-12">
                    <div class="sisf-sis-section-title sis-section-title">
                        <span class="sisf-m-subtitle">Our Story</span>
                        <h2 class="sisf-m-title">{{ $aboutTitle }}</h2>
                        <div class="sisf-m-text sis-about-story-body">
                            {!! $aboutBody !!}
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($impressiveNumbers) && count($impressiveNumbers) > 0)
            <div class="sisf-sis-page-counters sis-about-counters">
                <div class="row">
                    @foreach ($impressiveNumbers as $item)
                    <div class="col-lg-3 col-md-6">
                        <div class="sis-counter-item d-flex align-items-center gap-4">
                            @if(!empty($item->image))
                            <div class="sis-e-counter-image">
                                <figure>
                                    <img src="{{ \Illuminate\Support\Str::startsWith($item->image, 'http') ? $item->image : url('' . $item->image) }}" alt="{{ $item->name }}">
                                </figure>
                            </div>
                            @endif
                            <div class="sis-e-content">
                                <div class="sis-counter-title">
                                    <h2 class="d-flex align-items-center mb-1">
                                        <span class="sis-counter sisf-e-colored">{{ $item->value }}</span>
                                        @if(!empty($item->suffix))
                                        <span class="sisf-digit-label sisf-e-colored">{{ $item->suffix }}</span>
                                        @endif
                                    </h2>
                                </div>
                                <div class="sis-counter-text">
                                    <span>{{ $item->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    @if(isset($founder) && count($founder) > 0)
    <section class="sis-about-team section">
        <div class="container">
            <div class="sisf-sis-section-title text-center sis-section-title">
                <span class="sisf-m-subtitle">The People Behind The Journey</span>
                <h2 class="sisf-m-title">Meet Our <span class="sisf-e-colored">Tour Experts</span></h2>
                <div class="sisf-m-text">
                    <p>Friendly specialists who turn every itinerary into a memorable travel story.</p>
                </div>
            </div>
            <div class="row g-4 sis-about-team-grid">
                @foreach ($founder as $item)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <article class="sis-about-team-card">
                        <div class="sis-about-team-media">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}">
                        </div>
                        <div class="sis-about-team-info">
                            <h3 class="sis-about-team-name">{{ $item->name }}</h3>
                            <span class="sis-about-team-role">{{ $item->position ?? 'Tourist Guide' }}</span>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($ReviewCus) && count($ReviewCus) > 0)
    <section class="sis-testimonial-section sis-about-reviews section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sisf-sis-section-title text-center sis-section-title">
                        <span class="sisf-m-subtitle">Traveller’s Story</span>
                        <h2 class="sisf-m-title">What Our Clients <span class="sisf-e-colored">Say</span></h2>
                        <div class="sisf-m-text">
                            <p>Real travelers, unforgettable experiences — why guests trust us with their next adventure.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="testimonial-right page">
                        <div class="sis-comman--swiper-slider sisf-testimonials-list sisf-testimonial-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @foreach ($ReviewCus as $item)
                                    <div class="swiper-slide">
                                        <div class="sisf-e-inner sis-radius overflow-hidden position-relative mb-0">
                                            @if(!empty($item->avatar))
                                            <div class="sisf-e-testimonial-image">
                                                <figure>
                                                    <img src="{{ url('' . $item->avatar) }}" class="w-100" alt="{{ languageName($item->name) }}">
                                                </figure>
                                            </div>
                                            @endif
                                            <div class="sisf-e-top sis-e-testimonial-content p-3">
                                                <div class="quote-left-icon mb-2">
                                                    <span><i class="fa-solid sisf-e-colored fa-quote-left"></i></span>
                                                </div>
                                                <div class="sisf-bottom-with-content">
                                                    <div class="sisf-bottom--content">
                                                        <div class="sisf-m-inner">
                                                            <div class="sisf-e-author">
                                                                <span class="sisf-e-author-name pe-1 sisf-primary-text">{{ languageName($item->name) }}</span>
                                                                <span class="sisf-m-divider"></span>
                                                                <span class="sisf-e-author-role ps-2 sisf-primary-text">{{ languageName($item->position) ?? 'Tourist' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="sisf-ratings py-2">
                                                    <div class="sisf-m-star sisf--initial">
                                                        <span class="star">★</span>
                                                        <span class="star">★</span>
                                                        <span class="star">★</span>
                                                        <span class="star">★</span>
                                                        <span class="star">★</span>
                                                    </div>
                                                </div>
                                                <div class="sisf-e-content-center sisf-e-discription border-0">
                                                    {!! languageName($item->content) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(isset($categoryhome) && count($categoryhome) > 0)
    <section class="sis-destination-section sis-about-destinations section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sisf-sis-section-title text-center sis-section-title">
                        <span class="sisf-m-subtitle">Top Destinations</span>
                        <h2 class="sisf-m-title">Featured <span class="sisf-e-colored">Destinations</span></h2>
                        <div class="sisf-m-text">
                            <p>Destinations worth exploring — a few of our most loved places to begin your next trip.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="sisf-destination-list">
                        <div class="sis-comman-swiper-slider">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @foreach ($categoryhome as $item)
                                    @php
                                        $destinationName = languageName($item->name);
                                        $destinationUrl = route('allListProCate', ['danhmuc' => $item->slug]);
                                        $destinationImage = $item->imagehome ?: $item->avatar;
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="sisf-destination-list-item">
                                            <div class="sisf-e-inner sis-radius position-relative">
                                                <div class="sisf-destination-image position-relative">
                                                    <a href="{{ $destinationUrl }}" class="sisf-sis-page-link">
                                                        <figure>
                                                            <img src="{{ url('' . $destinationImage) }}" class="w-100 sis-radius" alt="{{ $destinationName }}">
                                                        </figure>
                                                    </a>
                                                </div>
                                                <div class="sisf-e-content d-flex align-items-center justify-content-between">
                                                    <div class="sisf-sis-e-title">
                                                        <h3 class="sisf-e-title">
                                                            <a href="{{ $destinationUrl }}">{{ $destinationName }}</a>
                                                        </h3>
                                                    </div>
                                                    <div class="sisf-m-icon-button">
                                                        <a href="{{ $destinationUrl }}"><i class="fa-solid fa-arrow-right-long"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="sis-comman-bottom-line-text sis-e-bottom d-flex flex-wrap gap-5 justify-content-center">
                        <div class="sisf-m-button">
                            <a href="{{ route('destination') }}" class="sis-btn-default">Find Your Adventure<i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
