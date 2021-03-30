@if (request()->input('media-action') === 'select-files')
    <html>
        <head>
            <meta name="csrf-token" content="{{ csrf_token() }}">
            {!! Assets::renderHeader() !!}
            {!! RvMedia::renderHeader() !!}
        </head>
        <body>
            {!! RvMedia::renderContent() !!}
            {!! Assets::renderFooter() !!}
            {!! RvMedia::renderFooter() !!}
        </body>
    </html>
@else
    {!! RvMedia::renderHeader() !!}

    {!! RvMedia::renderContent() !!}

    {!! RvMedia::renderFooter() !!}
@endif
