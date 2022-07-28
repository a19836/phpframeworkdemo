<?php
namespace CMSModule\wordpress;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		return parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/WordPressSettings.php", "WordPressSettings.php");
	}
}
?>
