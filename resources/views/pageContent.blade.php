@extends('layouts.main.master')
@section('title')
{{$pagecontentdetail->title}}
@endsection
@section('description')
{{$pagecontentdetail->title}}
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<div class="page-content sis-blog-detail-page">
    <div class="sisf-banner sis-br-radius mt-3 position-relative">
        <div class="banner-img">
            <figure>
                <img src="{{url('frontend/images/page-banner.jpg')}}" alt="{{$pagecontentdetail->title}}">
            </figure>
        </div>
        <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
            <div class="sisf-m-inner container">
                <div class="sisf-breadcrumbs mb-2">
                    <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}"><span>Home</span></a>
                    <span class="sisf-breadcrumbs-current text-white">{{$pagecontentdetail->title}}</span>
                </div>
                <div class="sisf-m-content sisf-content-grid">
                    <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">{{$pagecontentdetail->title}}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="sis-blog-detail-section section">
        <div class="container">
            <div class="row g-4 g-xl-5">
                <div class="col-xl-12 col-lg-8">
                    <article class="sis-blog-detail-main">
                       
                        <div class="sis-blog-detail-body">
                           
                            <div class="sis-blog-detail-content content">
                                {!!($pagecontentdetail->content)!!}
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection