<?php
namespace CMSModule\menu\list_menu_items;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return $this->simulateListFormFields($settings, $editable_settings);
	}
}
?>
