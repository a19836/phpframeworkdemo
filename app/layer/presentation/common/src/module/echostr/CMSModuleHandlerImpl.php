<?php
namespace CMSModule\echostr;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		return $settings["str"];
	}
}
?>
