<?php
namespace CMSModule\form;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$common_module_path = dirname($this->system_presentation_settings_module_path) . "/common";
		if (!is_dir($common_module_path))
			launch_exception(new \Exception("You must install the Common Module first!"));
		
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		$user_module_path = dirname($this->system_presentation_settings_module_path) . "/user";
		if (!is_dir($user_module_path))
			launch_exception(new \Exception("You must install the User Module first!"));
		
		return parent::install();
	}
}
?>
