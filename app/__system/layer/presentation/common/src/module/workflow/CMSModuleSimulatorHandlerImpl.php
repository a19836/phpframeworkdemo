<?php
namespace CMSModule\workflow;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		return ""; //for security reasons we don't execute this module code, bc we don't know what the user wrote and if we executed without all the GET and POST variables, we might be breaking something in his side.
	}
}
?>
