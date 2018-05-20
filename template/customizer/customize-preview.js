/**
 * Script load in customizer preview (iframe)
 */
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {

        var $ = jQuery.noConflict();

        function closeSidebar() {
            if ($("amp-sidebar").attr('open') === 'open') {
                $('.navbar-toggle').click();
            }
        }

        function openSidebar() {
            if ($("amp-sidebar").attr('open') !== 'open') {
                $('.navbar-toggle').click();
            }
        }

        function scrollToEnd() {
            $("html, body").animate({scrollTop: $(document).height()}, 500);
        }

        var c = wp.customize;

        c('better-amp-header-show-search', function (value) {
            value.bind(function (to) {
                $(".navbar-search")[to === '0' ? 'hide' : 'show']();
            });
        });

        c('better-amp-sidebar-show', function (value) {
            value.bind(function (to) {
                $(".navbar-toggle")[to === '0' ? 'hide' : 'show']();
                closeSidebar();
            });
        });

        c('better-amp-sidebar-logo-img', function (value) {
            value.bind(openSidebar);
        });


        c('better-amp-sidebar-logo-text', function (value) {
            value.bind(function (to) {
                openSidebar();
                $('.sidebar-brand .brand-name').html(to);
            });
        });

        c('better-amp-sidebar-footer-text', function (value) {
            value.bind(function (to) {
                openSidebar();
                $('.sidebar-footer-text').html(to);
            });
        });

        c('better-amp-post-show-thumbnail', function (value) {
            value.bind(function (to) {
                $(".post-thumbnail")[to === '0' ? 'hide' : 'show']();
            });
        });

        c('better-amp-post-show-comment', function (value) {
            value.bind(function (to) {
                scrollToEnd();
                $(".comments-wrapper")[to === '0' ? 'hide' : 'show']();
            });
        });

        c('better-amp-post-show-related', function (value) {
            value.bind(function (to) {
                scrollToEnd();
                $(".related-posts-wrapper")[to === '0' ? 'hide' : 'show']();
            });
        });

        c('better-amp-home-show-slide', function (value) {
            value.bind(function (to) {
                $(".homepage-slider")[to === '0' ? 'hide' : 'show']();
            });
        });

        c('better-amp-footer-copyright-text', function (value) {
            value.bind(function (to) {
                closeSidebar();
                scrollToEnd();
                $('.better-amp-copyright').html(to);
            });
        });

        c('better-amp-menu-text', function (value) {
            value.bind(function (to) {
                openSidebar();
                $("amp-sidebar").animate({scrollTop: $(document).height()}, 500);
                $('.sidebar-footer-text').html(to);
            });
        });

        c('better-amp-color-bg', function (value) {
            value.bind(function (to) {
                $('body.body').css('background', to);
            });
        });
        c('better-amp-color-theme', function (value) {
            value.bind(function (to) {
                $('.post-terms.cats .term-type,.post-terms a:hover,.search-form .search-submit,.better-amp-main-link a,.sidebar-brand,.site-header,.listing-item a.post-read-more:hover')
                    .css('background', to);
                $('.single-post .post-meta a,.entry-content ul.bs-shortcode-list li:before')
                    .css('color', to);
            });
        });
        c('better-amp-color-content-bg', function (value) {
            value.bind(function (to) {
                $('.better-amp-wrapper').css('background', to);
            });
        });
        c('better-amp-color-text', function (value) {
            value.bind(function (to) {
                $('.better-amp-wrapper').css('color', to);
            });
        });
        c('better-amp-color-footer-bg', function (value) {
            value.bind(function (to) {
                scrollToEnd();
                $('.better-amp-footer').css('background', to);
            });
        });
        c('better-amp-color-footer-nav-bg', function (value) {
            value.bind(function (to) {
                scrollToEnd();
                $('.better-amp-footer-nav').css('background', to);
            });
        });

        c('better-amp-footer-main-link', function (value) {
            value.bind(function (to) {
                scrollToEnd();

                $('.better-amp-main-link')[to.toString() === '1' ? 'show' : 'hide']();
            });
        });

        ['twitter', 'facebook', 'google_plus', 'email'].forEach(function (k) {
            c('better-amp-' + k, function (value) {
                value.bind(function (to) {
                    var $wrapper = $('.social-item.' + k);

                    $wrapper[to ? 'show' : 'hide']()
                        .find('a').attr('href', to);

                    openSidebar();
                });
            });
        });

        c('better-amp-post-social-share-show', function (value) {
            value.bind(function (to) {
                scrollToEnd();

                $('.social-list-wrapper')[to.toString() === 'show' ? 'show' : 'hide']();
            });
        });

        c('better-amp-post-social-share', function (value) {

            function sortSocialNetworks(networks) {

                var $currentItem,
                    pos = 0,
                    $context = $(".post-social-list .social-list"),
                    $items = $context.children('.social-item');

                for (var net in networks) {

                    $currentItem = $(".social-item." + net, $context);

                    if ($currentItem.length) {

                        $currentItem[networks[net] != '0' ? 'show' : 'hide']();

                        if ($currentItem.get(0) !== $items.get(pos)) {

                            $currentItem.insertBefore($items.get(pos));

                            break;
                        }
                    }

                    ++pos;
                }
            }

            sortSocialNetworks(this.get()['better-amp-post-social-share']);

            value.bind(function (networks) {
                scrollToEnd();
                sortSocialNetworks.call(this, networks);
            });
        });

        c('better-amp-post-social-share-count', function (value) {
            value.bind(function (to) {
                $(".post-social-list .share-handler .number")
                    [to === 'total' || to === 'total-and-site' ? 'show' : 'hide']();

                $(".post-social-list .social-item .number")
                    [to === 'total-and-site' ? 'show' : 'hide']();
            });
        });

    }, 100);
});