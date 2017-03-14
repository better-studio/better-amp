/**
 * Script load in wp-admin/customize.php page (outside iframe)
 */
jQuery(function ($) {

        wp.customize.bind('ready', function () {

            $("#customize-control-better-amp-show-on-front :radio").on('change', function () {

                var $container = $("#customize-control-better-amp-page-on-front");
                if (this.value === 'page') {
                    $container.attr('style', '');
                } else {
                    $container.css('pointer-events', 'none')
                              .css('opacity', '0.7');
                }
            }).filter(':checked').change();
            setTimeout(function () {
                $(".preview-tablet").click();
            }, 100);
        });
        function parseUrl(url) {
            var a  = document.createElement('a');
            a.href = url;
            return a;
        }

        function parseParams(query) {
            var re       = /([^&=]+)=?([^&]*)/g,
                decodeRE = /\+/g;  // Regex for replacing addition symbol with a space
            var decode = function (str) {
                return decodeURIComponent(str.replace(decodeRE, " "));
            };

            var params = {}, e;
            while (e = re.exec(query)) {
                var k = decode(e[ 1 ]), v = decode(e[ 2 ]);
                if (k.substring(k.length - 2) === '[]') {
                    k = k.substring(0, k.length - 2);
                    (params[ k ] || (params[ k ] = [])).push(v);
                }
                else params[ k ] = v;
            }
            return params;
        }

        function pageRedirect(url, section) {
            var url = window.location.pathname + '?url=' + url + '&autofocus[panel]=better-amp-panel';
            if (section) {
                url += '&autofocus[section]=' + section;
            }
            window.location = url;
        }

        function customizerRedirect(url) {
            var obj     = wp.customize.previewer;
            var message = {
                id: 'url',
                channel: obj.channel(),
                data: url
            };

            window.postMessage(JSON.stringify(message), obj.origin());
        }


        var currentUrl;
        window.addEventListener('message', function (event) {
            var data = JSON.parse(event.data);

            if (data.id === 'url') {
                currentUrl = data.data;
                var m      = currentUrl.match(/(.*?)\/?\?.+$/);
                if (m && m[ 1 ]) {
                    currentUrl = m[ 1 ];
                }
            }
        });

        wp.customize.bind('ready', function () {

            /**
             * Bind Click Events
             */
            (function () {
                var parsed       = parseUrl(document.location.href),
                    quStr        = decodeURIComponent(parsed.search).split('?').pop(),
                    parsedParams = parseParams(quStr),
                    isAmpVersion = parsedParams && /\/amp\/*$/ig.test(parsedParams.url);

                $("#accordion-panel-better-amp-panel>h3").on('click', function () {
                    if (!isAmpVersion) {
                        var ampVersionURL = encodeURIComponent(better_amp_customizer.amp_url);

                        customizerRedirect(ampVersionURL);
                    }
                });

                /*
                 var $context = $("#accordion-panel-better-amp-panel");
                 $(".customize-panel-back", $context).on('click', function () {
                 if (isAmpVersion) {
                 window.location.href = window.location.pathname;
                 }
                 });
                 */

                $("#accordion-section-better-amp-archive-section>h3").on('click', function () {
                    var crrUrl      = currentUrl ? currentUrl : parsedParams.url.replace(/\/+$/, ''),
                        redirectUrl = better_amp_customizer.archive_url.replace(/\/+$/, '');

                    if (redirectUrl !== crrUrl) {
                        customizerRedirect(redirectUrl);
                    }
                });

                $("#accordion-section-better-amp-post-section>h3").on('click', function () {
                    var crrUrl      = currentUrl ? currentUrl : parsedParams.url.replace(/\/+$/, ''),
                        redirectUrl = better_amp_customizer.post_url.replace(/\/+$/, '');

                    if (redirectUrl !== crrUrl) {
                        customizerRedirect(redirectUrl);
                    }
                });

            })();
        });
    }
);