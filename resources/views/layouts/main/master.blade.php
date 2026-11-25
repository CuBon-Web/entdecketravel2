<!-- https://traviox-html.wpthemeverse.com/home-slider.html -->
<!DOCTYPE html>
<html lang="en" data-x="html" data-x-toggle="html-overflow-hidden">
   <head>
      @php
         $brandName = config('app.name', 'Entdecke Vietnam');
         // Decode once: child @section often uses {{ }} which already escapes & → &amp;
         $seoTitle = html_entity_decode(strip_tags(trim($__env->yieldContent('title', $brandName))), ENT_QUOTES | ENT_HTML5, 'UTF-8');
         $seoDescriptionRaw = html_entity_decode(trim($__env->yieldContent('description', $brandName)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
         $seoDescription = \Illuminate\Support\Str::limit(strip_tags($seoDescriptionRaw), 160, '');
         $seoImage = trim($__env->yieldContent('image', url('' . $setting->favicon)));
         $seoCanonical = trim($__env->yieldContent('canonical', url()->current()));
         $seoRobots = trim($__env->yieldContent('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1'));
         $ga4Id = trim((string) ($setting->ga4_id ?? ''));
         $gtmId = trim((string) ($setting->gtm_id ?? ''));
         if ($ga4Id !== '' && !preg_match('/^G-[A-Z0-9]+$/i', $ga4Id)) {
            $ga4Id = '';
         }
         if ($gtmId !== '' && !preg_match('/^GTM-[A-Z0-9]+$/i', $gtmId)) {
            $gtmId = '';
         }
      @endphp
      <meta charset="UTF-8" />
      @if($gtmId !== '')
      <!-- Google Tag Manager -->
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
      <!-- End Google Tag Manager -->
      @endif
      @if($ga4Id !== '')
      <!-- Google Analytics 4 -->
      <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $ga4Id }}');
      </script>
      <!-- End Google Analytics 4 -->
      @endif
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>{{ $seoTitle }}</title>
      <meta name="description" content="{{ $seoDescription }}" />
      <meta name="robots" content="{{ $seoRobots }}" />
      <meta name="googlebot" content="{{ $seoRobots }}" />
      <meta name="theme-color" content="#ed3235" />
      <meta name="author" content="{{ $brandName }}" />
      <meta name="application-name" content="{{ $brandName }}" />
      <meta http-equiv="Content-Language" content="en" />
      <link rel="canonical" href="{{ $seoCanonical }}" />
      <link rel="alternate" href="{{ $seoCanonical }}" hreflang="en" />
      <link rel="alternate" href="{{ $seoCanonical }}" hreflang="x-default" />
      <link rel="describedby" href="/llms.txt" type="text/plain" />

      <meta property="og:type" content="website" />
      <meta property="og:locale" content="en_US" />
      <meta property="og:url" content="{{ $seoCanonical }}" />
      <meta property="og:title" content="{{ $seoTitle }}" />
      <meta property="og:description" content="{{ $seoDescription }}" />
      <meta property="og:image" content="{{ $seoImage }}" />
      <meta property="og:image:secure_url" content="{{ $seoImage }}" />
      <meta property="og:image:alt" content="{{ $seoTitle }}" />
      <meta property="og:site_name" content="{{ $brandName }}" />

      <meta name="twitter:card" content="summary_large_image" />
      <meta name="twitter:title" content="{{ $seoTitle }}" />
      <meta name="twitter:description" content="{{ $seoDescription }}" />
      <meta name="twitter:image" content="{{ $seoImage }}" />
      <meta name="twitter:url" content="{{ $seoCanonical }}" />

      <meta itemprop="name" content="{{ $seoTitle }}" />
      <meta itemprop="description" content="{{ $seoDescription }}" />
      <meta itemprop="image" content="{{ $seoImage }}" />
      <meta itemprop="url" content="{{ $seoCanonical }}" />
    <!-- <link rel="amphtml" href="amp/" /> -->
    <link rel="image_src" href="{{ $seoImage }}" />
    <script type="application/ld+json">
      {!! json_encode([
         '@context' => 'https://schema.org',
         '@type' => 'Organization',
         'name' => $brandName,
         'url' => url('/'),
         'logo' => url('' . $setting->logo),
      ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
      {!! json_encode([
         '@context' => 'https://schema.org',
         '@type' => 'WebSite',
         'name' => $brandName,
         'inLanguage' => 'en',
         'url' => url('/'),
         'publisher' => [
            '@type' => 'Organization',
            'name' => $brandName,
         ],
         'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
         ],
      ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @yield('schema')
    <link rel="shortcut icon" href="{{url(''.$setting->favicon)}}" type="image/x-icon">
    <link rel="icon" href="{{url(''.$setting->favicon)}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
     <!-- Bootstrap Css Link -->
    <link href="{{url('frontend/css/bootstrap.min.css')}}" rel="stylesheet" media="screen">
    <!-- Swiper Css -->
    <link rel="stylesheet" href="{{url('frontend/css/swiper-bundle.min.css')}}">
    <!-- Mouse Cursor Css File -->
    <link rel="stylesheet" href="{{url('frontend/css/mousecursor.css')}}">
    <!-- Magnific Popup Css File -->
    <link rel="stylesheet" href="{{url('frontend/css/magnific-popup.css')}}">
    <!-- Font Awesome Icon Css-->
    <link href="{{url('frontend/css/all.min.css')}}" rel="stylesheet" media="screen">
    <!-- SlickNav Css -->
    <link href="{{url('frontend/css/slicknav.min.css')}}" rel="stylesheet">
    <!-- Animation On Scroll Css File -->
    <link href="{{url('frontend/css/aos.css')}}" rel="stylesheet">
    <!-- Google Fonts Link-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Lexend Serif Fonts Link -->
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet">
    <!-- Onest Fonts Link -->
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <!-- Main Custom Css -->
    <link href="{{url('frontend/css/custom.css')}}" rel="stylesheet" media="screen">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      @yield('css')
      <style>
         .goog-te-banner-frame.skiptranslate{display:none !important;}
         iframe.goog-te-banner-frame{display:none !important;}
         body > .skiptranslate{display:none !important;}
         #goog-gt-tt, .goog-te-balloon-frame{display:none !important;}
         .goog-logo-link, .goog-te-gadget span{display:none !important;}
         .goog-te-gadget{font-size:0 !important; height:0 !important; overflow:hidden !important;}
         .goog-te-combo{display:none !important;}
         html{margin-top:0 !important;}
         body{top:0 !important; position:static !important;}
         .sis-google-translate{position:absolute;width:0;height:0;overflow:hidden;opacity:0;pointer-events:none;}
      </style>
   </head>
   <body>
      @if($gtmId !== '')
      <!-- Google Tag Manager (noscript) -->
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <!-- End Google Tag Manager (noscript) -->
      @endif
      <!-- Preloader Start -->
    <div class="sis-preloader">
        <div class="sis-loading-container rounded-0">
            <div class="sis-loading"></div>
            <div class="sis-loading-icon">
                <figure class="sis-reveal">
                    <img src="https://traviox-html.wpthemeverse.com/images/loader.png" alt="Traviox">
                </figure>
            </div>
        </div>
    </div>
    <!-- Preloader End -->
    <!-- Header New Start -->
    @include('layouts.header.index')
    <!-- Header New End -->
    @yield('content')
   @include('layouts.footer.index')
    <!-- Cursor Start -->
    <div class="sisf-cursor sisf-js-cursor">
        <div class="sisf-cursor-wrapper">
            <div class="sisf-cursor--follower sisf-js-follower"></div>
            <div class="sisf-cursor--label sisf-js-label"></div>
            <div class="sisf-cursor--drap sisf-js-drap"></div>
            <div class="sisf-cursor--icon sisf-js-icon"></div>
        </div>
    </div>
    <!-- Cursor End -->
    @include('layouts.partials.call-buttons')
    <!-- Back to Top Button Start -->
    <div class="sis-back-to-top-button">
        <button class="sis-back-to-top" id="backToTop">
            <span class="mt-1"><i class="fa fa-chevron-up"></i></span>
        </button>
    </div>
    <!-- Back to Top Button End -->
      
      
      
     <!-- Jquery Library File -->
    <script src="{{url('frontend/js/jquery-3.7.1.min.js')}}"></script>
    <!-- Bootstrap js File -->
    <script src="{{url('frontend/js/bootstrap.min.js')}}"></script>
    <!-- Validator js file -->
    <script src="{{url('frontend/js/validator.min.js')}}"></script>
    <!-- SlickNav js File -->
    <script src="{{url('frontend/js/jquery.slicknav.js')}}"></script>
    <!-- Swiper js File -->
    <script src="{{url('frontend/js/swiper-bundle.min.js')}}"></script>
    <!-- Animation On Scroll Js File -->
    <script src="{{url('frontend/js/aos.js')}}"></script>
    <!-- Counter js File -->
    <script src="{{url('frontend/js/jquery.waypoints.min.js')}}"></script>
    <script src="{{url('frontend/js/jquery.counterup.min.js')}}"></script>
    <!-- Magnific js File -->
    <script src="{{url('frontend/js/jquery.magnific-popup.min.js')}}"></script>
    <!-- Gsap Js File -->
    <script src="{{url('frontend/js/gsap.min.js')}}"></script>
    <!-- Cursor Js File -->
    <script src="{{url('frontend/js/magiccursor.js')}}"></script>
    <script src="{{url('frontend/js/cursor/cursor.js')}}"></script>
    <!-- Text Effect js file -->
    <script src="{{url('frontend/js/ScrollTrigger.min.js')}}"></script>
    <script src="{{url('frontend/js/SplitText.js')}}"></script>
    <!-- Main Custom js file -->
    <script src="{{url('frontend/js/script.js')}}"></script>
      @yield('js')
      <script>
         (function () {
            var root = document.getElementById('sis-call-buttons');
            var toggle = document.getElementById('sis-call-toggle');
            var actions = document.getElementById('sis-call-actions');
            if (!root || !toggle || !actions) return;

            toggle.addEventListener('click', function () {
               var isOpen = root.classList.toggle('is-open');
               toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
               if (isOpen) {
                  actions.removeAttribute('hidden');
               } else {
                  actions.setAttribute('hidden', '');
               }
            });
         })();
      </script>
      <script>
         function googleTranslateElementInit() {
            new google.translate.TranslateElement({
               pageLanguage: 'en',
               includedLanguages: 'en,vi,ja,ko,es,it',
               autoDisplay: false
            }, 'google_translate_element');
         }

         (function () {
            var LANGS = {
               en: 'EN',
               vi: 'VI',
               ja: 'JP',
               ko: 'KR',
               es: 'ES',
               it: 'IT'
            };

            function getCookie(name) {
               var match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
               return match ? decodeURIComponent(match[1]) : '';
            }

            function setCookie(name, value, days) {
               var expires = '';
               if (typeof days === 'number') {
                  var date = new Date();
                  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                  expires = '; expires=' + date.toUTCString();
               }
               document.cookie = name + '=' + value + expires + '; path=/';
               document.cookie = name + '=' + value + expires + '; path=/; domain=.' + location.hostname;
            }

            function clearGoogleTransCookie() {
               var domains = ['', location.hostname, '.' + location.hostname];
               domains.forEach(function (domain) {
                  var base = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                  document.cookie = domain ? base + '; domain=' + domain : base;
               });
            }

            function currentLang() {
               var cookie = getCookie('googtrans');
               if (!cookie) return 'en';
               var parts = cookie.split('/');
               return parts[2] || 'en';
            }

            function syncUi(lang) {
               var label = LANGS[lang] || 'EN';
               var current = document.getElementById('sis-lang-current');
               if (current) current.textContent = label;
               document.querySelectorAll('.sis-lang-option').forEach(function (btn) {
                  btn.classList.toggle('is-active', btn.getAttribute('data-lang') === lang);
               });
            }

            function applyGoogleLang(lang) {
               var combo = document.querySelector('.goog-te-combo');
               if (!combo) return false;
               if (combo.value === lang) return true;
               combo.value = lang;
               combo.dispatchEvent(new Event('change'));
               return true;
            }

            function changeLanguage(lang) {
               if (!LANGS[lang]) lang = 'en';
               if (lang === 'en') {
                  clearGoogleTransCookie();
                  if (!applyGoogleLang('en')) {
                     location.reload();
                     return;
                  }
               } else {
                  setCookie('googtrans', '/en/' + lang, 30);
                  if (!applyGoogleLang(lang)) {
                     location.reload();
                     return;
                  }
               }
               syncUi(lang);
            }

            document.addEventListener('DOMContentLoaded', function () {
               var switcher = document.getElementById('sis-lang-switcher');
               var btn = document.getElementById('sis-lang-btn');
               var menu = document.getElementById('sis-lang-menu');
               if (!switcher || !btn || !menu) return;

               syncUi(currentLang());

               btn.addEventListener('click', function (e) {
                  e.stopPropagation();
                  var open = !menu.hasAttribute('hidden');
                  if (open) {
                     menu.setAttribute('hidden', '');
                     btn.setAttribute('aria-expanded', 'false');
                     switcher.classList.remove('is-open');
                  } else {
                     menu.removeAttribute('hidden');
                     btn.setAttribute('aria-expanded', 'true');
                     switcher.classList.add('is-open');
                  }
               });

               menu.addEventListener('click', function (e) {
                  var option = e.target.closest('.sis-lang-option');
                  if (!option) return;
                  changeLanguage(option.getAttribute('data-lang'));
                  menu.setAttribute('hidden', '');
                  btn.setAttribute('aria-expanded', 'false');
                  switcher.classList.remove('is-open');
               });

               document.addEventListener('click', function (e) {
                  if (!switcher.contains(e.target)) {
                     menu.setAttribute('hidden', '');
                     btn.setAttribute('aria-expanded', 'false');
                     switcher.classList.remove('is-open');
                  }
               });
            });
         })();
      </script>
      <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
      </body>
</html>