
$(function() {



    $('.user-name').click(function() {
        $('.user-info-box').slideToggle();
    });


    $(document).click(function(event) {
        var $item = $('.user-name');
        var $clickedItem = $(event.target);

        if ($clickedItem.hasClass('user-info-box') || $clickedItem.hasClass('user-name')) {
            return;
        }

        if ($item.css('display') != 'none') {
            $('.user-info-box').slideUp();
        }
    });



});


