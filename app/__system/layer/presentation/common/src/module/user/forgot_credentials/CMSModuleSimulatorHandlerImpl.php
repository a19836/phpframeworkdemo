<?php
namespace CMSModule\user\forgot_credentials;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
