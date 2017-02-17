  /* Map */
  $(document).ready(function() {
  function initialize() {

    var gmarkers = [];
    var map = null;
    var infowindow = null;

     var styles = [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}];

    // var styles = [{
    //   'featureType': 'landscape',
    //   'stylers': [{
    //     'saturation': -100
    //   }, {
    //     'lightness': 50
    //   }, {
    //     'visibility': 'on'
    //   }]
    // }, {
    //   'featureType': 'poi',
    //   'stylers': [{
    //     'saturation': -100
    //   }, {
    //     'lightness': 11
    //   }, {
    //     'visibility': 'simplified'
    //   }]
    // }, {
    //   'featureType': 'road.highway',
    //   'stylers': [{
    //     'saturation': -90,
    //   }, {
    //     'visibility': 'simplified'
    //   }, ]
    // }, {
    //   'featureType': 'road.arterial',
    //   'stylers': [{
    //     'saturation': -90
    //   }, {
    //     'lightness': 30
    //   }, {
    //     'visibility': 'on'
    //   }]
    // }, {
    //   'featureType': 'road.local',
    //   'stylers': [{
    //     'saturation': -100
    //   }, {
    //     'lightness': 40
    //   }, {
    //     'visibility': 'on'
    //   }]
    // }, {
    //   'featureType': 'transit',
    //   'stylers': [{
    //     'saturation': -100
    //   }, {
    //     'visibility': 'on'
    //   }]
    // }, {
    //   'featureType': 'administrative.province',
    //   'stylers': [{
    //     'visibility': 'off'
    //   }]
    // }, {
    //   'featureType': 'water',
    //   'elementType': 'labels',
    //   'stylers': [{
    //     'visibility': 'on'
    //   }, {
    //     'lightness': -25
    //   }, {
    //     'saturation': -50
    //   }]
    // }, {
    //   'featureType': 'water',
    //   'elementType': 'geometry',
    //   'stylers': [{
    //     'lightness': 10
    //   }, {
    //     'saturation': -35
    //   }]
    // }];

    var mapOptions = {
      center: new google.maps.LatLng(11.120231, 76.120368),
      zoom: 15,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      zoomControl: true,
      scrollwheel: false,
      styles: styles
    };

    map = new google.maps.Map(document.getElementById('custom-map'), mapOptions);

    google.maps.event.addListener(map, 'click', function() {
      infowindow.close();
    });

    var locations = [
      ['Psybo Technologies', 11.120231, 76.120368]
    ];

    /*infowindow = new google.maps.InfoWindow({
      size: new google.maps.Size(150,50)
    });*/

    var custom_map = new google.maps.Map(document.getElementById('custom-map'), mapOptions);

    var marker = new MarkerWithLabel({
      position: custom_map.getCenter(),
      icon: {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 0, //tamaño 0
      },
      map: custom_map,
      labelAnchor: new google.maps.Point(20, 60),
      labelClass: 'label', // the CSS class for the label
    });

  }

  // add window listener for GMaps
  google.maps.event.addDomListener(window, 'load', initialize);

});