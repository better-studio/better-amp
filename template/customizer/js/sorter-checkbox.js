jQuery(function ($) {
    'use strict';

    wp.customize.bind('ready', function () {

        $(".bf-sorter-checkbox-list").sortable({
            placeholder: "placeholder-item",
            cancel: "li.disable-item"
        });

    });


    wp.customize.controlConstructor[ 'sorter-checkbox' ] = wp.customize.Control.extend({

        ready: function () {
            var control = this;

            function changed() {
                var results  = {},
                    $this    = $(this),
                    $wrapper = $this.closest('.bf-sorter-groups-container');

                $("input.sorter-checkbox", $wrapper).each(function () {
                    var val     = this.value,
                        $this   = $(this),
                        checked = $this.is(':checked');

                    if (control.params.choices[ val ]) {
                        results[ val ] = checked ? '1' : '0';
                    }

                    $this.closest('li')[checked ? 'addClass' : 'removeClass']('checked-item');
                }).promise().done(function () {

                    results['rand'] = Math.random()
                    control.setting.set(results);
                });
            }

            control.container.on('change', 'input', changed);

            $(".bf-sorter-checkbox-list", control.container).on('sortupdate', changed);
        }
    });
});

