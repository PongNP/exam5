<!DOCTYPE html>
<html>
    <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    </head>
    <body>
        <div id="map"></div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBYfGJkrRVvNkhoVkgdIew_3PKuq6ecMVk&callback=initMap" async defer></script>
        <script>
            var map;
            var infoWindow;
            function initMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 7
                });
                var infowindow = new google.maps.InfoWindow();
                let geocoder = new google.maps.Geocoder();
                let location = "Thailand";
                geocoder.geocode({ 'address': location }, function(results, status){
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                    }
                });

                // get all polygon
                $.get( "{{ url('api/region') }}", function( data ) {
                    try{
                        console.log(data);
                        map.data.addGeoJson(data);
                        console.log("Polygons loaded!!!");
                    }catch(err) {
                        console.log(err.message);
                    }
                }, "json");

                // get all marker
                $.get( "{{ url('api/order') }}", function( data ) {
                    try{
                        console.log(data);
                        map.data.addGeoJson(data);
                        console.log("Markers loaded!!!");
                    }catch(err) {
                        console.log(err.message);
                    }
                }, "json");
                
                // Click event
                google.maps.event.addListener(map, 'click', function() {
                    infowindow.close();
                });
                map.data.addListener('click', function(event) {
                    if(event.feature.getGeometry().getType()=="Polygon"){
                        let region_id = event.feature.getProperty("id");
                        $.get( "{{ url('api/region') }}/" + region_id, function( data ) {
                            var contentString = "" +
                                "<b>แขวง/ตำบล/</b> : " + event.feature.getProperty("sub_district_name_th") + "<br>" +
                                "<b>เขต/อำเภอ</b> : " + event.feature.getProperty("district_name_th") + "<br>" +
                                "<b>จังหวัด</b> : " + event.feature.getProperty("province_name_th") + "<br>" + 
                                "<hr>" +
                                "<b style=\"font-size: x-large;\">Order dockets</b><br>" +
                                "<b>Province</b> : " + data.dockets.province + "<br>" +
                                "<b>District</b> : " + data.dockets.district + "<br>" +
                                "<b>Sub-District</b> : " + data.dockets.sub_district + "<br>" +
                                "<hr>" +
                                "<b style=\"font-size: x-large;\">Sales amount</b><br>" +
                                "<b>Province</b> : " + data.sales.province + "<br>" +
                                "<b>District</b> : " + data.sales.district + "<br>" +
                                "<b>Sub-District</b> : " + data.sales.sub_district + "<br>";
                            infowindow.setContent(contentString);
                            infowindow.setPosition(event.latLng);
                            infowindow.open(map);
                        });
                    }
                });
            }
        </script>
        
  </body>
</html>