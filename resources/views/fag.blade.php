@extends('layouts.main.master')
@section('title')
FAQ | {{ $setting->company }}
@endsection
@section('description')
FAQ | {{ $setting->webname }}
@endsection
@section('schema')
@php
    $faqSchemaEntity = buildFaqSchemaMainEntity($setting->footer_content ?? []);
@endphp
@if(count($faqSchemaEntity) > 0)
<script type="application/ld+json">
{!! json_encode([
   '@context' => 'https://schema.org',
   '@type' => 'FAQPage',
   'mainEntity' => $faqSchemaEntity,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
@php
    $faqGroups = collect(json_decode($setting->footer_content) ?? [])
        ->filter(function ($item) {
            return !empty($item->fagcate) || !empty($item->fag_detail);
        })
        ->values();
    $contactPhone = $setting->hotline ?? $setting->phone ?? '';
    $contactTel = preg_replace('/\s+/', '', $contactPhone);
@endphp
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
                <span class="sisf-breadcrumbs-current text-white">Frequently Asked Questions</span>
            </div>
            <div class="sisf-m-content sisf-content-grid">
                <h1 class="sisf-m-title text-white sis-text-anime-style-3 entry-title">Frequently Asked Questions</h1>
            </div>
        </div>
    </div>
</div>
<div class="sisf-sis-faq-section section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="sisf-sis-page-list bg-white" data-aos="fade-up" data-aos-delay="100">
                    <div class="sisf-sis-list-title">
                        <h2 class="sis-comman-text text-white">Other Topics</h2>
                    </div>
                    @forelse ($faqGroups as $groupIndex => $group)
                    <div class="sisf-sis-list-item {{ $loop->last ? 'mb-1 border-0' : 'mb-3' }} d-flex align-items-center justify-content-between">
                        <div class="sis-list-item-name">
                            <h3><a href="#sis-faqs{{ $groupIndex + 1 }}">{{ $group->fagcate ?: ('Topic ' . ($groupIndex + 1)) }}</a></h3>
                        </div>
                        <div class="sis-list-item-icon">
                            <span><a href="#sis-faqs{{ $groupIndex + 1 }}"><i class="fa-solid fa-arrow-right-long"></i></a></span>
                        </div>
                    </div>
                    @empty
                    <div class="sisf-sis-list-item mb-1 border-0">
                        <div class="sis-list-item-name">
                            <h3><span>No FAQ topics yet</span></h3>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-8">
                @forelse ($faqGroups as $groupIndex => $group)
                @php
                    $groupTitle = trim($group->fagcate ?? '');
                    $titleParts = $groupTitle !== '' ? preg_split('/\s+/', $groupTitle) : [];
                    $titleLast = count($titleParts) > 1 ? array_pop($titleParts) : '';
                    $titleFirst = count($titleParts) ? implode(' ', $titleParts) : $groupTitle;
                    $faqItems = collect($group->fag_detail ?? [])->filter(function ($faq) {
                        return !empty($faq->name) || !empty($faq->content);
                    })->values();
                    $accordionId = 'sis-Accordion-' . ($groupIndex + 1);
                @endphp
                <div class="sis-e-content--inner" id="sis-faqs{{ $groupIndex + 1 }}">
                    <div class="sisf-sis-section-title sis-section-title">
                        <h2 class="sisf-m-title sis-text-anime-style-3">
                            @if($titleLast !== '')
                                {{ $titleFirst }} <span class="sisf-e-colored">{{ $titleLast }}</span>
                            @else
                                {{ $groupTitle !== '' ? $groupTitle : ('FAQ Group ' . ($groupIndex + 1)) }}
                            @endif
                        </h2>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="sisf-page-accordian sisf-sis-page-accordian sisf-page-bottom sis-radius bg-white overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                                <div class="accordion" id="{{ $accordionId }}">
                                    @foreach ($faqItems as $faqIndex => $faq)
                                    @php
                                        $itemId = 'sis-faq-' . ($groupIndex + 1) . '-' . ($faqIndex + 1);
                                        $isFirst = $faqIndex === 0;
                                        $isLast = $loop->last;
                                    @endphp
                                    <div class="accordion-item {{ $isLast ? 'border-0' : '' }} {{ $isFirst ? 'mt-0' : '' }}">
                                        <h3 class="accordion-header">
                                            <button class="accordion-button bg-white ps-4 {{ $isFirst ? 'mt-0' : 'collapsed border-0' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $itemId }}" aria-expanded="{{ $isFirst ? 'true' : 'false' }}" aria-controls="{{ $itemId }}">
                                                <span>Q{{ $faqIndex + 1 }}. {{ $faq->name }}</span>
                                            </button>
                                        </h3>
                                        <div id="{{ $itemId }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" data-bs-parent="#{{ $accordionId }}">
                                            <div class="accordion-body ps-4">
                                                <div class="sisf-e-content-inner">
                                                    <div class="mb-0 text-white">{!! $faq->content !!}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($faqItems->isEmpty())
                                    <div class="accordion-item border-0 mt-0">
                                        <div class="accordion-body ps-4">
                                            <div class="sisf-e-content-inner">
                                                <p class="mb-0 text-white">No questions in this topic yet.</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="sis-e-content--inner">
                    <div class="sisf-sis-section-title sis-section-title">
                        <h2 class="sisf-m-title sis-text-anime-style-3">Frequently Asked <span class="sisf-e-colored">Questions</span></h2>
                    </div>
                    <div class="sisf-page-accordian sisf-sis-page-accordian sisf-page-bottom sis-radius bg-white overflow-hidden p-4">
                        <p class="mb-0">FAQ content will appear here once it is added in settings.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
