function createSlider() {

    //rotation speed and timer
    var speed = 5000;

    jQuery('#carousel').hover(
        function () {
            jQuery('#buttons').show();
        },
        function () {
            jQuery('#buttons').hide();
        }
    );

    //grab the width and calculate left value
    var item_width = jQuery('#slides li').outerWidth();
    var left_value = item_width * (-1);

    //move the last item before first item, just in case user click prev button
    jQuery('#slides li:first').before(jQuery('#slides li:last'));

    //set the default item to the correct position
    jQuery('#slides ul').css({'left' : left_value});
    jQuery('#image_slider').val(jQuery('#slides li:nth-child(2) > img').attr('src'));

    //if user clicked on prev button
    jQuery('#prev').click(function() {

        //get the right position
        var left_indent = parseInt(jQuery('#slides ul').css('left')) + item_width;

        //slide the item
        jQuery('#slides ul').animate({'left' : left_indent}, 200,function(){

            //move the last item and put it as first item
            jQuery('#slides li:first').before(jQuery('#slides li:last'));

            //set the default item to correct position
            jQuery('#slides ul').css({'left' : left_value});
            jQuery('#image_slider').val(jQuery('#slides li:nth-child(2) > img').attr('src'));

        });

        //cancel the link behavior
        return false;

    });


    //if user clicked on next button
    jQuery('#next').click(function() {

        //get the right position
        var left_indent = parseInt(jQuery('#slides ul').css('left')) - item_width;

        //slide the item
        jQuery('#slides ul').animate({'left' : left_indent}, 200, function () {

            //move the first item and put it as last item
            jQuery('#slides li:last').after(jQuery('#slides li:first'));

            //set the default item to correct position
            jQuery('#slides ul').css({'left' : left_value});
            jQuery('#image_slider').val(jQuery('#slides li:nth-child(2) > img').attr('src'));
        });

        //cancel the link behavior
        return false;

    });
};