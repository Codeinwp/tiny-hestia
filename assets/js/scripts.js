/* global jQuery */

jQuery(document).ready(function ($) {

    var window_width = $(window).width();

    $.hestia = {
        'init':function () {
            this.navbar();
            this.fixPadding();
            this.necessaryClasses();
            this.sidebarToggle();
            this.shop();
            this.detectIos();
        },

        'navbar':function () {
            this.navCaret();
            this.parentLink();
            this.topBarSearch();
        },

        /**
         * Add classes when you click on menu's sub-item icon to display a sub-menu.
         * Prevents action on that link.
         */
        'navCaret':function () {
            $('.navbar .dropdown > a .caret').click(function () {
                event.preventDefault();
                event.stopPropagation();
                $(this).toggleClass('caret-open');
                $(this).parent().siblings().toggleClass('open');

                var navbarDropdown = $('.navbar .dropdown');
                if ( navbarDropdown.hasClass('open') ) {
                    navbarDropdown.removeClass('open');
                    $(this).toggleClass('caret-open');
                    $(this).parent().siblings().toggleClass('open');
                }
            });
        },

        /**
         * Add size for each search input in top-bar
         */
        'topBarSearch':function(){
            var searchInput = $('.hestia-top-bar input[type=search]');
            if ( searchInput.length > 0 ) {
                searchInput.each(function () {
                    $(this).attr('size', $(this).parent().find('.control-label').text().replace(/ |…/g, '').length);
                });
            }
        },



        /**
         * Add active parent links on navigation
         */
        'parentLink':function () {
            $('.navbar .dropdown > a').click(function () {
                location.href = this.href;
            });
        },

        /**
         * Add wrapper on view cart button after adding products to cart.
         */
        'shop':function(){
            $('.added_to_cart').live('DOMNodeInserted', function () {
                if (!( $(this).parent().hasClass('hestia-view-cart-wrapper') )) {
                    $(this).wrap('<div class="hestia-view-cart-wrapper"></div>');
                }
            });
        },

        /**
         * Adds classes on inputs and forms.
         */
        'necessaryClasses':function(){
            /**
             * Add menu-open class on body when menu is opened on mobile.
             */
            var navigation = $('#main-navigation');
            navigation.on('show.bs.collapse', function () {
                $('body').addClass('menu-open');
            });
            navigation.on('hidden.bs.collapse', function () {
                $('body').removeClass('menu-open');
            });

            /**
             * Add necessary classes on inputs and forms
             */
            var addToElements = [
                'input[type="text"]',
                'input[type="email"]',
                'input[type="url"]',
                'input[type="password"]',
                'input[type="tel"]',
                'input[type="search"]',
                'textarea'
            ];
            for ( i = 0; i < addToElements.length; i++ ) {
                if( typeof $(addToElements[i]) !== 'undefined') {
                    $(addToElements[i]).addClass('form-control');
                }
            }

            var select2Input = $('input.select2-input');
            if (typeof select2Input !== 'undefined') {
                select2Input.removeClass('form-control');
            }

            var formControl = $('.form-control');
            if ( typeof formControl !== 'undefined' ) {
                formControl.parent().addClass('form-group');
            }
        },

        /**
         * Fix padding on header and on pages for beaver builder.
         */
        'fixPadding':function () {
            if ( window_width > 768 ) {
                var navbar_height = $('.navbar-fixed-top').outerHeight();
                var beaver_offset = 40;
                $('.pagebuilder-section').css('padding-top', navbar_height);
                $('.fl-builder-edit .pagebuilder-section').css('padding-top', navbar_height + beaver_offset);
                $('.page-header.header-small .container').css('padding-top', navbar_height + 100);

                var headerHeight = $('.single-product .page-header.header-small').height();
                var offset = headerHeight + 100;
                $('.single-product .page-header.header-small .container').css('padding-top', headerHeight - offset);

                var marginOffset = headerHeight - navbar_height - 172;
                $('.woocommerce.single-product .blog-post .col-md-12 > div[id^=product].product').css('margin-top', -marginOffset );
            } else {
                $('.page-header.header-small .container').removeAttr( 'style' );
            }
        },

        /**
         * Detect if browser is iPhone or iPad then add body class
         */
        'detectIos':function () {
            if ($('.hestia-about').length > 0) {
                var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

                if (iOS) {
                    $('body').addClass('is-ios');
                }
            }
        },

        /**
         * Sidebar toggle function
         */
        'sidebarToggle':function () {
            if ($('.blog-sidebar-wrapper,.shop-sidebar-wrapper').length > 0) {

                $('.hestia-sidebar-open').click(function () {
                    $('.sidebar-toggle-container').css('left', '0');
                });

                $('.hestia-sidebar-close').click(function () {
                    $('.sidebar-toggle-container').css('left', '-100%');
                });
            }
        }
    };

    $.hestia.init();

    $(window).resize(function () {
        $.hestia.fixPadding();
    });

    /**
     * Returns a function, that, as long as it continues to be invoked, will not
     * be triggered. The function will be called after it stops being called for
     * N milliseconds. If `immediate` is passed, trigger the function on the
     * leading edge, instead of the trailing.
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            }, wait);
            if (immediate && !timeout) {
                func.apply(context, args);
            }
        };
    }

    /**
     * Checks if an element is displayed on screen.
     * @param elem
     * @returns {boolean}
     */
    function isElementInViewport(elem) {
        var $elem = $(elem);

        // Get the scroll position of the page.
        var scrollElem = ((navigator.userAgent.toLowerCase().indexOf('webkit') !== -1) ? 'body' : 'html' );
        var viewportTop = $(scrollElem).scrollTop();
        var viewportBottom = viewportTop + $(window).height();

        // Get the position of the element on the page.
        var elemTop = Math.round($elem.offset().top);
        var elemBottom = elemTop + $elem.height();

        return ((elemTop < viewportBottom) && (elemBottom > viewportTop));
    }
});