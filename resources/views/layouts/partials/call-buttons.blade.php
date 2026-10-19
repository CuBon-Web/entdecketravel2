@php
    $hotline = trim((string) ($setting->phone1 ?? ''));
    $phoneDigits = preg_replace('/\D+/', '', $hotline);
    if ($phoneDigits !== '' && strpos($phoneDigits, '0') === 0) {
        $phoneDigits = '84' . substr($phoneDigits, 1);
    }

    $hotlineTel = $hotline !== '' ? preg_replace('/\s+/', '', $hotline) : '';
    $zaloUrl = $phoneDigits !== '' ? 'https://zalo.me/' . $phoneDigits : '';
    $whatsappUrl = $phoneDigits !== '' ? 'https://wa.me/' . $phoneDigits : '';

    $facebookUrl = trim((string) ($setting->fbPixel ?? ''));
    if ($facebookUrl === '' || !preg_match('#^https?://#i', $facebookUrl)) {
        $facebookUrl = '';
    }

    $hasAny = $hotlineTel !== '' || $zaloUrl !== '' || $whatsappUrl !== '' || $facebookUrl !== '';
@endphp
@if($hasAny)
<div class="sis-call-buttons" id="sis-call-buttons">
    <div class="sis-call-actions" id="sis-call-actions" hidden>
        @if($hotlineTel !== '')
        <a href="tel:{{ $hotlineTel }}" class="sis-call-btn is-hotline" aria-label="Hotline {{ $hotline }}">
            <i class="fa-solid fa-phone"></i>
            <span class="sis-call-tooltip">Hotline</span>
        </a>
        @endif
        @if($zaloUrl !== '')
        <a href="{{ $zaloUrl }}" class="sis-call-btn is-zalo" target="_blank" rel="noopener noreferrer" aria-label="Zalo">
            <i class="fa-solid fa-comment-dots"></i>
            <span class="sis-call-tooltip">Zalo</span>
        </a>
        @endif
        @if($facebookUrl !== '')
        <a href="{{ $facebookUrl }}" class="sis-call-btn is-facebook" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
            <span class="sis-call-tooltip">Facebook</span>
        </a>
        @else
        <a href="https://www.facebook.com/" class="sis-call-btn is-facebook" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
            <span class="sis-call-tooltip">Facebook</span>
        </a>
        @endif
        @if($whatsappUrl !== '')
        <a href="{{ $whatsappUrl }}" class="sis-call-btn is-whatsapp" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="sis-call-tooltip">WhatsApp</span>
        </a>
        @endif
    </div>
    <button type="button" class="sis-call-toggle" id="sis-call-toggle" aria-label="Contact options" aria-expanded="false">
        <i class="fa-solid fa-headset sis-call-toggle-open"></i>
        <i class="fa-solid fa-xmark sis-call-toggle-close"></i>
    </button>
</div>
@endif
