{!! view_render_event('bagisto.shop.layout.header.locale.before') !!}

<div class="header-top__locale">

    @php
    $localeImage = null;
    @endphp
    @foreach (core()->getCurrentChannel()->locales as $locale)
    @if ($locale->code == app()->getLocale())
    @php
    $localeImage = $locale->locale_image;
    @endphp
    @endif
    @endforeach

    <div class="header-top__locale-icon">
        @if ($localeImage)
        <img src="{{ asset('/storage/' . $localeImage) }}" onerror="this.src = '{{ asset($localeImage) }}'" />
        @elseif (app()->getLocale() == 'en')
        <span class="icon american-english-icon"></span>
        @endif
    </div>

    <select class="header-top__locale-select" onchange="window.location.href = this.value" @if (count(core()->getCurrentChannel()->locales) == 1)

        @endif>

        @foreach (core()->getCurrentChannel()->locales as $locale)
        @if (isset($serachQuery))
        @dd(app()->getLocale());
        <option value="?{{ $serachQuery }}&locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>
            {{ $locale->name }}
        </option>
        @else
        <option value="?locale={{ $locale->code }}" {{ $locale->code == app()->getLocale() ? 'selected' : '' }}>{{ $locale->name }}</option>
        @endif
        @endforeach
    </select>

</div>

{!! view_render_event('bagisto.shop.layout.header.locale.after') !!}

{!! view_render_event('bagisto.shop.layout.header.currency-item.before') !!}

@if (core()->getCurrentChannel()->currencies->count() > 1)
<div class="pull-left">
    <div class="dropdown">
        <select class="btn btn-link dropdown-toggle control locale-switcher styled-select" onchange="window.location.href = this.value">
            @foreach (core()->getCurrentChannel()->currencies as $currency)
            @if (isset($serachQuery))
            <option value="?{{ $serachQuery }}&currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
            @else
            <option value="?currency={{ $currency->code }}" {{ $currency->code == core()->getCurrentCurrencyCode() ? 'selected' : '' }}>{{ $currency->code }}</option>
            @endif
            @endforeach

        </select>

    </div>
</div>
@endif

{!! view_render_event('bagisto.shop.layout.header.currency-item.after') !!}