@php
    $img = json_decode($pro->images, true);
    $thumb =
        is_array($img) && !empty($img[0])
            ? $img[0]
            : url('/frontend/images/no-image.png');
    $itemName = languageName($pro->name);
    $itemDesc = strip_tags(languageName($pro->description));
    $itemUrl = route('detailProduct', [
        'cate' => $pro->cate_slug,
        'type' => $pro->type_slug ? $pro->type_slug : 'type',
        'id' => $pro->slug,
    ]);
    $itemDiscount = (int) languageName($pro->discount);
    $itemPrice = (int) languageName($pro->price);
    $itemTagDisplay = null;
    if (isset($tagcateContext) && $tagcateContext && $tagcateContext->tags && $tagcateContext->tags->isNotEmpty() && $pro->tags) {
        $productTags = is_array($pro->tags) ? $pro->tags : json_decode($pro->tags, true);
        if (is_array($productTags)) {
            foreach ($tagcateContext->tags as $t) {
                $slug = (string) $t->slug;
                foreach ($productTags as $pt) {
                    $pt = (string) $pt;
                    if ($pt === $slug || strpos($pt, $slug . '-') === 0) {
                        $itemTagDisplay = languageName($t->name);
                        break 2;
                    }
                }
            }
        }
    }
    $itemCate = $itemTagDisplay ?: (isset($pro->cate) ? languageName($pro->cate->name) : '');
    $itemDuration = $pro->hang_muc ?? '';
@endphp
<div class="sisf-tour-list-item sis-radius bg-white">
    <div class="sisf-e-inner mb-0">
        <div class="sisf-tour-image position-relative">
            <a href="{{ $itemUrl }}">
                <figure class="sis-image-anime">
                    <img src="{{ url('' . $thumb) }}" class="w-100" alt="{{ $itemName }}">
                </figure>
            </a>
            @if ($itemCate)
                <div class="sis-tours-category">
                    <span>{{ $itemCate }}</span>
                </div>
            @endif
            @if ($itemDuration)
                <div class="sis-tour-item__duration">
                    <i class="fa-regular fa-calendar"></i>
                    <span>{{ $itemDuration }}</span>
                </div>
            @endif
        </div>
        <div class="sisf-tour-content p-3">
            <div class="sisf-tour-title mb-1">
                <h3 class="sis-tour-item__link">
                    <a href="{{ $itemUrl }}">{{ $itemName }}</a>
                </h3>
            </div>
            @if ($itemDesc)
                <div class="sisf-m-text">
                    <p class="mb-3">{{ \Illuminate\Support\Str::limit($itemDesc, 120) }}</p>
                </div>
            @endif
            <div class="sis-tour-item__footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="sis-tour-item__price_wrap">
                    <div class="sis-tour-item__start mb-1">
                        <span>Starting From</span>
                    </div>
                    <div class="sis-tour-item__price">
                        <h3 class="mb-0 sisf-e-colored">
                            @if ($itemDiscount > 0)
                                {{ number_format($itemDiscount) }} VND
                            @elseif ($itemPrice > 0)
                                {{ number_format($itemPrice) }} VND
                            @else
                                Contact Us
                            @endif
                        </h3>
                    </div>
                </div>
                <a href="{{ $itemUrl }}" class="sis-btn-default">Book Now <i class="fa-solid fa-arrow-right-long"></i></a>
            </div>
        </div>
    </div>
</div>
