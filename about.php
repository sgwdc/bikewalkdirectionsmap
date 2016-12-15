<?php
	// Track visitors
	$RelativeToRoot = "../../";
	include $RelativeToRoot . 'visitor_tracker.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>About | Bike/Walk/Transit Directions Interactive Map</title>
	<meta name="viewport" content="initial-scale=1.0">
	<meta charset="utf-8">

	<style>
		p {
			font-size:18px;
		}
	</style>
</head>
<body>
	<?php
		// Google Analytics tracking
		include_once($RelativeToRoot . 'analyticstracking.php');
	?>

	<h2>About this Bike/Walk/Transit Directions Interactive Map</h2>

	<p>This web application uses the Google Maps API and JavaScript/jQuery to provide users with multiple route choices for bicycling, walking or riding transit between two locations.</p>
	<p>First your destination location is either a) geocoded on the map by typing in an address, city and state, or b) reverse geocoded by simply clicking anywhere on the map.</p>
	<p>Then when you enter in the starting address, multiple routes by walking, biking, transit (and driving for comparison) are fetched in the background from the Google Maps JavaScript API.</p>
	<p>You can also turn on or off Google's Bicycle, Transit and Traffic layers.</p>
	<p>Please <a href="/contact/">contact Steven Greenwaters</a> with any questions.</p>
	<p>&nbsp;</p>
	<p><a href="./">Return to the interactive map</a></p>
	<p>&nbsp;</p>
</body>
</html>
