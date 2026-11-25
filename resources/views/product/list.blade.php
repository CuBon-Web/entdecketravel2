@extends('layouts.main.master')
@section('title')
    {{ seoTitle($seo_title ?? '', $title ?? config('app.name')) }}
@endsection
@section('description')
    {{ seoDescription($meta_description ?? '', 'Discover ' . ($title ?? config('app.name')) . ' tours with Entdecke Vietnam') }}
@endsection
@section('image')
    {{ isset($banner[0]) ? url('' . $banner[0]->image) : url('' . $setting->logo) }}
@endsection
@section('js')
    <script>
        $(function() {
            $(document).on('change', '.js-auto-filter', function() {
                $('#product-filter-form').submit();
            });

            $(document).on('click', '.js-reset-filter', function(e) {
                e.preventDefault();
                window.location.href = window.location.pathname;
            });

            $(document).on('click', '.trv-filter-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var $bx = $(this).closest('.trv-filter-bar-bx');
                var isOpen = $bx.hasClass('is-open');
                $('.trv-filter-bar-bx').removeClass('is-open');
                if (!isOpen) {
                    $bx.addClass('is-open');
                }
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.trv-filter-bar-bx').length) {
                    $('.trv-filter-bar-bx').removeClass('is-open');
                }
            });
        });
    </script>
@endsection
@section('css')
@endsection
@section('content')
    @php
        $bannerPage = $bannerPage ?? '';
        $bannerImage =
            !empty($bannerPage) ? $bannerPage : (isset($banner[0]->image) ? $banner[0]->image : '');
        $pageTitle = $title ?? 'Tour Packages';
    @endphp
    <div class="page-content sis-product-list-page">
        <div class="sisf-banner position-relative sis-product-list-banner"@if(!empty($bannerImage)) style="background-image: url({{ url('' . $bannerImage) }});"@endif>
            <div class="sisf-page-title">
                <div class="container">
                    <div class="sisf-m-inner">
                        <h1 class="sisf-m-title text-white">{{ $pageTitle }}</h1>
                        <div class="sisf-breadcrumbs mt-3">
                            <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}"><span>Home</span></a>
                            <span class="text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                            <span class="text-white">{{ $pageTitle }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-full p-t50 p-b80 sis-product-list-section">
            <div class="container">
                <div class="trv-pack-filter-bar-wrap m-b40">
                    <div class="trav-pack-filter-bar sis-radius bg-white">
                        <form id="product-filter-form" method="GET" action="">
                            <input type="hidden" name="cate" value="{{ request('cate', $cate_slug ?? request('danhmuc')) }}">
                            <input type="hidden" name="type" value="{{ request('type', $type_slug ?? '') }}">
                            <input type="hidden" name="typetwo" value="{{ request('typetwo', $type_two_slug ?? '') }}">
                            <div class="trv-filter-bar-section">
                                <div class="trv-filter-bar-left">
                                    <div class="trv-filter-bar-bx">
                                        <button class="trv-filter-btn" data-target="sort" type="button">Sort By <i class="fa-solid fa-chevron-down"></i></button>
                                        <div class="trv-filter-content" id="sort">
                                            <h4>Sort By</h4>
                                            <label><input class="js-auto-filter" type="radio" name="sortby" value="created-asc" {{ request('sortby', 'created-asc') == 'created-asc' ? 'checked' : '' }}> Newest</label>
                                            <label><input class="js-auto-filter" type="radio" name="sortby" value="price-asc" {{ request('sortby') == 'price-asc' ? 'checked' : '' }}> Price: Low to High</label>
                                            <label><input class="js-auto-filter" type="radio" name="sortby" value="price-desc" {{ request('sortby') == 'price-desc' ? 'checked' : '' }}> Price: High to Low</label>
                                        </div>
                                    </div>

                                    <div class="trv-filter-bar-bx">
                                        <button class="trv-filter-btn" data-target="package" type="button">Package Type <i class="fa-solid fa-chevron-down"></i></button>
                                        <div class="trv-filter-content" id="package">
                                            <h4>Package Type</h4>
                                            <label><input class="js-auto-filter" type="radio" name="cate_filter" value="" {{ request('cate_filter', '') == '' ? 'checked' : '' }}> All</label>
                                            @foreach ($categoryhome as $cateItem)
                                                <label><input class="js-auto-filter" type="radio" name="cate_filter" value="{{ $cateItem->slug }}" {{ request('cate_filter') == $cateItem->slug ? 'checked' : '' }}> {{ languageName($cateItem->name) }}</label>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="trv-filter-bar-bx">
                                        <button class="trv-filter-btn" data-target="duration" type="button">Duration <i class="fa-solid fa-chevron-down"></i></button>
                                        <div class="trv-filter-content" id="duration">
                                            <h4>Duration</h4>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="" {{ request('duration_range', '') == '' ? 'checked' : '' }}> All</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="1-3" {{ request('duration_range') == '1-3' ? 'checked' : '' }}> 1-3 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="4-7" {{ request('duration_range') == '4-7' ? 'checked' : '' }}> 4-7 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="8-11" {{ request('duration_range') == '8-11' ? 'checked' : '' }}> 8-11 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="12-15" {{ request('duration_range') == '12-15' ? 'checked' : '' }}> 12-15 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="16-20" {{ request('duration_range') == '16-20' ? 'checked' : '' }}> 16-20 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="21-23" {{ request('duration_range') == '21-23' ? 'checked' : '' }}> 21-23 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="24-27" {{ request('duration_range') == '24-27' ? 'checked' : '' }}> 24-27 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="28-31" {{ request('duration_range') == '28-31' ? 'checked' : '' }}> 28-31 Days</label>
                                            <label><input class="js-auto-filter" type="radio" name="duration_range" value="32+" {{ request('duration_range') == '32+' ? 'checked' : '' }}> 32+ Days</label>
                                        </div>
                                    </div>

                                    @if (!empty($filter) && count($filter) > 0)
                                        @foreach ($filter as $tagCate)
                                            @if (!empty($tagCate->tags) && count($tagCate->tags) > 0)
                                                <div class="trv-filter-bar-bx">
                                                    <button class="trv-filter-btn" data-target="tag-{{ $tagCate->id }}" type="button">{{ languageName($tagCate->name) }} <i class="fa-solid fa-chevron-down"></i></button>
                                                    <div class="trv-filter-content" id="tag-{{ $tagCate->id }}">
                                                        <h4>{{ languageName($tagCate->name) }}</h4>
                                                        @foreach ($tagCate->tags as $tag)
                                                            <label><input class="js-auto-filter" type="checkbox" name="fillter[]" value="{{ $tag->slug }}" {{ in_array($tag->slug, (array) request('fillter', [])) ? 'checked' : '' }}> {{ languageName($tag->name) }}</label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                <div class="trv-filter-bar-right">
                                    <a href="javascript:;" class="trv-reset-btn js-reset-filter">Reset All</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="js-product-list-filter sisf-tour-list-part">
                    @if (count($list) > 0)
                        <div class="row">
                            @foreach ($list as $item)
                                <div class="col-xl-4 col-lg-6 m-b30">
                                    @include('layouts.product.item', ['pro' => $item])
                                </div>
                            @endforeach
                        </div>
                        <div class="sis-product-list-pagination text-center mt-4">
                            {{ $list->links() }}
                        </div>
                    @else
                        <div class="row justify-content-center">
                            <div class="col-md-8 text-center sis-product-list-empty">
                                <h3>The content is being updated...</h3>
                                <p>Please check back soon for new tour packages.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
