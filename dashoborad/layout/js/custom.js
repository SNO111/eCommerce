

//auto expand textarea
function adjust_textarea(h) {
    h.style.height = "20px";
    h.style.height = (h.scrollHeight)+"px";
}
$(function () {
    $('.confirm').on('click', function() {
        return confirm('Are You Sure');
     });

$(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
  }).resize();


  /* quiz-window-close  */
  $('.quiz-window #toggle-click').on('click', function() {
    $('.quiz-window .quiz-window-body').slideToggle();
    var $arrows = $(this).find("i");
    $arrows.toggle();
  });


});
var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
  };