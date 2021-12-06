<?php
namespace CMSModule\zip\edit_zip;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateEditFormFields($settings, $editable_settings);
	}
}
?>
