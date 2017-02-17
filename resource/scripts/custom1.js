
$(document).ready(function() {

	/* Navigation Controll */
  $(window).on('scroll', function() {
     if (Math.round($(window).scrollTop()) > $('#header').height() - 80) {
      $('nav.navbar').removeClass('scrolled');
      if (screen.width > 767) {
        $('#img_white').addClass('sr-only');
        $('#img_og').removeClass('sr-only');
      }
    } else {
      $('nav.navbar').addClass('scrolled');
      if (screen.width > 767) {
        $('#img_og').addClass('sr-only');
        $('#img_white').removeClass('sr-only');
      }
    }
  });
  if (screen.width < 768) {
    $('#img_white').addClass('sr-only');
    $('#img_og').removeClass('sr-only');
  }

  $('#xtoggle').click(function() {
    $('#xtoggle').toggleClass("pushed");
  });

  $(window).scroll(function(){
    $('.parallax').css('background-position','center calc(0% + '+($(window).scrollTop()*0.4)+'px');
    $('#parallax2').css('background-position','center calc(-100% + '+($(window).scrollTop()*0.4)+'px');
  });
});


