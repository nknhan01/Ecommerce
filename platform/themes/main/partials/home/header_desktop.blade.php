<header class="headerDesktop container">
    <div class="headerDesktop__top">
        <div class="headerDesktop__top-left">
            <div class="headerDesktop__top-left--item">
                <a href="#" class="headerDesktop__top-left--item---link" title="">
                    Kênh người bán
                </a>
            </div>
            <div class="headerDesktop__top-left--item">
                <a href="#" class="headerDesktop__top-left--item---link" title="">
                    Tải ứng dụng
                </a>
            </div>
            <div class="headerDesktop__top-left--item fix_border_left">
                <p class="headerDesktop__top-left--item---text">Kết nối:</p>
                <a class="headerDesktop__top-left--item---link" href="#" title="">
                    <i class="headerDesktop__top-left--item---link----icon fab fa-facebook"></i>
                </a>
            </div>
        </div>
        <div class="headerDesktop__top-right">
            <div class="headerDesktop__top-right--item">
                <a href="#" class="headerDesktop__top-right--item---link" title="">
                    <i class="headerDesktop__top-right--item---link----icon far fa-bell"></i>
                    <span class="headerDesktop__top-right--item---link----text">Thông báo</span>
                </a>
            </div>
            <div class="headerDesktop__top-right--item">
                <a href="#" class="headerDesktop__top-right--item---link">
                    <i class="headerDesktop__top-right--item---link----icon far fa-question-circle"></i>
                    <span class="headerDesktop__top-right--item---link----text">Hỗ trợ</span>
                </a>
            </div>
            <div class="headerDesktop__top-right--item">
                <a href="#" class="headerDesktop__top-right--item---link">
                    Đăng nhập
                </a>
            </div>
            <div class="headerDesktop__top-right--item">
                <a href="#" class="headerDesktop__top-right--item---link">
                    Đăng ký
                </a>
            </div>
            <div href="#" class="headerDesktop__top-right--item fix_border_right">
                <a href="#" class="headerDesktop__top-right--item---link">
                    <i class="headerDesktop__top-right--item---link----icon fas fa-cart-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="headerDesktop__bottom">
        <div class="headerDesktop__bottom-icon">
            <a href="{{ route('public.index') }}" class="headerDesktop__bottom-icon--link">
                <img class="headerDesktop__bottom-icon--link---image img-fluid"
                    src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="logo-image">
            </a>
        </div>
        <div class="headerDesktop__bottom-content">
            <form class="headerDesktop__bottom-content--form">
                <input class="headerDesktop__bottom-content--form---search" placeholder="Gợi ý" aria-label="Gợi ý">
                <button class="headerDesktop__bottom-content--form---buttom">
                    <i class="headerDesktop__bottom-content--form---buttom----icon fas fa-search"></i>
                </button>
            </form>
            <div class="headerDesktop__bottom-content--tabs">
                <a class="headerDesktop__bottom-content--tabs---item" href="" title="item 1">item 1</a>
                <a class="headerDesktop__bottom-content--tabs---item" href="" title="item 2">item 2</a>
                <a class="headerDesktop__bottom-content--tabs---item" href="" title="item 3">item 3</a>
                <a class="headerDesktop__bottom-content--tabs---item" href="" title="item 4">item 4</a>
            </div>
        </div>
        
    </div>
</header>
