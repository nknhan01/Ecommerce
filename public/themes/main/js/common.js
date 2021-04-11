let App = {
    showMenu: function () {
        $(document).on('click', '.headerMobile__menu-icon', function () {
            const element = $(".headerMobile__submMenuWrap");

            if (!element) return false

            if (!element.hasClass('active')) {
                element.addClass('active')
            }
        })
    },
    handleUnActiveMenu: function () {
        $(window).scroll(function () {
            const element = $('.headerMobile__submMenuWrap');
            if (!element) return
            const isActive = !!element.hasClass('active') || false
            if (isActive) {
                element.removeClass('active')
            }
        })
    },
}

$(document).ready(function () {
    AOS.init()
    App.showMenu();
    App.handleUnActiveMenu();
})
