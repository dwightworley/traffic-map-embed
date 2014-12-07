<!DOCTYPE html>
<html lang="en">
<head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 
    <title>Create embeddable traffic maps</title>
     
    <style>
	
    #map_canvas{
        width:100%;
        height: 30em;
		}
		

    </style>
 
</head>
<body>
 
<?php

if($_GET){
 	
    $data = geocode($_GET['address']);
	$zoom = $_GET['zoom'];
	if (!$zoom) {
	$zoom = 14;
	}
	$mapaddress = urlencode($_GET['address']);
	$mapurl = "http://[YOUR DOMAIN HERE]/embedmap.php?address={$mapaddress}&zoom={$zoom}";
	 
    // proceeds only if address is geocoded
    if($data){
         
        $latitude = $data[0];
        $longitude = $data[1];
        $address = $data[2];
		
                     
    ?>
	
    <div id="map_canvas">Geocoding address...</div><br>
    
	
	
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>    
    <script type="text/javascript">
        function init_map() {
            var options = {
                zoom: <?php echo $zoom; ?>,
                center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
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
					map.panBy(0, 70);
			
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
