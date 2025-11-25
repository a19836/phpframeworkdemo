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

namespace CMSModule\menu\show_menu;

class CMSModuleSimulatorHandlerImpl extends \CMSModuleSimulatorHandler {
	
	public function simulate(&$settings = false, &$editable_settings = false) {
		$s = $settings;
		
		if (empty($s["items_type"]) && empty($s["template_type"]))
			$editable_settings = array(
				"elements" => array(
					".module_menu > ul.module_menu_ul li.module_menu_li > a > label" => "xxx",
				),
				"handlers" => array(
					"on_prepare_post_data" => "prepareModuleMenuPostDataWithRightMenuItemPath"
				),
				"js" => '
					function prepareModuleMenuPostDataWithRightMenuItemPath(elm, post_data) {
						var li = elm.closest("li");
						var path = "";
						
						do {
							var ul = li.parentNode;
							var is_main_node = ul.classList.contains("module_menu_ul");
							var path_index = 0;
							
							for (var i = 0; i < ul.childNodes.length; i++) {
								if (ul.childNodes[i] == li) 
									break;
								
								path_index++;
							}
							
							if (path)
								path = "/" + path_index + "/menus" + path;
							else
								path = "/" + path_index + "/label" + path;
							
							li = ul.closest("li");
						}
						while (ul && !is_main_node);
						
						if (path) {
							path = "menus" + path;
							post_data["setting_path"] = path;
						}
						
						return post_data;
					}
				'
			);
		
		return $this->getCMSModuleHandler()->execute($s);
	}
}
?>
