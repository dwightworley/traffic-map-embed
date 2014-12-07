<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>Create embeddable traffic maps</title>
     
    <style>
	
	input[type=text]{
        padding:0.5em;
        width:20em;
    }
     
    input[type=submit]{
        padding:0.4em;
    }
      
    #map_canvas{
        width:100%;
        height: 30em;
		}
		
		#iframecode {
		background-color: #e5eecc;
		}

    </style>
 
</head>
<body>
 
<?php

if($_GET){
 	
    //get user-submitted address and chosen zoom level
    //no local database involved so need to sanitize the input (Google likely does this on their end)
    $data = geocode($_GET['address']);
	  $zoom = $_GET['zoom'];

    //default zoom if user does not enter one
	if (!$zoom) {
	$zoom = 14;
	}

    //encode address
	$mapaddress = urlencode($_GET['address']);

    //create iframe link with encoded address and zoom level. Link refers to a separate page that displays
    //only the map without any additional elements for better embedding.
    //you must add your domain below
	$mapurl = "http://[ADD YOUR DOMAIN HERE]/embedmap_display.php?address={$mapaddress}&zoom={$zoom}";
	 
    // proceeds only if address is geocoded
    if($data){
         
        $latitude = $data[0];
        $longitude = $data[1];
        $address = $data[2];
		
                     
    ?>
	
    <!-- google map will be shown here -->
    <div id="map_canvas">Geocoding address...</div><br>
    
	COPY AND PASTE THE ENTIRE IFRAME CODE THAT IS BOLDED BELOW<br>
	The iframe below will embed at a smaller size than the map above. If you want to change the size, after cutting and pasting the code below, edit the values for width and height. If the map is not centered exactly where you want it, look at the map for a better or more appropriate intersection and enter it to create another map. Note: in rare cases, Google may not be able to find an address or intersection. If so, try another nearby location.<br><br>
	
    <?php
    //display iframe link
	if($mapurl) {
	echo "<div id='iframecode'>";
	echo "<b>&lt;iframe width=&quot;320&quot; height=&quot;320&quot; frameborder=&quot;0&quot; style=&quot;border:0&quot; scrolling='no' src=&quot;$mapurl&quot;&gt;&lt;iframe&gt;</b>"; 
	}
	echo "</div>";
	?>
	<br><br>
	This is what the embed will look like below if you do not edit the width and height values. To create another map scroll down and enter a new address.<br>
	
    <?php

	if($mapurl) {
	echo "<iframe width='320' height='320' frameborder='0' style='border:0' scrolling='no' src=" . $mapurl . "></iframe>"; 
	}
	?>
	
    <!-- JavaScript to show google map -->
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>    
    <script type="text/javascript">
        function init_map() {
            var options = {
                zoom: <?php echo $zoom; ?>,
                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP,

                //styles code to turn off points of interest on map
				styles:
						[
							{
								featureType: "poi",
								elementType: "labels",
						stylers:
							[
								{
									visibility: "off"
								}
							]

							}

						]
							
									};
            map = new google.maps.Map(document.getElementById("map_canvas"), options);
			var trafficLayer = new google.maps.TrafficLayer();
			trafficLayer.setMap(map);
					//map.panBy(0, 60);
			
        }
        google.maps.event.addDomListener(window, 'load', init_map);
    </script>
 
    <?php
 
    // mapping address error
    }else{
        echo "Can not find address/intersection.";
    }
}
?>
 
<div>
	<H1>Traffic map embed</H1>
	<div>Enter an address, intersection, zip code, place name, point of interest or road name below to generate a traffic map of the surrounding area you can embed.</div><br>
    <div>Examples:</div>
    <div>1. 1 Gannett Drive, White Plains, NY</div>
	<div>2. Hutchinson River Parkway, New Rochelle, NY</div>
    <div>3. I-87 and 9W, West Nyack, NY</div>
	<div>4. Tappan Zee Bridge</div>
	<br>
	
</div>
 
<!-- address form -->
<form action="embedmap.php" method="get">
    <input type='text' name='address' placeholder='Enter location' /><br>
	  <input type='text' name='zoom' placeholder='Zoom level - enter a number' /> (typically a number from 8 to 14; higher numbers zoom in more)<br>
    <input type='submit' value='Submit' />
</form>
 
<?php
 
// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}
?>
 
</body>
</html>
