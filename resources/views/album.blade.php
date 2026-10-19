@extends('layouts.main.master')
@section('title')
Gallery
@endsection
@section('description')
Gallery - Our activities
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('js')
@endsection
@section('css')
@endsection
@section('content')
<div class="sisf-banner sis-br-radius mt-3 position-relative">
    <div class="banner-img">
        <figure>
            <img src="{{$setting->linkpopup ?? url('frontend/images/page-banner.jpg')}}" alt="entdecketravel">
        </figure>
    </div>
    <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
        <div class="sisf-m-inner container">
            <div class="sisf-breadcrumbs mb-2">
                <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}">
                    <span>Home</span>
                </a>
                <span class="sisf-breadcrumbs-separator text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                <span class="sisf-breadcrumbs-current text-white">Gallery</span>
            </div>
            <div class="sisf-m-content sisf-content-grid ">
                <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">Image Gallery</h1>
            </div>
        </div>
    </div>
</div>
<div class="sis-page-gallery-section sisf-section">
    <div class="container">
       <div class="sis-gallery-items sis-page-gallery-box">
          <div class="row">
            @foreach($list as $item)
            @php
                $__albumImgs = $item->gallery_images;
                $__thumb = count($__albumImgs) ? $__albumImgs[0] : null;
            @endphp
            @if($__thumb)
             <div class="col-lg-4 col-md-6">
                <div class="sis-gallery-item mb-4 pb-2" data-aos="fade-up" data-aos-delay="100">
                   <a href="{{ url($__thumb) }}">
                      <figure class="sis-image-anime">
                         <img src="{{ url($__thumb) }}" class="w-100 sis-radius" alt="entdecketravel">
                      </figure>
                   </a>
                </div>
             </div>
             @else
             <div class="col-lg-4 col-md-6">
                <div class="sis-gallery-item mb-4 pb-2" data-aos="fade-up" data-aos-delay="100">
                   <p style="color: #999;">Chưa có ảnh</p>
                </div>
             </div>
             @endif
             @endforeach
          </div>
       </div>
    </div>
 </div>
@endsection

