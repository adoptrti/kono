/**
 *
 */
var map, latLng, marker, infoWindow, ad, geocoder = new google.maps.Geocoder();

function scrollToMap() {
    document.body.scrollTop = document.getElementById("maps").offsetTop;
    //animate(document.body, "scrollTop", "", 0, document.getElementById("googlemaps").offsetTop, 500, true);
}

function showAddress(val) {
    if (val === "") {
        alert("Please enter a search query");
        return;
    }
    infoWindow.close();
    geocoder.geocode({
        'address': decodeURI(val)
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            marker.setPosition(results[0].geometry.location);
            geocode(results[0].geometry.location);
        } else {
            defaultLocation();
        }
    });
}

function showInfoWindow(address) {
    var html = '';
    var pos = marker.getPosition();
    window.location.hash = '#' + pos.lat() + "," + pos.lng();
    html += '<b>Postal Address:</b> ' + address;
    html += '<br><small>' + '<i class="ti ti-location-pin"></i> Latitude: ' + pos.lat().toString().substr(0, 10) + ' &nbsp; Longitude: ' + pos.lng().toString().substr(0, 10) + '</small><br>';
    map.panTo(pos);
    infoWindow.setContent("<div id='iw' style='max-width:250px;color:#000'>" + html + "</div>");
    infoWindow.open(map, marker);
}

function formatGeocodeResults(results, lat, lng) {
    var i = void 0,
        c = void 0,
        o = {},
        data = results[0],
        comps = data.address_components;

    for (i = 0; i < comps.length; i += 1) {
        c = comps[i];
        if (c.types && c.types.length > 0) {
            o[c.types[0]] = c.long_name;
            o[c.types[0] + '_s'] = c.short_name;
        }
    }

    var geometry = data.geometry;
    return {
        coords: geometry && geometry.location ? {
            latitude: lat,
            longitude: lng
        } : null,
        address: {
            commonName: o.point_of_interest || o.premise || o.subpremise || o.colloquial_area || '',
            streetNumber: o.street_number || '',
            street: o.administrative_area_level_4 || o.administrative_area_level_3 || o.route || '',
            route: o.route || '',
            neighborhood: o.neighborhood || o.administrative_area_level_5 || o.administrative_area_level_4 || '',
            town: o.sublocality || o.administrative_area_level_2 || '',
            city: o.locality || o.administrative_area_level_1 || '',
            region: o.administrative_area_level_2 || o.administrative_area_level_1 || '',
            postalCode: o.postal_code || '',
            state: o.administrative_area_level_1 || '',
            stateCode: o.administrative_area_level_1_s || '',
            country: o.country || '',
            countryCode: o.country_s || ''
        },
        formattedAddress: data.formatted_address,
        type: geometry.location_type || '',
        placeId: data.place_id,
    };
}

function geocode(position) {
	geocoder.geocode({
        latLng: position,
        language: 'en'
    }, function(responses) {
        var pos = marker.getPosition();
        window.location.hash = '#' + pos.lat() + "," + pos.lng();
        if (responses && responses.length > 0) {
            var rr = formatGeocodeResults(responses, pos.lat(), pos.lng());

            $.get("/site/placeinfo", {
                t: 'json',
                data: JSON.stringify([rr.address, rr.coords])
            }, function(data) {
                $("#result").html(data);
                $("#googlemaps").height("200px");
                $("#askaddress").hide();
                showInfoWindow(responses[0].formatted_address);
            });
            showInfoWindow(responses[0].formatted_address);

        } else {
            window.alert('Sorry but Google Maps could not determine the approximate postal address of this location.');
        }
    });
}

function ctrlq() {

    var myOptions = {
        zoom: 14,
        styles: [{
                "featureType": "administrative",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#444444"
                }]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{
                    "color": "#f2f2f2"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [{
                        "saturation": -100
                    },
                    {
                        "lightness": 45
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [{
                    "visibility": "simplified"
                }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{
                        "color": "#46bcec"
                    },
                    {
                        "visibility": "on"
                    }
                ]
            }
        ],
        fullscreenControl: false,
        scrollwheel: false,
        panControl: true,
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        scaleControl: false,
        scaleControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        streetViewControl: true,
        streetViewControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM
        }
    };

    map = new google.maps.Map(document.getElementById('googlemaps'), myOptions);

    var ad = '<ins class="adsbygoogle" style="display:inline-block;width:320px;height:100px" data-ad-client="ca-pub-3152670624293746" data-ad-slot="1136209176"></ins>';
    var adNode = document.createElement('div');
    adNode.innerHTML = ad;
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(adNode);
    google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
        (adsbygoogle = window.adsbygoogle || []).push({});
    });

    var coordinates = window.location.hash;
    /*
    if (coordinates !== "") {
        var hashlocation = coordinates.split(",");
        if (hashlocation.length == 2) {
            showMap(hashlocation[0].substr(1), hashlocation[1], true);
            return;
        }
    }
    */

    if (coordinates !== "" && coordinates !== "#maps") {
        defaultLocation();
        showAddress(coordinates.substr(1));
    } else if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(locationFound, defaultLocation);
    } else {
        defaultLocation();
    }

    var input = document.getElementById('askaddress');
    map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(input);
    var autocomplete = new google.maps.places.Autocomplete(document.getElementById('addrbox'));
    autocomplete.bindTo('bounds', map);
    autocomplete.setTypes(["geocode"]);

    autocomplete.addListener('place_changed', function() {
        infoWindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); // Why 17? Because it looks good.
        }
        marker.setPosition(place.geometry.location);
        geocode(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
            showInfoWindow(address);
        }
    });

}

function locationFound(position) {
    showMap(position.coords.latitude, position.coords.longitude);
}

function defaultLocation() {
    showMap(28.61283974838129, 77.23114019763796);

}

function showMap(lat, lng, hideinfo) {

    latLng = new google.maps.LatLng(lat, lng);

    map.setCenter(latLng);

    map.panBy(0, 120);

    marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP
    });

    marker.addListener('click', toggleBounce);

    infoWindow = new google.maps.InfoWindow({
        content: '<div id="iw" style="max-width:300px;font-size:1.1em;color:#333">Drag the red marker anywhere on the map to know the approximate postal address of that location.<br>For help, please <a href="https://twitter.com/adoptrti" target="_blank">tweet</a> <i class="ti ti-twitter"></i> or <a href="mailto:kono@adoptrti.org?Subject=MapsAddress" taret="_blank">email us</a>.</div>'
    });


    if (hideinfo) {
        geocode(latLng);
    } else {
        infoWindow.open(map, marker);
        geocode(latLng);
    }

    google.maps.event.addListener(marker, 'dragstart', function(e) {
        infoWindow.close();
    });

    google.maps.event.addListener(marker, 'dragend', function(e) {
        var point = marker.getPosition();
        map.panTo(point);
        geocode(point);
    });


}

function toggleBounce() {
    if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
    } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
    }
}

ctrlq();
