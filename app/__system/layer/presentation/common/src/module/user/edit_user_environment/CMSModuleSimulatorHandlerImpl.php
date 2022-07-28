<?php
namespace CMSModule\user\edit_user_environment;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateEditFormFields($settings, $editable_settings);
	}
}
?>
