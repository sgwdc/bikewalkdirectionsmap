<?php
/* From old website
$status = "RaleighBPAC.org: INTERACTIVE MAP BIKE FACILITIES";
$CookieDomain = "raleighbpac.org";
$CookieName = "RaleighBPACVisitorID";

$RelativeToRoot = "../../";
//$RelativeToRoot = "../../../../Main/Web Media/";
//include $RelativeToRoot . "site_variables.php";
//include $RelativeToRoot . 'header.php';
*/
/* For running locally: (also comment out the two includes above)
function isIgnoredIP() {
	return true;
}
*/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bike/Ped Directions Map</title>
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

	<!-- Load jQuery (Currently only using for Hide Menu) -->
    <script src="js/jquery-2.2.4.min.js"></script>
    <!-- jQuery Migrate plugin to ensure compatibility with jQuery v2 -->
    <script src="js/jquery-migrate-1.4.1.js"></script>
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
	<!-- LOAD MAP CANVAS -->
	<div id="map_canvas" style="width:100%; height:100%">
		Loading map...
   </div>

	<div id="leftmenu_hidden" style="position:absolute; right:5px; top:48px; display:none;">
		<input type="button" name="ShowMenuID" id="ShowMenuID" value="Show Menu" style="font-size:18px; width:130px; font-weight:bold;" onClick="hideMenu(false)" />
	</div>

	<!-- LOAD TITLE (RaleighTransit.Info) -->
	<div id="leftmenu" style="position:absolute; right:5px; top:48px; width:225px; padding:5px; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80);">
		<form method="post" action="" style="margin:0;">

	<div><input type="button" name="HideMenuID" id="HideMenuID" value="Hide Menu" style="font-size:15px; width:100%; font-weight:bold;" onClick="hideMenu(true)" /></div>

		<span class="largetitle">Bike/Ped Directions</span>
		<span class="smallarial"><strong> | <a href="about.php">About</a></strong></span>
       <br>



        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>

		  <span class="smallarial"><strong>Background layers:</strong></span>
          <br />
          <!-- Colors for Google Bicycle Layer legend -->
          <div style="float:left; background-color:#004d00; width:15px;">&nbsp;</div>
          <div style="float:left; background-color:#00fe00; width:15px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="bikeLayer" id="bikeLayer" value="" />
                <span class="smallarial">Google Bicycle Layer</span></label><!-- <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths. Turn off this layer to see regular Google Maps." alt="Google's Bicycle Layer shows existing greenways, bicycle lanes, paths, routes, and mixed-use paths. Turn off this layer to see regular Google Maps." />-->
                  <br />
            <div style="clear:both"></div>
            
          <!-- Colors for Google Traffic Layer legend -->
          <div style="float:left; background-color:#3fae14; width:10px;">&nbsp;</div>
          <div style="float:left; background-color:#f9ca22; width:10px;">&nbsp;</div>
          <div style="float:left; background-color:#90040d; width:10px;">&nbsp;</div>
          <label>
              <input type="checkbox" name="trafficLayer" id="trafficLayer" value="" />
                <span class="smallarial">Realtime traffic</span></label> <img src="images/question_mark.jpg" align="absbottom" width="16" height="16" title="Google's Traffic Layer shows real-time (car) traffic conditions. Turn off this layer to see regular Google Maps." alt="Google's Traffic Layer shows real-time (car) traffic conditions. Turn off this layer to see regular Google Maps." /><br />
            <div style="clear:both"></div>


        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>
<div>
            <span class="smallarial"><strong>Zoom to:</strong></span>
              <span class="smallarial"><a href="javascript:zoomToLevel('downtownraleigh')">Downtown</a> | <a href="javascript:zoomToLevel('raleigh')">Raleigh</a> | <a href="javascript:zoomToLevel('triangle')">Triangle</a></span>
	</form>
	</div>
	<!-- END of #controls -->

	<div>
	    <span class="smallarial">
           <strong>Find address:</strong>
                <input id="address"  type="text" value="" style="width:125px; font-size:10px" onKeyDown="keyPressed(event)" /><br>
                 <strong>City:</strong>
                <input id="city"  type="text" value="Raleigh" style="width:70px; font-size:10px" onKeyDown="keyPressed(event)" />
                 <strong>State:</strong>
                <input id="state"  type="text" value="NC" style="width:25px; font-size:10px"  onkeydown="keyPressed(event)" />
                <input id="myHtmlInputButton" name="myHtmlInputButton" type="button" value="Find" style="width:40px; font-size:10px; font-weight:bold;" onClick="codeAddress()">
		</span>
		</div>
	</div>

  <!-- Load MINIMIZE BUTTON DIV -->
    <!-- The JavaScript doesn't work right now because I merged the title DIV with the layers DIV:
  <div id="minimize_legend" style="position:absolute; left:292px; top:26px; border:0px; border-color:#000; border-style:solid; background-color:#FFF; width:16px; padding:0px; opacity:1.00;filter:alpha(opacity=100);"><a href="javascript:minimize(true, 'legend');"><img src="images/icon_min_transparent.png" border="0"></a></div>
  -->
  
  <!-- Load MAXIMIZE BUTTON DIV -->
  <div id="maximize_legend" style="position:absolute; left:71px; top:100px; border:0px; border-color:#000; border-style:solid; background-color:#FFF; width:16px; padding:0px; opacity:1.00;filter:alpha(opacity=100); visibility:hidden"><a href="javascript:minimize(false, 'legend');"><img src="images/icon_min_transparent_16_maximize.png" border="0"></a></div>

  <!-- LOAD DIRECTIONS DIV -->
  <div id="directions_panel" style="position:absolute; left:70px; top:99px; width:245px; padding:5px; overflow:hidden; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80); visibility:hidden; ">
    <!--
    [ <a href="javascript:clearDirections()">Clear directions</a> ]
    -->
    
<a href="javascript:getDirections('walk')"><img src="images/ped_off.png" alt="Walking directions" name="pedIcon" width="39" height="25" border="0" id="pedIcon" /></a>
<a href="javascript:getDirections('bike')"><img src="images/bike_on.png" alt="Bicycling directions" name="bikeIcon" width="39" height="25" border="0" id="bikeIcon" /></a>
<a href="javascript:getDirections('drive')"><img src="images/car_off.png" alt="Driving directions" name="carIcon" width="39" height="25" border="0" id="carIcon" /></a>
<a href="javascript:clearDirections(); clearAddressMarker();"><img src="images/cancel.png" alt="Cancel directions" name="cancelIcon" width="39" height="25" border="0" id="cancelIcon" /></a>
  </div>
   </body>
</html>
