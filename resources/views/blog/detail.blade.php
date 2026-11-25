@extends('layouts.main.master')
@section('title')
{{ seoTitle($blog_detail->seo_title ?? '', languageName($blog_detail->title)) }}
@endsection
@section('description')
{{ seoDescription($blog_detail->meta_description ?? '', languageName($blog_detail->description)) }}
@endsection
@section('image')
{{ url('' . $blog_detail->image) }}
@endsection
@section('schema')
<script type="application/ld+json">
{!! json_encode([
   '@context' => 'https://schema.org',
   '@type' => 'BlogPosting',
   'headline' => languageName($blog_detail->title),
   'description' => strip_tags(languageName($blog_detail->description)),
   'image' => [url('' . $blog_detail->image)],
   'datePublished' => optional($blog_detail->created_at)->toIso8601String(),
   'dateModified' => optional($blog_detail->updated_at)->toIso8601String(),
   'mainEntityOfPage' => url()->current(),
   'author' => [
      '@type' => 'Organization',
      'name' => config('app.name'),
   ],
   'publisher' => [
      '@type' => 'Organization',
      'name' => config('app.name'),
      'logo' => [
         '@type' => 'ImageObject',
         'url' => url('' . $setting->logo),
      ],
   ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
@php
    $blogTitle = languageName($blog_detail->title);
    $blogImage = $blog_detail->image ?: url('frontend/images/page-banner.jpg');
    $blogDate = $blog_detail->created_at ? date_format($blog_detail->created_at, 'd M Y') : '';
    $cateName = optional($blog_detail->cate)->name ? languageName($blog_detail->cate->name) : 'Blog';
    $cateSlug = optional($blog_detail->cate)->slug;
@endphp
<div class="page-content sis-blog-detail-page">
    <div class="sisf-banner sis-br-radius mt-3 position-relative">
        <div class="banner-img">
            <figure>
                <img src="{{ url('' . $blogImage) }}" alt="{{ $blogTitle }}">
            </figure>
        </div>
        <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner container">
                <div class="sisf-breadcrumbs mb-2">
                    <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}"><span>Home</span></a>
                    <span class="sisf-breadcrumbs-separator text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                    @if($cateSlug)
                    <a class="sisf-breadcrumbs-link text-white" href="{{ route('listCateBlog', ['slug' => $cateSlug]) }}"><span>{{ $cateName ?: 'Blog' }}</span></a>
                    @else
                    <span class="sisf-breadcrumbs-current text-white">Blog</span>
                    @endif
                    <span class="sisf-breadcrumbs-separator text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                    <span class="sisf-breadcrumbs-current text-white">{{ \Illuminate\Support\Str::limit($blogTitle, 48) }}</span>
                </div>
                <div class="sisf-m-content sisf-content-grid">
                    <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">{{ $blogTitle }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="sis-blog-detail-section section">
        <div class="container">
            <div class="row g-4 g-xl-5">
                <div class="col-xl-8 col-lg-8">
                    <article class="sis-blog-detail-main">
                        <div class="sis-blog-detail-media">
                            <img src="{{ url('' . $blogImage) }}" alt="{{ $blogTitle }}">
                        </div>
                        <div class="sis-blog-detail-body">
                            <ul class="sis-blog-detail-meta">
                                <li><i class="fa-regular fa-user"></i> {{ $setting->company }}</li>
                                @if($blogDate)
                                <li><i class="fa-regular fa-calendar"></i> {{ $blogDate }}</li>
                                @endif
                                @if($cateSlug)
                                <li>
                                    <i class="fa-regular fa-folder"></i>
                                    <a href="{{ route('listCateBlog', ['slug' => $cateSlug]) }}">{{ $cateName }}</a>
                                </li>
                                @endif
                            </ul>
                            <h2 class="sis-blog-detail-title">{{ $blogTitle }}</h2>
                            <div class="sis-blog-detail-content content">
                                {!! languageName($blog_detail->content) !!}
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-xl-4 col-lg-4">
                    <aside class="sis-blog-sidebar">
                        @if(isset($blognew) && count($blognew) > 0)
                        <div class="sis-blog-widget">
                            <h3 class="sis-blog-widget-title">Recent Posts</h3>
                            <div class="sis-blog-recent-list">
                                @foreach ($blognew as $item)
                                @php
                                    $recentTitle = languageName($item->title);
                                    $recentUrl = route('detailBlog', ['slug' => $item->slug]);
                                    $recentImage = $item->image ?: url('/frontend/images/no-image.png');
                                    $recentDay = $item->created_at ? date_format($item->created_at, 'd') : '';
                                    $recentMonth = $item->created_at ? date_format($item->created_at, 'M') : '';
                                @endphp
                                <a href="{{ $recentUrl }}" class="sis-blog-recent-item">
                                    <div class="sis-blog-recent-thumb">
                                        <img src="{{ url('' . $recentImage) }}" alt="{{ $recentTitle }}">
                                        @if($recentDay)
                                        <span class="sis-blog-recent-date"><strong>{{ $recentDay }}</strong>{{ $recentMonth }}</span>
                                        @endif
                                    </div>
                                    <div class="sis-blog-recent-info">
                                        <h4>{{ $recentTitle }}</h4>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(isset($categoryhome) && count($categoryhome) > 0)
                        <div class="sis-blog-widget">
                            <h3 class="sis-blog-widget-title">Top Destinations</h3>
                            <ul class="sis-blog-dest-list">
                                @foreach ($categoryhome as $item)
                                <li>
                                    <a href="{{ route('allListProCate', ['danhmuc' => $item->slug]) }}">
                                        <span>{{ languageName($item->name) }}</span>
                                        <span class="sis-blog-dest-count">{{ count($item->product) }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if(isset($gallery) && count($gallery) > 0)
                        <div class="sis-blog-widget">
                            <h3 class="sis-blog-widget-title">Gallery</h3>
                            <div class="sis-blog-gallery-grid">
                                @foreach ($gallery as $item)
                                    @php $__gimgs = $item->gallery_images; @endphp
                                    @if(count($__gimgs))
                                    <div class="sis-blog-gallery-item">
                                        <a class="elem" href="{{ url($__gimgs[0]) }}" title="{{ $item->name }}" data-lcl-author="" data-lcl-thumb="{{ url($__gimgs[0]) }}" data-lcl-group="sidebar-album-{{ $item->id }}">
                                            <img src="{{ url($__gimgs[0]) }}" alt="{{ $item->name }}">
                                            <i class="fa-regular fa-image"></i>
                                        </a>
                                        @foreach(array_slice($__gimgs, 1) as $imgPath)
                                        <a class="elem" href="{{ url($imgPath) }}" style="display:none;" title="{{ $item->name }}" data-lcl-author="" data-lcl-thumb="{{ url($imgPath) }}" data-lcl-group="sidebar-album-{{ $item->id }}"></a>
                                        @endforeach
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
