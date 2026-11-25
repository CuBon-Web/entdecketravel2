@extends('layouts.main.master')
@section('title')
{{ seoTitle($seo_title ?? '', $title_page ?? 'Blog') }}
@endsection
@section('description')
{{ seoDescription($meta_description ?? '', 'Travel stories and updates from Entdecke Vietnam') }}
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('schema')
<script type="application/ld+json">
{!! json_encode([
   '@context' => 'https://schema.org',
   '@type' => 'CollectionPage',
   'name' => seoTitle($seo_title ?? '', $title_page ?? 'Blog'),
   'url' => url()->current(),
   'description' => seoDescription($meta_description ?? '', 'Travel stories and updates from Entdecke Vietnam'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endsection
@section('css')
@endsection
@section('content')
<div class="sisf-banner sis-br-radius mt-3 position-relative">
    <div class="banner-img">
       <figure>
          <img src="{{$bannerPage ?? url('frontend/images/page-banner.jpg')}}" alt="{{ $title_page ?? 'Blogs' }}">
       </figure>
    </div>
    <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
       <div class="sisf-m-inner container">
          <div class="sisf-breadcrumbs mb-2">
             <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}">
             <span>Home</span>
             </a>
             <span class="sisf-breadcrumbs-separator text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
             <span class="sisf-breadcrumbs-current text-white">{{ $title_page ?? 'Blogs' }}</span>
          </div>
          <div class="sisf-m-content sisf-content-grid ">
             <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">{{ $title_page ?? 'Blogs' }}</h1>
          </div>
       </div>
    </div>
 </div>
 <div class="sisf-page-section sisf-section">
    <div class="sisf-grid sisf-layout--template">
       <div class="sisf-grid-inner sis-blog-column container">
          <div class="sisf-blog sisf--blog sisf-blog-standard-page">
             <div class="row g-4">
                @forelse ($blog as $item)
                @php
                    $blogTitle = languageName($item->title);
                    $blogDesc = strip_tags(languageName($item->description));
                    $blogUrl = route('detailBlog', ['slug' => $item->slug]);
                    $blogImage = $item->image ?: url('/frontend/images/no-image.png');
                    $blogDate = $item->created_at ? \Illuminate\Support\Carbon::parse($item->created_at)->format('d M Y') : '';
                @endphp
                <div class="col-lg-4 col-md-6">
                   <div class="sis-letest-blog-item sis-radius bg-white p-3" data-aos="fade-up" data-aos-delay="{{ 100 + ($loop->index % 3) * 100 }}">
                      <div class="sisf-e-inner position-relative">
                         <div class="sisf-e-media-holder mb-3">
                            <div class="sisf-e-media">
                               <div class="sisf-e-media-image">
                                  <a href="{{ $blogUrl }}" class="d-block">
                                     <figure>
                                        <img src="{{ url('' . $blogImage) }}" class="w-100" alt="{{ $blogTitle }}">
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
                @empty
                <div class="col-12">
                   <div class="sis-letest-blog-item sis-radius bg-white p-4 text-center">
                      <p class="mb-0">No articles found in this category.</p>
                   </div>
                </div>
                @endforelse
             </div>
             @if(method_exists($blog, 'hasPages') && $blog->hasPages())
             <div class="sis-blog-pagination mt-5 d-flex justify-content-center">
                {{ $blog->links() }}
             </div>
             @endif
          </div>
       </div>
    </div>
 </div>







@endsection