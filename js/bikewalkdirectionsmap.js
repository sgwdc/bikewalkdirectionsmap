// Wait to initialize Google Maps until after the page has loaded (for Internet Explorer)
//google.maps.event.addDomListener(window, 'load', initialize);

// Define variables here so they'll be globally available to all functions
var root = this;
var initialLocation;
var currentLocationDisplay;
var geocoder;
var map;
var marker;
var directionsDisplay;
var directionsService;
var markerArray = [];	
/* Not currently implemented
var stepDisplayInfoWindow;
*/
var fromAddressText;
// be sure to add new layers here:
var bikeLayer;
var trafficLayer;
var searchedAddressInfoWindow;
var routesObject;

// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

// Run once jQuery has finished loading
jQuery(document).ready(function() {
	// Define event handler for Google BICYCLING Layer checkbox
	jQuery('input#bikeLayer:checkbox').on('change', function(event) {
		if (jQuery(this).is(':checked')) {
			bikeLayer.setMap(map);
			transitLayer.setMap(null);
			trafficLayer.setMap(null);
			jQuery(jQuery('input#transitLayer:checkbox')).prop('checked', false);
			jQuery(jQuery('input#trafficLayer:checkbox')).prop('checked', false);
		} else{
			bikeLayer.setMap(null);
		}
	});

	// Define event handler for Google TRANSIT Layer checkbox
	jQuery('input#transitLayer:checkbox').on('change', function(event) {
		if (jQuery(this).is(':checked')) {
			transitLayer.setMap(map);
			bikeLayer.setMap(null);
			trafficLayer.setMap(null);
			jQuery(jQuery('input#bikeLayer:checkbox')).prop('checked', false);
			jQuery(jQuery('input#trafficLayer:checkbox')).prop('checked', false);
		} else{
			transitLayer.setMap(null);
		}
	});

	// Define event handler for Google TRAFFIC Layer checkbox
	jQuery('input#trafficLayer:checkbox').on('change', function(event) {
		if (jQuery(this).is(':checked')) {
			trafficLayer.setMap(map);
			bikeLayer.setMap(null);
			transitLayer.setMap(null);
			jQuery(jQuery('input#bikeLayer:checkbox')).prop('checked', false);
			jQuery(jQuery('input#transitLayer:checkbox')).prop('checked', false);
		} else{
			trafficLayer.setMap(null);
		}
	});

	// Define event handler for the zoom level links in the menu
	jQuery('div#leftmenu .zoomLevels').on('click', function(event) {
		var newLevel = $(this).attr('id');
		if (newLevel == 'locationLevel1') {
			// District
			var locationLevel1 = new google.maps.LatLng(38.905,-77.019);
			map.setZoom(14);
		} else if (newLevel == 'locationLevel2') {
			// DC Area
			var locationLevel2 = new google.maps.LatLng(38.926,-77.062);
			map.setZoom(11);		
		} else if (newLevel == 'locationLevel3') {
			// Mid-Atlantic
			var locationLevel3 = new google.maps.LatLng(39.035,-77.259);
			map.setZoom(8);		
		}
		map.setCenter(eval(newLevel));
	});

	// Define event handler for the "Hide Menu" button
	jQuery('input#HideMenuID').on('click', function(event) {
		hideMenu(true);
	});

	// Define event handler for the "Show Menu" button
	jQuery('input#ShowMenuID').on('click', function(event) {
		hideMenu(false);
	});

	// Define event handler for the "Find address" button
	jQuery('input#myHtmlInputButton').on('click', function(event) {
		geocodeAddress();
	});

	// Define event handler for future instances of the "Show directions" button
	jQuery(document).on('click', 'input#get-directions', function(event) {
		findDirectionsPressed();
	});

	// Define event handler for detecting "ENTER" key press in any destination input fields
	jQuery('input.destination-field').keypress(function(event) {
		if ((event.which && event.which == 13) || 
			(event.keyCode && event.keyCode == 13))
			{
				jQuery("input#myHtmlInputButton").click();
		} 
	});

	// Define event handler for future instances of the transport method buttons
	jQuery('img.transport-mode').on('click', function(event) {
		setTransportModeIcon(event.target.id);
		getDirections(event.target.id);
	});

	// Define event handler for future instances of the transport method buttons
	jQuery(document).on('click', 'img#cancelButton', function(event) {
		jQuery("div#directions_panel").hide();
		jQuery("div#leftmenu").show();
		directionsDisplay.setMap(null);
		// Remove any existing markers
		removeAllMarkers();
	});

});

// This is called by the jQuery function above
function initialize() {
	searchedAddressInfoWindow = new google.maps.InfoWindow({
		content: "",
		size: new google.maps.Size(50,50),
		maxWidth: 500
	});
	/* Not currently implemented
	stepDisplayInfoWindow = new google.maps.InfoWindow({
		content: "",
		size: new google.maps.Size(50,50),
		maxWidth: 500
	});
	*/

	// Reset the form
	jQuery(jQuery('input#bikeLayer:checkbox')).prop('checked', true);
	
	directionsService = new google.maps.DirectionsService();

	directionsDisplay = new google.maps.DirectionsRenderer();

	// Define event handler for when the user selects a different route choice
	google.maps.event.addListener(directionsDisplay, 'routeindex_changed', function() {
		// Get the index of the newly selected route
  		var routeIndex = directionsDisplay.getRouteIndex();
  		// Ignore the initial events when the routes are first received
  		if (typeof routesObject != "undefined") {
			// Add markers for each step of the newly selected route
  			showSteps(routesObject[routeIndex])
  		}
	});
	
	directionsDisplay.setPanel(jQuery("div#directions_panel")[0]);
	
	// Centered on Petworth
	var initialLocation = new google.maps.LatLng(38.939,-77.023);
  
 	var myOptions = {
		zoom: 12,
		center: initialLocation,
	//		mapTypeId: google.maps.MapTypeId.TERRAIN
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		// Display the MAP CONTROL (
		mapTypeControl: true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		// Display the NAVIGATION CONTROLS (Map/Satellite/Hybrid/Terrain)
		navigationControl: true,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.ZOOM_PAN,
			position: google.maps.ControlPosition.LEFT
		},
		// Display the SCALE
		scaleControl: true,
		scaleControlOptions: {
			position: google.maps.ControlPosition.BOTTOM_LEFT
		}
	};

	// Create a Google Map in the "map_viewer" DIV tag
  	map = new google.maps.Map(jQuery("div#map_canvas")[0], myOptions);
	
	marker = new google.maps.Marker({
		map: map,
		draggable: false
		// There's no title to display until the user selects an address
		// title: "Steve Test"
	});

	// Load and display the Google Maps BICYCLING Layer
	bikeLayer = new google.maps.BicyclingLayer();
	bikeLayer.setMap(map);

	// Load (but don't display) the Google Maps TRAFFIC Layer
	trafficLayer = new google.maps.TrafficLayer();

	// Load (but don't display) the Google Maps TRANSIT Layer
	transitLayer = new google.maps.TransitLayer();

	//GEOCODER
	geocoder = new google.maps.Geocoder();

	/* Don't use autocomplete for now because it creates the impression no other addresses are valid, and it covers up the other input fields -SGW
	// Create handlers for address autocomplete, map clicks, etc.
	$(function() {
		$("#address").autocomplete({
								   
		  //This bit uses the geocoder to fetch address values
		  source: function(request, response) {
//			geocoder.geocode( {'address': request.term }, function(results, status) {
																   
		  var southWest = new google.maps.LatLng(35.74,-79.20);
		  var northEast = new google.maps.LatLng(36.10,-78.43);
		  var bounds = new google.maps.LatLngBounds(southWest,northEast);
																   
			geocoder.geocode( {'address': request.term, 'bounds' : bounds }, function(results, status) {
			  response($.map(results, function(item) {
				return {
				  label:  item.formatted_address,
				  value: item.formatted_address,
				  latitude: item.geometry.location.lat(),
				  longitude: item.geometry.location.lng()
				}
			  }));
			})
		  },
		  //This bit is executed upon selection of an address
		  select: function(event, ui) {
			$("#latitude").val(ui.item.latitude);
			$("#longitude").val(ui.item.longitude);
			var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
			marker.setPosition(location);
			map.setCenter(location);
			map.setZoom(16);
			
			// Store the selected address so we can replace it later
//			currentLocationDisplay = ui.item.label;
//			marker.setTitle(ui.item.label);
			marker.setTitle("Click for directions");
			
//			directionsDisplay.setMap(null);
			clearDirections();

			//searchedAddressInfoWindow.setContent("<span class='smallarial'><strong>Address:</strong><br>" + ui.item.label + "<br><br><a href='javascript:alert(\"Yo\");'>Get directions</a></span>");
		searchedAddressInfoWindow.setContent('<span class="smallarial">' +
			'<form action="#" onsubmit="findDirectionsPressed(\'' + ui.item.label + '\', \'' + this + '\'); return false;">'+
//			'<form action="#" onsubmit="return false;">'+
			
			'<strong>Get walk/bike/drive directions to:</strong><br>'+
			ui.item.label + "<br><br>" +

			'<strong>Leaving from:</strong><br>' +
			'<input type="text" id="startaddress" value="" style="width:200px"><br><br>' +

			'<input type="submit" value="Get directions">' +

//			'<a href="javascript:clearDirections()"><img src="images/cancel.png" alt="Cancel directions" name="cancelIcon" width="39" height="25" border="0" id="cancelIcon" /></a>' +
			'</form>');
				// Go ahead and display the InfoWindow for the marker
				searchedAddressInfoWindow.open(map, marker);
		  }
		});
	  });
	*/  

	  //Add listener to the map for reverse geocoding
	  google.maps.event.addListener(map, 'click', function(event) {
	  	// Only geocode if the user isn't already looking at directions
	  	if (jQuery('div#directions_panel').css('display') == "none") {
		  	geocodeLatLng(event);
	  	}
	  });

	  //Add listener to marker for reopening the InfoWindow to see the address and get directions
	  google.maps.event.addListener(marker, 'click', function(event) {
			searchedAddressInfoWindow.open(map,marker);
		});
	}
	// END function initialize()

function findDirectionsPressed() {
	// Make sure this variable is available to getDirections()
	root.fromAddressText = jQuery("input#fromaddress").val() + ", " + jQuery("input#fromcity").val() + ", " + jQuery("input#fromstate").val();
	searchedAddressInfoWindow.close();
	// Start with bike directions
	getDirections("bike");
}
	
function getDirections(tripMethod) {
	// Calls function above to highlight the right transport mode
	setTransportModeIcon(tripMethod);

	if (tripMethod == "walk") {
		travelMode = google.maps.DirectionsTravelMode.WALKING;
	} else if (tripMethod == "bike") {
		travelMode = google.maps.DirectionsTravelMode.BICYCLING;
	} else if (tripMethod == "drive") {
		travelMode = google.maps.DirectionsTravelMode.DRIVING;
	} else if (tripMethod == "transit") {
		travelMode = google.maps.DirectionsTravelMode.TRANSIT;
	}

	var request = {
		origin: root.fromAddressText, 
		destination: root.toAddressText,
		travelMode: travelMode,
		provideRouteAlternatives: true
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			jQuery("div#directions_panel").show();
			jQuery("div#leftmenu").hide();
			
			// Attach directions to the map now that we're actually going to use it
			directionsDisplay.setMap(null);
			directionsDisplay.setMap(map);
	
			directionsDisplay.setDirections(result);

			// Save all routes as a global variable so we can udpate the markers if the user selects a different route choice
			root.routesObject = result.routes;

			// Add markers for each step of the first route
			showSteps(result.routes[0]);
		} else {
			alert("There was an error: " + status);
		}
	});
}

// Remove any existing markers
function removeAllMarkers() {
	for (i = 0; i < markerArray.length; i++) {
		markerArray[i].setMap(null);
	}		
}

// Called by initialize() and transport mode button handler
function setTransportModeIcon(tripMethod) {
	// Iterate through each transport mode icon
	jQuery.each(jQuery('img.transport-mode'), function() {
		var thisMethod = jQuery(this)[0].id;
		// If this is the new mode, switch to the "on" icon
		if (thisMethod == tripMethod) {
			jQuery(this).attr('src', 'images/' + thisMethod + '_on.png');
		// Otherwise, switch to the "off" icon
		} else {
			jQuery(this).attr('src', 'images/' + thisMethod + '_off.png');
		}
	})
}

// Called by directionsService.route(), and by the "routeindex_changed" event handler
// For each step, place a marker, and add the text to the marker's InfoWindow. Also attach the marker to an array so we can keep track of it and remove it when calculating new routes
function showSteps(selectedRoute) {
  // Get the steps for the first leg of this trip (It is safe to assume there is only one)
  var steps = selectedRoute.legs[0].steps;

  // Remove any existing markers
  removeAllMarkers();

  // Add markers for the selected route
  for (var i = 0; i < steps.length; i++) {
	  var marker = new google.maps.Marker({
		position: steps[i].start_point, 
		map: map
	  });
	  /* Not currently implemented
	  attachInstructionText(marker, steps[i].instructions);
	  */
	  markerArray[i] = marker;
  }
}

/* Not currently implemented: To show a step's instructions when the user clicks a marker, we'll need to close any InfoWindows opened when the user clicked on an instruction in the directions
// Event listener for when the user clicks on a marker
function attachInstructionText(marker, text) {
  google.maps.event.addListener(marker, 'click', function() {
  	// Display the instructions for that step
	stepDisplayInfoWindow.setContent(text);
	stepDisplayInfoWindow.open(map, marker);
  });
}
*/

// Called when the user enters an address
function geocodeAddress() {
	// Concatenate the address fields into one string
	var addressToSearchFor = jQuery("input#address").val() + ", " + jQuery("input#city").val() + ", " + jQuery("input#state").val();

	// Send the user-entered address to the Google geocoder
	geocoder.geocode( { 'address': addressToSearchFor}, function(results, status) {
		// Pass the results to the callback function (shared with geocoding by map click)
		geocodeCallback(results, status);
	});
}

// Called when the user clicks on the map
function geocodeLatLng(event) {
  	// Send the latitude & longitude of the user click to the Google geocoder
	geocoder.geocode({'latLng': event.latLng}, function(results, status) {
		// Pass the results to the callback function (shared with geocoding by user-entered address)
		geocodeCallback(results, status);
	});
}

// Called by geocodeAddress() and the event listener for user clicks on the mouse
function geocodeCallback(results, status) {
	if (status == google.maps.GeocoderStatus.OK) {
		var firstAddress = results[0];
		// Center the map on the geocoded address
		map.setCenter(firstAddress.geometry.location);
		// Zoom in by two levels dynamically
		map.setZoom(map.getZoom() + 2);
		// Add a marker to the geocoded location
		marker.setMap(map);
		marker.setPosition(firstAddress.geometry.location);
		// Set the marker's rollover text
		marker.setTitle("Click for bike/walk/transit/driving directions");

		// Split the address string into separate strings
		addressPieces = firstAddress.formatted_address.split(', ');
		// Use the user input fields for the destination from the geocoding to populate the starting address input fields below:
		addressEntered = addressPieces[0];
		cityEntered = addressPieces[1];
		stateEntered = addressPieces[2];

		root.toAddressText = firstAddress.formatted_address;

		// Populate the InfoWindow content
		searchedAddressInfoWindow.setContent('<span class="smallarial">' +
			'<strong>Get walking, bicycling and driving trip routing directions to:</strong><br>'+
			// NOTE: Do not include the addressEntered field because no one would be traveling to their origin
			firstAddress.formatted_address + "<br><br>" +
			'<strong>Enter your starting address:</strong><br>' +
			'<input type="text" id="fromaddress" value="" style="width:300px; font-size:10px"><br>' +
			'<strong>City:</strong> <input id="fromcity"  type="text" value="' + cityEntered + '" style="width:168px; font-size:10px" />' +
			'&nbsp;&nbsp;<strong>State:</strong> <input id="fromstate" type="text" value="' + stateEntered + '" style="width:55px; font-size:10px" />' +
			'<br><input id="get-directions" type="submit" value="Show bicycling & walking directions">');

			// Go ahead and display the InfoWindow for the marker
			searchedAddressInfoWindow.open(map, marker);
			// Update the destination address (main search box)
			jQuery('input#address').val(addressEntered);
			jQuery('input#city').val(cityEntered);
			jQuery('input#state').val(stateEntered);
	} else {
		alert("ERROR: Geocoding failed for the following reason: " + status);
 	}
}

function hideMenu(changeToHidden) {
	if (changeToHidden) {
		jQuery("div#leftmenu").hide();
		jQuery("div#leftmenu_hidden").show();
		
	} else {
		jQuery("div#leftmenu_hidden").hide();
		jQuery("div#leftmenu").show();
	}
}
