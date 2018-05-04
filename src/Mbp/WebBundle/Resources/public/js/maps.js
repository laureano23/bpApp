$(function () {

    function initMap() {

        var location = new google.maps.LatLng(-34.6819928,-58.5496729);

        var mapCanvas = document.getElementById('map');
        var mapOptions = {
            center: location,
            zoom: 16,
            panControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions);

        

        var marker = new google.maps.Marker({
            position: location,
            map: map,
        
        });

        var contentString = '<div class="info-window">' +
                '<h3>Metalurgica BP</h3>' +
                '<div class="info-content">' +
                '<p>Fabrica de radiadores</p>' +
                '</div>' +
                '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString,
            maxWidth: 400
        });

        infowindow.open(map, marker);


    }

    google.maps.event.addDomListener(window, 'load', initMap);
});