<?php
namespace CMSModule\user\list_activities;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateListFormFields($settings, $editable_settings);
	}
}
?>
