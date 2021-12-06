<?php
namespace CMSModule\event\edit_object_event;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateEditFormFields($settings, $editable_settings);
	}
}
?>
