// Wait to initialize Google Maps until after the page has loaded (for Internet Explorer)
//google.maps.event.addDomListener(window, 'load', initialize);

// Define variables here so they'll be globally available to all functions
var root = this;
var geocoder;
var map;
var marker;
// be sure to add new layers here:
var bikeLayer;
var bikeLanes_Layer;
var sharrows_Layer;
var multiUse_Layer;
var wideOutsideLanes_Layer;
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

	raleighCouncilDistricts_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/CityCouncilBoundaries2011_v3.kmz', {preserveViewport:true});
	raleighCouncilDistricts_Layer.setMap(map);

	existingBikeLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/Existing_BikeLanes3.kmz', {preserveViewport:true});
	existingBikeLanes_Layer.setMap(map);

	existingSharrows_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/Existing_Sharrows3.kmz', {preserveViewport:true});
	existingSharrows_Layer.setMap(map);

	existingMultiUse_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/Existing_MultiUse4.kmz', {preserveViewport:true});
	existingMultiUse_Layer.setMap(map);

	existingWideOutsideLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/Existing_WideOutsideLanes4.kmz', {preserveViewport:true});
	existingWideOutsideLanes_Layer.setMap(map);

	futureBikeLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/Future_BikeLanes2.kmz', {preserveViewport:true});
//		futureBikeLanes_Layer.setMap(map);

	futureSharrows_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/Future_Sharrows2.kmz', {preserveViewport:true});
//		futureSharrows_Layer.setMap(map);

	longtermBikeLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/LongTerm_BikeLanes3.kmz', {preserveViewport:true});
//			longtermBikeLanes_Layer.setMap(map);

	longtermSharrows_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/LongTerm_Sharrows3.kmz', {preserveViewport:true});
//			longtermSharrows_Layer.setMap(map);

	longtermWideOutsideLanes_Layer = new google.maps.KmlLayer('http://livingstreets.com/portfolio/bpacmap/LongTerm_WideOutsideLane3.kmz', {preserveViewport:true});
//			longtermWideOutsideLanes_Layer.setMap(map);
	
	//GEOCODER
	geocoder = new google.maps.Geocoder();


	  //Add listener to marker for opening the InfoWindow to get directions
	  google.maps.event.addListener(marker, 'click', function(event) {
//		   	alert("clicked")
			searchedAddressInfoWindow.open(map,marker);
		});

	  //Add listener to close the InfoWindow when the user clicks on the map
	  google.maps.event.addListener(map, 'click', function(event) {
//		   	alert("clicked")
			searchedAddressInfoWindow.close();
			document.getElementById('address').blur();
			document.getElementById('city').blur();
			document.getElementById('state').blur();
		});

	}
	// END function initialize()

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
