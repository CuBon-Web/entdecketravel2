@extends('layouts.main.master')
@section('title')
Feedback | {{$setting->company}}
@endsection
@section('description')
Feedback | {{$setting->webname}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<div class="sisf-banner sis-br-radius mt-3 position-relative">
    <div class="banner-img">
        <figure>
            <img src="{{ url('frontend/images/page-banner.jpg') }}" alt="{{ $setting->company }}">
        </figure>
    </div>
    <div class="sisf-page-title sisf-m sisf-title--standard sisf-alignment--center">
        <div class="sisf-m-inner container">
            <div class="sisf-breadcrumbs mb-2">
                <a class="sisf-breadcrumbs-link text-white" href="{{ route('home') }}">
                    <span>Home</span>
                </a>
                <span class="sisf-breadcrumbs-separator text-white mx-2"><i class="fa-solid fa-chevron-right"></i></span>
                <span class="sisf-breadcrumbs-current text-white">Testimonial</span>
            </div>
            <div class="sisf-m-content sisf-content-grid">
                <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">Testimonial</h1>
            </div>
        </div>
    </div>
</div>
<div class="sis-feedback-page section">
    <div class="container">
        <div class="row g-4">
            @foreach ($data as $item)
            <div class="col-lg-4 col-md-6">
                <div class="sis-feedback-card sisf-e-inner sis-radius overflow-hidden position-relative mb-0 {{ empty($item->avatar) ? 'is-no-image' : '' }}">
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

@endsection