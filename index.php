<?php
$status = "RaleighBPAC.org: INTERACTIVE MAP BIKE FACILITIES";
$CookieDomain = "raleighbpac.org";
$CookieName = "RaleighBPACVisitorID";

$RelativeToRoot = "../../";
//$RelativeToRoot = "../../../../Main/Web Media/";
//include $RelativeToRoot . "site_variables.php";
//include $RelativeToRoot . 'header.php';
/* For running locally: (also comment out the two includes above)
function isIgnoredIP() {
	return true;
}
*/
?>

<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <meta name="description" content="Shows the Google Bicycle Layer, plus additional layers for bike/ped facilities in Raleigh, North Carolina.">
    <meta name="keywords" content="raleigh, bicycle, pedestrian, bicycling, biking, bike, walk, walking, map, maps, directions, greenway, bike plan, bike lane, sharrow">

    <title>Raleigh BPAC Planning Map</title>
    
    <style type="text/css">
    .smallarial, .smallarial a {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	}
    .smallarialsmaller a {
	font-size: 11px;
	}
	/* Used by the "Address" field: */
	.ui-autocomplete {
		background-color: white;
		width: 300px;
		border: 1px solid #cfcfcf;
		list-style-type: none;
		padding-left: 0px;
		font-size:12px;
	}

	.largetitle {
		font-size: 15px;
/*		font-family: Verdana, Geneva, sans-serif;*/
		font-family: Arial, Helvetica, sans-serif;
		font-weight: bold;
	}
		
    </style>
    
    <!-- Load Google Maps API V3 -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<!-- Load jQuery -->
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>
	<!-- Load all custom JavaScript -->

    <script type="text/javascript">
	// Wait to initialize Google Maps until after the page has loaded (for Internet Explorer)
	google.maps.event.addDomListener(window, 'load', initialize);

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
//	var trafficLayer;
//	var existingBikeFacilities_Layer;
//	var plannedBikeFacilities_Layer;
//	var fy11Recommended_Layer;
//	var ncdotPedBikeCrashes_Layer;
//	var top25BikeFacilities_Layer;
//	var bikePlanFacilities_Layer;
//	var raleighGreenways_Layer;
//	var ncsuBikeFacilities_Layer;
//	var raleighTrails2007_Layer;
//	var raleighTrailAreas2007_Layer;
//	var raleighCouncilDistricts_Layer;
//	var september2011Bike_Layer;
//	var weCar_Layer;
	var bikeLanes_Layer;
	var sharrows_Layer;
	var multiUse_Layer;
	var wideOutsideLanes_Layer;

	var searchedAddressInfoWindow = new google.maps.InfoWindow({
		content: "",
		size: new google.maps.Size(50,50),
		maxWidth: 500
	});

// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	
<?php /* MUST WAIT FOR ONLOAD, NOT JQUERY READY TO AVOID ERRORS IN INTERNET EXPLORER
	// Start after jQuery has loaded (instead of after the page has loaded with "body onload")
	$(document).ready(function() {
		// This function is below
//	  initialize();
	});
*/ ?>
	// This is called by the jQuery function above
	function initialize() {
	
		// Reset the form
//		document.forms[0].zoomToLevel.selectedIndex = 0;
	///	document.forms[0].raleighzoning.checked = false;
	//	document.forms[0].catstops.checked = false;
	//	document.forms[0].ttalightrail.checked = false;
//		document.forms[0].bikeLayer.checked = true;
//		document.forms[0].trafficLayer.checked = false;
//		document.forms[0].existingBikeFacilities.checked = true;
//		document.forms[0].plannedBikeFacilities.checked = true;
//		document.forms[0].ncdotPedBikeCrashes.checked = false;
//		document.forms[0].top25BikeFacilities.checked = false;
//		document.forms[0].fy11Recommended.checked = false;
//		document.forms[0].bikePlanFacilities.checked = false;
//		document.forms[0].raleighGreenways.checked = false;
//		document.forms[0].ncsuBikeFacilities.checked = false;
//		document.forms[0].raleighTrails2007.checked = false;
//		document.forms[0].raleighTrailAreas2007.checked = false;
//		document.forms[0].raleighCouncilDistricts.checked = false;
//		document.forms[0].weCar.checked = false;
//		document.forms[0].september2011Bike.checked = false;
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
		/*
		// Display custom KML/KMZ layers		
		// NOTE: THESE NEED TO BE LOADED IN REVERSE ORDER FOR PROPER LAYERING
		raleighTrailAreas2007_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Raleigh_TrailAreas_2007-lines-ms1_ALLINCLUSIVE3.kmz', {preserveViewport:true});
//		raleighTrails2007_Layer.setMap(map);

		raleighTrails2007_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Raleigh_Trails_2007-lines-ms1_ALLINCLUSIVE4.kmz', {preserveViewport:true});
//		raleighTrails2007_Layer.setMap(map);

		ncsuBikeFacilities_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/NCSU_Greenways2.kmz', {preserveViewport:true});
//		ncsuBikeFacilities_Layer.setMap(map);

		raleighGreenways_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Export_Grnwys2.kmz', {preserveViewport:true});
//		raleighGreenways_Layer.setMap(map);

		bikePlanFacilities_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/network_august2_bikeonly_ALLINCLUSIVE_compact14.kmz', {preserveViewport:true});
//		bikePlanFacilities_Layer.setMap(map);

		fy11Recommended_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Raleigh_Bicycle_Projects-March_2011.kmz', {preserveViewport:true});
//		fy11Recommended_Layer.setMap(map);

		top25BikeFacilities_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Top25Project6.kmz', {preserveViewport:true});
//		top25BikeFacilities_Layer.setMap(map);

		ncdotPedBikeCrashes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/NCHighwaySafetyImprovementProgram-2010BikePed3.kmz', {preserveViewport:true});
//		ncdotPedBikeCrashes.setMap(map);
		raleighCouncilDistricts_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/New_City_Council_Boundaries_2011_1000outputscale_Modified.kmz', {preserveViewport:true});
//		raleighTrails2007_Layer.setMap(map);

		raleighCATRoutes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/raleighCATRoutes.kmz', {preserveViewport:true});
//		raleighTrails2007_Layer.setMap(map);

		weCar_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/WeCar2.kmz', {preserveViewport:true});
//		weCar_Layer.setMap(map);

		september2011Bike_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Pavement_Marking_Plans_20110919.kmz', {preserveViewport:true});
//		september2011Bike_Layer.setMap(map);

*/
		// Display the Google Maps Bike Layer
		bikeLayer = new google.maps.BicyclingLayer();
		bikeLayer.setMap(map);

		raleighCouncilDistricts_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/CityCouncilBoundaries2011_v3.kmz', {preserveViewport:true});
		raleighCouncilDistricts_Layer.setMap(map);
		google.maps.event.addListenerOnce(root["raleighCouncilDistricts_Layer"], "defaultviewport_changed", function(kmlEvent) {


			existingBikeLanes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Existing_BikeLanes3.kmz', {preserveViewport:true});
			existingBikeLanes_Layer.setMap(map);
	
			existingSharrows_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Existing_Sharrows3.kmz', {preserveViewport:true});
			existingSharrows_Layer.setMap(map);
	
			existingMultiUse_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Existing_MultiUse4.kmz', {preserveViewport:true});
			existingMultiUse_Layer.setMap(map);
	
			existingWideOutsideLanes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Existing_WideOutsideLanes4.kmz', {preserveViewport:true});
			existingWideOutsideLanes_Layer.setMap(map);
	
			futureBikeLanes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Future_BikeLanes2.kmz', {preserveViewport:true});
	//		futureBikeLanes_Layer.setMap(map);
	
			futureSharrows_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/Future_Sharrows2.kmz', {preserveViewport:true});
	//		futureSharrows_Layer.setMap(map);

			longtermBikeLanes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/LongTerm_BikeLanes3.kmz', {preserveViewport:true});
//			longtermBikeLanes_Layer.setMap(map);
	
			longtermSharrows_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/LongTerm_Sharrows3.kmz', {preserveViewport:true});
//			longtermSharrows_Layer.setMap(map);
	
			longtermWideOutsideLanes_Layer = new google.maps.KmlLayer('http://raleighbpac.org/map/LongTerm_WideOutsideLane3.kmz', {preserveViewport:true});
//			longtermWideOutsideLanes_Layer.setMap(map);

	
			// Display the Google Maps TRAFFIC Layer
	//		trafficLayer = new google.maps.TrafficLayer();
	//		trafficLayer.setMap(map);
	
		});
		
		//GEOCODER
		geocoder = new google.maps.Geocoder();

		


/* FIND A WAY TO CLEAR ANY INFOWINDOWS WHEN THE USER CLICKS ON THE MAP
		google.maps.event.addListener(map, 'click',
			function() {
//				alert("closing");
				debugText = "";
				for (oneItem in root.content) {
					debugText += oneItem + ", type= " + typeof(root.content[oneItem]) + "\n";
				}
				alert(debugText);
				infowindow.close();
	        }); 
*/














	
/* Not using auto-complete now:
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
	
	//				searchedAddressInfoWindow.setContent("<span class='smallarial'><strong>Address:</strong><br>" + ui.item.label + "<br><br><a href='javascript:alert(\"Yo\");'>Get directions</a></span>");
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
	
			/* TEMPORARILY DISABLED BECAUSE IT'S GEOCODING EVERY TIME THE USER CLICKS, INCLUDING WHEN ZOOMING IN:
		  //Add listener to marker for reverse geocoding
		  google.maps.event.addListener(map, 'click', function(event) {
		//	alert("event.latLng= " + event.latLng);						   
			geocoder.geocode({'latLng': event.latLng}, function(results, status) {
			  if (status == google.maps.GeocoderStatus.OK) {
				if (results[0]) {
				  $('#address').val(results[0].formatted_address);
				  $('#latitude').val(event.latLng.lat());
				  $('#longitude').val(event.latLng.lng());
				marker.setPosition(event.latLng);
				}
			  }
			});
		  });
	*/
	
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
		// function initialize()

// obsoleted layerNames_array

/* Removed support for toggline multiple layers at once */
//	function toggleLayer(checkboxName, layerNames_array) {
	function toggleLayer(checkboxName) {
		/*
		if (document.forms[0][checkboxName].checked) {
			root[checkboxName + "_Layer"].setMap(map, false);
		} else {
			root[checkboxName + "_Layer"].setMap(null);
		}
		*/
		/*
bikeLanes
sharrows
multiUse
wideOutsideLanes


existingSharrows_Layer
existingMultiUse_Layer
existingBikeLanes_Layer
existingWideOutsideLanes_Layer		
		*/
		
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
/* These layers don't exist yet
			// if "Wide outside lanes" is selected, but NOT visible
			if (document.forms[0]['wideOutsideLanes'].checked && root["futureWideOutsideLanes_Layer"].getMap() == undefined) {
				root["futureWideOutsideLanes_Layer"].setMap(map, false);
			// If "Wide outside lanes" is NOT selected
			} else if (!document.forms[0]['wideOutsideLanes'].checked) {
				root["futureWideOutsideLanes_Layer"].setMap(null);
			}

			// if "Multi-use paths" is selected, but NOT visible
			if (document.forms[0]['multiUse'].checked && root["futureMultiUse_Layer"].getMap() == undefined) {
				root["futureMultiUse_Layer"].setMap(map, false);
			// If "Multi-use paths" is NOT selected
			} else if (!document.forms[0]['multiUse'].checked) {
				root["futureMultiUse_Layer"].setMap(null);
			}
*/
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
/*
			// if "Multi-use paths" is selected, but NOT visible
			if (document.forms[0]['multiUse'].checked && root["longtermMultiUse_Layer"].getMap() == undefined) {
				root["longtermMultiUse_Layer"].setMap(map, false);
			// If "Multi-use paths" is NOT selected
			} else if (!document.forms[0]['multiUse'].checked) {
				root["longtermMultiUse_Layer"].setMap(null);
			}
			*/
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

/* Not currently using:
	function addressClicked(clickedIn) {
		if (clickedIn) {
//			alert("yes");
//			tempValue = document.getElementById('address').value;
			document.getElementById('address').value='';
		} else {
//			if (tempValue) {
			if (currentLocationDisplay) {
				document.getElementById('address').value = currentLocationDisplay;
			}
		}
	//	;
	}
*/




	function toggleAllLayers(doSelectAll) {
		
//		document.forms[0].existingBikeFacilities.checked = doSelectAll;
//		document.forms[0].plannedBikeFacilities.checked = doSelectAll;
//		document.forms[0].top25BikeFacilities.checked = doSelectAll;
//		document.forms[0].ncdotPedBikeCrashes.checked = doSelectAll;
//		document.forms[0].fy11Recommended.checked = doSelectAll;
//		document.forms[0].bikePlanFacilities.checked = doSelectAll;
//		document.forms[0].raleighGreenways.checked = doSelectAll;
//		document.forms[0].ncsuBikeFacilities.checked = doSelectAll;
//		document.forms[0].raleighTrails2007.checked = doSelectAll;
//		document.forms[0].raleighTrailAreas2007.checked = doSelectAll;
//		document.forms[0].raleighCouncilDistricts.checked = doSelectAll;
//		document.forms[0].raleighCATRoutes.checked = doSelectAll;
//		document.forms[0].weCar.checked = doSelectAll;
//		document.forms[0].september2011Bike.checked = doSelectAll;
		document.forms[0].bikeLanes.checked = doSelectAll;
		document.forms[0].sharrows.checked = doSelectAll;
		document.forms[0].multiUse.checked = doSelectAll;
		document.forms[0].wideOutsideLanes.checked = doSelectAll;
		
//		toggleLayer("existingBikeFacilities");		
//		toggleLayer("plannedBikeFacilities");		
//		toggleLayer("top25BikeFacilities");
//		toggleLayer("ncdotPedBikeCrashes");
//		toggleLayer("fy11Recommended");
//		toggleLayer("bikePlanFacilities");		
//		toggleLayer("raleighGreenways");		
//		toggleLayer("ncsuBikeFacilities");		
//		toggleLayer("raleighTrails2007");		
//		toggleLayer("raleighTrailAreas2007");		
//		toggleLayer("raleighCouncilDistricts");
//		toggleLayer("raleighCATRoutes");
//		toggleLayer("weCar");
//		toggleLayer("september2011Bike");
		toggleLayer("bikeLanes");
		toggleLayer("sharrows");
		toggleLayer("multiUse");
		toggleLayer("wideOutsideLanes");
	}
	
/* OBSOLETED AND INTEGRATED INTO WHAT WAS FORMERLY getDirections2()
	// When "Get directions" is clicked in the InfoWindow for an address that was searched for
	function getDirections(fromAddressText, tripMethod) {
//		alert("getDirections()");
//		searchedAddressInfoWindow.setContent("<span class='smallarial'><strong>Address:</strong><br>" + ui.item.label + "<br><br><a href='javascript:getDirections()'>Get directions</a></span>");
		searchedAddressInfoWindow.setContent('<span class="smallarial"><strong>Directions from:</strong><br>' +
			'<form action="#" onsubmit="getDirections2(\'' + fromAddressText + '\', \'' + tripMethod + '\'); return false;">'+
			'<input type="text" id="endaddress" value=""><br><br><strong>To:</strong><br>'+
			fromAddressText + "<br><br>" +
			'<input type="submit" value="Find directions"></form>');
		
	}
		*/
		
		
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
		/*
		debugText = "";
		for (oneItem in theObject) {
			debugText += oneItem + "= " + theObject[oneItem] + "\n";
		}
//		alert(debugText);		
		alert("TheObject= " + debugText);
		*/
		
//		toAddressText = document.getElementById("endaddress").value;
		//alert("getDirections2() received= " + fromAddressText + ", to= " + toAddressText);
		
		// Temporarily hardcoded
//		tripMethod = "ped";

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
				/*													 
		debugText = "";
		for (oneItem in results[0]) {
			debugText += oneItem + "= " + results[0][oneItem] + "\n";	
		}
		 alert("results= " + debugText);
		 */
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
			/*
			var marker = new google.maps.Marker({
				map: map, 
				position: results[0].geometry.location
			});
			*/
			marker.setMap(map);
			marker.setPosition(results[0].geometry.location);
			marker.setTitle("Click for directions");
			

//			searchedAddressInfoWindow.setContent("<span class='smallarial'><strong>Address:</strong><br>" + ui.item.label + "<br><br><a href='javascript:alert(\"Yo\");'>Get directions</a></span>");

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

//			'<a href="javascript:clearDirections()"><img src="images/cancel.png" alt="Cancel directions" name="cancelIcon" width="39" height="25" border="0" id="cancelIcon" /></a>' +
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
/* Just use getDirections()
	function changeTravelMode(newTravelMode) {
		alert("changeTravelMode() received= " + newTravelMode);
	}
*/
    </script>

	<?php // DON'T DISPLAY GOOGLE ANALYTICS FOR IGNORED IPs:
//	_gaq.push(['_setAccount', 'UA-17356967-1']);
//    if (!isIgnoredIP($_SERVER['REMOTE_ADDR'])) { ?>
		<script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-4162060-3']);
          _gaq.push(['_trackPageview']);
          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
	<?php //}; ?>

</head>
<!--
<body style="margin:0px; padding:0px;" onLoad="initialize()">
-->
<body style="margin:0px; padding:0px;">
		<form id="layercheckboxes" method="post" action="" style="margin:0;">

	<!-- LOAD MAP CANVAS -->
	<div id="map_canvas" style="width:100%; height:100%">
		Loading map...
   </div>


	<div id="leftmenu_hidden" style="position:absolute; right:5px; top:48px; display:none;">
		<input type="button" name="ShowMenuID" id="ShowMenuID" value="Show Menu" style="font-size:18px; width:130px; font-weight:bold;" onClick="hideMenu(false)" />
	</div>

<!--
	<div id="leftmenu" style="position:absolute; left:70px; top:99px; width:240px; padding:5px; overflow:hidden; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80);">
-->
	<!-- LOAD TITLE (RaleighTransit.Info) -->
	<div id="leftmenu" style="position:absolute; right:5px; top:48px; width:225px; padding:5px; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80);">

	<div><input type="button" name="HideMenuID" id="HideMenuID" value="Hide Menu" style="font-size:15px; width:100%; font-weight:bold;" onClick="hideMenu(true)" /></div>

		<span class="largetitle">Raleigh BPAC Planning</span>
		<span class="smallarial"><strong> | <a href="about.php">About</a></strong></span>
       <br>


		  <span class="smallarial"><strong>Time period:</strong><br />
          <!--
          <label><input type="radio" name="TimePeriod" id="Current" value="Yes" checked="checked" />Current</label>
          <label><input type="radio" name="TimePeriod" id="NearFuture" value="Yes" />Future</label>
          <label><input type="radio" name="TimePeriod" id="LongTerm" value="Yes" />Long-term</label>
          -->
          
          <label><input type="checkbox" name="current" id="current" value="yes" checked="checked" onClick="updateVisibleLayers()" /><strong>Current</strong></label><label><input type="checkbox" name="future" id="future" value="yes" onClick="updateVisibleLayers()" /><strong>Future</strong></label><label><input type="checkbox" name="longTerm" id="longTerm" value="yes" onClick="updateVisibleLayers()" /><strong>Long-term</strong></label>
          
  		<br>
		<script language="javascript">
		jQuery(document).ready(function() {
			jQuery('#NearFuture').attr('disabled', 'true');
			jQuery('#LongTerm').attr('disabled', 'true');
		});
        </script>

        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>


      		<!-- Create a divider with some spacing -->
<!-- Didn't work in MSIE:      
            <div style="height:5px;"></div>
            <div style="height:1px; background-color:#000"></div>
            <div style="height:5px;"></div>
-->





<!---
        <div><img src="blackdot.gif" width="100%" height="1"></div>

            
          <div style="float:left; background-color:rgb(0,0,0); width:30px;">&nbsp;</div>
          <label>
                  <input type="checkbox" name="existingBikeFacilities" id="existingBikeFacilities" onClick="toggleLayer('existingBikeFacilities')" value="yes" />
                <span class="smallarial">Raleigh: Existing bike</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Existing bicycle facilities within the City of Raleigh" alt="Existing bicycle facilities within the City of Raleigh" /><br />
            <div style="clear:both"></div>
            
            <div style="float:left; background-color:rgb(0,85,255); width:30px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="plannedBikeFacilities" id="plannedBikeFacilities" onClick="toggleLayer('plannedBikeFacilities')" value="yes" />
                <span class="smallarial">Raleigh: Planned bike</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Bicycle facilities within the City of Raleigh that are currently slated for implementation in the very near future." alt="Bicycle facilities within the City of Raleigh that are currently slated for implementation in the very near future." /><br />
            <div style="clear:both"></div>
--->


<!---
            <div style="float:left; background-color:rgb(0,85,255); width:30px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="raleighCATRoutes" id="raleighCATRoutes" onClick="toggleLayer('raleighCATRoutes')" value="yes" />
                <span class="smallarial">Raleigh: CAT transit</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Description." alt="Description." /><br />
            <div style="clear:both"></div>

        <div><img src="blackdot.gif" width="100%" height="1"></div>


              <div style="float:left; background-color:rgb(255,85,255); width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="bikePlanFacilities" id="bikePlanFacilities" onClick="toggleLayer('bikePlanFacilities')" value="yes" />
                <span class="smallarial">Raleigh: Bike Plan (ALL)</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="All 447 miles of bicycle facilities recommended in the Raleigh Bicycle Plan." alt="All 447 miles of bicycle facilities recommended in the Raleigh Bicycle Plan." /><br />
            <div style="clear:both"></div>


            <div style="float:left; background-color:rgb(121,0,181); width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="top25BikeFacilities" id="top25BikeFacilities" onClick="toggleLayer('top25BikeFacilities')" value="yes" />
                <span class="smallarial">Raleigh: Bike Plan Priority</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Bicycle facilities identified in the Raleigh Bicycle Plan as 'Top 25' priority projects." alt="Bicycle facilities identified in the Raleigh Bicycle Plan as 'Top 25' priority projects." /><br />
            <div style="clear:both"></div>


              <div style="float:left; background-color:#ff0000; width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="fy11Recommended" id="fy11Recommended" onClick="toggleLayer('fy11Recommended')" value="yes" />
                <span class="smallarial">Raleigh: FY'11 Recomm.</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="FY'11 Recommended Projects." alt="FY'11 Recommended Projects" /><br />
            <div style="clear:both"></div>


              <div style="float:left; background-color:#ff0000; width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="september2011Bike" id="september2011Bike" onClick="toggleLayer('september2011Bike')" value="yes" />
                <span class="smallarial">Raleigh: STP-DA 9/19/11</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="" alt="" /><br />
            <div style="clear:both"></div>


	       <div><img src="blackdot.gif" width="100%" height="1"></div>
              <div style="float:left; width:30px">
              <img src="ncdotBikePedCrashesSymbol.png" width="25" height="25">
              </div>
            <label>                  
              <input type="checkbox" name="ncdotPedBikeCrashes" id="ncdotPedBikeCrashes" onClick="toggleLayer('ncdotPedBikeCrashes')" value="yes" />
                <span class="smallarial">Raleigh: Ped/Bike Crashes</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="NCDOT Highway Safety Improvement Program - Potentially Hazardous Bicycle and Pedestrians Intersection Locations" alt="NCDOT Highway Safety Improvement Program - Potentially Hazardous Bicycle and Pedestrians Intersection Locations" /><br />
            <div style="clear:both"></div>





        <div><img src="blackdot.gif" width="100%" height="1"></div>

            <div style="float:left; background-color:rgb(0,165,248); width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="raleighGreenways" id="raleighGreenways" onClick="toggleLayer('raleighGreenways')" value="yes" />
                <span class="smallarial">Raleigh: Greenways</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Greenway layer provided by the City of Raleigh (most of these facilities are also included in the Google Bicycle Layer)" alt="Greenway layer provided by the City of Raleigh (most of these facilities are also included in the Google Bicycle Layer)" /><br />
            <div style="clear:both"></div>

            <div style="float:left; background-color:rgb(255,0,9); width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="ncsuBikeFacilities" id="ncsuBikeFacilities" onClick="toggleLayer('ncsuBikeFacilities')" value="yes" />
                <span class="smallarial">NCSU Bike Layer</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Bicycle facilities layer provided by NC State University (many of these facilities are also included in the Google Bicycle Layer)" alt="Bicycle facilities layer provided by NC State University (many of these facilities are also included in the Google Bicycle Layer)" /><br />
            <div style="clear:both"></div>

        <div><img src="blackdot.gif" width="100%" height="1"></div>
            
            <div style="float:left; background-color:rgb(85,85,9); width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="raleighTrailAreas2007" id="raleighTrailAreas2007" onClick="toggleLayer('raleighTrailAreas2007')" value="yes" />
                <span class="smallarial">Raleigh Trail Areas 2007</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="'Raleigh Trail Areas 2007' layer provided by Wake County government. Note that this includes private golf courses." alt="'Trail Areas' layer provided by Wake County. Note that this includes private golf courses." /><br />
            <div style="clear:both"></div>
            
            <div style="float:left; background-color:rgb(255,85,9); width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="raleighTrails2007" id="raleighTrails2007" onClick="toggleLayer('raleighTrails2007')" value="yes" />
                <span class="smallarial">Raleigh Trails 2007</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="'Raleigh Trails 2007' layer provided by Wake County government." alt="'Raleigh Trails 2007' layer provided by Wake County government." /><br />

         <div style="clear:both"></div>
        

        <div><img src="blackdot.gif" width="100%" height="1"></div>


            <div style="float:left; background-color:rgb(7,198,211); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(153,81,32); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(201,212,79); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(185,20,80); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(4,77,159); width:6px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="raleighCouncilDistricts" id="raleighCouncilDistricts" onClick="toggleLayer('raleighCouncilDistricts')" value="yes" />
                <span class="smallarial">Raleigh Council Dist. 2011</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Raleigh City Council Districts" alt="Raleigh City Council Districts" /><br />
            <div style="clear:both"></div>


          <div style="float:left; width:30px; text-align:center"><img src="images/placemark_icon_cropped_15ht.png"></div>
          <label>
                  <input type="checkbox" name="weCar" id="weCar" onClick="toggleLayer('weCar')" value="yes" />
                <span class="smallarial">WeCar locations</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="WeCar (owned by Enterprise Rent-A-Car) is a service that rents neighborhood-based cars to members by the hour through an online reservation system." alt="WeCar (owned by Enterprise Rent-A-Car) is a service that rents neighborhood-based cars to members by the hour through an online reservation system." /><br />
            <div style="clear:both"></div>


--->


		  <span class="smallarial"><strong>Show bike facilities:</strong><!-- <font size=-1>[ <a href="javascript:toggleAllLayers(false)">Clear All</a> ]</font>--></span>
          <br />



              <div style="float:left; background-color:#ff0000; width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="bikeLanes" id="bikeLanes" onClick="updateVisibleLayers()" value="yes" checked="checked" />
                <span class="smallarial">Bike lanes</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="" alt="" />--><br />
            <div style="clear:both"></div>

              <div style="float:left; background-color:#ffaa00; width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="sharrows" id="sharrows" onClick="updateVisibleLayers()" value="yes" checked="checked" />
                <span class="smallarial">Sharrows</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="" alt="Sharrows (shared lane markings) " />--><br />
            <div style="clear:both"></div>

              <div style="float:left; background-color:#ffff00; width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="wideOutsideLanes" id="wideOutsideLanes" onClick="updateVisibleLayers()" value="yes" checked="checked" />
                <span class="smallarial">Wide outside lanes</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="" alt="" />--><br />
            <div style="clear:both"></div>

              <div style="float:left; background-color:#0000ff; width:30px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="multiUse" id="multiUse" onClick="updateVisibleLayers()" value="yes" checked="checked" />
                <span class="smallarial">Multi-use paths</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="" alt="" />--><br />
            <div style="clear:both"></div>


        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>

		  <span class="smallarial"><strong>Background layers:</strong></span>
          <br />

          <div style="float:left; background-color:#004d00; width:15px;">&nbsp;</div>
          <div style="float:left; background-color:#00fe00; width:15px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="bikeLayer" id="bikeLayer" onClick="toggleBikeLayer()" value="yes" <?php /* Why was this disabled-- why not allow turning this layer off? disabled="disabled" */ ?> />
                <span class="smallarial">Google Bicycle Layer</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths. Turn off this layer to see regular Google Maps." alt="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths. Turn off this layer to see regular Google Maps." />-->
                  <br />
            <div style="clear:both"></div>
            
            <div style="float:left; background-color:rgb(7,198,211); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(153,81,32); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(201,212,79); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(185,20,80); width:6px;">&nbsp;</div>
            <div style="float:left; background-color:rgb(4,77,159); width:6px;">&nbsp;</div>
            <label>                  
              <input type="checkbox" name="raleighCouncilDistricts" id="raleighCouncilDistricts" onClick="toggleLayer('raleighCouncilDistricts')" value="yes" <?php /* Why was this disabled-- why not allow turning this layer off? disabled="disabled" */ ?> />
                <span class="smallarial">Raleigh Council Districts</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Raleigh City Council Districts" alt="Raleigh City Council Districts" />--><br />
            <div style="clear:both"></div>

        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>





<!--- simplify for now
		  <span class="smallarial"><strong>Google background layers:</strong></span>
          <br />

          <div style="float:left; background-color:#004d00; width:15px;">&nbsp;</div>
          <div style="float:left; background-color:#00fe00; width:15px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="bikeLayer" id="bikeLayer" onClick="toggleBikeLayer()" value="yes" />
                <span class="smallarial">Bicycle facilities</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths. Turn off this layer to see regular Google Maps." alt="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths. Turn off this layer to see regular Google Maps." />
                  <br />
            <div style="clear:both"></div>


          <div style="float:left; background-color:#3fae14; width:10px;">&nbsp;</div>
          <div style="float:left; background-color:#f9ca22; width:10px;">&nbsp;</div>
          <div style="float:left; background-color:#90040d; width:10px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="trafficLayer" id="trafficLayer" onClick="toggleTrafficLayer()" value="yes" />
                <span class="smallarial">Realtime traffic</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Traffic Layer shows real-time (car) traffic conditions. Turn off this layer to see regular Google Maps." alt="Google's Traffic Layer shows real-time (car) traffic conditions. Turn off this layer to see regular Google Maps." /><br />
            <div style="clear:both"></div>


        <div><img src="blackdot.gif" width="100%" height="1"></div>
        --->
        
        
        
<div>
            <span class="smallarial"><strong>Zoom to:</strong></span>
              <!--
              <label>
              <select name="zoomToLevel" id="zoomToLevel" onChange="zoomToLevelChanged();">
                <option selected="selected">Select level here</option>
                <option value="triangle">Triangle area</option>
                <option value="raleigh">Raleigh metro area</option>
                <option value="downtown">Downtown Raleigh</option>
              </select>
              </label>
              -->


              <span class="smallarial"><a href="javascript:zoomToLevel('downtownraleigh')">Downtown</a> | <a href="javascript:zoomToLevel('raleigh')">Raleigh</a> | <a href="javascript:zoomToLevel('triangle')">Triangle</a></span>
</div>
<div>



	    <span class="smallarial">
           <strong>Find address:</strong>
        <!--    
                <input id="address" type="textbox" value="" style="width:250px; height:20px"> 
                <input type="button" value="Find address" onclick="codeAddress()"> 
        -->
        <!--
                <input id="address"  type="text" style="width:225px; font-size:10px" onClick="addressClicked(true)" onBlur="addressClicked(false)" /><br><br>
                -->
                <input id="address"  type="text" value="" style="width:125px; font-size:10px" onKeyDown="keyPressed(event)" /><br>
                 <strong>City:</strong>
                <input id="city"  type="text" value="Raleigh" style="width:70px; font-size:10px" onKeyDown="keyPressed(event)" />
                 <strong>State:</strong>
                <input id="state"  type="text" value="NC" style="width:25px; font-size:10px"  onkeydown="keyPressed(event)" />
                <input id="myHtmlInputButton" name="myHtmlInputButton" type="button" value="Find" style="width:40px; font-size:10px; font-weight:bold;" onClick="codeAddress()">
                
    <!--    
            <a href='javascript:document.getElementById("directions").style.visibility = "hidden";'>link</a>
     -->
    	</span>
</div>


<!-- Now using CSS padding instead:
    	<table cellpadding="10" cellspacing="0" border="0"><tr><td>
-->
<!-- No room for this screenshot:
        <p><img src="pedbikemap_screenshot.jpg" width="200" height="150" border="1" alt="Screenshot"></p>
-->
<!-- OBsoleted above:
		<p><strong><font size="4">RaleighTransit.Info</font></strong></p>
        -->
        



<!-- This functionality is already included:        
			<p>
		  <strong>Select view: </strong><br />
          <label>
            <input type="radio" name="SelectView" id="Terrain" value="yes" checked="checked" onclick="changeView('terrain');" />
            Terrain view</label><br />
        
          <label>
            <input type="radio" name="SelectView" id="Road" value="yes" onclick="changeView('road');" />
            Road view</label><br />
			</p>
            -->



<!--- what for?
        <div><img src="blackdot.gif" width="100%" height="1"></div>
--->




<!--
		</td></tr></table>
-->

        </div>
   

<!--
	<div id="titlediv" style="position:absolute; left:90px; top:5px; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80);">
        &nbsp;<span class="largetitle">RaleighTransit.Info </span>&nbsp;
-->

	<!-- Load MINIMIZE BUTTON DIV -->
    <!--- The JavaScript doesn't work right now because I merged the title DIV with the layers DIV:
	<div id="minimize_legend" style="position:absolute; left:292px; top:26px; border:0px; border-color:#000; border-style:solid; background-color:#FFF; width:16px; padding:0px; opacity:1.00;filter:alpha(opacity=100);"><a href="javascript:minimize(true, 'legend');"><img src="images/icon_min_transparent.png" border="0"></a></div>
  --->
  
	<!-- Load MAXIMIZE BUTTON DIV -->
	<div id="maximize_legend" style="position:absolute; left:71px; top:100px; border:0px; border-color:#000; border-style:solid; background-color:#FFF; width:16px; padding:0px; opacity:1.00;filter:alpha(opacity=100); visibility:hidden"><a href="javascript:minimize(false, 'legend');"><img src="images/icon_min_transparent_16_maximize.png" border="0"></a></div>


<!---
	<div id="leftmenu" style="position:absolute; left:70px; top:99px; width:245px; padding:5px; overflow:hidden; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80);">
--->
	<!-- LOAD DIRECTIONS DIV -->
	<div id="directions_panel" style="position:absolute; left:70px; top:99px; width:245px; padding:5px; overflow:hidden; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80); visibility:hidden;">
    <!--
    [ <a href="javascript:clearDirections()">Clear directions</a> ]
    -->
    
<!--
<a href="javascript:getDirections('ped')"><img src="images/ped_off.png" alt="Walking directions" name="pedIcon" width="39" height="25" border="0" id="pedIcon" /></a>
<a href="javascript:getDirections('bike')"><img src="images/bike_off.png" alt="Bicycling directions" name="bikeIcon" width="39" height="25" border="0" id="bikeIcon" /></a>
<a href="javascript:getDirections('car')"><img src="images/car_off.png" alt="Driving directions" name="carIcon" width="39" height="25" border="0" id="carIcon" /></a>
<a href="javascript:clearDirections()"><img src="images/cancel.png" alt="Cancel directions" name="cancelIcon" width="39" height="25" border="0" id="cancelIcon" /></a>
    -->
<a href="javascript:getDirections('walk')"><img src="images/ped_off.png" alt="Walking directions" name="pedIcon" width="39" height="25" border="0" id="pedIcon" /></a>
<a href="javascript:getDirections('bike')"><img src="images/bike_on.png" alt="Bicycling directions" name="bikeIcon" width="39" height="25" border="0" id="bikeIcon" /></a>
<a href="javascript:getDirections('drive')"><img src="images/car_off.png" alt="Driving directions" name="carIcon" width="39" height="25" border="0" id="carIcon" /></a>
<a href="javascript:clearDirections(); clearAddressMarker();"><img src="images/cancel.png" alt="Cancel directions" name="cancelIcon" width="39" height="25" border="0" id="cancelIcon" /></a>
   
 <!--
	    <span class="smallarial"><strong>Directions</strong>
        </span>
-->
<!--
        <a href="javascript:alert(this);">link</a>
-->
	</div>
  
              </form>

</body>
</html>