<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="<? echo $cmsInstance->settingsInstance->getParam('language'); ?>"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><? echo $cmsInstance->settingsInstance->getParam('title'); ?></title>
        <meta name="description" content="<? echo $cmsInstance->settingsInstance->getParam('description'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<?
			foreach($cmsInstance->settingsInstance->getParam('css') as $cssFile) {
				echo '<link rel="stylesheet" href="/ccCMS/REST/css/' . $cssFile . '">';
			}
		?>
        <!--[if lt IE 9]>
            <script src="/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
      <div id="layout" class="content pure-g">
