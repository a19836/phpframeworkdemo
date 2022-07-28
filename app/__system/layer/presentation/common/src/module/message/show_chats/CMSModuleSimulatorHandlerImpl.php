<?php
namespace CMSModule\message\show_chats;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
