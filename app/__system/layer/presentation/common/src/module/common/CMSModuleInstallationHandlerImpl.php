<?php
namespace CMSModule\common;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$translator_module_path = dirname($this->system_presentation_settings_module_path) . "/translator";
		if (!is_dir($translator_module_path))
			launch_exception(new \Exception("You must install the Translator Module first!"));
		
		return parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/CommonSettings.php", "CommonSettings.php") && $this->copyUnzippedFileToPresentationWebrootFolder("system_settings/webroot/module.css", "module.css") && $this->copyUnzippedFileToPresentationWebrootFolder("system_settings/webroot/module.js", "module.js");
	}
}
?>
