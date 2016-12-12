<?php
	// Track visitors
	$RelativeToRoot = "../../";
	include $RelativeToRoot . 'visitor_tracker.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Bike/Walk/Transit Trip Planning Directions</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">
	
	<!-- Load jQuery (Currently only using for Hide Menu) -->
	<script src="js/jquery-3.1.0.min.js"></script>
	<!-- jQuery Migrate plugin to ensure compatibility with jQuery v2 -->
	<script src="js/jquery-migrate-3.0.0.js"></script>
	<!-- Not currently using since autocomplete is disabled
	<script src="js/jquery-ui-1.8.2.custom.min.js"></script>
	-->

	<!-- Load all custom JavaScript -->
	<script src="js/bikewalkdirectionsmap.js"></script>

	<!-- CSS styles -->
	<link rel="stylesheet" href="bikewalkdirectionsmap.css">

	<!-- Google Maps API -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOfx4wE7bdVp_1rW8dONgMrlI1V55Lb78&callback=initialize"
	async defer></script>
</head>
<body>
	<?php
		// Google Analytics tracking
		include_once($RelativeToRoot . 'analyticstracking.php');
	?>

	<!-- LOAD MAP CANVAS -->
	<div id="map_canvas">
		Loading map...
	</div>

	<div id="controls_hidden">
		<input type="button" id="ShowMenuID" value="Show Menu">
	</div>

	<div id="controls">
		<div><input type="button" id="HideMenuID" value="Hide Menu"></div>

		<div class="largetitle">Bike/Walk/Transit Directions</div>
		<strong><a href="about.php">About this map</a></strong>

		<div class="divider"></div>

		<strong>Transportation Layers:</strong>
		<div class="clearfix">
			<!-- Colors for Google BICYCLING Layer legend -->
			<div class="legendcolor twowide darkgreen"></div>
			<div class="legendcolor twowide brightgreen"></div>
			<label><input type="checkbox" id="bikeLayer" value="">
			Google Bicycle Layer</label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths." alt="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths.">
			<br>
		</div>

		<div class="clearfix">
			<!-- Colors for Google TRANSIT Layer legend -->
			<div class="legendcolor fivewide red"></div>
			<div class="legendcolor fivewide orange"></div>
			<div class="legendcolor fivewide yellow"></div>
			<div class="legendcolor fivewide green"></div>
			<div class="legendcolor fivewide blue"></div>
			<label><input type="checkbox" id="transitLayer" value="">
			Google Transit Layer</label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Transit Layer shows rail transit lines." alt="Google's Transit Layer shows rail transit lines.">
			<br>
		</div>

		<div class="clearfix">
			<!-- Colors for Google TRAFFIC Layer legend -->
			<div class="legendcolor fourwide red"></div>
			<div class="legendcolor fourwide orange"></div>
			<div class="legendcolor fourwide green"></div>
			<label><input type="checkbox" id="trafficLayer" value="">
			Google Realtime Traffic</label>
			<img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Traffic Layer shows real-time traffic conditions." alt="Google's Traffic Layer shows real-time traffic conditions.">
			<br>
		</div>

		<div class="divider"></div>

		<div>
			<strong>Zoom to:</strong>
			<a id="locationLevel1" class="zoomLevels" href="javascript:void(0);">District</a> |
			<a id="locationLevel2" class="zoomLevels" href="javascript:void(0);">DC Area</a> |
			<a id="locationLevel3" class="zoomLevels" href="javascript:void(0);">Mid-Atlantic</a>
		</div>

		<div id="destination">
			<strong>Enter your destination address:</strong>
			<input id="address" type="text" class="destination-field" value=""><br>
			<strong>City:</strong>
			<input id="city" type="text" class="destination-field" value="Washington">
			<strong>State:</strong>
			<input id="state" type="text" class="destination-field" value="DC">
			<input id="myHtmlInputButton" type="button" value="Find address">
		</div>
	</div>

	<!-- Directions DIV -->
	<div id="directions_panel">
		<img src="images/walk_off.png" alt="Walking directions" class="transport-mode" width="39" height="25" border="0" id="walk">
		<img src="images/bike_on.png" alt="Bicycling directions" class="transport-mode" width="39" height="25" border="0" id="bike">
		<img src="images/transit_off.png" alt="Transit directions" class="transport-mode" width="39" height="25" border="0" id="transit">
		<img src="images/drive_off.png" alt="Driving directions" class="transport-mode" width="39" height="25" border="0" id="drive">
		<img src="images/cancel.png" alt="Cancel directions" width="39" height="25" border="0" id="cancelButton">
	</div>
</body>
</html>
