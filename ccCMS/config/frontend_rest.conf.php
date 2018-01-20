<?php

//If /REST/ - urls called by javascript from other hosts, just add the hostnames here.
$allowedACAOs = [
	SITE_URL
];

//Allowed /REST/ endpoints. Add ip addresses to the subarray if you want to limit the endpoints to certain hosts.
$allowedRest = [
	'/\/custom\/(.*)/i' => [],
];