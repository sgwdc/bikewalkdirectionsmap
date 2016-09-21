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
var stepDisplay;
var fromAddressText;
// be sure to add new layers here:
var bikeLayer;
var searchedAddressInfoWindow;

// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

// This is called by the jQuery function above
function initialize() {
	searchedAddressInfoWindow = new google.maps.InfoWindow({
		content: "",
		size: new google.maps.Size(50,50),
		maxWidth: 500
	});

	// Reset the form
	document.forms[0].bikeLayer.checked = true;
	
	directionsService = new google.maps.DirectionsService();

	directionsDisplay = new google.maps.DirectionsRenderer();
	
	directionsDisplay.setPanel(document.getElementById("directions_panel"));
	
//	  var initialLocation = new google.maps.LatLng(35.80, -78.66);
	// Changed this to match where the "Raleigh" link moves to:
	var initialLocation = new google.maps.LatLng(35.83,-78.644); // Same as "Raleigh" link
  
 	var myOptions = {
		  // Doesn't really matter what zoom level we use b/c it's going to zoom to the layers as they are added
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
  	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	// Instead of doing this here, add it only when we need it
//		directionsDisplay.setMap(map);
	
	marker = new google.maps.Marker({
		map: map,
		draggable: false
		// There's no title to display until the user selects an address
		// title: "Steve Test"
	});

	// Display the Google Maps Bike Layer
	bikeLayer = new google.maps.BicyclingLayer();
	bikeLayer.setMap(map);

	// Display the Google Maps TRAFFIC Layer
	trafficLayer = new google.maps.TrafficLayer();
	trafficLayer.setMap(map);

	//GEOCODER
	geocoder = new google.maps.Geocoder();

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

			//console.log("Trying to display the address-- did it work?");
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

	/* ALSO DISABLED FOR NOW:
	  //Add listener to marker for reverse geocoding
	  google.maps.event.addListener(marker, 'dragend', function() {
//		alert("marker.getPosition()= " + marker.getPosition());
		geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			if (results[0]) {
			  $('#address').val(results[0].formatted_address);
			  $('#latitude').val(marker.getPosition().lat());
			  $('#longitude').val(marker.getPosition().lng());
			  
				// Store the selected address so we can replace it later
				currentLocationDisplay = results[0].formatted_address;
//				alert("checkpoint1a: " + marker.title);
//				marker.title = results[0].formatted_address;
//				alert("checkpoint1b: " + marker.title);
				marker.setTitle(results[0].formatted_address);

			  
			}
		  }
		});
	  });
	*/  

	  // NOTE THAT THIS IS GEOCODING EVERY TIME THE USER CLICKS, INCLUDING WHEN ZOOMING IN:
	  //Add listener to the map for reverse geocoding
	  google.maps.event.addListener(map, 'click', function(event) {
		//alert("event.latLng= " + event.latLng);						   
		geocoder.geocode({'latLng': event.latLng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
					$('#address').val(results[0].formatted_address);
					$('#latitude').val(event.latLng.lat());
					$('#longitude').val(event.latLng.lng());
					marker.setPosition(event.latLng);

					/* This is duplicative, but here as an experiment -SGW */
					searchedAddressInfoWindow.setContent('<span class="smallarial">' +
						'<form action="#" onsubmit="findDirectionsPressed(\'' + results[0].formatted_address + '\', \'' + this + '\'); return false;">'+
			//			'<form action="#" onsubmit="return false;">'+
						
						'<strong>Get walk/bike/drive directions to:</strong><br>'+
			//			addressToSearchFor + "<br><br>" +
						results[0].formatted_address + "<br><br>" +
						'<strong>Enter starting address:</strong><br>' +
						'<input type="text" id="fromaddress" value="" style="width:200px; font-size:10px"><br>' +
						'<strong>City:</strong> <input id="fromcity"  type="text" value="Raleigh" style="width:105px; font-size:10px" />' +
						'&nbsp;&nbsp;<strong>State:</strong> <input id="fromstate" type="text" value="NC" style="width:25px; font-size:10px" />' +
						//                <input type="button" value="Find" onClick="codeAddress()">

						'<br><input type="submit" value="Get directions">' +

			//			'<a href="javascript:clearDirections()"><img src="images/cancel.png" alt="Cancel directions" name="cancelIcon" width="39" height="25" border="0" id="cancelIcon" /></a>' +
						'</form>');
					
						// Go ahead and display the InfoWindow for the marker
						searchedAddressInfoWindow.open(map, marker);
				}
		 	}
		}); // END geocoder.geocode()
	  });


	  //Add listener to marker for reopening the InfoWindow to see the address and get directions
	  google.maps.event.addListener(marker, 'click', function(event) {
//		   	alert("clicked")
			searchedAddressInfoWindow.open(map,marker);
		});

	  /* This probably isn't possible when reverse geocoding is enabled:
	  //Add listener to close the InfoWindow when the user clicks on the map
	  google.maps.event.addListener(map, 'click', function(event) {
//		   	alert("clicked")
			searchedAddressInfoWindow.close();
			document.getElementById('address').blur();
			document.getElementById('city').blur();
			document.getElementById('state').blur();
		});
		*/
	}
	// END function initialize()

function toggleBikeLayer() {
	if (document.forms[0].bikeLayer.checked) {
		bikeLayer.setMap(map);
			trafficLayer.setMap(null);
			document.forms[0].trafficLayer.checked = false;
	} else{
		bikeLayer.setMap(null);
	}
}

function toggleTrafficLayer() {
	if (document.forms[0].trafficLayer.checked) {
		trafficLayer.setMap(map);
		bikeLayer.setMap(null);
		document.forms[0].bikeLayer.checked = false;
	} else{
		trafficLayer.setMap(null);
	}
}

function zoomToLevel(newLevel) {
	if (newLevel == "triangle") {
		// var downtownLocation = new google.maps.LatLng(35.875698,-78.833084);
		var triangleLocation = new google.maps.LatLng(35.87,-78.77);
		map.setCenter(triangleLocation);		
		map.setZoom(11);		
	} else if (newLevel == "raleigh") {
		// var downtownLocation = new google.maps.LatLng(35.83034,-78.665199);
		var raleighLocation = new google.maps.LatLng(35.83,-78.644);
		map.setCenter(raleighLocation);		
		map.setZoom(12);		
	} else if (newLevel == "downtownraleigh") {
		// var downtownLocation = new google.maps.LatLng(35.779177,-78.643398);
		var downtownRaleighLocation = new google.maps.LatLng(35.78,-78.644);
		map.setCenter(downtownRaleighLocation);		
		map.setZoom(15);
	}
}

/* OBSOLETED AND INTEGRATED INTO WHAT WAS FORMERLY getDirections2() -Un-obsoleted -SGW */
// When "Get directions" is clicked in the InfoWindow for an address that was searched for
function getDirections(fromAddressText, tripMethod) {
	alert("getDirections()");
//		searchedAddressInfoWindow.setContent("<span class='smallarial'><strong>Address:</strong><br>" + ui.item.label + "<br><br><a href='javascript:getDirections()'>Get directions</a></span>");
	searchedAddressInfoWindow.setContent('<span class="smallarial"><strong>Directions from:</strong><br>' +
		'<form action="#" onsubmit="getDirections2(\'' + fromAddressText + '\', \'' + tripMethod + '\'); return false;">'+
		'<input type="text" id="endaddress" value=""><br><br><strong>To:</strong><br>'+
		fromAddressText + "<br><br>" +
		'<input type="submit" value="Find directions"></form>');
	
}

function findDirectionsPressed(toAddressText) {
	// make sure this variable is available to getDirections()
//		root.fromAddressText = document.getElementById("startaddress").value;
	root.fromAddressText = document.getElementById("fromaddress").value + ", " + document.getElementById("fromcity").value + ", " + document.getElementById("fromstate").value;
	root.toAddressText = toAddressText;
	searchedAddressInfoWindow.close();
	// ONly handle bike directions for now
	getDirections("bike");
//		getDirections("drive");
}
// ******************************************************************************8
// ******************************************************************************8
// ******************************************************************************8
// * TO DO: SEPARATE getDirections() into two functions so it can be used only
// * to specify an address, or only to change the trip method
// ******************************************************************************8
// ******************************************************************************8
// ******************************************************************************8
	
// Formerly known as getDirections2()
function getDirections(tripMethod) {
	// First, clear out any existing markerArray
	// from previous calculations.
	for (i = 0; i < markerArray.length; i++) {
		markerArray[i].setMap(null);
	}		

	if (tripMethod == "walk") {
		travelMode = google.maps.DirectionsTravelMode.WALKING;
	} else if (tripMethod == "bike") {
		travelMode = google.maps.DirectionsTravelMode.BICYCLING;
	} else if (tripMethod == "drive") {
		travelMode = google.maps.DirectionsTravelMode.DRIVING;
	} else {
		alert("WARNING: tripMethod was not passed to getDirections()"); 
//			travelMode = google.maps.DirectionsTravelMode.DRIVING;
	}
	
	// Calls function above to highlight the right transport mode
	setTransportModeIcon(tripMethod);
	
	
//		var start = document.getElementById("start").value;
//		var end = document.getElementById("end").value;
	var request = {
//			origin: fromAddressText, 
//			destination: toAddressText,
		origin: root.fromAddressText, 
		destination: root.toAddressText,
//			travelMode: google.maps.DirectionsTravelMode.DRIVING,
//			travelMode: google.maps.DirectionsTravelMode.BICYCLING,
//			travelMode: google.maps.DirectionsTravelMode.WALKING,
		travelMode: travelMode,
		provideRouteAlternatives: true
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			
			
	//		directions.visibility = "visible";
	//		document.getElementById("directions").style.visibility = "visible";
			document.getElementById("directions_panel").style.visibility = "visible";
			document.getElementById("leftmenu").style.visibility = "hidden";
			
			// Attach directions to the map now that we're actually going to use it
			directionsDisplay.setMap(null);
			directionsDisplay.setMap(map);
	
			directionsDisplay.setDirections(result);

			// Add markers for each step of the route
			showSteps(result);
			
		} else {
			alert("There was an error: " + status);
		}
		
		/*
The DirectionsStatus may return the following values:

* OK indicates the response contains a valid DirectionsResult.
* NOT_FOUND indicates at least one of the locations specified in the requests's origin, destination, or waypoints could not be geocoded.
* ZERO_RESULTS indicates no route could be found between the origin and destination.
* MAX_WAYPOINTS_EXCEEDED indicates that too many DirectionsWaypoints were provided in the DirectionsRequest. The maximum allowed waypoints is 8, plus the origin, and destination. Maps API Premier customers are allowed 23 waypoints, plus the origin, and destination.
* INVALID_REQUEST indicates that the provided DirectionsRequest was invalid.
* OVER_QUERY_LIMIT indicates the webpage has sent too many requests within the allowed time period.
* REQUEST_DENIED indicates the webpage is not allowed to use the directions service.
* UNKNOWN_ERROR indicates a directions request could not be processed due to a server error. The request may succeed if you try again.
		*/
	});
}

function setTransportModeIcon(transportMode) {
	if (transportMode == "walk") {
		MM_swapImage('pedIcon','','images/ped_on.png',0);
	} else {
		MM_swapImage('pedIcon','','images/ped_off.png',0);
	}
	
	if (transportMode == "bike") {
		MM_swapImage('bikeIcon','','images/bike_on.png',0);
	} else {
		MM_swapImage('bikeIcon','','images/bike_off.png',0);
	}
	
	if (transportMode == "drive") {
		MM_swapImage('carIcon','','images/car_on.png',0);
	} else {
		MM_swapImage('carIcon','','images/car_off.png',0);
	}
}

// COPY MOST OF THIS TO getDirections2() above after it works
function steve(directionsType) {
	document.getElementById("directions_panel").style.visibility = "visible";
	document.getElementById("leftmenu").style.visibility = "hidden";

	// First, clear out any existing markerArray
	// from previous calculations.
	for (i = 0; i < markerArray.length; i++) {
		markerArray[i].setMap(null);
	}		

	MM_swapImage('carIcon','','images/car_off.png',0);
	MM_swapImage('bikeIcon','','images/car_off.png',0);
	
	// Calls function above to highlight the right transport mode
	setTransportModeIcon(directionsType);

	if (directionsType == "walk") {
//			travelMode: google.maps.DirectionsTravelMode.BICYCLING,
		var travelModeToUse = google.maps.DirectionsTravelMode.WALKING;
//			MM_swapImage('pedIcon','','images/ped_on.png',0);
	} else if (directionsType == "bike") {
		var travelModeToUse = google.maps.DirectionsTravelMode.BICYCLING;
//			MM_swapImage('bikeIcon','','images/bike_on.png',0);
	} else if (directionsType == "drive") {
		var travelModeToUse = google.maps.DirectionsTravelMode.DRIVING;
//			MM_swapImage('carIcon','','images/car_on.png',0);
	} else {
		alert("WARNING: directionsType= " + directionsType);
	}
	
	// Attach directions to the map now that we're actually going to use it
	directionsDisplay.setMap(map);
	
	root.fromAddressText = "804 W Morgan St, Raleigh, NC";
	root.toAddressText = "5224 Troutman Ln, Raleigh, NC";
	

	var request = {
//			origin: "804 W Morgan St, Raleigh, NC", 
//			destination: "5224 Troutman Ln, Raleigh, NC",
		origin: root.fromAddressText, 
		destination: root.toAddressText,
		travelMode: travelModeToUse,
		provideRouteAlternatives: true
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
//			  directionsDisplay.setDirections(result);

//				var warnings = document.getElementById("warnings_panel");
//				warnings.innerHTML = "" + result.routes[0].warnings + "";
			directionsDisplay.setDirections(result);
			showSteps(result);
		}
	});
}
// Internal function
function showSteps(directionResult) {
  // For each step, place a marker, and add the text to the marker's
  // info window. Also attach the marker to an array so we
  // can keep track of it and remove it when calculating new
  // routes.
  var myRoute = directionResult.routes[0].legs[0];

  for (var i = 0; i < myRoute.steps.length; i++) {
	  var marker = new google.maps.Marker({
		position: myRoute.steps[i].start_point, 
		map: map
	  });
	  attachInstructionText(marker, myRoute.steps[i].instructions);
	  markerArray[i] = marker;
  }
}
// Internal function
function attachInstructionText(marker, text) {
  google.maps.event.addListener(marker, 'click', function() {
	stepDisplay.setContent(text);
	stepDisplay.open(map, marker);
  });
}

function clearDirections() {
	document.getElementById("directions_panel").style.visibility = "hidden";
	document.getElementById("leftmenu").style.visibility = "visible";
//		directionsDisplay.setDirections(null);
	directionsDisplay.setMap(null);
//		directionsDisplay.setDirections(null);
	
	// First, clear out any existing markerArray
	// from previous calculations.
	for (i = 0; i < markerArray.length; i++) {
		markerArray[i].setMap(null);
	}		
}

function clearAddressMarker() {
	marker.setMap(null);
	document.getElementById('address').value='';
	document.getElementById('city').value='Raleigh';
	document.getElementById('state').value='NC';
	document.getElementById('address').blur();
	document.getElementById('city').blur();
	document.getElementById('state').blur();
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

// Handler to allow ENTER key to submit the "Enter address" form
function keyPressed(event) {
	if ((event.which && event.which == 13) || 
		(event.keyCode && event.keyCode == 13))
		{document.getElementById('myHtmlInputButton').click();
		return false;
	} 
	else return true;		
}

function codeAddress() {
//    var address = document.getElementById("address").value;
	var addressToSearchFor = document.getElementById("address").value + ", " + document.getElementById("city").value + ", " + document.getElementById("state").value;

	geocoder.geocode( { 'address': addressToSearchFor}, function(results, status) {
	var firstAddress = results[0];
			/*													 
	debugText = "";
	for (oneItem in results[0]) {
		debugText += oneItem + "= " + results[0][oneItem] + "\n";	
	}
	 alert("results= " + debugText);
	 */
	  if (status == google.maps.GeocoderStatus.OK) {
		  if (firstAddress.formatted_address == "Raleigh, NC, USA") {
			  alert("Please enter a valid address, and click 'Enter'");
			  return;
		  }
		  
//			directionsDisplay.setMap(null);
		//clearAddressMarker();
		  
		map.setCenter(firstAddress.geometry.location);
		map.setZoom(16);
		/*
		var marker = new google.maps.Marker({
			map: map, 
			position: results[0].geometry.location
		});
		*/
		marker.setMap(map);
		marker.setPosition(firstAddress.geometry.location);
		marker.setTitle("Click for directions");
		
		// Split the formatted address into pieces
		var addressPieces = firstAddress.formatted_address.split(', ');
		var twoLineAddress = addressPieces[0] + '<br>' + addressPieces[1] + ', ' + addressPieces[2];
		searchedAddressInfoWindow.setContent('<span class="smallarial">' + '<strong>Found address:</strong><br>' + twoLineAddress + '</span>');
	
		// Go ahead and display the InfoWindow for the marker
		searchedAddressInfoWindow.open(map, marker);
		
	  } else {
		alert("Geocode was not successful for the following reason: " + status);
	  }
	});
}

// To minimize/maximize a DIV
function minimize(toggleOnOff, divToToggle) {
	if (divToToggle == "legend") {
//			alert("legend rcvd");
		if (toggleOnOff) {
//				alert("minimize");
//				document.getElementById("leftmenu").style.visibility = "hidden";
			document.getElementById("leftmenu").style.height = "20px";
			document.getElementById("minimize_legend").style.visibility = "hidden";
			document.getElementById("maximize_legend").style.visibility = "visible";
		} else {
//				document.getElementById("leftmenu").style.visibility = "visible";
			document.getElementById("leftmenu").style.height = "auto";
			document.getElementById("minimize_legend").style.visibility = "visible";
			document.getElementById("maximize_legend").style.visibility = "hidden";
		}
	} else {
		alert("WARNING: minimize() received divToToggle= " + divToToggle);
	}
}

function hideMenu(changeToHidden) {
//		alert("hideMenu()");
	if (changeToHidden) {
		jQuery("div#leftmenu").hide();
		jQuery("div#leftmenu_hidden").show();
		
	} else {
		jQuery("div#leftmenu_hidden").hide();
		jQuery("div#leftmenu").show();
	}
}
