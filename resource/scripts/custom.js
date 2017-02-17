
$(document).ready(function() {

	/* Navigation Controll */
  $(window).on('scroll', function() {
    if (Math.round($(window).scrollTop()) > 100) {
      $('nav.navbar').removeClass('scrolled');
    } else {
      $('nav.navbar').addClass('scrolled');
    }
  });

  $('#xtoggle').click(function() {
    $('#xtoggle').toggleClass("pushed");
  }); 

  $(window).scroll(function(){
    $('.parallax').css('background-position','center calc(0% + '+($(window).scrollTop()*0.4)+'px');
    $('#parallax2').css('background-position','center calc(-100% + '+($(window).scrollTop()*0.4)+'px');
  });
});


