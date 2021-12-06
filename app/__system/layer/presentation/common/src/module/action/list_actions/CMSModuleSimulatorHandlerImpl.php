<?php
namespace CMSModule\action\list_actions;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateListFormFields($settings, $editable_settings);
	}
}
?>
