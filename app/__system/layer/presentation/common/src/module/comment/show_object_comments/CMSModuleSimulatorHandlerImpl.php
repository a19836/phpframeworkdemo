<?php
namespace CMSModule\comment\show_object_comments;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
