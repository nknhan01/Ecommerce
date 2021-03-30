@if (count($groups) > 1)
    <span class="admin-list-language-chooser">
        <span>{{ trans('plugins/language::language.translations') }}: </span>
        @foreach ($groups as $language)
            @if (!in_array($language['lang_locale'], [Language::getDefaultLocale(), $group['lang_locale']]))
                <span>
                    {!! language_flag($language['lang_flag'], $language['lang_name']) !!}
                    <a href="{{ route('languages.theme-translations', $language['lang_locale'] == Language::getDefaultLocale() ? [] : ['ref_lang' => $language['lang_locale']]) }}">{{ $language['lang_name'] }}</a>
                </span>&nbsp;
            @endif
        @endforeach
        <input type="hidden" name="ref_lang" value="{{ request()->input('ref_lang') }}">
    </span>
@endif
