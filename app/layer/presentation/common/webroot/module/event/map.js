//$(function () {
window.addEventListener("load", function() {
	//if no jquery lib, tries to load it automatically
	if (typeof $ != "function") {
		if (jquery_lib_url && (typeof autoload_jquery_lib == "undefined" || autoload_jquery_lib)) {
			var exists = false;
			var scripts = document.head.getElementsByTagName("scripts");
			
			for (var i = 0; i < scripts.length; i++)
				if (scripts[i].getAttribute("src") == jquery_lib_url) {
					exists = true;
					break;
				}
			
			if (!exists) {
				var script = document.createElement("script");
				script.setAttribute("src", jquery_lib_url);
				document.head.appendChild(script);
				
				if (typeof console != "undefined" && typeof console.log == "function")
					console.log("Loading jquery lib automatically with url: " + jquery_lib_url);
			}
		}
		else
			alert("jQuery lib must be loaded first!");
	}
});

function openMap(elm, url) {
	var popup = $(".event_map_popup");
	popup.remove();//Remove iframe because if we open the popup for the 2nd time, it takes too long loading the new url, showing the map for the previous request.
	
    	popup = $(document.createElement("div"));
    	popup.addClass("myfancypopup event_map_popup");
    	popup.html('<iframe allowfullscreen="allowfullscreen"></iframe>');
    	$("body").append(popup);
    	
    	MyFancyPopup.init({
        	elementToShow: popup,
		parentElement: window,
		onOpen: function() {
		    popup.children("iframe")[0].src = url;
		}
	});
	MyFancyPopup.showPopup();
}

function initializeMapAddressSearch() {
	var main_elm = typeof address_search_map_main_element != "undefined" && address_search_map_main_element ? (address_search_map_main_element instanceof jQuery ? address_search_map_main_element : $(address_search_map_main_element)) : $(document);
	var map_class = typeof address_search_map_class != "undefined" && address_search_map_class ? "." + address_search_map_class : "";
	
	var mapOptions, map, marker, searchBox,
		infoWindow = '',
		element = main_elm.find( map_class + ' .map_canvas' ),
		searchEl = main_elm.find( map_class + ' .map_search'),
		addressEl = main_elm.find( '.address input' ),
		fullAddressEl = main_elm.find( '.full_address input' ),
		latEl = main_elm.find( '.latitude input' ),
		longEl = main_elm.find( '.longitude input' ),
		localityEl = main_elm.find( '.locality input' ),
		countryEl = main_elm.find( '.country select' ),
		postalCodeEl = main_elm.find( '.zip_id input' ),
		addressOnUpdate = addressEl.attr('onMapSearchUpdate');
		fullAddressOnUpdate = fullAddressEl.attr('onMapSearchUpdate');
		latOnUpdate = latEl.attr('onMapSearchUpdate');
		longOnUpdate = longEl.attr('onMapSearchUpdate');
		localityOnUpdate = localityEl.attr('onMapSearchUpdate');
		countryOnUpdate = countryEl.attr('onMapSearchUpdate');
		postalCodeOnUpdate = postalCodeEl.attr('onMapSearchUpdate');
	
	if (element[0]) {
	    mapOptions = {
    		// How far the maps zooms in.
    		zoom: 16,
    		// Current Lat and Long position of the pin/
    		center: new google.maps.LatLng(38.707532, -9.13644899999997),
    		// center : {
    		// 	lat: 38.7636,
    		// 	lng: -9.1997
    		// },
    		disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
    		scrollWheel: true, // If set to false disables the scrolling on the map.
    		draggable: true, // If set to false , you cannot move the map around.
    		// mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
    		// maxZoom: 11, // Wont allow you to zoom more than this
    		// minZoom: 9  // Wont allow you to go more up.
    
    	};
    	
        /**
    	 * Creates the map using google function google.maps.Map() by passing the id of canvas and
    	 * mapOptions object that we just created above as its parameters.
    	 *
    	 */
    	// Create an object map with the constructor function Map()
    	map = new google.maps.Map( element[0], mapOptions ); // Till this like of code it loads up the map.
    
    	/**
    	 * Creates the marker on the map
    	 *
    	 */
    	marker = new google.maps.Marker({
    		position: mapOptions.center,
    		map: map,
    		// icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
    		draggable: true
    	});
        
        if (searchEl[0]) {
            searchEl.keypress(
                function(event){
                     if (event.which == '13') //disable enter key on search
                        event.preventDefault();
            });
            
        	/**
        	 * Creates a search box
        	 */
        	searchBox = new google.maps.places.SearchBox( searchEl[0] );
        
        	/**
        	 * When the place is changed on search box, it takes the marker to the searched location.
        	 */
        	google.maps.event.addListener( searchBox, 'places_changed', function () {
        		var places = searchBox.getPlaces(),
        			bounds = new google.maps.LatLngBounds(),
        			i, place, lat, long, resultArray;
        			
        		//console.log(places);
        		if (places) {
        			var address = places[0].formatted_address;
        
        			for( i = 0; (place = places[i]); i++ ) {
        				bounds.extend( place.geometry.location );
        				marker.setPosition( place.geometry.location );  // Set marker position new.
        			}
        
        			map.fitBounds( bounds );  // Fit to the bound
        			map.setZoom( 15 ); // This function sets the zoom to 15, meaning zooms to level 15.
        			// console.log( map.getZoom() );
        			
        			lat = marker.getPosition().lat();
        			long = marker.getPosition().lng();
        			setTextFieldValue(latEl, ("" + lat).substr(0, 19), latOnUpdate); //19 because of the "-" in the begginning
        			setTextFieldValue(longEl, ("" + long).substr(0, 19), longOnUpdate); //19 because of the "-" in the begginning
        
        			resultArray =  places[0].address_components;
        			
				var local = "";
				var countr = "";
				var postal_cod = "";
				var address_simple = address;
        
        			// Get the city and set the city input value to the one selected
        			if (resultArray) {
        				
        				for( i = 0; i < resultArray.length; i++ ) {
        					if ( resultArray[ i ].types[0] ) {
        						switch (resultArray[ i ].types[0]) {
        							case "locality":
        							case "administrative_area_level_2":
        							case "administrative_area_level_1":
        								if (!local) {
        									local = resultArray[ i ].long_name;
        									address_simple = address_simple.replace(local, "");
        									setTextFieldValue(localityEl, local, localityOnUpdate);
        								}
        								break;
        							case "country":
        								if (!countr) {
        									countr = ("" + resultArray[ i ].long_name);
        									address_simple = address_simple.replace(countr, "");
    										
    										setTextFieldValue(countryEl, "", countryOnUpdate);
    										
    										if (countr && countryEl[0])
        										countryEl.find('option').filter(function() {
											   return $(this).text().toLowerCase() == countr.toLowerCase();
											})[0].selected = true;
        								}
        								break;
        							case "postal_code":
        							case "postal_code_prefix":
        								if (!postal_cod) {
        									postal_cod = resultArray[ i ].long_name;
        									address_simple = address_simple.replace(postal_cod, "");
        									setTextFieldValue(postalCodeEl, postal_cod, postalCodeOnUpdate);
        								}
        								break;
        						}
        					}
        				}
        			}
        			
				address_simple = address_simple.replace(/ ,/g, "").replace(/^\s+|\s+$/gm,'');
    				address_simple = address_simple.substr(address_simple.length - 1, 1) == "," ? address_simple.substr(0, address_simple.length - 1) : address_simple;
    				
    				setTextFieldValue(addressEl, address_simple, addressOnUpdate);
    				setTextFieldValue(fullAddressEl, address, fullAddressOnUpdate);
        			
        			// Closes the previous info window if it already exists
        			if ( infoWindow )
        				infoWindow.close();
        			/**
        			 * Creates the info Window at the top of the marker
        			 */
        			infoWindow = new google.maps.InfoWindow({
        				content: address
        			});
        
        			infoWindow.open( map, marker );
        		}
        	} );
        }
    
    	/**
    	 * Finds the new position of the marker when the marker is dragged.
    	 */
    	google.maps.event.addListener( marker, "dragend", function ( event ) {
    		var lat, long, address, resultArray;
    
    		//console.log( 'i am dragged' );
    		lat = marker.getPosition().lat();
    		long = marker.getPosition().lng();
    
    		var geocoder = new google.maps.Geocoder();
    		geocoder.geocode( { latLng: marker.getPosition() }, function ( result, status ) {
    			if ( 'OK' === status && result[0] ) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
    				address = result[0].formatted_address;
    				resultArray =  result[0].address_components;
				
				var local = "";
				var countr = "";
				var postal_cod = "";
    				var address_simple = address;
    				
    				if (resultArray) {
    				
    					// Get the city and set the city input value to the one selected
    					for( var i = 0; i < resultArray.length; i++ ) {
    						if ( resultArray[ i ].types[0] ) {
    							switch (resultArray[ i ].types[0]) {
    								case "locality":
    								case "administrative_area_level_2":
    								case "administrative_area_level_1":
    									if (!local) {
    										local = resultArray[ i ].long_name;
    										address_simple = address_simple.replace(local, "");
    										setTextFieldValue(localityEl, local, localityOnUpdate);
    									}
    									break;
    								case "country":
    									if (!countr) {
    										countr = ("" + resultArray[ i ].long_name);
    										address_simple = address_simple.replace(countr, "");
    										
								                setTextFieldValue(countryEl, "", countryOnUpdate);
								                
    										if (countr && countryEl[0])
        										countryEl.find('option').filter(function() {
										           return $(this).text().toLowerCase() == countr.toLowerCase();
										        })[0].selected = true;
    									}
    									break;
    								case "postal_code":
    								case "postal_code_prefix":
    									if (!postal_cod) {
    										postal_cod = resultArray[ i ].long_name;
    										address_simple = address_simple.replace(postal_cod, "");
    										setTextFieldValue(postalCodeEl, postal_cod, postalCodeOnUpdate);
    									}
    									break;
    							}
    						}
    					}
    				}
    				
    				address_simple = address_simple.replace(/ ,/g, "").replace(/^\s+|\s+$/gm,'');
    				address_simple = address_simple.substr(address_simple.length - 1, 1) == "," ? address_simple.substr(0, address_simple.length - 1) : address_simple;
    				
    				setTextFieldValue(addressEl, address_simple, addressOnUpdate);
    				setTextFieldValue(fullAddressEl, address, fullAddressOnUpdate);
    				setTextFieldValue(latEl, ("" + lat).substr(0, 19), latOnUpdate); //19 because of the "-" in the begginning
    				setTextFieldValue(longEl, ("" + long).substr(0, 19), longOnUpdate); //19 because of the "-" in the begginning
    
    			} 
    			else if (console && console.log)
    				console.log( 'Geocode was not successful for the following reason: ' + status );
    			
    			// Closes the previous info window if it already exists
    			if ( infoWindow ) 
    				infoWindow.close();
    
    			/**
    			 * Creates the info Window at the top of the marker
    			 */
    			infoWindow = new google.maps.InfoWindow({
    				content: address
    			});
    
    			infoWindow.open( map, marker );
    		} );
    	});
    	
    	//Set current latitude and longitude
    	var current_latitude = latEl.val();
    	var current_longitude = longEl.val();
    	
    	if (current_latitude || current_longitude) {
    	    map.setCenter( new google.maps.LatLng(current_latitude, current_longitude) );
            marker.setPosition( new google.maps.LatLng(current_latitude, current_longitude) );
    	}
    	else
    	    getCurrentLocation(function (position) {
    	        current_latitude = position.coords.latitude;
        	    current_longitude = position.coords.longitude;
                
                if (current_latitude || current_longitude) {
                    map.setCenter( new google.maps.LatLng(current_latitude, current_longitude) );
                    marker.setPosition( new google.maps.LatLng(current_latitude, current_longitude) );
                }
            }, function (error) {
                var msg = error.message;
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        msg = "User denied the request for Geolocation."
                        break;
                    case error.POSITION_UNAVAILABLE:
                        msg = "Location information is unavailable."
                        break;
                    case error.TIMEOUT:
                        msg = "The request to get user location timed out."
                        break;
                    case error.UNKNOWN_ERROR:
                        msg = "An unknown error occurred."
                        break;
                }
                
                if (msg && console && console.log) 
                    console.log(msg);
            });
	}
	else if (console && console.log)
	    	console.log( 'Map Element does not exists' );
	
	
	function setTextFieldValue(field, value, func) {
		field.val(value);
		
		if (typeof func == "function")
			func(field);
		else if (func && eval('typeof ' + func + ' == "function"')) //in case func is a string passed through an attribute
			eval(func + '(field);');
	}
}

function getCurrentLocation(show_position_func) {
    if (navigator.geolocation)
        navigator.geolocation.getCurrentPosition(show_position_func);
    else if (console && console.log)
        console.log("Geolocation is not supported by this browser.");
}
