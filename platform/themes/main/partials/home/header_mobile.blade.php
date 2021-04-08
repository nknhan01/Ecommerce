<header class="headerMobile container">
    <div class="headerMobile__icon">
        <a href="{{ route('public.index') }}" class="headerMobile__icon-link">
            <img class="headerMobile__icon-link--image img-fluid"
                src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="logo-image">
        </a>
    </div>
    <div class="headerMobile__menu">
        <button class="headerMobile__menu-icon">
            <i class="headerMobile__menu-icon--image fas fa-bars"></i>
        </button>
        <div class="headerMobile__menu-content">
            <form class="headerMobile__menu-content--form">
                <input class="headerMobile__menu-content--form---search" placeholder="Gợi ý" aria-label="Gợi ý">
                <button class="headerMobile__menu-content--form---buttom">
                    <i class="headerMobile__menu-content--form---buttom----icon fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</header>
