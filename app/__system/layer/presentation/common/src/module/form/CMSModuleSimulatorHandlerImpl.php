<?php
namespace CMSModule\form;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		$s["actions"] = $this->prepareSimulatedActions($s["actions"]);
		
		return $this->getCMSModuleHandler()->execute($s);
	}
	
	private function prepareSimulatedActions($actions) {
		if ($actions) {
			$new_actions = array();
			
			foreach ($actions as $action) {
				$action_type = $action["action_type"];
				$action["condition_type"] = "execute_always";
				
				switch ($action_type) {
					case "html":
					case "show_ok_msg":
					case "show_ok_msg_and_stop":
					case "show_ok_msg_and_die":
					case "show_error_msg":
					case "show_error_msg_and_stop":
					case "show_error_msg_and_die":
					case "draw_graph":
						$new_actions[] = $action;
						break;
					case "loop":
					case "group":
						$action["actions"] = $this->prepareSimulatedActions($action["actions"]);
						$new_actions[] = $action;
						break;
				}
			}
			
			$actions = $new_actions;
		}
		
		return $actions;
	}
}
?>
