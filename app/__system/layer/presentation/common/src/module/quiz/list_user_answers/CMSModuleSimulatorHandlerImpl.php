<?php
namespace CMSModule\quiz\list_user_answers;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateListFormFields($settings, $editable_settings);
	}
}
?>
