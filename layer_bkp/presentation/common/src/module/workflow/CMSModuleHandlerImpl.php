<?php
namespace CMSModule\workflow;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$code = $settings["code"];
		$external_vars = $settings["external_vars"];
		
		$html = \PHPScriptHandler::parseContent($code, $external_vars);
		
		return $html;
	}
}
?>
