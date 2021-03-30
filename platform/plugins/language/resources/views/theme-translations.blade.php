@extends('core/base::layouts.master')
@section('content')
    <div class="widget meta-boxes">
        <div class="widget-title">
            <h4>&nbsp; {{ trans('plugins/language::language.theme-translations') }}</h4>
        </div>
        <div class="widget-body box-translation">
            @if (count(Language::getSupportedLocales()) > 1)
                {!! Form::open(['role' => 'form', 'route' => 'languages.theme-translations', 'method' => 'POST']) !!}
                    <input type="hidden" name="locale" value="{{ $group['lang_locale'] }}">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-info">{{ trans('plugins/language::language.translate_from') }} <strong>{{ $defaultLanguage ? $defaultLanguage->lang_name : Language::getDefaultLocale() }}</strong> {{ trans('plugins/language::language.to') }} <strong>{{ $group['lang_name'] }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                @include('plugins/language::partials.list-theme-languages-to-translate', compact('groups', 'group'))
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive table-striped">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ $defaultLanguage ? $defaultLanguage->lang_name : Language::getDefaultLocale() }}</th>
                                <th>{{ $group['lang_name'] }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($translations as $key => $translation)
                                <tr>
                                    <td class="text-left" style="width: 50%">
                                        {!! htmlentities($key, ENT_QUOTES, 'UTF-8', false) !!}
                                        <input type="hidden" name="translations[{{ $key }}][key]" value="{!! htmlentities($key, ENT_QUOTES, 'UTF-8', false) !!}">
                                    </td>
                                    <td class="text-left" style="width: 50%">
                                        <input type="text" class="form-control" name="translations[{{ $key }}][value]" value="{!! htmlentities($translation, ENT_QUOTES, 'UTF-8', false) !!}">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-info button-save-theme-translations">{{ trans('core/base::forms.save') }}</button>
                    </div>
                {!! Form::close() !!}
            @else
                <p class="text-warning">{{ trans('plugins/language::language.no_other_languages') }}</p>
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
@stop
