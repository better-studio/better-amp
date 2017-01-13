jQuery(function ($) {
    $(document).on('click', ".cb-enable", function () {
        var parent = $(this).parents('.bf-switch');
        $('.cb-disable', parent).removeClass('selected');
        $(this).addClass('selected');

        $('.checkbox', parent).attr('value', 1)
                              .trigger('change');

    }).on('click', ".cb-disable", function () {
        var parent = $(this).parents('.bf-switch');
        $('.cb-enable', parent).removeClass('selected');
        $(this).addClass('selected');

        $('.checkbox', parent).attr('value', 0)
                              .trigger('change');
    });
});
