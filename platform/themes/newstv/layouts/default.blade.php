{!! Theme::partial('header') !!}

<main class="main" id="main">
    <div class="container">
        <div class="main-content">
            <div class="main-left">
                {!! Theme::content() !!}
            </div>
            {!! Theme::partial('sidebar') !!}
        </div>
    </div>
</main>

{!! Theme::partial('footer') !!}

