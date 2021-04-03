/**
 Demo script to handle the theme demo
 **/

var Demo = function () {

    // Handle Theme Settings
    var handleTheme = function () {

        var panel = $('.theme-panel');

        // handle theme colors
        var setColor = function (color) {
            $('#style_color').attr('href', '/themes/newstv/assets/css/' + color + '.css');
        };

        $('.theme-colors > ul > li > a', panel).click(function (event) {
            event.preventDefault();
            var color = $(this).attr('data-style');
            setColor(color);
            $('ul > li > a', panel).removeClass('current');
            $(this).addClass('current');
        });

        $('.theme-panel-control').click(function (event) {
            event.preventDefault();
            $(this).parents('.theme-panel-wrap').toggleClass('active');
        });
    };

    return {

        //main function to initiate the theme
        init: function () {
            // handles style customer tool
            handleTheme();
        }
    };

}();

jQuery(document).ready(function () {
    Demo.init(); // init theme core componets
});
