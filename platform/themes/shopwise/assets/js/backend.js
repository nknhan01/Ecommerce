(function ($) {
    'use strict';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.botbleCookieNewsletter = (() => {

        const COOKIE_VALUE = 1;
        const COOKIE_NAME = 'botble_cookie_newsletter';
        const COOKIE_DOMAIN = $('div[data-session-domain]').data('session-domain');
        const COOKIE_MODAL = $('#newsletter-modal');

        function newsletterWithCookies(expirationInDays) {
            setCookie(COOKIE_NAME, COOKIE_VALUE, expirationInDays);
        }

        function cookieExists(name) {
            return document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1;
        }

        function hideCookieDialog() {
            COOKIE_MODAL.modal('hide', {}, 500);
        }

        function setCookie(name, value, expirationInDays) {
            const date = new Date();
            date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value
                + ';expires=' + date.toUTCString()
                + ';domain=' + COOKIE_DOMAIN
                + ';path=/';
        }

        if (!cookieExists(COOKIE_NAME)) {
            setTimeout(() => {
                COOKIE_MODAL.modal('show', {}, 500);
            }, 5000);
        }

        COOKIE_MODAL.on('hidden.bs.modal', () => {
            if (!cookieExists(COOKIE_NAME) && $('#dont_show_again').is(':checked')) {
                newsletterWithCookies(3);
            } else {
                newsletterWithCookies(1/24);
            }
        });

        return {
            newsletterWithCookies: newsletterWithCookies,
            hideCookieDialog: hideCookieDialog
        };
    })();

    var showError = message => {
        $('.newsletter-error-message').html(message).show();
    }

    var showSuccess = message => {
        $('.newsletter-success-message').html(message).show();
    }

    var handleError = data => {
        if (typeof (data.errors) !== 'undefined' && data.errors.length) {
            handleValidationError(data.errors);
        } else if (typeof (data.responseJSON) !== 'undefined') {
            if (typeof (data.responseJSON.errors) !== 'undefined') {
                if (data.status === 422) {
                    handleValidationError(data.responseJSON.errors);
                }
            } else if (typeof (data.responseJSON.message) !== 'undefined') {
                showError(data.responseJSON.message);
            } else {
                $.each(data.responseJSON, (index, el) => {
                    $.each(el, (key, item) => {
                        showError(item);
                    });
                });
            }
        } else {
            showError(data.statusText);
        }
    }

    var handleValidationError = errors => {
        let message = '';
        $.each(errors, (index, item) => {
            if (message !== '') {
                message += '<br />';
            }
            message += item;
        });
        showError(message);
    }

    window.showAlert = (messageType, message) => {
        if (messageType && message !== '') {
            let alertId = Math.floor(Math.random() * 1000);

            let html = `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
                            <span class="close ion-close" data-dismiss="alert" aria-label="close"></span>
                            <i class="ion-` + (messageType === 'alert-success' ? 'checkmark-circled': 'alert-circled') + ` message-icon"></i>
                            ${message}
                        </div>`;

            $('#alert-container').append(html).ready(() => {
                window.setTimeout(() => {
                    $(`#alert-container #${alertId}`).remove();
                }, 6000);
            });
        }
    }

    $(document).ready(function () {

        $(document).on('click', '.newsletter-form button[type=submit]', function (event) {
            event.preventDefault();
            event.stopPropagation();

            let _self = $(this);

            _self.addClass('button-loading');
            $('.newsletter-success-message').html('').hide();
            $('.newsletter-error-message').html('').hide();

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: new FormData(_self.closest('form')[0]),
                contentType: false,
                processData: false,
                success: res => {
                    _self.removeClass('button-loading');

                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha();
                    }

                    if (!res.error) {
                        window.botbleCookieNewsletter.newsletterWithCookies(30);
                        _self.closest('form').find('input[type=email]').val('');
                        showSuccess(res.message);
                        setTimeout(() => {
                            _self.closest('.modal-body').find('button[data-dismiss="modal"]').trigger('click');
                        }, 2000);
                    } else {
                        showError(res.message);
                    }
                },
                error: res => {
                    if (typeof refreshRecaptcha !== 'undefined') {
                        refreshRecaptcha();
                    }
                    _self.removeClass('button-loading');
                    handleError(res);
                }
            });
        });
    });

    $(document).ready(function () {
        $(document).on('change', '.switch-currency', function () {
            $(this).closest('form').submit();
        });

        $(document).on('change', '.product-filter-item', function () {
            $(this).closest('form').submit();
        });

        $(document).on('click', '.js-add-to-wishlist-button', function (event) {
            event.preventDefault();
            let _self = $(this);

            _self.addClass('button-loading');

            $.ajax({
                url: _self.prop('href'),
                method: 'POST',
                success: res => {

                    if (res.error) {
                        _self.removeClass('button-loading');
                        window.showAlert('alert-danger', res.message);
                        return false;
                    }

                    window.showAlert('alert-success', res.message);

                    $('.btn-wishlist span').text(res.data.count);

                    _self.removeClass('button-loading').removeClass('js-add-to-wishlist-button').addClass('js-remove-from-wishlist-button active');
                },
                error: () => {
                    _self.removeClass('button-loading');
                }
            });
        });


        $(document).on('click', '.js-remove-from-wishlist-button', function (event) {
            event.preventDefault();
            let _self = $(this);

            _self.addClass('button-loading');

            $.ajax({
                url: _self.prop('href'),
                method: 'DELETE',
                success: res => {

                    if (res.error) {
                        _self.removeClass('button-loading');
                        window.showAlert('alert-danger', res.message);
                        return false;
                    }

                    window.showAlert('alert-success', res.message);

                    $('.btn-wishlist span').text(res.data.count);

                    _self.closest('tr').remove();
                    _self.removeClass('button-loading').removeClass('js-remove-from-wishlist-button active').addClass('js-add-to-wishlist-button');
                },
                error: () => {
                    _self.removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.add-to-cart-button', function (event) {
            event.preventDefault();
            let _self = $(this);

            _self.prop('disabled', true).addClass('button-loading');

            $.ajax({
                url: _self.prop('href'),
                method: 'POST',
                data: {
                    id: _self.data('id')
                },
                dataType: 'json',
                success: res => {
                    _self.prop('disabled', false).removeClass('button-loading').addClass('active');

                    if (res.error) {
                        window.showAlert('alert-danger', res.message);
                        return false;
                    }

                    window.showAlert('alert-success', res.message);

                    if (_self.prop('name') === 'checkout' && res.data.next_url !== undefined) {
                        window.location.href = res.data.next_url;
                    } else {
                        $.ajax({
                            url: window.siteUrl + '/ajax/cart',
                            method: 'GET',
                            success: response => {
                                if (!response.error) {
                                    $('.cart_box').html(response.data.html);
                                    $('.btn-shopping-cart span').text(response.data.count);
                                }
                            }
                        });
                    }
                },
                error: () => {
                    _self.prop('disabled', false).removeClass('button-loading');
                }
            });
        });

        $(document).on('click', '.remove-cart-button', function (event) {
            event.preventDefault();

            $('.confirm-remove-item-cart').data('url', $(this).prop('href'));
            $('#remove-item-modal').modal('show');
        });


        $(document).on('click', '.confirm-remove-item-cart', function (event) {
            event.preventDefault();
            let _self = $(this);

            _self.prop('disabled', true).addClass('button-loading');

            $.ajax({
                url: _self.data('url'),
                method: 'GET',
                success: res => {
                    _self.prop('disabled', false).removeClass('button-loading');

                    if (res.error) {
                        return false;
                    }

                    _self.closest('.modal').modal('hide');

                    if ($('.form--shopping-cart').length) {
                        $('.form--shopping-cart').load(window.location.href + ' .form--shopping-cart > *');
                    }

                    $.ajax({
                        url: window.siteUrl + '/ajax/cart',
                        method: 'GET',
                        success: response => {
                            if (!response.error) {
                                $('.cart_box').html(response.data.html);
                                $('.btn-shopping-cart span').text(response.data.count);
                            }
                        }
                    });
                },
                error: () => {
                    _self.prop('disabled', false).removeClass('button-loading');
                }
            });
        });

        window.onBeforeChangeSwatches = function () {
            $('.add-to-cart-form button[type=submit]').prop('disabled', true).addClass('btn-disabled');
        }

        window.onChangeSwatchesSuccess = function (res) {
            $('.add-to-cart-form .error-message').hide();
            $('.add-to-cart-form .success-message').hide();
            if (res.error) {
                $('.add-to-cart-form button[type=submit]').prop('disabled', true).addClass('btn-disabled');
                $('.number-items-available').html('<span class="text-danger">(' + res.message + ')</span>').show();
                $('#hidden-product-id').val('');
            } else {
                $('.add-to-cart-form').find('.error-message').hide();
                $('.product_price .product-sale-price-text').text(res.data.display_sale_price);
                if (res.data.sale_price !== res.data.price) {
                    $('.product_price .product-price-text').text(res.data.display_price).show();
                    $('.product_price .on_sale .on_sale_percentage_text').text(res.data.sale_percentage).show();
                    $('.product_price .show').hide();
                } else {
                    $('.product_price .product-price-text').text(res.data.sale_percentage).hide();
                    $('.product_price .on_sale .on_sale_percentage_text').text(res.data.sale_percentage).hide();
                    $('.product_price .on_sale').hide();
                }

                $('.product_description #product-sku').text(res.data.sku);

                $('#hidden-product-id').val(res.data.id);
                $('.add-to-cart-form button[type=submit]').prop('disabled', false).removeClass('btn-disabled');
                $('.number-items-available').html('<span class="text-success">(' + res.message + ')</span>').show();

                let thumbHtml = '';
                res.data.image_with_sizes.thumb.forEach(function (item, index) {
                    thumbHtml += '<div class="item"><a href="#" class="product_gallery_item ' + (index === 0 ? 'active' : '') + '" data-image="' + res.data.image_with_sizes.origin[index] +'" data-zoom-image="' + res.data.image_with_sizes.origin[index] + '"><img src="' + item + '" alt="image"/></a></div>'
                });

                let slider = $('.slick_slider');

                slider.slick('unslick');

                slider.html(thumbHtml);

                slider.slick({
                    rtl: $('body').prop('dir') === 'rtl',
                    arrows: slider.data('arrows'),
                    dots: slider.data('dots'),
                    infinite: slider.data('infinite'),
                    centerMode: slider.data('center-mode'),
                    vertical: slider.data('vertical'),
                    fade: slider.data('fade'),
                    cssEase: slider.data('css-ease'),
                    autoplay: slider.data('autoplay'),
                    verticalSwiping: slider.data('vertical-swiping'),
                    autoplaySpeed: slider.data('autoplay-speed'),
                    speed: slider.data('speed'),
                    pauseOnHover: slider.data('pause-on-hover'),
                    draggable: slider.data('draggable'),
                    slidesToShow: slider.data('slides-to-show'),
                    slidesToScroll: slider.data('slides-to-scroll'),
                    asNavFor: slider.data('as-nav-for'),
                    focusOnSelect: slider.data('focus-on-select'),
                    responsive: slider.data('responsive')
                });

                $(window).trigger('resize');

                let image = $('#product_img');
                image.prop('src', res.data.image_with_sizes.origin[0]).data('zoom-image', res.data.image_with_sizes.origin[0]);

                let zoomActive = false;

                zoomActive = !zoomActive;
                if (zoomActive) {
                    if (image.length > 0) {
                        image.elevateZoom({
                            cursor: 'crosshair',
                            easing: true,
                            gallery: 'pr_item_gallery',
                            zoomType: 'inner',
                            galleryActiveClass: 'active'
                        });
                    }
                } else {
                    $.removeData(image, 'elevateZoom');//remove zoom instance from image
                    $('.zoomContainer:last-child').remove();// remove zoom container from DOM
                }
            }
        };

        let handleError = function (data, form) {
            if (typeof (data.errors) !== 'undefined' && !_.isArray(data.errors)) {
                handleValidationError(data.errors, form);
            } else if (typeof (data.responseJSON) !== 'undefined') {
                if (typeof (data.responseJSON.errors) !== 'undefined' && data.status === 422) {
                    handleValidationError(data.responseJSON.errors, form);
                } else if (typeof (data.responseJSON.message) !== 'undefined') {
                    $(form).find('.error-message').html(data.responseJSON.message).show();
                } else {
                    let message = '';
                    $.each(data.responseJSON, (index, el) => {
                        $.each(el, (key, item) => {
                            message += item + '<br />';
                        });
                    });

                    $(form).find('.error-message').html(message).show();
                }
            } else {
                $(form).find('.error-message').html(data.statusText).show();
            }
        };

        let handleValidationError = function (errors, form) {
            let message = '';
            $.each(errors, (index, item) => {
                message += item + '<br />';
            });

            $(form).find('.success-message').html('').hide();
            $(form).find('.error-message').html('').hide();

            $(form).find('.error-message').html(message).show();
        };

        $(document).on('click', '.add-to-cart-form button[type=submit]', function (event) {
            event.preventDefault();
            event.stopPropagation();

            let _self = $(this);

            if (!$('#hidden-product-id').val()) {
                _self.prop('disabled', true).addClass('btn-disabled');
                return;
            }

            _self.prop('disabled', true).addClass('btn-disabled').addClass('button-loading');

            _self.closest('form').find('.error-message').hide();
            _self.closest('form').find('.success-message').hide();

            $.ajax({
                type: 'POST',
                cache: false,
                url: _self.closest('form').prop('action'),
                data: new FormData(_self.closest('form')[0]),
                contentType: false,
                processData: false,
                success: res => {
                    _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');

                    if (res.error) {
                        _self.closest('form').find('.error-message').html(res.message).show();
                        return false;
                    }

                    _self.closest('form').find('.success-message').html(res.message).show();

                    if (_self.prop('name') === 'checkout' && res.data.next_url !== undefined) {
                        window.location.href = res.data.next_url;
                    } else {
                        $.ajax({
                            url: window.siteUrl + '/ajax/cart',
                            method: 'GET',
                            success: function (response) {
                                if (!response.error) {
                                    $('.cart_box').html(response.data.html);
                                    $('.btn-shopping-cart span').text(response.data.count);
                                }
                            }
                        });
                    }
                },
                error: res => {
                    _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                    handleError(res, _self.closest('form'));
                }
            });
        });

        $(document).on('change', '.submit-form-on-change', function () {
            $(this).closest('form').submit();
        });

        $(document).on('click', '.form-review-product button[type=submit]', function (event) {
            event.preventDefault();
            event.stopPropagation();
            $(this).prop('disabled', true).addClass('btn-disabled').addClass('button-loading');

            $.ajax({
                type: 'POST',
                cache: false,
                url: $(this).closest('form').prop('action'),
                data: new FormData($(this).closest('form')[0]),
                contentType: false,
                processData: false,
                success: res => {
                    if (!res.error) {
                        $(this).closest('form').find('select').val(0);
                        $(this).closest('form').find('textarea').val('');

                        showSuccess(res.message);

                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showError(res.message);
                    }

                    $(this).prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                },
                error: res => {
                    $(this).prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                    handleError(res, $(this).closest('form'));
                }
            });
        });

    });

    $('.form-coupon-wrapper .coupon-code').keypress(event => {
        if (event.keyCode === 13) {
            $('.apply-coupon-code').trigger('click');
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });

    $(document).on('click', '.btn-apply-coupon-code', event => {
        event.preventDefault();
        let _self = $(event.currentTarget);
        _self.prop('disabled', true).addClass('btn-disabled').addClass('button-loading');

        $.ajax({
            url: _self.data('url'),
            type: 'POST',
            data: {
                coupon_code: _self.closest('.form-coupon-wrapper').find('.coupon-code').val(),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: res => {
                if (!res.error) {
                    $('.form--shopping-cart').load(window.location.href + '?applied_coupon=1 .form--shopping-cart > *', function () {
                        _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                    });
                } else {
                    $('.coupon-error-msg .text-danger').text(res.message);
                    _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                }
            },
            error: data => {
                if (typeof (data.responseJSON) !== 'undefined') {
                    if (data.responseJSON.errors !== 'undefined') {
                        $.each(data.responseJSON.errors, (index, el) => {
                            $.each(el, (key, item) => {
                                $('.coupon-error-msg .text-danger').text(item);
                            });
                        });
                    } else if (typeof (data.responseJSON.message) !== 'undefined') {
                        $('.coupon-error-msg .text-danger').text(data.responseJSON.message);
                    }
                } else {
                    $('.coupon-error-msg .text-danger').text(data.status.text);
                }
                _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
            }
        });
    });

    $(document).on('click', '.btn-remove-coupon-code', event => {
        event.preventDefault();
        let _self = $(event.currentTarget);
        _self.prop('disabled', true).addClass('btn-disabled').addClass('button-loading');

        $.ajax({
            url: _self.data('url'),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: res => {
                if (!res.error) {
                    $('.form--shopping-cart').load(window.location.href + ' .form--shopping-cart > *', function () {
                        _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                    });
                } else {
                    $('.coupon-error-msg .text-danger').text(res.message);
                    _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
                }
            },
            error: data => {
                if (typeof (data.responseJSON) !== 'undefined') {
                    if (data.responseJSON.errors !== 'undefined') {
                        $.each(data.responseJSON.errors, (index, el) => {
                            $.each(el, (key, item) => {
                                $('.coupon-error-msg .text-danger').text(item);
                            });
                        });
                    } else if (typeof (data.responseJSON.message) !== 'undefined') {
                        $('.coupon-error-msg .text-danger').text(data.responseJSON.message);
                    }
                } else {
                    $('.coupon-error-msg .text-danger').text(data.status.text);
                }
                _self.prop('disabled', false).removeClass('btn-disabled').removeClass('button-loading');
            }
        });
    });

})(jQuery);
