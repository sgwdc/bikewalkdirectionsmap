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
    <title>Raleigh BPAC Planning Map</title>
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

	<!-- Load jQuery -->
    <script src="js/jquery-1.4.2.min.js"></script>
    <script src="js/jquery-ui-1.8.2.custom.min.js"></script>

</head>
<!--
<body style="margin:0px; padding:0px;" onLoad="initialize()">
-->
<body style="margin:0px; padding:0px;">
		<!-- This breaks things as of Sept 15, 2016
		<form id="layercheckboxes" method="post" action="" style="margin:0;">
		-->

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
  			<!--
              </form>
              -->


    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map_canvas'), {
          center: {lat: 38.9393885, lng: -77.023365},
          zoom: 12
        });
        // Call to bpacmap.js
        //initialize();
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOfx4wE7bdVp_1rW8dONgMrlI1V55Lb78&callback=initMap"
    async defer></script>

	<!-- Load all custom JavaScript -->
    <script src="js/bpacmap.js"></script>




  </body>
</html>