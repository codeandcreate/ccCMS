<?php

//example to bring up the ccPHP Framework on a dynamic page
define('CMS_BASE_PATH', realpath(dirname(__FILE__) . "/../../ccCMS/"));
define('GLOBAL_CACHE_DIR', dirname(__FILE__) . "/cache/");
define('CMS_MODE', 'frontend');
define('CCPHP_CUSTOM_CLASSES_PATH', CMS_BASE_PATH . "/customized/");
include CMS_BASE_PATH . "/kernel/ccPHP/ccPHP.lib.php";

//url config:
$examplePageUrl = ["localhost", ".com"];
$_PARAM = new ccPHP_core_param($examplePageUrl[0], $examplePageUrl[1]);
// ccPHP_core_param on a custom port, for example for MAMP:
//	$_PARAM = new ccPHP_core_param($examplePageUrl[0], $examplePageUrl[1], "http", ":8888");

?>
<!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico" />
        <title>Example Page</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" type="text/css" href="/css/site.css">
        <script src="/js/html5shiv.js"></script>
    </head>
    <body>
		<pre><?

			//getting stuff from the url;
			var_dump($_PARAM->getParam());
		
			?>
		</pre>
	</body>
</html>