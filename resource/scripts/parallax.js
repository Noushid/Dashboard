$(document).ready(function() {

  var $window = $(window);

  $('.parallax [data-type="background"]').each(function() {
    var $bgobj = $(this); // assigning the object
    $(window).scroll(function() {
      var yPos = -($window.scrollTop() / $bgobj.data('speed'));
      // Put together the final background position (bgp)
      var bgp = '0% ' + yPos + 'px';
      // Move the background
      $bgobj.css("background-position", bgp);

      $(".parallax-content").each(function(){
        $(this).css({
          top: -40 - $(window).scrollTop()* -0.1 + '%'
        });
      });
      
    });
  });

  $('#parallax2 [data-type="background"]').each(function() {
    var $bgobj = $(this); // assigning the object
    $(window).scroll(function() {
      var yPos = -($window.scrollTop() / $bgobj.data('speed'));
      // Put together the final background position (bgp)
      var bgp = - '120% ' + yPos + 'px';
      // Move the background
      $bgobj.css("background-position", bgp);
      
    });
  });


  // $(window).on("scroll", function(){

  //   $(".parallax-content").each(function(){
  //     $(this).css({
  //       top: -40 - $(window).scrollTop()* -0.1 + '%'
  //     });
  //   });
  // });

});