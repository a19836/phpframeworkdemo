<?php
namespace CMSModule\workflow;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$common_module_path = dirname($this->system_presentation_settings_module_path) . "/common";
		if (!is_dir($common_module_path))
			launch_exception(new \Exception("You must install the Common Module first!"));
		
		return parent::install();
	}
}
?>
