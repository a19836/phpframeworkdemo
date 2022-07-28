<?php
namespace CMSModule\translator;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/TranslatorSettings.php", "TranslatorSettings.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/TranslatorUtil.php", "TranslatorUtil.php")) {
			return true;
		}
	}
}
?>
