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
var bikeLanes_Layer;
var sharrows_Layer;
var multiUse_Layer;
var wideOutsideLanes_Layer;

// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

// This is called by the jQuery function above
function initialize() {
	console.log("initialize()");
	var searchedAddressInfoWindow = new google.maps.InfoWindow({
		content: "",
		size: new google.maps.Size(50,50),
		maxWidth: 500
	});

	// Reset the form
	document.forms[0].bikeLanes.checked = true;
	document.forms[0].sharrows.checked = true;
	document.forms[0].multiUse.checked = true;
	document.forms[0].wideOutsideLanes.checked = true;
	document.forms[0].current.checked = true;
	document.forms[0].future.checked = false;
	document.forms[0].longTerm.checked = false;
	document.forms[0].bikeLayer.checked = true;
	document.forms[0].raleighCouncilDistricts.checked = true;
	
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
	/* Why is there only a handler for the first layer? -SGW
	raleighCouncilDistricts_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/CityCouncilBoundaries2011_v3.kmz', {preserveViewport:true});
	raleighCouncilDistricts_Layer.setMap(map);
	console.log( google.maps.event.addListenerOnce);
	console.log( root["raleighCouncilDistricts_Layer"]);
	google.maps.event.addListenerOnce(root["raleighCouncilDistricts_Layer"], "defaultviewport_changed", function(kmlEvent) {
		console.log("here");
		*/

		raleighCouncilDistricts_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/CityCouncilBoundaries2011_v3.kmz', {preserveViewport:true});
		raleighCouncilDistricts_Layer.setMap(map);

		existingBikeLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/Existing_BikeLanes3.kmz', {preserveViewport:true});
		existingBikeLanes_Layer.setMap(map);

		existingSharrows_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/Existing_Sharrows3.kmz', {preserveViewport:true});
		existingSharrows_Layer.setMap(map);

		existingMultiUse_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/Existing_MultiUse4.kmz', {preserveViewport:true});
		existingMultiUse_Layer.setMap(map);

		existingWideOutsideLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/Existing_WideOutsideLanes4.kmz', {preserveViewport:true});
		existingWideOutsideLanes_Layer.setMap(map);

		futureBikeLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/Future_BikeLanes2.kmz', {preserveViewport:true});
//		futureBikeLanes_Layer.setMap(map);

		futureSharrows_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/Future_Sharrows2.kmz', {preserveViewport:true});
//		futureSharrows_Layer.setMap(map);

		longtermBikeLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/LongTerm_BikeLanes3.kmz', {preserveViewport:true});
//			longtermBikeLanes_Layer.setMap(map);

		longtermSharrows_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/LongTerm_Sharrows3.kmz', {preserveViewport:true});
//			longtermSharrows_Layer.setMap(map);

		longtermWideOutsideLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/LongTerm_WideOutsideLane3.kmz', {preserveViewport:true});
//			longtermWideOutsideLanes_Layer.setMap(map);
	//});
	
	//GEOCODER
	geocoder = new google.maps.Geocoder();

	  //Add listener to marker for opening the InfoWindow to get directions
	  google.maps.event.addListener(marker, 'click', function(event) {
//		   	alert("clicked")
			searchedAddressInfoWindow.open(map,marker);
		});
		/* SGW
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
	// function initialize()

/* Removed support for toggline multiple layers at once */
//	function toggleLayer(checkboxName, layerNames_array) {
function toggleLayer(checkboxName) {
	if (checkboxName == "bikeLanes") {
		if (document.forms[0][checkboxName].checked) {
			root["existingBikeLanes_Layer"].setMap(map, false);
		} else {
			root["existingBikeLanes_Layer"].setMap(null);
		}
	} else if (checkboxName == "sharrows") {
		if (document.forms[0][checkboxName].checked) {
			root["existingSharrows_Layer"].setMap(map, false);
		} else {
			root["existingSharrows_Layer"].setMap(null);
		}
	} else if (checkboxName == "multiUse") {
		if (document.forms[0][checkboxName].checked) {
			root["existingMultiUse_Layer"].setMap(map, false);
		} else {
			root["existingMultiUse_Layer"].setMap(null);
		}
	} else if (checkboxName == "wideOutsideLanes") {
		if (document.forms[0][checkboxName].checked) {
			root["existingWideOutsideLanes_Layer"].setMap(map, false);
		} else {
			root["existingWideOutsideLanes_Layer"].setMap(null);
		}
	} else if (checkboxName == "raleighCouncilDistricts") {
		if (document.forms[0][checkboxName].checked) {
			root["raleighCouncilDistricts_Layer"].setMap(map, false);
		} else {
			root["raleighCouncilDistricts_Layer"].setMap(null);
		}
	} else {
		alert("toggleLayer() received checkboxName= " + checkboxName);
	}
}

function updateVisibleLayers() {
	// If "Current" is selected
	if (document.forms[0]['current'].checked) {
		// if "Bike lanes" is selected, but NOT visible
		if (document.forms[0]['bikeLanes'].checked && root["existingBikeLanes_Layer"].getMap() == undefined) {
			root["existingBikeLanes_Layer"].setMap(map, false);
		// If "Bike lanes" is NOT selected
		} else if (!document.forms[0]['bikeLanes'].checked) {
			root["existingBikeLanes_Layer"].setMap(null);
		}
		
		// if "Sharrows" is selected, but NOT visible
		if (document.forms[0]['sharrows'].checked && root["existingSharrows_Layer"].getMap() == undefined) {
			root["existingSharrows_Layer"].setMap(map, false);
		// If "Sharrows" is NOT selected
		} else if (!document.forms[0]['sharrows'].checked) {
			root["existingSharrows_Layer"].setMap(null);
		}

		// if "Wide outside lanes" is selected, but NOT visible
		if (document.forms[0]['wideOutsideLanes'].checked && root["existingWideOutsideLanes_Layer"].getMap() == undefined) {
			root["existingWideOutsideLanes_Layer"].setMap(map, false);
		// If "Wide outside lanes" is NOT selected
		} else if (!document.forms[0]['wideOutsideLanes'].checked) {
			root["existingWideOutsideLanes_Layer"].setMap(null);
		}

		// if "Multi-use paths" is selected, but NOT visible
		if (document.forms[0]['multiUse'].checked && root["existingMultiUse_Layer"].getMap() == undefined) {
			root["existingMultiUse_Layer"].setMap(map, false);
		// If "Multi-use paths" is NOT selected
		} else if (!document.forms[0]['multiUse'].checked) {
			root["existingMultiUse_Layer"].setMap(null);
		}
	} else {
		root["existingBikeLanes_Layer"].setMap(null);
		root["existingSharrows_Layer"].setMap(null);
		root["existingWideOutsideLanes_Layer"].setMap(null);
		root["existingMultiUse_Layer"].setMap(null);
	}

	// If "Future" is selected
	if (document.forms[0]['future'].checked) {
		// if "Bike lanes" is selected, but NOT visible
		if (document.forms[0]['bikeLanes'].checked && root["futureBikeLanes_Layer"].getMap() == undefined) {
			root["futureBikeLanes_Layer"].setMap(map, false);
		// If "Bike lanes" is NOT selected
		} else if (!document.forms[0]['bikeLanes'].checked) {
			root["futureBikeLanes_Layer"].setMap(null);
		}
		
		// if "Sharrows" is selected, but NOT visible
		if (document.forms[0]['sharrows'].checked && root["futureSharrows_Layer"].getMap() == undefined) {
			root["futureSharrows_Layer"].setMap(map, false);
		// If "Sharrows" is NOT selected
		} else if (!document.forms[0]['sharrows'].checked) {
			root["futureSharrows_Layer"].setMap(null);
		}
	} else {
		root["futureBikeLanes_Layer"].setMap(null);
		root["futureSharrows_Layer"].setMap(null);
/* These layers don't exist yet
		root["futureWideOutsideLanes_Layer"].setMap(null);
		root["futureMultiUse_Layer"].setMap(null);
*/
	}
	
	// If "Current" is selected
	if (document.forms[0]['longTerm'].checked) {
		// if "Bike lanes" is selected, but NOT visible
		if (document.forms[0]['bikeLanes'].checked && root["longtermBikeLanes_Layer"].getMap() == undefined) {
			root["longtermBikeLanes_Layer"].setMap(map, false);
		// If "Bike lanes" is NOT selected
		} else if (!document.forms[0]['bikeLanes'].checked) {
			root["longtermBikeLanes_Layer"].setMap(null);
		}
		
		// if "Sharrows" is selected, but NOT visible
		if (document.forms[0]['sharrows'].checked && root["longtermSharrows_Layer"].getMap() == undefined) {
			root["longtermSharrows_Layer"].setMap(map, false);
		// If "Sharrows" is NOT selected
		} else if (!document.forms[0]['sharrows'].checked) {
			root["longtermSharrows_Layer"].setMap(null);
		}

		// if "Wide outside lanes" is selected, but NOT visible
		if (document.forms[0]['wideOutsideLanes'].checked && root["longtermWideOutsideLanes_Layer"].getMap() == undefined) {
			root["longtermWideOutsideLanes_Layer"].setMap(map, false);
		// If "Wide outside lanes" is NOT selected
		} else if (!document.forms[0]['wideOutsideLanes'].checked) {
			root["longtermWideOutsideLanes_Layer"].setMap(null);
		}
	} else {
		root["longtermBikeLanes_Layer"].setMap(null);
		root["longtermSharrows_Layer"].setMap(null);
		root["longtermWideOutsideLanes_Layer"].setMap(null);
//			root["longtermMultiUse_Layer"].setMap(null);
	}
}

function toggleBikeLayer() {
	if (document.forms[0].bikeLayer.checked) {
		bikeLayer.setMap(map);
//			trafficLayer.setMap(null);
//			document.forms[0].trafficLayer.checked = false;
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

function toggleAllLayers(doSelectAll) {
	document.forms[0].bikeLanes.checked = doSelectAll;
	document.forms[0].sharrows.checked = doSelectAll;
	document.forms[0].multiUse.checked = doSelectAll;
	document.forms[0].wideOutsideLanes.checked = doSelectAll;
	toggleLayer("bikeLanes");
	toggleLayer("sharrows");
	toggleLayer("multiUse");
	toggleLayer("wideOutsideLanes");
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
	
	var request = {
		origin: root.fromAddressText, 
		destination: root.toAddressText,
		travelMode: travelMode,
		provideRouteAlternatives: true
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
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
		origin: root.fromAddressText, 
		destination: root.toAddressText,
		travelMode: travelModeToUse,
		provideRouteAlternatives: true
	};
	directionsService.route(request, function(result, status) {
		if (status == google.maps.DirectionsStatus.OK) {
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
//		alert("clearDirections()");
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

// When the "Enter address" form is submitted:
function codeAddress() {
//    var address = document.getElementById("address").value;
	var addressToSearchFor = document.getElementById("address").value + ", " + document.getElementById("city").value + ", " + document.getElementById("state").value;

	geocoder.geocode( { 'address': addressToSearchFor}, function(results, status) {
	  if (status == google.maps.GeocoderStatus.OK) {
		  if (results[0].formatted_address == "Raleigh, NC, USA") {
			  alert("Please enter a valid address, and click 'Enter'");
			  return;
		  }
		  
//			directionsDisplay.setMap(null);
		clearDirections();
		clearAddressMarker();
		  
		map.setCenter(results[0].geometry.location);
		map.setZoom(16);
		marker.setMap(map);
		marker.setPosition(results[0].geometry.location);
		marker.setTitle("Click for directions");
		
	searchedAddressInfoWindow.setContent('<span class="smallarial">' +
		'<form action="#" onsubmit="findDirectionsPressed(\'' + addressToSearchFor + '\', \'' + this + '\'); return false;">'+
//			'<form action="#" onsubmit="return false;">'+
		
		'<strong>Get walk/bike/drive directions to:</strong><br>'+
//			addressToSearchFor + "<br><br>" +
		results[0].formatted_address + "<br><br>" +
		'<strong>From address:</strong><br>' +
		'<input type="text" id="fromaddress" value="" style="width:200px; font-size:10px"><br>' +
		'<strong>City:</strong> <input id="fromcity"  type="text" value="Raleigh" style="width:105px; font-size:10px" />' +
		'&nbsp;&nbsp;<strong>State:</strong> <input id="fromstate" type="text" value="NC" style="width:25px; font-size:10px" />' +
		//                <input type="button" value="Find" onClick="codeAddress()">

		'<br><input type="submit" value="Get directions">' +
		'</form>');
	
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
