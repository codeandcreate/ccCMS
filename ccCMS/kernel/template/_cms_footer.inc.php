			</div><?
				foreach($cmsInstance->settingsInstance->getParam('js') as $jsFile) {
					echo '<script src="/ccCMS/REST/js/' . $jsFile . '"></script>';
				}
			?>
    </body>
</html>
