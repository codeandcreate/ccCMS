<div id="nav" class="pure-u">
  <a href="#" class="nav-menu-button">☰</a>

  <div class="nav-inner">
    <h1><? echo $cmsInstance->settingsInstance->getParam('title'); ?></h1>

    <div class="pure-menu">
      <ul class="pure-menu-list"><?
				foreach($cmsInstance->backendModuleInstances AS $bmIndex => $bmInstance) {?>
          <li class="pure-menu-item"><?
						$moduleOptions = $bmInstance->getModuleOptions();
						if (empty($moduleOptions)) {?>
							<a href="#" class="pure-menu-link" onclick="ccCMS.openModule('<? echo $bmIndex; ?>');"><? echo $bmInstance->getModuleName(); ?></a><?
						} else {?>
								todo<?
							}?>
					</li><?
				}?>
      </ul>
    </div>
  </div>
</div><? /*


<header>
	<label for="toggleMain">
		☰
	</label>
	<h1><? echo $cmsInstance->settingsInstance->getParam('title'); ?></h1>
	<input type="checkbox" id="toggleMain">
	<nav>
		<ul class="popupMenu"><?
		foreach($cmsInstance->backendModuleInstances AS $bmIndex => $bmInstance) {?>
			<li><?
				$moduleOptions = $bmInstance->getModuleOptions();
				if (empty($moduleOptions)) {?>
					<label onclick="ccCMS.openModule('<? echo $bmIndex; ?>');"><? echo $bmInstance->getModuleName(); ?></label><?
				} else {?>
					<label for="toggle_<? echo $bmIndex; ?>"><? echo $bmInstance->getModuleName(); ?></label>
					<input type="checkbox" id="toggle_<? echo $bmIndex; ?>">
					<ul>
						<li>option</li>
					</ul><?
				}?>

			</li><?
		}
	?>
	</nav>
</header>*/
