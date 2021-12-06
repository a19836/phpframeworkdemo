<?php
namespace CMSModule\zip\edit_zone;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateEditFormFields($settings, $editable_settings);
	}
}
?>
