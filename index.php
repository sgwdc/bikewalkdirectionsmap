<?php
  // Track visitors
  $RelativeToRoot = "../../";
  include $RelativeToRoot . 'visitor_tracker.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bicycling/Walking/Transit Trip Planning Directions</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* The below is included because some CSS that works within quirks mode is not valid in standards mode. In specific, all percentage-based sizes must inherit from parent block elements, and if any of those ancestors fail to specify a size, they are assumed to be sized at 0 x 0 pixels. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
      /* END standards mode fix */

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
    
	<!-- Load jQuery (Currently only using for Hide Menu) -->
    <script src="js/jquery-3.1.0.min.js"></script>
    <!-- jQuery Migrate plugin to ensure compatibility with jQuery v2 -->
    <script src="js/jquery-migrate-3.0.0.js"></script>
    <!-- Not currently using since autocomplete is disabled
    <script src="js/jquery-ui-1.8.2.custom.min.js"></script>
    -->

	<!-- Load all custom JavaScript -->
    <script src="js/bikewalkdirectionsmap.js"></script>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOfx4wE7bdVp_1rW8dONgMrlI1V55Lb78&callback=initialize"
    async defer></script>

</head>
<body style="margin:0px; padding:0px;">
  <?php
  // Google Analytics tracking
  include_once($RelativeToRoot . 'analyticstracking.php');
  ?>

	<!-- LOAD MAP CANVAS -->
	<div id="map_canvas" style="width:100%; height:100%">
		Loading map...
   </div>

	<div id="leftmenu_hidden" style="position:absolute; right:5px; top:48px; display:none;">
		<input type="button" id="ShowMenuID" value="Show Menu" style="font-size:18px; width:130px; font-weight:bold;" />
	</div>

	<!-- LOAD TITLE (RaleighTransit.Info) -->
	<div id="leftmenu" style="position:absolute; right:5px; top:48px; width:225px; padding:5px; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.90;filter:alpha(opacity=90);">

	<div><input type="button" id="HideMenuID" value="Hide Menu" style="font-size:15px; width:100%; font-weight:bold;"  /></div>

		<span class="largetitle">Bicycling/Walking/Transit<br>Trip Planning Directions</span>
		<span class="smallarial"><strong><br>
    <a href="about.php">About this map</a></strong></span>

        <div><img src="blackdot.gif" width="100%" height="1"></div>

		  <span class="smallarial"><strong>Transportation Layers:</strong></span>
          <br />
          <!-- Colors for Google BICYCLING Layer legend -->
          <div style="float:left; background-color:#004d00; width:15px;">&nbsp;</div>
          <div style="float:left; background-color:#00fe00; width:15px;">&nbsp;</div>
          <label>
              <input type="checkbox" id="bikeLayer" value="" />
                <span class="smallarial">Google Bicycle Layer</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths." alt="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths." />
                  <br />
            <div style="clear:both"></div>

          <!-- Colors for Google TRANSIT Layer legend -->
          <div style="float:left; background-color:#ea4434; width:6px;">&nbsp;</div>
          <div style="float:left; background-color:#f38b38; width:6px;">&nbsp;</div>
          <div style="float:left; background-color:#f3d810; width:6px;">&nbsp;</div>
          <div style="float:left; background-color:#009b57; width:6px;">&nbsp;</div>
          <div style="float:left; background-color:#0d7bba; width:6px;">&nbsp;</div>
          <label>
              <input type="checkbox" id="transitLayer" value="" />
                <span class="smallarial">Google Transit Layer</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Transit Layer shows rail transit lines." alt="Google's Transit Layer shows rail transit lines." /><br />
            <div style="clear:both"></div>
            
          <!-- Colors for Google TRAFFIC Layer legend -->
          <div style="float:left; background-color:#e60000; width:10px;">&nbsp;</div>
          <div style="float:left; background-color:#f07d02; width:10px;">&nbsp;</div>
          <div style="float:left; background-color:#84ca50; width:10px;">&nbsp;</div>
          <label>
              <input type="checkbox" id="trafficLayer" value="" />
                <span class="smallarial">Google Realtime Traffic</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Traffic Layer shows real-time traffic conditions." alt="Google's Traffic Layer shows real-time traffic conditions." /><br />
            <div style="clear:both"></div>


        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>
<div>
            <span class="smallarial"><strong>Zoom to:</strong></span>
              <span class="smallarial">
                <a id="locationLevel1" class="zoomLevels" href="javascript:void(0);">District</a> |
                <a id="locationLevel2" class="zoomLevels" href="javascript:void(0);">DC Area</a> |
                <a id="locationLevel3" class="zoomLevels" href="javascript:void(0);">Mid-Atlantic</a>
              </span>
	</div>
	<!-- END of #controls -->

	<div>
	    <span class="smallarial">
           <strong>Enter your destination address:</strong>
                <input id="address" type="text" class="destination-field" value="" style="width:220px; font-size:10px" /><br>
                 <strong>City:</strong>
                <input id="city" type="text" class="destination-field" value="Washington" style="width:88px; font-size:10px" />
                 <strong>State:</strong>
                <input id="state" type="text" class="destination-field" value="DC" style="width:58px; font-size:10px" />
                <input id="myHtmlInputButton" type="button" value="Find address" style="font-size:10px; font-weight:bold;">
		</span>
		</div>
	</div>

  <!-- LOAD DIRECTIONS DIV -->
  <div id="directions_panel" style="position:absolute; left:70px; top:5px; width:245px; padding:5px; overflow:hidden; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.90;filter:alpha(opacity=90); display:none; ">
<img src="images/walk_off.png" alt="Walking directions" class="transport-mode" width="39" height="25" border="0" id="walk" />
<img src="images/bike_on.png" alt="Bicycling directions" class="transport-mode" width="39" height="25" border="0" id="bike" />
<img src="images/transit_off.png" alt="Transit directions" class="transport-mode" width="39" height="25" border="0" id="transit" />
<img src="images/drive_off.png" alt="Driving directions" class="transport-mode" width="39" height="25" border="0" id="drive" />
<img src="images/cancel.png" alt="Cancel directions" width="39" height="25" border="0" id="cancelButton" />
  </div>
   </body>
</html>
