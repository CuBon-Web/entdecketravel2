<header id="sisf-page-header" class="sisf-main-header sis-br-radius mt-3 sisf-standerd-header">
    <div id="sisf-page-header-inner" class="sisf-skin--light position-relative d-flex align-items-center">
        <div class="container">
            <!-- Logo Start -->
            <a class="navbar-brand sisf-header-logo-link mobile-block" href="{{route('home')}}">
                <img src="{{ url('' . $setting->logo) }}" alt="{{ $setting->company ?? 'Logo' }}">
            </a>
            <!-- Logo End -->
            <div class="sisf-centered-header-wrapper sisf--header d-flex justify-content-between align-items-center">
                <!-- Logo Start -->
                <a class="navbar-brand sisf-header-logo-link" href="{{route('home')}}">
                    <img src="{{ url('' . $setting->logo) }}" alt="{{ $setting->company ?? 'Logo' }}">
                </a>
                <!-- Logo End -->
                <nav class="navbar navbar-expand-lg ms-auto me-4">
                    <!-- Main Menu Start -->
                    <div class="collapse navbar-collapse sis-main-menu">
                        <div class="nav-menu-wrapper">
                            <ul class="navbar-nav" id="menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('home')}}">Home</a>
                                 
                                </li>
                                <li class="nav-item submenu">
                                    <a class="nav-link" href="javascript:;">About Us<i class="fas fa-chevron-down custom-toggle-icon ps-2"></i></a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a class="nav-link" href="{{route('aboutUs')}}">About Us</a></li>
                                        <li class="nav-item"><a class="nav-link" href="{{route('fags')}}">FAQs</a></li>
                                        <li class="nav-item"><a class="nav-link" href="{{route('feedback')}}">Feedback</a></li>
                                    </ul>
                                </li>
                                
                                <li class="nav-item submenu">
                                    <a class="nav-link" href="javascript:;">Destinations<i class="fas fa-chevron-down custom-toggle-icon ps-2"></i></a>
                                    <ul class="sub-menu">
                                        @foreach ($categoryhome as $item)
                                        <li class="nav-item submenu">
                                            <a class="nav-link" href="{{ route('allListProCate', ['danhmuc' => $item->slug]) }}">
                                                <span>{{ languageName($item->name) }}</span>
                                                <span class="menu-item-arrow ps-0">
                                                    <i class="fa-solid fa-chevron-right"></i>
                                                </span>
                                            </a>
                                            <ul class="sub-menu">
                                                @foreach ($item->typeCate->sortBy('id') as $type)
                                                <li class="nav-item"><a class="nav-link" href="{{ route('allListType', ['danhmuc' => $item->slug, 'loaidanhmuc' => $type->slug]) }}">{{ languageName($type->name) }}</a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @foreach ($tagCate as $item)
                                <li class="nav-item submenu">
                                    <a class="nav-link" href="javascript:;">{{ $item->name }}<i class="fas fa-chevron-down custom-toggle-icon ps-2"></i></a>
                                    <ul class="sub-menu">
                                        @foreach ($item->tags as $tag)
                                        <li class="nav-item"><a class="nav-link" href="{{ route('allListTags', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                                <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                                <li class="nav-item submenu">
                                    <a class="nav-link" href="javascript:;">Blogs<i class="fas fa-chevron-down custom-toggle-icon ps-2"></i></a>
                                    <ul class="sub-menu">
                                        @foreach ($blogCate as $item)
                                        <li class="nav-item"><a class="nav-link" href="{{ route('listCateBlog', ['slug' => $item->slug]) }}">{{ languageName($item->name) }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('lienHe') }}">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Main Menu End -->
                </nav>
                <div class="sisf-widget-holder sisf--two d-flex align-items-center">
                    <div class="sis-lang-switcher" id="sis-lang-switcher">
                        <button type="button" class="sis-lang-btn" id="sis-lang-btn" aria-haspopup="listbox" aria-expanded="false">
                            <i class="fa-solid fa-globe"></i>
                            <span class="sis-lang-current" id="sis-lang-current">EN</span>
                            <i class="fa-solid fa-chevron-down sis-lang-caret"></i>
                        </button>
                        <ul class="sis-lang-menu" id="sis-lang-menu" role="listbox" hidden>
                            <li><button type="button" class="sis-lang-option is-active" data-lang="en" data-label="EN">English</button></li>
                            <li><button type="button" class="sis-lang-option" data-lang="vi" data-label="VI">Tiếng Việt</button></li>
                            <li><button type="button" class="sis-lang-option" data-lang="ja" data-label="JP">日本語</button></li>
                            <li><button type="button" class="sis-lang-option" data-lang="ko" data-label="KR">한국어</button></li>
                            <li><button type="button" class="sis-lang-option" data-lang="es" data-label="ES">Español</button></li>
                            <li><button type="button" class="sis-lang-option" data-lang="it" data-label="IT">Italiano</button></li>
                        </ul>
                    </div>
                    <div id="google_translate_element" class="sis-google-translate" aria-hidden="true"></div>
                </div>
            </div>
            <div class="navbar-toggle"></div>
            <div class="responsive-menu"></div>
        </div>
    </div>
</header>