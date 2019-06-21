$(function() {

    'use straict';

  /* Switch between login & signup */

  $('.login-container h1 span').click(function() {

    $(this).addClass('actived').siblings().removeClass('actived');

    $('.login-container > div').hide(); // Remove all div that have forms 

    $('.' + $(this).data('class')).fadeIn(100); // Show the div with some class name 
    
  });


/*scroll to top*/
  /*  $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            scrollDistance: 300, // Distance from top/bottom before showing element (px)
            scrollFrom: 'top', // 'top' or 'bottom'
            scrollSpeed: 300, // Speed back to top (ms)
            easingType: 'linear', // Scroll to top easing (see http://easings.net/)
            animation: 'fade', // Fade, slide, none
            animationSpeed: 200, // Animation in speed (ms)
            scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
            //scrollTarget: false, // Set a custom target element for scrolling to the top
            scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
            scrollTitle: false, // Set a custom <a> title if required.
            scrollImg: false, // Set true to use image
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
            zIndex: 2147483647 // Z-Index for the overlay
        });
    });

*/
    /* Hide Placeholder on form  Focus */

    $('[placeholder]').focus(function() {

        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function() {

        $(this).attr('placeholder', $(this).attr('data-text'));

    });
    /* Product Page Item */
    $('.color-choose input').on('click', function() {
        var headphonesColor = $(this).attr('data-image');
  
        $('.active').removeClass('active');
        $('.left-column img[data-image = ' + headphonesColor + ']').addClass('active');
        $(this).addClass('active');
    });


    /* Star Rating */
    var logID = 'log',
    log = $('<div id="'+logID+'"></div>');
    $('body').append(log);
    $('[type*="radio"]').change(function () {
        var me = $(this);
        log.html(me.attr('value'));
    });


    $('.confirm').on('click', function() {
        return confirm('Are You Sure');
    });
    


});
