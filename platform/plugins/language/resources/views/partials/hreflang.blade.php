@foreach($supportedLocales as $localeCode => $properties)
<link rel="alternate" href="{{ url('') }}/{{ $localeCode }}/{{ ltrim(str_replace(url(''), '', url()->current()), '/') }}" hreflang="{{ $localeCode }}" />
@endforeach
