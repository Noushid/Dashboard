$(document).ready(function() {
  $('button.humburger').click(function(){
	  $('.page-wrapper').toggleClass('open');
	}); 
	if ($(window).width() >= 1200) {
		$('.page-wrapper').addClass('open');
	}

	// Calender and Clock Widget
	var d = new Date();
	var monthNames = ["January", "February", "March", "April", "May", "June",
	  "July", "August", "September", "October", "November", "December"
	];
	$("#month").text(monthNames[d.getMonth()]);
	$("#day").text(d.getDate());
	var weekNames = ["Sunday","Monday", "Tuesday", "Wednessday", "Thursday", "Friday", "Saturday"];
	$("#week").text(weekNames[d.getDay()]);
	function timer() {
	  var d = new Date();
	  var h = d.getHours(),
	      mm = d.getMinutes(),
	      ss = d.getSeconds(),
	      dd = 'AM',
	      hh = h;
	  if (hh >= 12) {
	    hh = h - 12;
	    dd= 'PM';
	  }
	  if (hh === 0) {
	    hh = 12;
	  }
	  hh = hh<10?'0'+hh:hh;
	  mm = mm<10?'0'+mm:mm;
	  ss = ss<10?'0'+ss:ss;
	      
	  $("#hour").html(hh);
	  $("#minut").html(mm);
	  // $("#second").html(ss);
	  $("#date").html(dd);

	}
	setInterval(function(){ timer();}, 1000);
	//End Calender and Clock Widget

});