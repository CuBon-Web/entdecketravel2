@extends('layouts.main.master')
@section('title')
{{$product->name}}
@endsection
@section('description')
{{languageName($product->description)}}
@endsection
@section('image')
@php
$img = json_decode($product->images);
$route = json_decode($product->size);
$datetour = json_decode($product->preserve);
$ingredient = json_decode($product->ingredient);
//  dd(json_decode($product->content));
@endphp
{{url(''.$img[0])}}
@endsection
@section('schema')
<script type="application/ld+json">
{!! json_encode([
   '@context' => 'https://schema.org',
   '@type' => 'TouristTrip',
   'name' => $product->name,
   'description' => strip_tags(languageName($product->description)),
   'image' => [url('' . $img[0])],
   'url' => url()->current(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
   '@context' => 'https://schema.org',
   '@type' => 'BreadcrumbList',
   'itemListElement' => [
      [
         '@type' => 'ListItem',
         'position' => 1,
         'name' => 'Home',
         'item' => url('/'),
      ],
      [
         '@type' => 'ListItem',
         'position' => 2,
         'name' => 'Tour',
         'item' => url('/'),
      ],
      [
         '@type' => 'ListItem',
         'position' => 3,
         'name' => $product->name,
         'item' => url()->current(),
      ],
   ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection
@section('css')
@endsection
@section('js')
<script>
function initDialPhones() {
    var nodes = document.querySelectorAll('.js-dial-phone');
    var dataNode = document.getElementById('tour-dial-codes');
    if (!nodes.length || !dataNode) return;
    var dialData = [];
    try {
        dialData = JSON.parse(dataNode.textContent || '[]');
    } catch (err) {
        dialData = [];
    }
    var widgets = [];

    function flagSrc(iso) {
        return 'https://flagcdn.com/w40/' + String(iso || '').toLowerCase() + '.png';
    }
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
            dialBtn.setAttribute('aria-label', 'Country code ' + country.label);
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
            searchInput.setAttribute('aria-label', 'Search country or code');
            searchInput.autocomplete = 'off';
            searchInput.addEventListener('input', function () {
                filterDialMenu(searchInput.value);
            });
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') event.preventDefault();
            });
            searchWrap.appendChild(searchInput);
            var list = document.createElement('div');
            list.className = 'tour-price-dial-list';
            var currentIso = isoInput ? isoInput.value : 'VN';
            dialData.forEach(function (country) {
                var item = document.createElement('button');
                item.type = 'button';
                item.className = 'tour-price-dial-item' + (country.iso === currentIso ? ' is-active' : '');
                item.setAttribute('role', 'option');
                item.setAttribute('data-search', dialSearchText([country.label, country.name, country.iso, country.dial, String(country.dial || '').replace('+', '')].join(' ')));
                item.innerHTML = '<img class="tour-price-flag-img" alt="" src="' + flagSrc(country.iso) + '"><span class="tour-price-dial-name"></span><span class="tour-price-dial-code"></span>';
                item.querySelector('.tour-price-dial-name').textContent = country.label;
                item.querySelector('.tour-price-dial-code').textContent = country.dial;
                item.addEventListener('click', function () {
                    selectDial(country, item);
                    closeDialMenu();
                });
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
            if (searchInput) {
                searchInput.value = '';
                filterDialMenu('');
            }
            dialMenu.hidden = false;
            dialBtn.setAttribute('aria-expanded', 'true');
            var active = dialMenu.querySelector('.tour-price-dial-item.is-active');
            if (active && active.scrollIntoView) active.scrollIntoView({ block: 'nearest' });
            if (searchInput) searchInput.focus();
        }
        function closeDialMenu() {
            dialMenu.hidden = true;
            dialBtn.setAttribute('aria-expanded', 'false');
        }
        dialBtn.addEventListener('click', function () {
            if (dialMenu.hidden) openDialMenu();
            else closeDialMenu();
        });
        widgets.push({ root: root, menu: dialMenu, close: closeDialMenu });
    });

    document.addEventListener('click', function (event) {
        widgets.forEach(function (widget) {
            if (widget.menu.hidden) return;
            if (widget.root.contains(event.target)) return;
            widget.close();
        });
    });
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        widgets.forEach(function (widget) { widget.close(); });
    });
}
function refreshTourPriceCaptcha() {
    var img = document.getElementById('tour-price-captcha-img');
    if (img) {
        img.src = '{{ route('contactCaptcha', ['scope' => 'quote']) }}&t=' + Date.now();
    }
}
document.addEventListener('DOMContentLoaded', function () {
    initDialPhones();

    function setActive(href) {
        var navLinksAll = document.querySelectorAll('#sis-tour-section-nav a[href^="#"], .navbar-one a[href^="#"]');
        Array.prototype.forEach.call(navLinksAll, function (a) {
            a.classList.toggle('active', a.getAttribute('href') === href);
            a.classList.toggle('is-active', a.getAttribute('href') === href);
        });
    }

    var quoteForm = document.getElementById('tour-price-form');
    if (quoteForm) {
        var specificDate = quoteForm.querySelector('input[name="date_type"][value="specific"]');
        var flexibleDate = quoteForm.querySelector('input[name="date_type"][value="flexible"]');
        var dateRow = document.getElementById('tour-price-date-row');
        var dateInput = quoteForm.querySelector('input[name="travel_date"]');

        function syncTravelDate() {
            var isSpecific = specificDate && specificDate.checked;
            if (dateRow) dateRow.style.display = isSpecific ? '' : 'none';
            if (dateInput) dateInput.required = !!isSpecific;
        }
        function syncDateHint() {
            if (!dateRow || !dateInput) return;
            dateRow.classList.toggle('has-value', !!dateInput.value);
        }
        if (specificDate) specificDate.addEventListener('change', syncTravelDate);
        if (flexibleDate) flexibleDate.addEventListener('change', syncTravelDate);
        if (dateInput) {
            dateInput.addEventListener('change', syncDateHint);
            dateInput.addEventListener('input', syncDateHint);
        }
        syncTravelDate();
        syncDateHint();

        quoteForm.addEventListener('submit', function (e) {
            var email = (quoteForm.querySelector('input[name="email"]').value || '').trim();
            var confirmEmail = (quoteForm.querySelector('input[name="email_confirm"]').value || '').trim();
            var alertBox = document.getElementById('tour-price-client-error');
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

        if (document.querySelector('.tour-price-alert')) {
            setActive('#tourprice');
            var quoteSection = document.getElementById('tourprice');
            if (quoteSection) {
                var quoteTop = quoteSection.getBoundingClientRect().top + window.pageYOffset - 100;
                window.scrollTo({ top: quoteTop, behavior: 'smooth' });
            }
        }
    }

    var bookingForm = document.getElementById('booking-enquiry-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            var email = (bookingForm.querySelector('input[name="email"]').value || '').trim();
            var confirmEmail = (bookingForm.querySelector('input[name="email_confirm"]').value || '').trim();
            var alertBox = document.getElementById('booking-client-error');
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
    }
    if (document.querySelector('.booking-enquiry-alert')) {
        var bookingBox = document.getElementById('booking-enquiry-form');
        if (bookingBox) {
            var bookingTop = bookingBox.getBoundingClientRect().top + window.pageYOffset - 100;
            window.scrollTo({ top: bookingTop, behavior: 'smooth' });
        }
    }

    var sectionNav = document.getElementById('sis-tour-section-nav');
    if (sectionNav) {
        var navLinks = Array.prototype.slice.call(sectionNav.querySelectorAll('a[href^="#"]'));
        navLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                var target = document.querySelector(link.getAttribute('href'));
                if (!target) return;
                e.preventDefault();
                var top = target.getBoundingClientRect().top + window.pageYOffset - 110;
                window.scrollTo({ top: top, behavior: 'smooth' });
            });
        });

        var sections = navLinks
            .map(function (link) { return document.querySelector(link.getAttribute('href')); })
            .filter(Boolean);

        function syncSectionNav() {
            var current = sections[0];
            sections.forEach(function (section) {
                if (section.getBoundingClientRect().top <= 140) {
                    current = section;
                }
            });
            navLinks.forEach(function (link) {
                link.classList.toggle('is-active', current && link.getAttribute('href') === '#' + current.id);
            });
        }

        window.addEventListener('scroll', syncSectionNav, { passive: true });
        syncSectionNav();
    }

    if (window.jQuery && $.fn.magnificPopup && $('#sis-td-gallery').length) {
        var $gallery = $('#sis-td-gallery');

        function sisMfpScrollActiveThumb() {
            var mfp = $.magnificPopup.instance;
            if (!mfp || !mfp.wrap) return;
            var $active = $(mfp.wrap).find('.sis-mfp-thumb.is-active');
            var track = $(mfp.wrap).find('.sis-mfp-thumbs-track').get(0);
            if (!$active.length || !track) return;
            var thumb = $active.get(0);
            var left = thumb.offsetLeft - (track.clientWidth / 2) + (thumb.clientWidth / 2);
            track.scrollTo({ left: Math.max(0, left), behavior: 'smooth' });
        }

        function sisMfpBuildThumbs() {
            var mfp = $.magnificPopup.instance;
            if (!mfp || !mfp.wrap) return;
            var $wrap = $(mfp.wrap);
            $wrap.find('.sis-mfp-thumbs').remove();
            $wrap.removeClass('mfp-with-thumbs');

            var $links = $gallery.find('a[href]');
            if ($links.length < 2) return;

            var $strip = $('<div class="sis-mfp-thumbs mfp-prevent-close" aria-label="Gallery thumbnails"></div>');
            var $track = $('<div class="sis-mfp-thumbs-track mfp-prevent-close"></div>');

            $links.each(function (index) {
                var $el = $(this);
                var src = $el.find('img').first().attr('src') || $el.attr('href');
                if (!src) return;
                var $btn = $('<button type="button" class="sis-mfp-thumb mfp-prevent-close" aria-label="Image ' + (index + 1) + '"></button>');
                $btn.append($('<img>').attr({ src: src, alt: '' }));
                if (index === mfp.index) $btn.addClass('is-active');
                $btn.on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (index !== mfp.index) mfp.goTo(index);
                });
                $track.append($btn);
            });

            if (!$track.children().length) return;
            $strip.append($track);
            $wrap.addClass('mfp-with-thumbs').find('.mfp-container').append($strip);
            sisMfpScrollActiveThumb();
        }

        function sisMfpSyncThumbs() {
            var mfp = $.magnificPopup.instance;
            if (!mfp || !mfp.wrap) return;
            var $thumbs = $(mfp.wrap).find('.sis-mfp-thumb');
            if (!$thumbs.length) {
                sisMfpBuildThumbs();
                return;
            }
            $thumbs.removeClass('is-active').eq(mfp.index).addClass('is-active');
            sisMfpScrollActiveThumb();
        }

        $gallery.magnificPopup({
            delegate: 'a[href]',
            type: 'image',
            tLoading: 'Loading image #%curr%...',
            mainClass: 'mfp-fade sis-mfp-gallery',
            closeOnContentClick: false,
            closeBtnInside: true,
            fixedContentPos: true,
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                verticalFit: true,
                titleSrc: function (item) {
                    return item.el.attr('title') || '';
                }
            },
            callbacks: {
                open: sisMfpBuildThumbs,
                change: sisMfpSyncThumbs,
                close: function () {
                    var mfp = $.magnificPopup.instance;
                    if (mfp && mfp.wrap) {
                        $(mfp.wrap).removeClass('mfp-with-thumbs').find('.sis-mfp-thumbs').remove();
                    }
                }
            }
        });

        $(document).on('click', '#sis-td-gallery-open', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var items = $gallery.find('a[href]');
            if (items.length) {
                items.eq(0).trigger('click');
            }
        });
    }
});
</script>
@endsection
@section('content')
@php
    $tourName = languageName($product->name);
    $gallery = array_values(array_filter((array) $img));
    $bannerImg = $gallery[0] ?? '';
    $adultPrice = (float) languageName($product->price);
    $listPrice = (float) languageName($product->discount);
    $durationLabel = trim((string) ($product->hang_muc ?? ''));
    $ingredient = (!empty($ingredient) && is_array($ingredient)) ? $ingredient : [];
    $dialCountries = json_decode(@file_get_contents(public_path('frontend/js/dial-codes.json')), true) ?: [];
    $fallbackDial = ['iso' => 'VN', 'dial' => '+84', 'name' => 'Vietnam', 'label' => 'Vietnam (Việt Nam)'];
    $pickDial = function ($iso) use ($dialCountries, $fallbackDial) {
        foreach ($dialCountries as $dialCountry) {
            if (($dialCountry['iso'] ?? '') === $iso) {
                return $dialCountry;
            }
        }
        return $fallbackDial;
    };
    $activeForm = old('form_type');
    $quoteDial = $pickDial($activeForm === 'quote' ? old('phone_iso', 'VN') : 'VN');
    $enquiryDial = $pickDial($activeForm === 'enquiry' ? old('phone_iso', 'VN') : 'VN');
    $nationalities = ['Vietnamese', 'American', 'Argentine', 'Australian', 'Austrian', 'Belgian', 'Brazilian', 'British', 'Cambodian', 'Canadian', 'Chinese', 'Danish', 'Dutch', 'Filipino', 'Finnish', 'French', 'German', 'Indian', 'Indonesian', 'Irish', 'Italian', 'Japanese', 'Korean', 'Laotian', 'Malaysian', 'Mexican', 'New Zealander', 'Norwegian', 'Russian', 'Singaporean', 'Spanish', 'Swedish', 'Swiss', 'Taiwanese', 'Thai'];
    $priceIncludes = array_values(array_filter((array) json_decode($product->price_includes, true), function ($item) {
        return trim((string) $item) !== '';
    }));
    $priceExcludes = array_values(array_filter((array) json_decode($product->price_excludes, true), function ($item) {
        return trim((string) $item) !== '';
    }));
    $displayPrice = $adultPrice > 0 ? number_format($adultPrice) . ' VND' : 'Contact Us';
@endphp
<div class="page-content sis-tour-detail-page sis-td-v2">
    <script>document.documentElement.classList.add('sis-td-page');document.body.classList.add('sis-td-page');</script>

    <div class="sisf-banner position-relative sis-product-list-banner sis-tour-detail-banner"@if(!empty($bannerImg)) style="background-image: url({{ url('' . $bannerImg) }});"@endif>
        <div class="sisf-page-title">
            <div class="container">
                <div class="sisf-m-inner">
                    <h1 class="sisf-m-title text-white">{{ $tourName }}</h1>
                    <div class="sisf-breadcrumbs mt-3">
                        <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}"><span>Home</span></a>
                        <span class="text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                        <a class="sisf-breadcrumbs-link text-white" href="{{ route('allProduct') }}"><span>Tours</span></a>
                        <span class="text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                        <span class="text-white">{{ $tourName }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($gallery) > 0)
    <section class="sis-td-hero">
        <div class="container">
            <div id="sis-td-gallery" class="sis-td-mosaic sis-td-magnific-gallery {{ count($gallery) === 1 ? 'is-single' : (count($gallery) < 4 ? 'is-compact' : '') }}">
                <a class="sis-td-mosaic-main" href="{{ url('' . $gallery[0]) }}" title="{{ $tourName }}">
                    <img src="{{ url('' . $gallery[0]) }}" alt="{{ $tourName }}">
                </a>
                @if(count($gallery) > 1)
                <div class="sis-td-mosaic-side">
                    @foreach(array_slice($gallery, 1, 4) as $gIndex => $gImg)
                        <a class="sis-td-mosaic-thumb {{ $gIndex === 3 && count($gallery) > 5 ? 'has-more' : '' }}" href="{{ url('' . $gImg) }}" title="{{ $tourName }}">
                            <img src="{{ url('' . $gImg) }}" alt="{{ $tourName }}">
                            @if($gIndex === 3 && count($gallery) > 5)
                                <span class="sis-td-mosaic-count">+{{ count($gallery) - 5 }} photos</span>
                            @endif
                        </a>
                    @endforeach
                </div>
                @endif
                @if(count($gallery) > 5)
                <div class="d-none">
                    @foreach(array_slice($gallery, 5) as $extra)
                        <a href="{{ url('' . $extra) }}" title="{{ $tourName }}"></a>
                    @endforeach
                </div>
                @endif
                <button type="button" class="sis-td-mosaic-btn" id="sis-td-gallery-open">
                    <i class="fa-regular fa-images"></i> Show all photos
                </button>
            </div>
        </div>
    </section>
    @endif

    <section class="sis-td-intro">
        <div class="container">
            <div class="sis-td-intro-inner">
                <div class="sis-td-intro-main">
                    <h2 class="sis-td-title">{{ $tourName }}</h2>
                    <div class="sis-td-chips">
                        @if($durationLabel !== '')
                        <span class="sis-td-chip"><i class="fa-regular fa-clock"></i> {{ $durationLabel }}</span>
                        @endif
                        @if(count($ingredient) > 0)
                        <span class="sis-td-chip"><i class="fa-solid fa-route"></i> {{ count($ingredient) }} {{ count($ingredient) === 1 ? 'Day' : 'Days' }} itinerary</span>
                        @endif
                        @if(count($gallery) > 0)
                        <span class="sis-td-chip"><i class="fa-regular fa-image"></i> {{ count($gallery) }} photos</span>
                        @endif
                    </div>
                    @php
                        $placeTitles = [];
                        foreach ($ingredient as $item) {
                            $placeTitle = trim((string) languageName(json_encode($item->title ?? '')));
                            if ($placeTitle !== '' && strtolower($placeTitle) !== 'null') {
                                $placeTitles[] = $placeTitle;
                            }
                        }
                    @endphp
                    @if(count($placeTitles) > 0)
                    <p class="sis-td-places">
                        <i class="fa-solid fa-location-dot"></i>
                        @foreach ($placeTitles as $key => $placeTitle)
                            {{ $placeTitle }}@if($key < count($placeTitles) - 1)<span>·</span>@endif
                        @endforeach
                    </p>
                    @endif
                </div>
                <div class="sis-td-intro-price d-none d-lg-flex">
                    <div>
                        <span class="sis-td-price-label">From</span>
                        <div class="sis-td-price-now">
                            @if ($adultPrice > 0)
                                @if ($listPrice > $adultPrice)
                                    <s>{{ number_format($listPrice) }}</s>
                                @endif
                                <strong>{{ number_format($adultPrice) }} <small>VND</small></strong>
                            @else
                                <strong>Contact Us</strong>
                            @endif
                        </div>
                        @if ($adultPrice > 0)
                        <span class="sis-td-price-unit">per person</span>
                        @endif
                    </div>
                    <a href="#booking" class="sis-btn-default">Book now <i class="fa-solid fa-arrow-right-long"></i></a>
                </div>
            </div>

            <nav class="sis-td-tabs" id="sis-tour-section-nav">
                <a href="#overview">Overview</a>
                @if (count($ingredient) > 0)
                <a href="#itinerary">Itinerary</a>
                @endif
                <a href="#tourprice">Get a quote</a>
                <a href="#includes">Includes</a>
                <a href="#booking" class="d-lg-none">Book</a>
            </nav>
        </div>
    </section>

    <section class="sis-td-content-wrap">
        <div class="container">
            <div class="sis-td-layout">
                <main class="sis-td-main">

                    <article id="overview" class="sis-td-panel">
                        <div class="sis-td-panel-head">
                            <h2>Overview</h2>
                            <p>What makes this journey special</p>
                        </div>
                        <div class="sis-tour-content content">
                            {!! languageName($product->content) !!}
                        </div>
                    </article>

                    @if (count($ingredient) > 0)
                    <article id="itinerary" class="sis-td-panel">
                        <div class="sis-td-panel-head">
                            <h2>Day-by-day itinerary</h2>
                            <p>{{ count($ingredient) }} days carefully planned for you</p>
                        </div>
                        <div class="sis-td-itinerary" id="sis-td-itinerary">
                            @foreach ($ingredient as $key => $item)
                            @php
                                $dayTitle = languageName(json_encode($item->title ?? ''));
                                $dayContent = languageName(json_encode($item->content ?? ''));
                            @endphp
                            <details class="sis-td-day" {{ $key === 0 ? 'open' : '' }}>
                                <summary>
                                    <span class="sis-td-day-badge">Day {{ $key + 1 }}</span>
                                    <span class="sis-td-day-title">{{ $dayTitle }}</span>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </summary>
                                <div class="sis-td-day-body">
                                    @if ($dayContent !== strip_tags($dayContent))
                                        <div class="itinerary-html">{!! $dayContent !!}</div>
                                    @else
                                        <p>{!! nl2br(e($dayContent)) !!}</p>
                                    @endif
                                </div>
                            </details>
                            @endforeach
                        </div>
                    </article>
                    @endif

                    <article id="tourprice" class="sis-td-panel">
                        <div class="sis-td-panel-head">
                            <h2>Get a tailored quote</h2>
                            <p>Prices vary by dates, group size and hotel options. Send your details and we’ll reply with a custom quote.</p>
                        </div>

                        @if(session('quote_error'))
                        <div class="alert alert-danger tour-price-alert">{{ session('quote_error') }}</div>
                        @endif
                        @if($errors->any() && old('form_type') === 'quote')
                        <div class="alert alert-danger tour-price-alert">{{ $errors->first() }}</div>
                        @endif
                        <div id="tour-price-client-error" class="alert alert-danger" style="display:none;"></div>

                        <form id="tour-price-form" class="sis-td-form tour-price-form" method="post" action="{{ route('postBookTour') }}">
                            @csrf
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="sis-td-honeypot" aria-hidden="true">
                            <input type="hidden" name="form_type" value="quote">
                            <input type="hidden" name="tour_id" value="{{ $product->id }}">
                            <input type="hidden" name="service_id" value="">
                            <input type="hidden" name="total" value="0">
                            <script type="application/json" id="tour-dial-codes">{!! json_encode($dialCountries, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>

                            <div class="sis-td-form-grid">
                                <div class="sis-td-field sis-td-field-full">
                                    <span class="sis-td-label">Travel date</span>
                                    <div class="sis-td-radios tour-price-radios">
                                        <label class="sis-td-radio">
                                            <input type="radio" name="date_type" value="specific" {{ (old('form_type') === 'quote' ? old('date_type', 'specific') : 'specific') === 'specific' ? 'checked' : '' }}>
                                            <span>Specific Date</span>
                                        </label>
                                        <label class="sis-td-radio">
                                            <input type="radio" name="date_type" value="flexible" {{ old('form_type') === 'quote' && old('date_type') === 'flexible' ? 'checked' : '' }}>
                                            <span>Flexible Date</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="sis-td-field sis-td-field-full tour-price-row-follow {{ old('form_type') === 'quote' && old('travel_date') ? 'has-value' : '' }}" id="tour-price-date-row">
                                    <label class="sis-td-label" for="tour-price-date-input">Select date</label>
                                    <div class="tour-price-field tour-price-date">
                                        <i class="bi bi-calendar3"></i>
                                        <span class="tour-price-date-hint">Specific Date</span>
                                        <input id="tour-price-date-input" type="date" name="travel_date" value="{{ old('form_type') === 'quote' ? old('travel_date') : '' }}" min="{{ date('Y-m-d') }}" aria-label="Specific Date" required>
                                    </div>
                                </div>

                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="tour-price-name">Full name</label>
                                    <div class="tour-price-field">
                                        <input id="tour-price-name" type="text" name="name" placeholder="Your full name" required maxlength="191" value="{{ old('form_type') === 'quote' ? old('name') : '' }}">
                                    </div>
                                </div>

                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="tour-price-phone">Phone</label>
                                    <div class="tour-price-phone js-dial-phone">
                                        <button type="button" class="tour-price-dial-btn" data-dial-btn aria-haspopup="listbox" aria-expanded="false" aria-label="Country code {{ $quoteDial['label'] }}">
                                            <img class="tour-price-flag-img" data-dial-flag src="https://flagcdn.com/w40/{{ strtolower($quoteDial['iso']) }}.png" alt="">
                                            <span data-dial-label>{{ $quoteDial['dial'] }}</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </button>
                                        <input id="tour-price-phone" type="tel" name="phone" placeholder="Your phone number" required maxlength="30" value="{{ old('form_type') === 'quote' ? old('phone') : '' }}">
                                        <div class="tour-price-dial-menu" data-dial-menu role="listbox" hidden></div>
                                        <input type="hidden" name="phone_code" data-dial-code value="{{ $quoteDial['dial'] }}">
                                        <input type="hidden" name="phone_country" data-dial-country value="{{ $quoteDial['name'] }}">
                                        <input type="hidden" name="phone_iso" data-dial-iso value="{{ $quoteDial['iso'] }}">
                                    </div>
                                </div>

                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="tour-price-email">Email</label>
                                    <div class="tour-price-field">
                                        <i class="bi bi-envelope"></i>
                                        <input id="tour-price-email" type="email" name="email" placeholder="Your email address" required maxlength="191" value="{{ old('form_type') === 'quote' ? old('email') : '' }}">
                                    </div>
                                </div>

                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="tour-price-email-confirm">Confirm email</label>
                                    <div class="tour-price-field">
                                        <i class="bi bi-envelope"></i>
                                        <input id="tour-price-email-confirm" type="email" name="email_confirm" placeholder="Your email address" required maxlength="191" value="{{ old('form_type') === 'quote' ? old('email_confirm') : '' }}">
                                    </div>
                                </div>

                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="tour-price-hotel">Hotel category</label>
                                    <div class="tour-price-field">
                                        <select id="tour-price-hotel" name="hotel_category" required>
                                            @foreach (['2 Stars', '3 Stars', '4 Stars', '5 Stars', 'Cruise'] as $hotel)
                                            <option value="{{ $hotel }}" {{ (old('form_type') === 'quote' ? old('hotel_category', '3 Stars') : '3 Stars') === $hotel ? 'selected' : '' }}>{{ $hotel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="tour-price-travellers">Travellers</label>
                                    <div class="tour-price-field">
                                        <select id="tour-price-travellers" name="travellers" required>
                                            @for ($persons = 1; $persons <= 20; $persons++)
                                            <option value="{{ $persons }}" {{ (int) (old('form_type') === 'quote' ? old('travellers', 2) : 2) === $persons ? 'selected' : '' }}>{{ $persons }} {{ $persons === 1 ? 'Person' : 'Persons' }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="sis-td-field sis-td-field-full">
                                    <span class="sis-td-label">Verification code</span>
                                    <div class="tour-price-captcha">
                                        <div class="simple-captcha-box">
                                            <img id="tour-price-captcha-img" class="simple-captcha-image" src="{{ route('contactCaptcha', ['scope' => 'quote']) }}" alt="Captcha">
                                            <button type="button" class="simple-captcha-refresh" onclick="refreshTourPriceCaptcha()">Refresh</button>
                                        </div>
                                        <input class="form-control mt-2" name="captcha_code" type="text" required maxlength="4" pattern="[0-9]{4}" inputmode="numeric" autocomplete="off" placeholder="Enter 4 digits in the image">
                                    </div>
                                </div>
                            </div>

                            <div class="sis-td-form-actions">
                                <button type="submit" class="sis-btn-default">Get Quote <i class="fa-solid fa-arrow-right-long"></i></button>
                            </div>
                        </form>
                    </article>

                    <article id="includes" class="sis-td-panel">
                        <div class="sis-td-panel-head">
                            <h2>What’s included</h2>
                            <p>Transparent pricing — know exactly what you get</p>
                        </div>
                        <div class="sis-td-includes">
                            <div class="sis-td-include-card is-in">
                                <h3><i class="fa-solid fa-circle-check"></i> Included</h3>
                                @if (count($priceIncludes))
                                <ul>
                                    @foreach ($priceIncludes as $item)
                                    <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="sis-td-empty">Details will be confirmed in your quote.</p>
                                @endif
                            </div>
                            <div class="sis-td-include-card is-out">
                                <h3><i class="fa-solid fa-circle-xmark"></i> Not included</h3>
                                @if (count($priceExcludes))
                                <ul>
                                    @foreach ($priceExcludes as $item)
                                    <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="sis-td-empty">Details will be confirmed in your quote.</p>
                                @endif
                            </div>
                        </div>
                    </article>

                </main>

                <aside class="sis-td-aside" id="booking">
                    <div class="sis-td-book-card">
                        <div class="sis-td-book-head">
                            <h3>Book this tour</h3>
                            <p>Share a few details — a specialist will contact you shortly.</p>
                        </div>

                        <form id="booking-enquiry-form" class="sis-td-form booking-enquiry" method="post" action="{{ route('postBookTour') }}">
                            @csrf
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="sis-td-honeypot" aria-hidden="true">
                            <input type="hidden" name="form_type" value="enquiry">
                            <input type="hidden" name="tour_id" value="{{ $product->id }}">
                            <input type="hidden" name="service_id" value="{{ isset($servicereqest) ? json_encode($servicereqest) : '' }}">
                            <input type="hidden" name="total" value="0">
                            @php $isEnquiryOld = old('form_type') === 'enquiry'; @endphp
                            @if(session('error'))
                            <div class="alert alert-danger booking-enquiry-alert">{{ session('error') }}</div>
                            @endif
                            <div id="booking-client-error" class="alert alert-danger" style="display:none;"></div>

                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-hotel">Hotel category</label>
                                <select id="booking-hotel" class="form-control" name="hotel_category" required>
                                    <option value="" {{ $isEnquiryOld && old('hotel_category') ? '' : 'selected' }} disabled>Select hotel</option>
                                    @foreach (['2 Stars', '3 Stars', '4 Stars', '5 Stars', 'Cruise'] as $hotel)
                                    <option value="{{ $hotel }}" {{ $isEnquiryOld && old('hotel_category') === $hotel ? 'selected' : '' }}>{{ $hotel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sis-td-field-row">
                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="booking-people">Travellers</label>
                                    <select id="booking-people" class="form-control" name="travellers" required>
                                        <option value="" {{ $isEnquiryOld && old('travellers') ? '' : 'selected' }} disabled>Guests</option>
                                        @for ($persons = 1; $persons <= 20; $persons++)
                                        <option value="{{ $persons }}" {{ $isEnquiryOld && (int) old('travellers') === $persons ? 'selected' : '' }}>{{ $persons }} {{ $persons === 1 ? 'Person' : 'Persons' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="sis-td-field">
                                    <label class="sis-td-label" for="booking-date">Travel date</label>
                                    <input id="booking-date" class="form-control" type="date" name="travel_date" required min="{{ date('Y-m-d') }}" value="{{ $isEnquiryOld ? old('travel_date') : '' }}">
                                </div>
                            </div>
                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-name">Full name</label>
                                <input id="booking-name" class="form-control" type="text" placeholder="Your full name" name="name" required maxlength="191" value="{{ $isEnquiryOld ? old('name') : '' }}">
                            </div>
                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-email">Email</label>
                                <input id="booking-email" class="form-control" type="email" placeholder="example@mail.com" name="email" required maxlength="191" value="{{ $isEnquiryOld ? old('email') : '' }}">
                            </div>
                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-email-confirm">Confirm email</label>
                                <input id="booking-email-confirm" class="form-control" type="email" placeholder="example@mail.com" name="email_confirm" required maxlength="191" value="{{ $isEnquiryOld ? old('email_confirm') : '' }}">
                            </div>
                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-phone">Phone *</label>
                                <div class="tour-price-phone js-dial-phone">
                                    <button type="button" class="tour-price-dial-btn" data-dial-btn aria-haspopup="listbox" aria-expanded="false" aria-label="Country code {{ $enquiryDial['label'] }}">
                                        <img class="tour-price-flag-img" data-dial-flag src="https://flagcdn.com/w40/{{ strtolower($enquiryDial['iso']) }}.png" alt="">
                                        <span data-dial-label>{{ $enquiryDial['dial'] }}</span>
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                    <input id="booking-phone" type="tel" name="phone" placeholder="Your telephone" required maxlength="30" value="{{ $isEnquiryOld ? old('phone') : '' }}">
                                    <div class="tour-price-dial-menu" data-dial-menu role="listbox" hidden></div>
                                    <input type="hidden" name="phone_code" data-dial-code value="{{ $enquiryDial['dial'] }}">
                                    <input type="hidden" name="phone_country" data-dial-country value="{{ $enquiryDial['name'] }}">
                                    <input type="hidden" name="phone_iso" data-dial-iso value="{{ $enquiryDial['iso'] }}">
                                </div>
                                <p class="booking-phone-note">* Backup contact if email fails</p>
                            </div>
                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-nationality">Nationality</label>
                                <select id="booking-nationality" class="form-control" name="nationality" required>
                                    @foreach ($nationalities as $nationality)
                                    <option value="{{ $nationality }}" {{ ($isEnquiryOld ? old('nationality', 'Vietnamese') : 'Vietnamese') === $nationality ? 'selected' : '' }}>{{ $nationality }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sis-td-field">
                                <label class="sis-td-label" for="booking-message">Message</label>
                                <textarea id="booking-message" class="form-control" placeholder="Tell us your preferences…" name="note" maxlength="5000">{{ $isEnquiryOld ? old('note') : '' }}</textarea>
                            </div>
                            @include('layouts.partials.simple-captcha')
                            <button type="submit" class="sis-btn-default w-100 justify-content-center">Send enquiry <i class="fa-solid fa-arrow-right-long"></i></button>
                        </form>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if(isset($productlq) && count($productlq) > 0)
    <section class="sis-tour-related-section">
        <div class="container">
            <div class="sisf-sis-section-title text-center sis-section-title">
                <span class="sisf-m-subtitle">Explore more</span>
                <h2 class="sisf-m-title">You may also <span class="sisf-e-colored">like</span></h2>
            </div>
            <div class="row">
                @foreach ($productlq as $item)
                    <div class="col-xl-3 col-lg-4 col-sm-6 m-b30">
                        @include('layouts.product.item', ['pro' => $item])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="sis-td-mobile-bar" id="sis-td-mobile-bar">
        <div class="sis-td-mobile-bar-price">
            <span>From</span>
            <strong>{{ $displayPrice }}</strong>
        </div>
        <a href="#booking" class="sis-btn-default">Book now</a>
    </div>

</div>
@endsection
