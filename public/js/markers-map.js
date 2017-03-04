
function $_kav(element) {
    return document.getElementById(element);
}

var speedTest = {};
speedTest.pics = null;
speedTest.map = null;
speedTest.markers = [];
speedTest.infoWindow = null;

speedTest.init = function () {
    var infoboxOptions = {
        content: document.createElement("div"), 
        disableAutoPan: true,
        maxWidth: 450, 
        boxClass: "kwitaraInfomob",
        zIndex: null, 
        closeBoxMargin: "-13px 0px 0px 0px",
        infoBoxClearance: new google.maps.Size(1, 1),
        closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
        isHidden: false,
        pane: "floatPane", 
        enableEventPropagation: false
    };


    var latlng = new google.maps.LatLng(49.89, 2.295);
    var styles = [{"featureType": "all", "elementType": "geometry", "stylers": [{"visibility": "simplified"}, {"hue": "#ff7700"}]}, {"featureType": "administrative", "elementType": "all", "stylers": [{"visibility": "simplified"}]}, {"featureType": "administrative.country", "elementType": "labels.text.fill", "stylers": [{"color": "#1d1d1d"}]}, {"featureType": "administrative.province", "elementType": "labels.text.fill", "stylers": [{"color": "#ffffff"}, {"visibility": "on"}]}, {"featureType": "administrative.province", "elementType": "labels.text.stroke", "stylers": [{"color": "#ed5929"}, {"weight": "5.00"}, {"visibility": "on"}]}, {"featureType": "administrative.locality", "elementType": "labels.text.fill", "stylers": [{"color": "#787878"}, {"visibility": "on"}]}, {"featureType": "administrative.locality", "elementType": "labels.text.stroke", "stylers": [{"color": "#ffffff"}, {"visibility": "on"}, {"weight": "5.00"}]}, {"featureType": "administrative.neighborhood", "elementType": "labels.text", "stylers": [{"visibility": "on"}]}, {"featureType": "administrative.neighborhood", "elementType": "labels.text.fill", "stylers": [{"visibility": "on"}, {"color": "#2d2d2d"}]}, {"featureType": "administrative.neighborhood", "elementType": "labels.text.stroke", "stylers": [{"color": "#ffffff"}, {"visibility": "on"}, {"weight": "5.00"}]}, {"featureType": "administrative.land_parcel", "elementType": "geometry.fill", "stylers": [{"saturation": "64"}]}, {"featureType": "landscape", "elementType": "geometry", "stylers": [{"color": "#fafafa"}]}, {"featureType": "poi", "elementType": "all", "stylers": [{"visibility": "off"}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#2c2c2c"}]}, {"featureType": "road", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"color": "#d5d5d5"}]}, {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "on"}, {"color": "#ff0000"}]}, {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"color": "#ed5929"}]}, {"featureType": "road", "elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"weight": "5.00"}]}, {"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#ed5929"}, {"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"visibility": "on"}, {"color": "#ed5929"}]}, {"featureType": "road.highway", "elementType": "labels.text", "stylers": [{"color": "#ffffff"}]}, {"featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{"color": "#ffffff"}]}, {"featureType": "road.highway", "elementType": "labels.text.stroke", "stylers": [{"visibility": "on"}, {"color": "#ed5929"}]}, {"featureType": "road.arterial", "elementType": "geometry.stroke", "stylers": [{"color": "#d9d9d9"}, {"visibility": "on"}]}, {"featureType": "transit.station", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]}, {"featureType": "transit.station.airport", "elementType": "geometry.fill", "stylers": [{"visibility": "simplified"}, {"lightness": "4"}, {"saturation": "-100"}]}, {"featureType": "water", "elementType": "all", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "elementType": "geometry.fill", "stylers": [{"color": "#e1e1e1"}, {"visibility": "on"}]}];
    //Litgh  var styles =[{"stylers":[{"hue":"#ff1a00"},{"invert_lightness":true},{"saturation":-100},{"lightness":33},{"gamma":0.5}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#2D333C"}]}];

    var options = {
        zoom: 6,
        scrollwheel: false,
        styles: styles,
        'center': latlng,
        'mapTypeId': google.maps.MapTypeId.ROADMAP
    };
    speedTest.map = new google.maps.Map($_kav('map_canvas'), options);
    speedTest.pics = data.property;
    speedTest.infoWindow = new InfoBox(infoboxOptions); //new google.maps.InfoWindow();

    speedTest.showMarkers();
    
    // I create an OverlayView, and set it to add the "markerLayer" class to the markerLayer DIV
    var myoverlay = new google.maps.OverlayView();
    myoverlay.draw = function () {
         this.getPanes().markerLayer.id='markerLayer';
    };
    myoverlay.setMap(speedTest.map);
};

speedTest.showMarkers = function () {
    speedTest.markers = [];
    if (speedTest.markerClusterer) {
        speedTest.markerClusterer.clearMarkers();
    }
    var numMarkers = speedTest.pics.length;
    for (var i = 0; i < numMarkers; i++) {
        var titleText = speedTest.pics[i].label;
        if (titleText === '') {
            titleText = 'No title';
        }

        var propertyCat = speedTest.pics[i].img;


        var item = document.createElement('DIV');
        var title = document.createElement('A');
        title.href = '#';
        title.className = 'title';
        title.innerHTML = titleText;
        var latLng = new google.maps.LatLng(speedTest.pics[i].latitude,
                speedTest.pics[i].longitude);

        var imageUrl = propertyCat;

        var markerImage = new google.maps.MarkerImage(imageUrl, new google.maps.Size(70 , 70));

        var marker = new google.maps.Marker({
            'position': latLng,
            'icon': markerImage,
            'optimized':false
            
        });

        var fn = speedTest.markerClickFunction(speedTest.pics[i], latLng);
        google.maps.event.addListener(marker, 'click', fn);
        google.maps.event.addDomListener(title, 'click', fn);
        speedTest.markers.push(marker);
         
         
     
    }

    window.setTimeout(speedTest.time, 0);
};

speedTest.markerClickFunction = function (pic, latlng) {
    return function (e) {
        e.cancelBubble = true;
        e.returnValue = false;
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
        var title       = pic.label;
        var property_url= '?nav=business&part=proposition&id=' + pic.ID;
        var business    = pic.name;
        var property_cover = pic.img;
        var country       = pic.country;
        var city        = pic.city ;
        var address        = pic.adress ;
        var zip_code        = pic.zip_code ;
        var remuneration        = pic.remuneration ;
        var distance        = pic.distance ;

        var html_property = '<div class="map-one-property" style="min-width: 450px ! important; overflow: hidden;z-index: 1;"> ' +

                            '     ' +

                            '     <div class="map-property-thumb">' +
                            '        <a href="' + property_url + '" title="' + title + '"><img src="' + property_cover + '" alt=""></a>' +
                            '     </div>   ' +

                            '     <div class="map-property-body">	' +

                            '         <h2><a href="' + property_url+ '">' + title + '</a></h2>' + 

                            '         <span class="map-property-type">' +status+ '</span>' +
                            '         <span class="map-property-price"> ' + business +' </span>' +
                            '         <p>' + address + ',' + zip_code + '</p>' +

                            '         <div class="map-property-details">' +

                            '             <span><i class="fa fa-map-marker"></i> ' + city + ',' + country + '</span> ' +
                            '             <span><i class="fa fa-road"></i> ' + (Math.round(distance * 100)/100).toString().replace('.',',') + 'km</span> ';

        switch (remuneration){
            case 1 : html_property += '<span><i class="fa fa-money"></i> <i class="fa fa-check"></i></span>'; break;
            case 2 : html_property += '<span><i class="fa fa-money"></i> <i class="fa fa-arrow"></i></span>'; break;
            default : html_property += '<span><i class="fa fa-money"></i><b title="Demander cette information directement à l\'entreprise"> ??</b></span>'; break;
        }

        html_property +=    '         </div>' +

                            '     </div>' +

                            '</div>';

        speedTest.infoWindow.setContent(html_property);
        speedTest.infoWindow.setPosition(latlng);
        speedTest.infoWindow.open(speedTest.map);
    };
};

speedTest.clear = function () {
    for (var i = 0, marker; marker = speedTest.markers[i]; i++) {
        marker.setMap(null);
    }
};

speedTest.change = function () {
    speedTest.clear();
    speedTest.showMarkers();
};

speedTest.time = function () {

    var start = new Date();

    for (var i = 0, marker; marker = speedTest.markers[i]; i++) {
        marker.setMap(speedTest.map);
    }
    var end = new Date();
};

 