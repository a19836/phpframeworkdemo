<?php
namespace CMSModule\workflow;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$code = isset($settings["code"]) ? $settings["code"] : null;
		$external_vars = isset($settings["external_vars"]) ? $settings["external_vars"] : null;
		
		$html = \PHPScriptHandler::parseContent($code, $external_vars);
		
		return $html;
	}
}
?>
