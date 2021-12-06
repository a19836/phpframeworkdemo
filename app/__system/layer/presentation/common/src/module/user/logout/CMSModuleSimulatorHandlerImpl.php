<?php
namespace CMSModule\user\logout;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		
		$editable_settings = array(
			"elements" => array(
				".validation_message" => "validation_message",
				".non_validation_message" => "non_validation_message",
			)
		);
		
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
