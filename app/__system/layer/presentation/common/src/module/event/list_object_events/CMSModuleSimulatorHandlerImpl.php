<?php
namespace CMSModule\event\list_object_events;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateListFormFields($settings, $editable_settings);
	}
}
?>
