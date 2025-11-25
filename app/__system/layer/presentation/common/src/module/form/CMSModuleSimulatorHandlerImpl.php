<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

namespace CMSModule\form;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		$s["actions"] = isset($s["actions"]) ? $this->prepareSimulatedActions($s["actions"]) : null;
		
		return $this->getCMSModuleHandler()->execute($s);
	}
	
	private function prepareSimulatedActions($actions) {
		if ($actions) {
			$new_actions = array();
			
			foreach ($actions as $action) {
				$action_type = isset($action["action_type"]) ? $action["action_type"] : null;
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
						$action["actions"] = isset($action["actions"]) ? $this->prepareSimulatedActions($action["actions"]) : null;
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
