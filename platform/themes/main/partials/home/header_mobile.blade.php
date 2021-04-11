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
    </div>
    <div class="headerMobile__submMenuWrap">
        <div class="headerMobile__submMenuWrap-title">
            <h2 class="headerMobile__submMenuWrap-title--text">{{ theme_option('company_name') }}</h2>
        </div>
        <form class="headerMobile__submMenuWrap-form">
            <input class="headerMobile__submMenuWrap-form--search" placeholder="Gợi ý" aria-label="Gợi ý">
            <input class="headerMobile__submMenuWrap-form--button">
            <i class="headerMobile__submMenuWrap-form--button---icon fas fa-search"></i>
        </form>
        <div class="headerMobile__submMenuWrap-item ">
            <div class="headerMobile__submMenuWrap-item--icon">
                <i class="fas fa-user headerMobile__submMenuWrap-item--icon---image"></i>
            </div>
            <div class="headerMobile__submMenuWrap-item--content">Kênh người bán</div>
        </div>
        <div class="headerMobile__submMenuWrap-item">
            <div class="headerMobile__submMenuWrap-item--icon">
                <i class="fas fa-user headerMobile__submMenuWrap-item--icon---image"></i>
            </div>
            <div class="headerMobile__submMenuWrap-item--content">Tải ứng dụng</div>
        </div>.
        <div class="headerMobile__submMenuWrap-item">
            <div class="headerMobile__submMenuWrap-item--icon">
                <i class="fas fa-user headerMobile__submMenuWrap-item--icon---image"></i>
            </div>
            <div class="headerMobile__submMenuWrap-item--content">Hỗ trợ</div>
        </div>
        <div class="headerMobile__submMenuWrap-item">
            <div class="headerMobile__submMenuWrap-item--icon">
                <i class="fas fa-user headerMobile__submMenuWrap-item--icon---image"></i>
            </div>
            <div class="headerMobile__submMenuWrap-item--content">Đăng nhập</div>
        </div>
        <div class="headerMobile__submMenuWrap-item">
            <div class="headerMobile__submMenuWrap-item--icon">
                <i class="fas fa-user headerMobile__submMenuWrap-item--icon---image"></i>
            </div>
            <div class="headerMobile__submMenuWrap-item--content">Đăng ký</div>
        </div>
        <div class="headerMobile__submMenuWrap-item">
            <div class="headerMobile__submMenuWrap-item--icon">
                <i class="fas fa-cart-plus headerMobile__submMenuWrap-item--icon---image"></i>
            </div>
            <div class="headerMobile__submMenuWrap-item--content">Giỏ hàng</div>
        </div>
    </div>
    
</header>
