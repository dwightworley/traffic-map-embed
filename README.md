traffic-map-embed
=================

Easily create embeddable Google traffic maps for blogs and websites.

This is an easy solution for news organizations and others to quickly create embeddable traffic maps to display on their blogs and websites. This is useful during traffic accidents, tie-ups or severe weather to show traffic congestion. It builds on the excellent work of Mike Dalisay at the codeofaninja.com blog and uses code from his Google Maps Geocoding for PHP tutorial at https://www.codeofaninja.com/2014/06/google-maps-geocoding-example-php.html.

embedmap.php is the primary file. embedmap_display.php is just used for the iframe.

<b>LIVE DEMO</b>: http://www.dwightworley.net/projectexamples/traffic_embed/embedmap.php

<b>MY CHANGES/ADDITIONS:</b> 

I added:

Code to show the traffic layer on the map.
Code to allow the user to change the zoom level of the resulting map.
Code to dynamically create the iframe link.
Code to remove POIs from map.
An additional page (embedmap_display.php). The iframe link refers to this page, which contains just the map with no other elements for better embedding.

I changed:

Removed the marker from the map.
Changed the form collection method from POST to GET. Since a link is created from the address and zoom level it’s useful to be able to see the values in the address bar.

<b>NOTES:</b> Google places rate limits on its free maps product. If you are going to use this on a website with a high volume of traffic, you will want to update the code to use your API key to ensure you aren’t cut off.

This is not meant for external use by readers or visitors to your website. The purpose is to produce an iframe link of a traffic map that you can add to your website or blog.

I created this for a former employer and also built a version that uses traffic cameras in some New York, New Jersey and Connecticut locations. If you’re interested in viewing with traffic cameras in those areas, you can take a look at that page and its page source: http://data.lohud.com/embedcams.php.

Also, you are advised to upload the files to your server and add your domain name where indicated. While you are free to use it on my domain, if many people do so you are likely to hit any rate limits sooner since all requests are coming from a single domain.
