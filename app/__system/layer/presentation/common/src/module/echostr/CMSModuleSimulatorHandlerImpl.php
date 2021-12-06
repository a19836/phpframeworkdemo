<?php
namespace CMSModule\echostr;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		
		$editable_settings = array(
			"elements" => array(
				"" => "str",
			)
		);
		
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
