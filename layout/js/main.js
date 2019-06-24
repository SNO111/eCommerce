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
    

/* Set rates + misc */
var taxRate = 0.05;
var shippingRate = 15.00; 
var fadeTime = 300;


/* Assign actions */
$('.product-quantity input').change( function() {
  updateQuantity(this);
});

$('.product-removal button').click( function() {
  removeItem(this);
});


/* Recalculate cart */
function recalculateCart()
{
  var subtotal = 0;
  
  /* Sum up row totals */
  $('.product').each(function () {
    subtotal += parseFloat($(this).children('.product-line-price').text());
  });
  
  /* Calculate totals */
  var tax = subtotal * taxRate;
  var shipping = (subtotal > 0 ? shippingRate : 0);
  var total = subtotal + tax + shipping;
  
  /* Update totals display */
  $('.totals-value').fadeOut(fadeTime, function() {
    $('#cart-subtotal').html(subtotal.toFixed(2));
    $('#cart-tax').html(tax.toFixed(2));
    $('#cart-shipping').html(shipping.toFixed(2));
    $('#cart-total').html(total.toFixed(2));
    if(total == 0){
      $('.checkout').fadeOut(fadeTime);
    }else{
      $('.checkout').fadeIn(fadeTime);
    }
    $('.totals-value').fadeIn(fadeTime);
  });
}


/* Update quantity */
function updateQuantity(quantityInput)
{
  /* Calculate line price */
  var productRow = $(quantityInput).parent().parent();
  var price = parseFloat(productRow.children('.product-price').text());
  var quantity = $(quantityInput).val();
  var linePrice = price * quantity;
  
  /* Update line price display and recalc cart totals */
  productRow.children('.product-line-price').each(function () {
    $(this).fadeOut(fadeTime, function() {
      $(this).text(linePrice.toFixed(2));
      recalculateCart();
      $(this).fadeIn(fadeTime);
    });
  });  
}

$('#search').keyup(function() {
    $('#show_result').show();
    var text = $(this).val();
      $.ajax({
        type: 'GET',
        url: 'search.php',
        data: 'txt=' + text,
        success: function(data) {
          $('#show_result').html(data);

        }

      });
})



});

