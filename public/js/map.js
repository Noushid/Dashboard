function init_map() {
  var myOptions = {
    zoom: 17,
    scrollwheel: false,
    center: new google.maps.LatLng(11.1230731, 76.11637089999999),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
  marker = new google.maps.Marker({
    map: map,
    position: new google.maps.LatLng(11.1230731, 76.11637089999999)
  });
  infowindow = new google.maps.InfoWindow({
    content: "<b>VM Builders</b><br/>Rajiv Gandhi Bypass Road,Manjeri<br/> Manjeri"
  });
  google.maps.event.addListener(marker, "click", function() {
    infowindow.open(map, marker);
  });
  infowindow.open(map, marker);
}
google.maps.event.addDomListener(window, 'load', init_map);