<div class="address-item @if ($address->is_default) is-default @endif" data-id="{{ $address->id }}">
    <p class="name">{{ $address->name }}</p>
    <p class="address"
       title="{{ $address->address }}, {{ $address->city }}, {{ $address->state }} @if (count(EcommerceHelper::getAvailableCountries()) > 1), {{ $address->country_name }} @endif @if (EcommerceHelper::isZipCodeEnabled() && $address->zip_code), {{ $address->zip_code }} @endif">
        {{ $address->address }}, {{ $address->city }}, {{ $address->state }} @if (count(EcommerceHelper::getAvailableCountries()) > 1), {{ $address->country_name }} @endif @if (EcommerceHelper::isZipCodeEnabled() && $address->zip_code), {{ $address->zip_code }} @endif
    </p>
    <p class="phone">{{ __('Phone') }}: {{ $address->phone }}</p>
    @if ($address->email)
        <p class="email">{{ __('Email') }}: {{ $address->email }}</p>
    @endif
    @if ($address->is_default)
        <span class="default">{{ __('Default') }}</span>
    @endif
</div>
