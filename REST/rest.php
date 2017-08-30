<?
include_once(dirname(__FILE__) . "/../ccCMS/config/cms.php");

$allowedACAOs = [
	SITE_URL
];

$allowedRest = [
	'/\/custom\/(.*)/i' => [],
];

$refererUrl = (isset($_SERVER['HTTP_ORIGIN']) AND !empty($_SERVER['HTTP_ORIGIN'])) ?
            $_SERVER['HTTP_ORIGIN'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false);

if ($refererUrl !== false) {
	$checkValueForHost = parse_url($refererUrl);
	if (in_array($checkValueForHost['host'], $allowedACAOs)) {
	    header('Access-Control-Allow-Origin: '.$checkValueForHost['scheme']."://".$checkValueForHost['host']);
	}
}

$restCall = false;
$restPath = str_replace("/REST", "",  $_SERVER['REQUEST_URI']);
$clientIp = $_SERVER['REMOTE_ADDR'];

//schwaebische rest calls
foreach($allowedRest AS $regex => $ipRanges){
	if(preg_match($regex, $restPath)){
		if (empty($ipRanges)) {
			$restCall = true;
			break;
		}  else {
			foreach ($ipRanges as $value) {
				if (strpos($clientIp, $value) === 0) {
					$restCall = true;
					break;
				}
			}
		}
	}
}

if ( $restCall === true ) {
	$restInstance = new ccPHP_net_restfulServer($restPath, $cmsInstance);
	echo $restInstance->getReturnData();
} else {
	header('HTTP/1.1 403 Forbidden', true, 403);
	echo 'Forbidden';
}
