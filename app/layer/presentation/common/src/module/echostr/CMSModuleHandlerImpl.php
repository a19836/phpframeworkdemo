<?php
namespace CMSModule\echostr;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		return isset($settings["str"]) ? $settings["str"] : null;
	}
}
?>
