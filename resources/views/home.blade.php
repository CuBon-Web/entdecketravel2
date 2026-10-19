@extends('layouts.main.master')
@section('title')
    {{ $setting->company }}
@endsection
@section('description')
    {{ $setting->webname }}
@endsection
@section('image')
    {{ url('' . $banner[0]->image) }}
@endsection
@section('schema')
    <script type="application/ld+json">
{!! json_encode([
   '@context' => 'https://schema.org',
   '@type' => 'WebPage',
   'name' => $setting->company,
   'url' => url()->current(),
   'description' => $setting->webname,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection
@section('css')
<style>
.home-quote-form .tour-price-phone {
    position: relative;
    display: flex;
    align-items: stretch;
    min-height: 48px;
    border: 1px solid #d5e0dc;
    border-radius: 10px;
    background: #fff;
}
.home-quote-form .tour-price-phone:focus-within {
    border-color: #85D200;
    box-shadow: 0 0 0 3px rgba(133, 210, 0, 0.18);
}
.home-quote-form .tour-price-dial-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 auto;
    height: 46px;
    margin: 0;
    padding: 0 12px;
    border: 0;
    border-right: 1px solid #d5e0dc;
    background: #f4f7f5;
    color: #346065;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
}
.home-quote-form .tour-price-flag-img {
    width: 20px;
    height: 15px;
    object-fit: cover;
    border-radius: 2px;
}
.home-quote-form .tour-price-phone input[type="tel"] {
    border: 0;
    outline: 0;
    flex: 1;
    min-width: 0;
    height: 46px;
    padding: 0 14px;
    background: transparent;
}
.home-quote-form .tour-price-dial-menu {
    position: absolute;
    z-index: 40;
    top: calc(100% + 6px);
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
    overflow: hidden;
}
.home-quote-form .tour-price-dial-menu:not([hidden]) {
    display: flex;
    flex-direction: column;
}
.home-quote-form .tour-price-dial-search {
    padding: 8px;
    border-bottom: 1px solid #e5e7eb;
}
.home-quote-form .tour-price-dial-search input {
    width: 100%;
    height: 38px;
    border: 1px solid #d5e0dc;
    border-radius: 8px;
    padding: 0 12px;
    outline: none;
}
.home-quote-form .tour-price-dial-list {
    max-height: 240px;
    overflow: auto;
    padding: 6px 0;
}
.home-quote-form .tour-price-dial-empty {
    display: none;
    padding: 12px 14px;
    color: #8b949e;
}
.home-quote-form .tour-price-dial-empty.is-visible {
    display: block;
}
.home-quote-form .tour-price-dial-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    border: 0;
    background: #fff;
    text-align: left;
    padding: 8px 14px;
    cursor: pointer;
}
.home-quote-form .tour-price-dial-item:hover,
.home-quote-form .tour-price-dial-item.is-active {
    background: #f3f4f6;
}
.home-quote-form .tour-price-dial-code {
    color: #8b949e;
    margin-left: auto;
}
.home-quote-form .tour-price-radios {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.home-quote-form .tour-price-radios label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 0;
    cursor: pointer;
}
.home-quote-form .simple-captcha-box {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.home-quote-form .simple-captcha-image {
    display: block;
    height: 56px;
    width: 160px;
    border: 2px dashed #85d200;
    border-radius: 6px;
    background: #f5f5f5;
}
.home-quote-form .simple-captcha-refresh {
    border: 0;
    background: transparent;
    color: #85d200;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
}
</style>
@endsection
@section('js')
<script>
function initHomeDialPhones() {
    var nodes = document.querySelectorAll('.home-quote-form .js-dial-phone');
    var dataNode = document.getElementById('home-dial-codes');
    if (!nodes.length || !dataNode) return;
    var dialData = [];
    try { dialData = JSON.parse(dataNode.textContent || '[]'); } catch (e) { dialData = []; }
    var widgets = [];
    function flagSrc(iso) { return 'https://flagcdn.com/w40/' + String(iso || '').toLowerCase() + '.png'; }
    function dialSearchText(value) {
        return String(value || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }
    Array.prototype.forEach.call(nodes, function (root) {
        var dialBtn = root.querySelector('[data-dial-btn]');
        var dialMenu = root.querySelector('[data-dial-menu]');
        var dialLabel = root.querySelector('[data-dial-label]');
        var flagEl = root.querySelector('[data-dial-flag]');
        var dialCodeInput = root.querySelector('[data-dial-code]');
        var countryInput = root.querySelector('[data-dial-country]');
        var isoInput = root.querySelector('[data-dial-iso]');
        var searchInput = null;
        var ready = false;
        if (!dialBtn || !dialMenu) return;
        function selectDial(country, item) {
            if (!country) return;
            if (dialCodeInput) dialCodeInput.value = country.dial;
            if (countryInput) countryInput.value = country.name;
            if (isoInput) isoInput.value = country.iso;
            if (dialLabel) dialLabel.textContent = country.dial;
            if (flagEl) flagEl.src = flagSrc(country.iso);
            Array.prototype.forEach.call(dialMenu.querySelectorAll('.tour-price-dial-item'), function (el) {
                el.classList.remove('is-active');
            });
            if (item) item.classList.add('is-active');
        }
        function filterDialMenu(query) {
            var q = dialSearchText(query).trim();
            var shown = 0;
            Array.prototype.forEach.call(dialMenu.querySelectorAll('.tour-price-dial-item'), function (item) {
                var haystack = item.getAttribute('data-search') || '';
                var match = q === '' || haystack.indexOf(q) !== -1;
                item.hidden = !match;
                if (match) shown += 1;
            });
            var empty = dialMenu.querySelector('.tour-price-dial-empty');
            if (empty) empty.classList.toggle('is-visible', shown === 0);
        }
        function buildDialMenu() {
            if (ready) return;
            ready = true;
            var searchWrap = document.createElement('div');
            searchWrap.className = 'tour-price-dial-search';
            searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Search country or code';
            searchInput.autocomplete = 'off';
            searchInput.addEventListener('input', function () { filterDialMenu(searchInput.value); });
            searchWrap.appendChild(searchInput);
            var list = document.createElement('div');
            list.className = 'tour-price-dial-list';
            var currentIso = isoInput ? isoInput.value : 'VN';
            dialData.forEach(function (country) {
                var item = document.createElement('button');
                item.type = 'button';
                item.className = 'tour-price-dial-item' + (country.iso === currentIso ? ' is-active' : '');
                item.setAttribute('data-search', dialSearchText([country.label, country.name, country.iso, country.dial].join(' ')));
                item.innerHTML = '<img class="tour-price-flag-img" alt="" src="' + flagSrc(country.iso) + '"><span class="tour-price-dial-name"></span><span class="tour-price-dial-code"></span>';
                item.querySelector('.tour-price-dial-name').textContent = country.label;
                item.querySelector('.tour-price-dial-code').textContent = country.dial;
                item.addEventListener('click', function () { selectDial(country, item); closeDialMenu(); });
                list.appendChild(item);
            });
            var empty = document.createElement('div');
            empty.className = 'tour-price-dial-empty';
            empty.textContent = 'No matching country';
            dialMenu.appendChild(searchWrap);
            dialMenu.appendChild(list);
            dialMenu.appendChild(empty);
        }
        function openDialMenu() {
            buildDialMenu();
            if (searchInput) { searchInput.value = ''; filterDialMenu(''); }
            dialMenu.hidden = false;
            dialBtn.setAttribute('aria-expanded', 'true');
            if (searchInput) searchInput.focus();
        }
        function closeDialMenu() {
            dialMenu.hidden = true;
            dialBtn.setAttribute('aria-expanded', 'false');
        }
        dialBtn.addEventListener('click', function () {
            if (dialMenu.hidden) openDialMenu(); else closeDialMenu();
        });
        widgets.push({ root: root, menu: dialMenu, close: closeDialMenu });
    });
    document.addEventListener('click', function (event) {
        widgets.forEach(function (widget) {
            if (widget.menu.hidden || widget.root.contains(event.target)) return;
            widget.close();
        });
    });
}
function refreshHomeQuoteCaptcha() {
    var img = document.getElementById('home-quote-captcha-img');
    if (img) img.src = '{{ route('contactCaptcha', ['scope' => 'quote']) }}&t=' + Date.now();
}
document.addEventListener('DOMContentLoaded', function () {
    initHomeDialPhones();
    var form = document.getElementById('enquiryForm');
    if (!form) return;
    var specificDate = form.querySelector('input[name="date_type"][value="specific"]');
    var flexibleDate = form.querySelector('input[name="date_type"][value="flexible"]');
    var dateRow = document.getElementById('home-quote-date-row');
    var dateInput = form.querySelector('input[name="travel_date"]');
    function syncTravelDate() {
        var isSpecific = specificDate && specificDate.checked;
        if (dateRow) dateRow.style.display = isSpecific ? '' : 'none';
        if (dateInput) dateInput.required = !!isSpecific;
    }
    if (specificDate) specificDate.addEventListener('change', syncTravelDate);
    if (flexibleDate) flexibleDate.addEventListener('change', syncTravelDate);
    syncTravelDate();
    form.addEventListener('submit', function (e) {
        var email = (form.querySelector('input[name="email"]').value || '').trim();
        var confirmEmail = (form.querySelector('input[name="email_confirm"]').value || '').trim();
        var alertBox = document.getElementById('home-quote-client-error');
        if (email !== confirmEmail) {
            e.preventDefault();
            if (alertBox) {
                alertBox.textContent = 'Email addresses do not match.';
                alertBox.style.display = 'block';
            }
        } else if (alertBox) {
            alertBox.style.display = 'none';
        }
    });
});
</script>
@endsection
@section('content')
<!-- Hero Section Start -->
<div class="sis-hero hero-slider sis-br-radius mt-3 sis-home-image">
    <div class="hero-slider-layout hero-slider--layout position-relative">
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach ($banner as $item)
                @php
                    $bannerVideoRaw = trim((string) ($item->video ?? ''));
                    $bannerYoutubeId = null;
                    if ($bannerVideoRaw !== '') {
                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $bannerVideoRaw, $m)) {
                            $bannerYoutubeId = $m[1];
                        } elseif (preg_match('/^[A-Za-z0-9_-]{11}$/', $bannerVideoRaw)) {
                            $bannerYoutubeId = $bannerVideoRaw;
                        }
                    }
                @endphp
                <div class="swiper-slide">
                    <div class="hero-slide align-items-center pb-0">
                        <div class="hero-slider-image {{ $bannerYoutubeId ? 'is-youtube' : '' }}">
                            @if($bannerYoutubeId)
                            <iframe
                                class="bg-video-youtube"
                                src="https://www.youtube.com/embed/{{ $bannerYoutubeId }}?autoplay=1&mute=1&controls=0&playsinline=1&loop=1&playlist={{ $bannerYoutubeId }}&rel=0&modestbranding=1&showinfo=0&iv_load_policy=3&disablekb=1&fs=0"
                                title="{{ $item->title ?: 'Banner video' }}"
                                frameborder="0"
                                allow="autoplay; encrypted-media; picture-in-picture"
                                allowfullscreen
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            ></iframe>
                            @else
                            <img src="{{url('' . $item->image)}}" alt="{{ $item->title ?: 'Banner' }}" decoding="async" loading="{{ $loop->first ? 'eager' : 'lazy' }}" @if($loop->first) fetchpriority="high" @endif>
                            @endif
                        </div>
                        <!-- Content Start -->
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-9 col-lg-6">
                                    <!-- Hero Content Start -->
                                    <div class="hero-content text-start pb-0">
                                        <!-- Hero Title Start -->
                                        <div class="sis-section-title mb-0">
                                            <span class="sisf-subtitle text-white sis-text-anime-style-2">Welcome to Entdecke Travel</span>
                                            <h1 class="sis-text-anime-style-2 text-start">{!!$item->title!!}</h1>
                                            <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="300">
                                                <p class="text-white text-start">{!!$item->description!!}</p>
                                            </div>
                                            <div class="button-group d-flex align-items-center flex-wrap gap-3">
                                                <div class="sisf-m-button aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">
                                                    <a href="{{$item->link}}" class="sis-btn-default">Detail<i class="fa-solid fa-arrow-right-long"></i></a>
                                                </div>
                                                <div class="sisf-m-button aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                                                    <a href="{{route('lienHe')}}" class="sis-btn-default gap-1 popup-video btn-light">Contact Us<span class="fa-solid fa-play-circle"></span></a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Hero Title End -->
                                    </div>
                                    <!-- Hero Content End -->
                                </div>
                                <div class="col-xl-3 col-lg-6"></div>
                            </div>
                        </div>
                        <!-- Content End -->
                    </div>
                </div>
                @endforeach
            </div>
            <div class="hero-pagination d-flex justify-content-center"></div>
        </div>
    </div>
</div>
<!-- Hero Section End -->
<!-- About Us Section Start -->
<div class="sis-about-us-section sis-page-background pb-0 section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <!-- Section Title Start -->
                <div class="sisf-sis-section-title sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">About Entdecke Travel</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3"> {{$setting->company}}</h2>
                    <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                        {!!$gioithieu->description!!}
                    </div>
                </div>
                <div class="sisf-m-button pt-4" data-aos="fade-up" data-aos-delay="700">
                    <a href="{{route('aboutUs')}}" class="sis-btn-default">Discover Our Story<i class="fa-solid fa-arrow-right-long"></i></a>
                </div>
            </div>
            <div class="col-lg-6">
                @php
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
                    $aboutImageRight = $aboutImages[1] ?? $aboutImages[0] ?? null;
                @endphp
                <div class="sisf-about-images d-flex align-items-end">
                    @if($aboutImageSmall)
                    <div class="sisf-about-image-small" data-aos="zoom-in" data-aos-delay="500">
                        <figure>
                            <img src="{{ url('' . $aboutImageSmall) }}" alt="{{ optional($gioithieu)->title ?? $setting->company }}" decoding="async" loading="lazy">
                        </figure>
                    </div>
                    @endif
                    @if($aboutImageRight)
                    <div class="sisf-about-image-right">
                        <figure class="sis-reveal sis-image-anime">
                            <img src="{{ url('' . $aboutImageRight) }}" class="w-100" alt="{{ optional($gioithieu)->title ?? $setting->company }}" decoding="async" loading="lazy">
                        </figure>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="sisf-sis-page-counters">
            <div class="row">
                @foreach (($impressiveNumbers ?? collect()) as $item)
                <div class="col-lg-3 col-md-6">
                    <div class="sis-counter-item d-flex align-items-center gap-4" data-aos="fade-up" data-aos-delay="{{ 100 + ($loop->index * 200) }}">
                        @if(!empty($item->image))
                        <div class="sis-e-counter-image">
                            <figure>
                                <img src="{{ \Illuminate\Support\Str::startsWith($item->image, 'http') ? $item->image : url('' . $item->image) }}" alt="{{ $item->name }}" decoding="async" loading="lazy">
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
    </div>
</div>
<!-- About Us Section End -->
<!-- Top Destination Section Start -->
<div class="sis-destination-section section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Section Title Start -->
                <div class="sisf-sis-section-title text-center sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">Top Destinations</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3">Explore Our <span class="sisf-e-colored">Most<br> Loved Destinations</span></h2>
                    <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                        <p>Discover handpicked places where breathtaking landscapes, rich cultures, and unforgettable experiences<br> come together to create your perfect escape.</p>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="sisf-destination-list">
                    <div class="sis-comman-swiper-slider" data-aos="fade-up" data-aos-delay="100">
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
                                                        <img src="{{ url('' . $destinationImage) }}" class="w-100 sis-radius" alt="{{ $destinationName }}" decoding="async" loading="lazy">
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
                <div class="sis-comman-bottom-line-text sis-e-bottom d-flex flex-wrap gap-5">
                    <div class="sisf-m-button" data-aos="fade-up" data-aos-delay="100">
                        <a href="{{ route('destination') }}" class="sis-btn-default">Find Your Adventure<i class="fa-solid fa-arrow-right-long"></i></a>
                    </div>
                    <div class="sisf-sis-e-box">
                        <p class="mb-1" data-aos="fade-up" data-aos-delay="300"><span class="pe-2"><img src="/frontend/images/users.png" alt="{{ $setting->company }}" decoding="async" loading="lazy"></span> Pack your dreams, leave the planning to us, and experience journeys that you'll remember forever. <a href="{{ route('aboutUs') }}">Being your journey</a></p>
                        <p class="mb-0" data-aos="fade-up" data-aos-delay="500">Rated 4.9/5 by Happy Travelers Worldwide
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Top Destination Section End -->
<!-- Tour List Section Start -->
<div class="sis-tour-list-section sis-br-radius position-relative">
    <div class="sis-page-list-background sis-radius">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="sisf-sis-section-title sis-section-title">
                        <span class="sisf-m-subtitle sis-text-anime-style-3">Our Tour Packages</span>
                        <h2 class="sisf-m-title text-white sis-text-anime-style-3"><span class="sisf-e-colored">Explore the World's</span> Most Inspiring Places</h2>
                    </div>
                    <!-- Section Title End -->
                </div>
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="sisf-sis-section-title sis-section-title">
                        <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                            <p class="text-white">From iconic landmarks and pristine beaches to breathtaking mountains and vibrant cities, discover handpicked destinations designed for unforgettable adventures and lifelong memories.</p>
                        </div>
                        <div class="sisf-m-button-inner d-flex justify-content-between flex-wrap">
                            <div class="sisf-m-button pt-4" data-aos="fade-up" data-aos-delay="300">
                                <a href="{{ route('destination') }}" class="sis-btn-default">Explore the Packages<i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                            <div class="sis-e-icon-top position-relative d-inline-block" data-aos="zoom-in" data-aos-delay="600">
                                <div class="sis-e-icon-spin">
                                    <figure>
                                        <img src="/frontend/images/icon-top.svg" class="spin-img" alt="icon-top" decoding="async" loading="lazy">
                                    </figure>
                                </div>
                                <div class="sis-m-icon-top">
                                    <figure>
                                        <img src="/frontend/images/page-comman-icon.svg" alt="page-comman-icon" decoding="async" loading="lazy">
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>
            <div class="sisf-tour-list-part">
                <div class="row">
                    <div class="col-12">
                        <div class="sis-comman--swiper-slider" data-aos="zoom-in" data-aos-delay="300">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    @foreach ($homePro as $item)
                                    @php
                                        $tourImages = json_decode($item->images, true);
                                        $tourThumb = is_array($tourImages) && !empty($tourImages[0]) ? $tourImages[0] : url('/frontend/images/no-image.png');
                                        $tourName = languageName($item->name);
                                        $tourUrl = route('detailProduct', ['cate' => $item->cate_slug, 'type' => $item->type_slug ? $item->type_slug : 'type', 'id' => $item->slug]);
                                        $tourCate = languageName(optional($item->cate)->name);
                                        $tourDesc = strip_tags(languageName($item->description));
                                        $tourDiscount = (int) languageName($item->discount);
                                        $tourPrice = (int) languageName($item->price);
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="sisf-tour-list-item sis-radius bg-white">
                                            <div class="sisf-e-inner mb-0">
                                                <div class="sisf-tour-image position-relative">
                                                    <a href="{{ $tourUrl }}">
                                                        <figure class="sis-image-anime">
                                                            <img src="{{ url('' . $tourThumb) }}" class="w-100" alt="{{ $tourName }}" decoding="async" loading="lazy">
                                                        </figure>
                                                    </a>
                                                    @if($tourCate)
                                                    <div class="sis-tours-category">
                                                        <span>{{ $tourCate }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="sisf-tour-content p-3">
                                                    <div class="sisf-tour-title mb-1">
                                                        <h3 class="sis-tour-item__link">
                                                            <a href="{{ $tourUrl }}">{{ $tourName }}</a>
                                                        </h3>
                                                    </div>
                                                    <div class="sisf-m-text">
                                                        <p class="mb-3">{{ \Illuminate\Support\Str::limit($tourDesc, 140) }}</p>
                                                    </div>
                                                    <div class="sis-tour-item__price_wrap">
                                                        <div class="sis-tour-item__start mb-1">
                                                            <span>Starting From</span>
                                                        </div>
                                                        <div class="sis-tour-item__price">
                                                            <h3 class="mb-0 sisf-e-colored">
                                                                @if ($tourDiscount > 0)
                                                                    {{ number_format($tourDiscount) }} VND / Person
                                                                @elseif ($tourPrice > 0)
                                                                    {{ number_format($tourPrice) }} VND / Person
                                                                @else
                                                                    Contact Us
                                                                @endif
                                                            </h3>
                                                        </div>
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
        </div>
    </div>
</div>
<!-- Tour List Section End -->
<!-- Why Choose Us Section Start -->
@if(isset($whychoise) && count($whychoise) > 0)
<div class="sis-how-it-work-section section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sisf-sis-section-title text-center sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">Why Choose Us</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3">Why Travelers <span class="sisf-e-colored">Choose Entdecke</span></h2>
                    <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                        <p>Trusted expertise, tailor-made journeys, and dedicated support - everything you need for a seamless Asian adventure.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="sisf-journey-staps">
                    <div class="nav-tabs sis-journey-stap-tab" role="tablist">
                        @foreach ($whychoise as $key => $item)
                        @php
                            $whyTitle = languageName($item->content);
                            $whyDesc = languageName($item->description);
                            $whyIcon = $item->image;
                            $isLast = $loop->last;
                        @endphp
                        <div class="sis-journey-stap-type {{ $key === 0 ? 'active' : '' }} {{ $isLast ? 'pb-0' : '' }}" data-bs-toggle="tab" data-bs-target="#why-choose-{{ $item->id }}" role="tabpanel">
                            <div class="sisf-how-works-right-content" data-aos="fade-up" data-aos-delay="100">
                                <div class="sisf-e-inner d-flex gap-4">
                                    @if($whyIcon)
                                    <div class="sisf-e-icon">
                                        <figure>
                                            <img src="{{ url('' . $whyIcon) }}" alt="{{ $whyTitle }}" decoding="async" loading="lazy">
                                        </figure>
                                    </div>
                                    @endif
                                    <div class="sisf-e-content">
                                        <div class="sisf-m-title mb-2">
                                            <h3 class="sisf-m-titl-inner">{{ $whyTitle }}</h3>
                                        </div>
                                        <div class="sisf-m-text">
                                            <p class="mb-0 sisf-page-text-line">{{ $whyDesc }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="sis-journey-tab-content tab-content">
                        @foreach ($whychoise as $key => $item)
                        @php
                            $whyTitle = languageName($item->content);
                            $whyBadge = $item->name ?: $whyTitle;
                            $whyAvatar = $item->image_detail;
                            $whyIcon = $item->image;
                        @endphp
                        <div id="why-choose-{{ $item->id }}" class="tab-pane {{ $key === 0 ? 'active' : 'fade' }} sis-zoom-anim">
                            <div class="sisf-e-content-on-image position-relative">
                                <div class="sisf-how-work-image">
                                    @if($whyAvatar)
                                    <figure class="sis-image-anime sis-reveal">
                                        <img src="{{ url('' . $whyAvatar) }}" class="w-100 sis-radius" alt="{{ $whyTitle }}" decoding="async" loading="lazy">
                                    </figure>
                                    @endif
                                </div>
                                @if($whyBadge)
                                <div class="sis-e-left-top-text sisf-float-left-right px-3" data-aos="zoom-in" data-aos-delay="300">
                                    <h3>{{ $whyBadge }}</h3>
                                </div>
                                @endif
                                @if($whyIcon)
                                <div class="sis-e-right-content">
                                    <div class="sisf-e-inner sis-radius bg-white p-3 sis-fade">
                                        <div class="sisf-m-inner d-flex align-items-center gap-4">
                                            <div class="sisf-icon-image">
                                                <figure>
                                                    <img src="{{ url('' . $whyIcon) }}" class="sis-radius" alt="{{ $whyTitle }}" decoding="async" loading="lazy">
                                                </figure>
                                            </div>
                                            <div class="sisf-e-content">
                                                <div class="sisf-m-title">
                                                    <h3>{{ $whyTitle }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="sis-comman-bottom-line-text sis-e-bottom text-center">
                    <div class="sisf-sis-e-box">
                        <p class="mb-1" data-aos="fade-up" data-aos-delay="300"><span class="pe-2"><img src="/frontend/images/users.png" alt="{{ $setting->company }}" decoding="async" loading="lazy"></span> From dream destinations to unforgettable memories, we make every journey effortless and inspiring. <a href="{{ route('aboutUs') }}">Being your journey</a></p>
                        <p class="mb-0" data-aos="fade-up" data-aos-delay="500">Trusted by Thousands of Travelers
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                            <span class="sisf-e-colored">★</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- Why Choose Us Section End -->

<!-- Experiences Section Start -->
@php
    $introVideoRaw = trim((string) ($setting->google ?? ''));
    $introYoutubeId = null;
    if ($introVideoRaw !== '') {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $introVideoRaw, $m)) {
            $introYoutubeId = $m[1];
        } elseif (preg_match('/^[A-Za-z0-9_-]{11}$/', $introVideoRaw)) {
            $introYoutubeId = $introVideoRaw;
        }
    }
    $introMp4 = (!$introYoutubeId && $introVideoRaw !== '' && preg_match('/\.(mp4|webm|ogg)(\?|$)/i', $introVideoRaw))
        ? $introVideoRaw
        : 'https://traviox.wpthemeverse.com/wp-content/uploads/2026/06/video3.mp4';
@endphp
<div class="sis-experiences-section sis-br-radius position-relative overflow-hidden">
    <div class="sis-experiences-background">
        <div class="intro-video-box">
            <div class="sisf-sis-back-video {{ $introYoutubeId ? 'is-youtube' : 'is-mp4' }}">
                @if($introYoutubeId)
                <iframe
                    class="bg-video bg-video-youtube"
                    src="https://www.youtube.com/embed/{{ $introYoutubeId }}?autoplay=1&mute=1&controls=0&playsinline=1&loop=1&playlist={{ $introYoutubeId }}&rel=0&modestbranding=1&showinfo=0&iv_load_policy=3&disablekb=1&fs=0"
                    title="Intro video"
                    frameborder="0"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    allowfullscreen
                    loading="lazy"
                ></iframe>
                @else
                <video class="bg-video" autoplay loop muted playsinline>
                    <source src="{{ $introMp4 }}" type="video/mp4">
                </video>
                @endif
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="sisf-sis-section-title text-center mb-0 sis-section-title">
                        <h2 class="sisf-m-title text-white sis-text-anime-style-3"><span class="sisf-e-colored">Travel Beyond</span> The Ordinary With<br> <span class="sisf-e-colored">Extraordinary Experiences</span></h2>
                        <div class="sisf-m-button pt-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="500">
                            <a href="{{ route('lienHe') }}" class="sis-btn-default">Plan Your Journey<i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Experiences Section End -->
<!-- Testimonials Section Start -->
<div class="sis-testimonial-section section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Section Title Start -->
                <div class="sisf-sis-section-title text-center sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">Traveller’s Story</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3">Journeys That<br> <span class="sisf-e-colored">Inspire Confidence</span></h2>
                    <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                        <p>Real travelers, unforgettable experiences. Discover why thousands trust Entdecke Travel to turn every trip into a<br> seamless and memorable adventure.</p>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="testimonial-right page" data-aos="fade-up" data-aos-delay="300">
                    <div class="sis-comman--swiper-slider sisf-testimonials-list sisf-testimonial-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                @foreach ($ReviewCus as $item)
                                <div class="swiper-slide">
                                    <div class="sisf-e-inner sis-radius overflow-hidden position-relative mb-0">
                                        <div class="sisf-e-testimonial-image">
                                            <figure>
                                                <img src="{{ url('' . $item->avatar) }}" class="w-100" alt="Entdecke Travel" decoding="async" loading="lazy">
                                            </figure>
                                        </div>
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
                                                            <span class="sisf-e-author-role ps-2 sisf-primary-text">{{ languageName($item->position) }}</span>
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
</div>
<!-- Testimonials Section End -->
<!-- FAQ Section Start -->
<div class="sis-faq-section section">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 position-relative">
                <div class="sis-e-faqs-image">
                    <figure class="sis-image-anime sis-reveal">
                        <img src="/frontend/images/faqs-img.jpg" class="w-100 sis-radius" alt="Entdecke Travel" decoding="async" loading="lazy">
                    </figure>
                </div>
                <div class="sisf-faqs-contact-part sisf-contact-left-bottom" data-aos="zoom-in-left" data-aos-delay="500">
                    <div class="sisf-e-inner sisf-float-left-right bg-white sis-radius p-4">
                        <div class="sisf-e-content">
                            <div class="sisf-m-title">
                                <h3>Need Personal Travel Assistance?</h3>
                            </div>
                            <div class="sisf-m-text">
                                <p>Our travel specialists are here to help you plan the perfect<br> getaway and answer any questions you may have.</p>
                            </div>
                        </div>
                        <div class="sisf-sis-contact-information">
                            <div class="sisf-contact-box mb-2 d-flex align-items-center gap-3">
                                <div class="sisf--icon">
                                    <a href="tel:{{$setting->phone1}}" class="sis-comman-text"><i class="fa-solid fa-phone-volume"></i></a>
                                </div>
                                <div class="sisf-sis-e-content">
                                    <a href="tel:{{$setting->phone1}}" class="sis-title sis-comman-text d-block">{{$setting->phone1}}</a>
                                </div>
                            </div>
                            <div class="sisf-contact-box mb-2 d-flex align-items-center gap-3">
                                <div class="sisf--icon">
                                    <a href="mailto:{{$setting->email}}" class="sis-comman-text"><i class="fa-regular fa-envelope"></i></a>
                                </div>
                                <div class="sisf-sis-e-content">
                                    <a href="mailto:{{$setting->email}}" class="sis-title sis-comman-text d-block">{{$setting->email}}</a>
                                </div>
                            </div>
                            <div class="sisf-contact-box d-flex align-items-center gap-3">
                                <div class="sisf--icon">
                                    <a href="{{route('lienHe')}}" class="sis-comman-text"><i class="fa-solid fa-clock"></i></a>
                                </div>
                                <div class="sisf-sis-e-content">
                                    <a href="#" class="sis-title sis-comman-text d-block">Mon – Sat: 9:00 AM – 7:00 PM</a>
                                </div>
                            </div>
                            <div class="sisf-m-button pt-4">
                                <a href="{{route('lienHe')}}" class="sis-btn-default">Contact Our Experts<i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <!-- Section Title Start -->
                <div class="sisf-sis-section-title sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">FAQs</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3">Frequently <span class="sisf-e-colored">Asked Questions</span></h2>
                    <div class="sisf-m-text" data-aos="fade-up" data-aos-delay="100">
                        <p>Have questions about planning your next adventure? Here are answers to some of the most common questions to help you book with confidence.</p>
                    </div>
                </div>
                @php
                    $faqDetails = collect();
                    foreach (json_decode($setting->footer_content) ?? [] as $item) {
                        foreach ($item->fag_detail ?? [] as $faqItem) {
                            $faqDetails->push($faqItem);
                        }
                    }
                @endphp
                <!-- Section Title End -->
                <div class="sisf-page-accordian sisf-sis-page-accordian sisf-page-bottom sis-radius bg-white overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion" id="sisf-Accordion">
                        @foreach ($faqDetails as $key => $faq)
                        <div class="accordion-item {{ $loop->last ? 'border-0' : '' }} mt-0">
                            <h2 class="accordion-header sis-comman-title">
                                <button class="accordion-button {{ $key === 0 ? '' : 'collapsed' }} {{ $key === 0 ? 'mt-0' : '' }}" data-bs-toggle="collapse" data-bs-target="#sis-faq-{{ $key }}">
                                    <span>{{ $key + 1 }}. {{ $faq->name }}</span>
                                </button>
                            </h2>
                            <div id="sis-faq-{{ $key }}" class="accordion-collapse collapse {{ $key === 0 ? 'show' : '' }}" data-bs-parent="#sisf-Accordion">
                                <div class="accordion-body">
                                    <div class="sisf-e-content-inner">
                                        <div class="mb-0 text-white">{!! $faq->content !!}</div>
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
<!-- FAQ Section End -->
<!-- Contact Us Section Start -->
<div class="sis-contact-us-section sis-br-radius section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="sisf-e-content text-center mt-5">
                    <p class="text-white">Trusted by 25,000+ Travelers and 500+ Global Travel Partners</p>
                </div>
                @if(isset($partner) && count($partner) > 0)
                @foreach ([100, 300, 500] as $rowIndex => $aosDelay)
                <div class="row">
                    <div class="col-12">
                        <div class="sis-logo-slider {{ $rowIndex === 1 ? 'my-4' : ($rowIndex === 2 ? 'mb-4' : '') }}" data-aos="fade-up" data-aos-delay="{{ $aosDelay }}">
                            <div class="{{ $rowIndex === 0 ? 'sis-comman-swiper-slider' : ($rowIndex === 1 ? 'sis-comman--swiper-slider' : 'sisf-comman--swiper-slider ms-auto me-auto') }}">
                                <div class="swiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($partner as $item)
                                        <div class="swiper-slide">
                                            <div class="sisf-e-inner">
                                                <div class="sis-logo-slide-image text-center">
                                                    <figure>
                                                        @if(!empty($item->link))
                                                        <a href="{{ $item->link }}" target="_blank" rel="noopener noreferrer">
                                                            <img src="{{ url('' . $item->image) }}" alt="{{ $item->name ?: $setting->company }}" decoding="async" loading="lazy">
                                                        </a>
                                                        @else
                                                        <img src="{{ url('' . $item->image) }}" alt="{{ $item->name ?: $setting->company }}" decoding="async" loading="lazy">
                                                        @endif
                                                    </figure>
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
                @endforeach
                @endif
            </div>
            <div class="col-lg-6">
                <div class="sis-contect-right bg-white p-4 sis-radius" data-aos="fade-up" data-aos-delay="100">
                    <!-- Setion Title Start -->
                    <div class="sisf-sis-section-title sis-section-title">
                        <span class="sisf-m-subtitle sis-text-anime-style-3">Start Your Joruney</span>
                        <h2 class="sisf-m-title sis-text-anime-style-3"><span class="sisf-e-colored">Let's Plan</span> Your Next Unforgettable Adventure</h2>
                    </div>
                    <!-- Setion Title End -->
                    <div class="form-section">
                        @php
                            $dialPath = public_path('frontend/js/dial-codes.json');
                            $dialCountries = (is_file($dialPath) ? json_decode(file_get_contents($dialPath), true) : null) ?: [];
                            $fallbackDial = ['iso' => 'VN', 'dial' => '+84', 'name' => 'Vietnam', 'label' => 'Vietnam (Việt Nam)'];
                            if (empty($dialCountries)) {
                                $dialCountries = [$fallbackDial];
                            }
                            $homeDial = $fallbackDial;
                            $oldIso = old('form_type') === 'quote' ? old('phone_iso', 'VN') : 'VN';
                            foreach ($dialCountries as $dialCountry) {
                                if (($dialCountry['iso'] ?? '') === $oldIso) {
                                    $homeDial = $dialCountry;
                                    break;
                                }
                            }
                        @endphp
                        <script type="application/json" id="home-dial-codes">{!! json_encode($dialCountries, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

                        @if(session('quote_error'))
                        <div class="alert alert-danger">{{ session('quote_error') }}</div>
                        @endif
                        @if($errors->any() && old('form_type') === 'quote')
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <div id="home-quote-client-error" class="alert alert-danger" style="display:none;"></div>

                        <form id="enquiryForm" class="home-quote-form p-0 m-0" method="POST" action="{{ route('postBookTour') }}">
                            @csrf
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;height:0;width:0;opacity:0;" aria-hidden="true">
                            <input type="hidden" name="form_type" value="quote">
                            <input type="hidden" name="tour_id" value="">
                            <input type="hidden" name="service_id" value="">
                            <input type="hidden" name="total" value="0">

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2">Travel date</label>
                                        <div class="tour-price-radios">
                                            <label>
                                                <input type="radio" name="date_type" value="specific" {{ (old('form_type') === 'quote' ? old('date_type', 'specific') : 'specific') === 'specific' ? 'checked' : '' }}>
                                                Specific Date
                                            </label>
                                            <label>
                                                <input type="radio" name="date_type" value="flexible" {{ old('form_type') === 'quote' && old('date_type') === 'flexible' ? 'checked' : '' }}>
                                                Flexible Date
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12" id="home-quote-date-row">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-travel-date">Specific Date</label>
                                        <input id="home-quote-travel-date" type="date" class="form-control sis-comman-bg" name="travel_date" value="{{ old('form_type') === 'quote' ? old('travel_date') : '' }}" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-name">Full name *</label>
                                        <input id="home-quote-name" type="text" class="form-control sis-comman-bg" name="name" placeholder="Your full name" required maxlength="191" value="{{ old('form_type') === 'quote' ? old('name') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-email">Email *</label>
                                        <input id="home-quote-email" type="email" class="form-control sis-comman-bg" name="email" placeholder="Your email address" required maxlength="191" value="{{ old('form_type') === 'quote' ? old('email') : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-email-confirm">Confirm email *</label>
                                        <input id="home-quote-email-confirm" type="email" class="form-control sis-comman-bg" name="email_confirm" placeholder="Your email address" required maxlength="191" value="{{ old('form_type') === 'quote' ? old('email_confirm') : '' }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-phone">Phone *</label>
                                        <div class="tour-price-phone js-dial-phone">
                                            <button type="button" class="tour-price-dial-btn" data-dial-btn aria-haspopup="listbox" aria-expanded="false" aria-label="Country code {{ $homeDial['label'] }}">
                                                <img class="tour-price-flag-img" data-dial-flag src="https://flagcdn.com/w40/{{ strtolower($homeDial['iso']) }}.png" alt="">
                                                <span data-dial-label>{{ $homeDial['dial'] }}</span>
                                                <i class="bi bi-chevron-down"></i>
                                            </button>
                                            <input id="home-quote-phone" type="tel" name="phone" placeholder="Your phone number" required maxlength="30" value="{{ old('form_type') === 'quote' ? old('phone') : '' }}">
                                            <div class="tour-price-dial-menu" data-dial-menu role="listbox" hidden></div>
                                            <input type="hidden" name="phone_code" data-dial-code value="{{ $homeDial['dial'] }}">
                                            <input type="hidden" name="phone_country" data-dial-country value="{{ $homeDial['name'] }}">
                                            <input type="hidden" name="phone_iso" data-dial-iso value="{{ $homeDial['iso'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-hotel">Hotel category *</label>
                                        <select id="home-quote-hotel" class="form-select form-control sis-comman-bg" name="hotel_category" required>
                                            @foreach (['2 Stars', '3 Stars', '4 Stars', '5 Stars', 'Cruise'] as $hotel)
                                            <option value="{{ $hotel }}" {{ (old('form_type') === 'quote' ? old('hotel_category', '3 Stars') : '3 Stars') === $hotel ? 'selected' : '' }}>{{ $hotel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2" for="home-quote-travellers">Number of travellers *</label>
                                        <select id="home-quote-travellers" class="form-select form-control sis-comman-bg" name="travellers" required>
                                            @for ($persons = 1; $persons <= 20; $persons++)
                                            <option value="{{ $persons }}" {{ (int) (old('form_type') === 'quote' ? old('travellers', 2) : 2) === $persons ? 'selected' : '' }}>{{ $persons }} {{ $persons === 1 ? 'Person' : 'Persons' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="sis-form-label mb-2">Verification code *</label>
                                        <div class="simple-captcha-box">
                                            <img id="home-quote-captcha-img" class="simple-captcha-image" src="{{ route('contactCaptcha', ['scope' => 'quote']) }}" alt="Captcha">
                                            <button type="button" class="simple-captcha-refresh" onclick="refreshHomeQuoteCaptcha()">Refresh</button>
                                        </div>
                                        <input class="form-control sis-comman-bg mt-2" name="captcha_code" type="text" required maxlength="4" pattern="[0-9]{4}" inputmode="numeric" autocomplete="off" placeholder="Enter 4 digits in the image">
                                    </div>
                                </div>
                            </div>
                            <div class="sisf-m-btn pt-3">
                                <button type="submit" class="sis-btn-default">Start Planning My Trip<i class="fa-solid fa-arrow-right-long"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact Us Section End -->
<!-- Letest Blog Section Start -->
<div class="sis-letest-blog-section section">
    <div class="container">
        <div class="row align-items-end">
            <div class="col-lg-7">
                <!-- Section Title Start -->
                <div class="sisf-sis-section-title sis-section-title">
                    <span class="sisf-m-subtitle sis-text-anime-style-3">Latest Blogs</span>
                    <h2 class="sisf-m-title sis-text-anime-style-3">Discover Inspiration for Your <span class="sisf-e-colored">Next Adventure</span></h2>
        
                </div>
                <!-- Section Title End -->
            </div>
            <div class="col-lg-5">
                <!-- Section Title Start -->
                <div class="sisf-sis-section-title sis-section-title">
                    <div class="sisf-m-text mb-3 pb-3" data-aos="fade-up" data-aos-delay="100">
                        <p class="mt-0">Explore expert travel tips, destination guides, hidden gems, and inspiring stories designed to help you plan unforgettable journeys and create meaningful experiences wherever yournext adventure takes you.</p>
                    </div>
                </div>
                <!-- Section Title End -->
            </div>
        </div>
        @if(isset($hotnews) && count($hotnews) > 0)
        <div class="row">
            <div class="col-12">
                <div class="sis-comman-swiper-slider" data-aos="fade-up" data-aos-delay="100">
                    <div class="swiper">
                        <div class="swiper-wrapper">
                            @foreach ($hotnews as $item)
                            @php
                                $blogTitle = languageName($item->title);
                                $blogDesc = strip_tags(languageName($item->description));
                                $blogUrl = route('detailBlog', ['slug' => $item->slug]);
                                $blogImage = $item->image ?: url('/frontend/images/no-image.png');
                                $blogDate = $item->created_at ? \Illuminate\Support\Carbon::parse($item->created_at)->format('d M Y') : '';
                            @endphp
                            <div class="swiper-slide">
                                <div class="sis-letest-blog-item sis-radius bg-white p-3 mb-0">
                                    <div class="sisf-e-inner position-relative">
                                        <div class="sisf-e-media-holder mb-3">
                                            <div class="sisf-e-media">
                                                <div class="sisf-e-media-image">
                                                    <a href="{{ $blogUrl }}" class="d-block">
                                                        <figure>
                                                            <img src="{{ url('' . $blogImage) }}" class="w-100" alt="{{ $blogTitle }}" decoding="async" loading="lazy">
                                                        </figure>
                                                    </a>
                                                </div>
                                                @if($blogDate)
                                                <div class="sisf-blog-category">
                                                    <a href="{{ $blogUrl }}">{{ $blogDate }}</a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="sisf-e-content">
                                            <div class="sisf-e-text">
                                                <div class="sisf-e-title-wraper">
                                                    <h2 class="sisf-e-title sis-comman-text entry-title">
                                                        <a class="sisf-e-title-link blog-title-link" href="{{ $blogUrl }}">
                                                            {{ $blogTitle }}
                                                        </a>
                                                    </h2>
                                                </div>
                                                <div class="sisf-m-text">
                                                    <p class="sisf-e-discription sisf-page-text-line mb-0">{{ \Illuminate\Support\Str::limit($blogDesc, 120) }}</p>
                                                </div>
                                                <div class="sisf-m-btn pt-4">
                                                    <a href="{{ $blogUrl }}" class="sis-btn-default d-inline-block">Read More <i class="fa-solid fa-arrow-right-long"></i></a>
                                                </div>
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
        @endif
    </div>
</div>
<!-- Letest Blog Section End -->
<!-- Explore More Section Start -->
<div class="sis-explore-more-section">
    <div class="container">
        <div class="sisf-partners-content-part sis-primary-background sis-radius position-relative">
            <div class="sisf-sis-top-right-image">
                <figure>
                    <img src="/frontend/images/explore-bg.png" alt="explore-bg" decoding="async" loading="lazy">
                </figure>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <!-- Section Title Start -->
                    <div class="sisf-sis-section-title mb-0 ps-5 sis-section-title">
                        <span class="sisf-m-subtitle text-white sis-text-anime-style-3">Explore More. Worry Less.</span>
                        <h2 class="sisf-m-title text-white sis-text-anime-style-3">Book dream vacations with special limited-time offers.</h2>
                        <div class="sisf-m-button pt-3" data-aos="fade-up" data-aos-delay="100">
                            <a href="{{route('lienHe')}}" class="sis-btn-default btn-light">Limited Time Offer<i class="fa-solid fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                    <!-- Section Title End -->
                </div>
                <div class="col-lg-5">
                    <div class="sisf-explore-right-image" data-aos="zoom-in" data-aos-delay="400">
                        <figure>
                            <img src="/frontend/images/explore-img-right.png" alt="explore-img-right" decoding="async" loading="lazy">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Explore More Section End -->
@endsection
