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

	<div><input type="button" name="HideMenuID" id="HideMenuID" value="Hide Menu" style="font-size:15px; width:100%; font-weight:bold;" onClick="hideMenu(true)" /></div>

		<span class="largetitle">Raleigh BPAC Planning</span>
		<span class="smallarial"><strong> | <a href="about.php">About</a></strong></span>
       <br>
		  <span class="smallarial"><strong>Time period:</strong><br />
          
          <label><input type="checkbox" name="current" id="current" value="yes" checked="checked" onClick="updateVisibleLayers()" /><strong>Current</strong></label><label><input type="checkbox" name="future" id="future" value="yes" onClick="updateVisibleLayers()" /><strong>Future</strong></label><label><input type="checkbox" name="longTerm" id="longTerm" value="yes" onClick="updateVisibleLayers()" /><strong>Long-term</strong></label>
          
  		<br>
		<script language="javascript">
		jQuery(document).ready(function() {
			jQuery('#NearFuture').attr('disabled', 'true');
			jQuery('#LongTerm').attr('disabled', 'true');
		});
        </script>

        <div style="padding:2px 0px 2px 0px;"><img src="blackdot.gif" width="100%" height="1"></div>

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
<div>
            <span class="smallarial"><strong>Zoom to:</strong></span>
              <span class="smallarial"><a href="javascript:zoomToLevel('downtownraleigh')">Downtown</a> | <a href="javascript:zoomToLevel('raleigh')">Raleigh</a> | <a href="javascript:zoomToLevel('triangle')">Triangle</a></span>
</div>
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









	<!-- Load MAXIMIZE BUTTON DIV -->
	<div id="maximize_legend" style="position:absolute; left:71px; top:100px; border:0px; border-color:#000; border-style:solid; background-color:#FFF; width:16px; padding:0px; opacity:1.00;filter:alpha(opacity=100); visibility:hidden"><a href="javascript:minimize(false, 'legend');"><img src="images/icon_min_transparent_16_maximize.png" border="0"></a></div>

	<!-- LOAD DIRECTIONS DIV -->
	<div id="directions_panel" style="position:absolute; left:70px; top:99px; width:245px; padding:5px; overflow:hidden; border:1px; border-color:#000; border-style:solid; background-color:#FFF; opacity:0.80;filter:alpha(opacity=80); visibility:hidden;">
	</div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCOfx4wE7bdVp_1rW8dONgMrlI1V55Lb78&callback=initialize"
    async defer></script>

  <!-- Load all custom JavaScript -->
    <script src="js/bpacmap.js"></script>

  </body>
</html>
