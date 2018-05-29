(function ($) {
    $('.gradeUpdate').on('change', 'select', function () {
        var $form = $(this).closest('form');
        $form.request();
    });
})(jQuery);
