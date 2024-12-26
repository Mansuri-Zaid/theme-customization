jQuery(document).ready(function($) {
    $('#filter-button').on('click', function() {
        var dimensions = $('#dimensions').val();
        var starting_price = $('#starting_price').val();

        $.ajax({
            url: ajaxfilter.ajaxurl,
            type: 'GET',
            data: {
                action: 'filter_posts',
                dimensions: dimensions,
                starting_price: starting_price
            },
            success: function(response) {
                $('#filtered-posts').html(response);
            }
        });
    });
});
