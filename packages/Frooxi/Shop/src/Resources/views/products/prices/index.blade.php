@if ($prices['final']['price'] < $prices['regular']['price'])
    @php
        $discountPercentage = floatval($product->discount_percentage ?: $product->flash_sale_discount);
        if ($discountPercentage <= 0 && $prices['regular']['price'] > 0) {
            $discountPercentage = round((1 - $prices['final']['price'] / $prices['regular']['price']) * 100);
        }
    @endphp
    <p
        class="final-price font-medium text-zinc-500 line-through max-sm:leading-4"
        aria-label="{{ $prices['regular']['formatted_price'] }}"
    >
        {{ $prices['regular']['formatted_price'] }}
    </p>

    <div class="flex items-center gap-2">
        <p class="font-semibold max-sm:leading-4">
            {{ $prices['final']['formatted_price'] }}
        </p>
        <span class="inline-flex rounded-md bg-red-100 px-1.5 py-0.5 text-xs font-semibold text-red-600">
            {{ $discountPercentage }}% OFF
        </span>
    </div>
@else
    <p class="final-price font-semibold max-sm:leading-4">
        {{ $prices['regular']['formatted_price'] }}
    </p>
@endif