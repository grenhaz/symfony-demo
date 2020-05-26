$(function () {
    $('*[data-ajax-url]').each(function () {
        var $this = $(this);
        var url = $(this).data('ajax-url');
        
        $.ajax({
            'url': url,
            'success': function (response) {
                $this.html(response);
            }
        });
    });
    $('*[rel=popover]').popover({
        'trigger': 'hover'
    });
});