jQuery(function ($) {

    var $context = $(".better-amp-panel");

    var utils = {
        // Panel loader
        // status: loading, succeed, error
        panel_loader: function (status, message) {

            var $bf_loading = $('.bf-loading');

            if ($bf_loading.length === 0) {

                $(document.body)
                    .append('<div class="bf-loading">\n    <div class="loader">\n        <div class="loader-icon in-loading-icon "><i class="dashicons dashicons-update"></i></div>\n        <div class="loader-icon loaded-icon"><i class="dashicons dashicons-yes"></i></div>\n        <div class="loader-icon not-loaded-icon"><i class="dashicons dashicons-no-alt"></i></div>\n        <div class="message">An Error Occurred!</div>\n    </div>\n</div>')
                    .append('<style>\n    .bf-loading {\n        position: fixed;\n        top: 0;\n        left: 0;\n        width: 100%;\n        height: 100%;\n        background-color: #636363;\n        background-color: rgba(0, 0, 0, 0.41);\n        display: none;\n        z-index: 99999;\n    }\n\n    .bf-loading .loader {\n        width: 300px;\n        height: 180px;\n        position: absolute;\n        top: 50%;\n        left: 50%;\n        margin-top: -90px;\n        margin-left: -150px;\n        text-align: center;\n    }\n\n    .bf-loading.not-loaded,\n    .bf-loading.loaded,\n    .bf-loading.in-loading {\n        display: block;\n    }\n\n    .bf-loading.in-loading .loader {\n        color: white;\n    }\n\n    .bf-loading.loaded .loader {\n        color: #27c55a;\n    }\n\n    .bf-loading.not-loaded .loader {\n        color: #ff0000;\n    }\n\n    .bf-loading .loader .loader-icon {\n        font-size: 30px;\n        -webkit-transition: all 0.2s ease;\n        -moz-transition: all 0.2s ease;\n        -ms-transition: all 0.2s ease;\n        -o-transition: all 0.2s ease;\n        transition: all .2s ease;\n        opacity: 0;\n        border-radius: 10px;\n        background-color: #333;\n        background-color: rgba(51, 51, 51, 0.86);\n        width: 60px;\n        height: 60px;\n        line-height: 60px;\n        margin-top: 20px;\n        display: none;\n        position: absolute;\n        left: 50%;\n        margin-left: -30px;\n    }\n\n    .bf-loading .loader .loader-icon .dashicons,\n    .bf-loading .loader .loader-icon .dashicons-before:before {\n        font-size: 55px;\n        line-height: 60px;\n        width: 60px;\n        height: 60px;\n        text-align: center;\n    }\n\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon,\n    .bf-loading.in-loading.loader .loader-icon.in-loading-icon {\n        opacity: 1;\n        display: inline-block;\n    }\n\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon .dashicons,\n    .bf-loading.in-loading .loader .loader-icon.in-loading-icon .dashicons-before:before {\n        -webkit-animation: spin 1.15s linear infinite;\n        -moz-animation: spin 1.15s linear infinite;\n        animation: spin 1.15s linear infinite;\n        font-size: 30px;\n    }\n\n    .bf-loading.loaded .loader .loader-icon.loaded-icon {\n        opacity: 1;\n        display: inline-block;\n        font-size: 50px;\n    }\n\n    .bf-loading.loaded .loader .loader-icon.loaded .dashicons,\n    .bf-loading.loaded .loader .loader-icon.loaded .dashicons-before:before {\n        width: 57px;\n    }\n\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon {\n        opacity: 1;\n        display: inline-block;\n    }\n\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon .dashicons,\n    .bf-loading.not-loaded .loader .loader-icon.not-loaded-icon .dashicons-before:before {\n        font-size: 50px;\n        line-height: 62px;\n    }\n\n    .bf-loading .loader .message {\n        display: none;\n        color: #ff0000;\n        font-size: 12px;\n        line-height: 24px;\n        min-width: 100px;\n        max-width: 300px;\n        left: auto;\n        right: auto;\n        text-align: center;\n        background-color: #333;\n        background-color: rgba(51, 51, 51, 0.86);\n        border-radius: 5px;\n        padding: 4px 20px;\n        margin-top: 90px;\n    }\n\n    .bf-loading.with-message .loader .message {\n        display: inline-block;\n    }\n\n    .bf-loading.loaded .loader .message {\n        color: #27c55a;\n    }\n\n    .bf-loading.in-loading .loader .message {\n        color: #fff;\n    }\n\n    @-moz-keyframes spin {\n        100% {\n            -moz-transform: rotate(360deg);\n        }\n    }\n\n    @-webkit-keyframes spin {\n        100% {\n            -webkit-transform: rotate(360deg);\n        }\n    }\n\n    @keyframes spin {\n        100% {\n            -webkit-transform: rotate(360deg);\n            transform: rotate(360deg);\n        }\n    }\n</style>');

                $bf_loading = $('.bf-loading');
            }

            message = typeof message !== 'undefined' ? message : '';

            if (status == 'loading') {

                $bf_loading.removeClass().addClass('bf-loading in-loading');

                if (message != '') {
                    $bf_loading.find('.message').html(message);
                    $bf_loading.addClass('with-message');
                }

            } else if (status == 'error') {

                $bf_loading.removeClass().addClass('bf-loading not-loaded');

                if (message != '') {
                    $bf_loading.find('.message').html(message);
                    $bf_loading.addClass('with-message');
                }

                setTimeout(function () {
                    $bf_loading.removeClass('not-loaded');
                    $bf_loading.find('.message').html('');
                    $bf_loading.removeClass('with-message');
                }, 1500);

            } else if (status == 'succeed') {

                $bf_loading.removeClass().addClass('bf-loading loaded');

                if (message != '') {
                    $bf_loading.find('.message').html(message);
                    $bf_loading.addClass('with-message');
                }

                setTimeout(function () {
                    $bf_loading.removeClass('loaded');
                    $bf_loading.find('.message').html('');
                    $bf_loading.removeClass('with-message');
                }, 1000);

            } else if (status == 'hide') {

                setTimeout(function () {
                    $bf_loading.removeClass(' in-loading');
                    $bf_loading.find('.message').html('');
                    $bf_loading.removeClass('with-message');
                }, 500);
            }

        },
    };

    var betterAmpPanel = {

        init: function () {

            this.bindEvents();
        },

        bindEvents: function () {

            $("#bf-nav a", $context).on('click', this.onTabClicked.bind(this));
            $(".bf-save-button", $context).on('click', this.onSaveClicked.bind(this));
            $(".bf-reset-button", $context).on('click', this.onResetClicked.bind(this));
            $("#bf-download-export-btn", $context).on('click', this.onExportClicked.bind(this));
            $(".bf-import-upload-btn", $context).on('click', this.onImportClicked.bind(this));

            $context.off( 'click', '.fields-group-title-container' )
                .on('click', '.fields-group-title-container', this.onGroupTitleClicked.bind(this));
        },


        onGroupTitleClicked: function (e) {

            e.stopPropagation();

            var $this = $(e.target).closest('.fields-group'),
                $_group = $this.closest('.fields-group'),
                $_button = $this.find('.collapse-button');

            if ($_group.hasClass('bf-open')) {

                $_group.children('.bf-group-inner').slideUp(400);

                $_group.removeClass('bf-open').addClass('bf-close');
                $_button.find('.fa').removeClass('fa-minus').addClass('fa-plus');

            } else {

                $_group.removeClass('bf-close').addClass('bf-open');
                $_button.find('.fa').removeClass('fa-plus').addClass('fa-minus');

                $_group.children('.bf-group-inner').slideDown(400);
            }
        },

        onTabClicked(e) {

            e.preventDefault();

            var $tab = $(e.target),
                id = $tab.data('go'),
                $group = $('#bf-group-' + id);

            $group.siblings().hide();
            $group.show();

            $tab.closest('ul').find('a').removeClass('active_tab');
            $tab.addClass('active_tab');
        },

        onResetClicked: function (e) {

            e.preventDefault();

            this.ajax({action: 'better-amp-panel-reset', _wpnonce: this.token()});
        },

        token: function () {

            return $("#_wpnonce", $context).val();
        },

        onSaveClicked: function (e) {

            e.preventDefault();

            var data = $("#bf_options_form", $context).serialize();

            this.ajax(data);
        },

        onExportClicked: function (e) {

            e.preventDefault();

            window.location.href = ajaxurl + '?action=better-amp-panel-export&_wpnonce=' + this.token();
        },

        onImportClicked: function (e) {

            e.preventDefault();

            var formData = new FormData(),
                file = $("#bf-import-file-input", $context)[0];

            if (!file.files || !file.files[0]) {

                return;
            }
            formData.append("file", file.files[0], file.name);
            formData.append('action', 'better-amp-panel-import');
            formData.append('_wpnonce', this.token());

            this.ajax(formData, {
                async: true,
                cache: false,
                contentType: false,
                processData: false,
            });
        },

        ajax: function (data, override, success) {

            utils.panel_loader('loading');

            $.ajax($.extend({
                url: ajaxurl,
                type: 'POST',
                data: data,
            }, override))
                .done(function (res) {

                    if (res && res.success) {

                        utils.panel_loader('succeed');

                        success && success(res);

                        setTimeout(function () {
                            window.location.reload();
                        });
                    } else {

                        utils.panel_loader('error');
                    }
                })
                .fail(function () {
                    utils.panel_loader('error');
                });
        }
    }

    betterAmpPanel.init();
})