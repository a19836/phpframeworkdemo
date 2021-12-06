<?php
namespace CMSModule\wordpress\get_html_contens;

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		
		include_once get_lib("org.phpframework.cms.wordpress.WordPressCMSBlockHandler");
		include $EVC->getConfigPath("config");
		include_once $EVC->getModulePath("wordpress/WordPressSettings", $EVC->getCommonProjectName());
		
		//Add join point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Settings parser", array(
			"EVC" => &$EVC,
			"settings" => &$settings,
		), "Change this module's settings.");
		
		//prepare blocks
		$blocks = $settings["blocks"] ? array_values($settings["blocks"]) : array();
		
		//prepare wordpress settings
		$wordpress_installation_name = $settings["wordpress_installation_name"] ? $settings["wordpress_installation_name"] : $GLOBALS["default_db_driver"];
		
		$wordpress_settings = array(
			"wordpress_folder" => $wordpress_installation_name, //db_driver name
			"wordpress_request_content_url" => "{$project_url_prefix}module/wordpress/get_html_contens/get_wordpress_content",
			"wordpress_request_content_connection_timeout" => \WordPressSettings::getConstantVariable("WORDPRESS_REQUEST_CONTENT_CONNECTION_TIMEOUT"),
			"wordpress_request_content_encryption_key" => \WordPressSettings::getConstantVariable("WORDPRESS_REQUEST_CONTENT_ENCRYPTION_KEY_HEX"),
			"cookies_prefix" => $wordpress_installation_name,
		);
		
		//prepare url_query
		$url_query = $settings["path"];
		
		//prepare wordpress_options
		$wordpress_options = array(
			"allowed_wordpress_urls" => $settings["allowed_wordpress_urls"],
			"parse_wordpress_urls" => array_key_exists("parse_wordpress_urls", $settings) ? $settings["parse_wordpress_urls"] : true,
			"parse_wordpress_relative_urls" => array_key_exists("parse_wordpress_relative_urls", $settings) ? $settings["parse_wordpress_relative_urls"] : true,
		);
		
		if ($settings["theme_type"] == "")
			$wordpress_options["phpframework_template"] = true;
		else if ($settings["theme_type"] == "wordpress_theme" && $settings["wordpress_theme"])
			$wordpress_options["phpframework_template"] = $settings["wordpress_theme"];
		
		if ($blocks)
			foreach ($blocks as $block) {
				switch ($block["block_type"]) {
					case "full_page_html": $wordpress_options["full_page_html"] = true; break; //set this bc if the full_page_html is the only block available we need to have the $wordpress_options not empty, in order to get the sidebars and menus, so we can exclude them after...
					case "header": $wordpress_options["header"] = true; break;
					case "footer": $wordpress_options["footer"] = true; break;
					case "post_title": $wordpress_options["post"]["title"] = true; break;
					case "post_content": $wordpress_options["post"]["content"] = true; break;
					case "post_comments": 
						if ($block["post_comments"] == "pretty")
							$wordpress_options["post"]["comments"]["pretty"] = array("comments_from_theme" => $block["get_directly_from_theme"]);
						else
							$wordpress_options["post"]["comments"]["raw"] = true; 
						break;
					case "widget": 
						$widget_instance = $block["widget_instance"];
						$widget_args = $block["widget_args"];
						
						if (!isset($widget_instance) && $block["widget_options"]) {
							$widget_options = $block["widget_options"];
							$id_base = $widget_options["id_base"];
							$multi_number = $widget_options["multi_number"];
							$widget_instance = $widget_options["widget-" . $id_base];
							
							if (is_array($widget_instance) && isset($multi_number)) 
								$widget_instance = $widget_instance[$multi_number];
						}
						
						$wordpress_options["widgets_display"][] = array("widget_id" => $block["widget"], "widget_instance" => $widget_instance, "widget_args" => $widget_args);
						break;
					case "side_bar": $wordpress_options["side_bars_name"][] = array("name" => $block["side_bar"], "side_bar_from_theme" => $block["get_directly_from_theme"]); break;
					case "menu": $wordpress_options["menus_name"][] = array("name" => $block["menu"], "menu_from_theme" => $block["get_directly_from_theme"]); break;
					case "menu_location": $wordpress_options["menu_locations_name"][] = array("name" => $block["menu_location"], "menu_from_theme" => $block["get_directly_from_theme"]); break;
					case "pages_list": $wordpress_options["pages_list"] = true; break;
				}
			}
		
		$block_id = $this->getCMSSetting("block_id");
		
		//Add join point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("WordPress inputs parser", array(
			"EVC" => &$EVC,
			"wordpress_settings" => &$wordpress_settings,
			"block_id" => &$block_id,
			"url_query" => &$url_query,
			"wordpress_options" => &$wordpress_options,
		), "Change the settings that will be used to call the WordPress");
		
		$WordPressCMSBlockHandler = new \WordPressCMSBlockHandler($EVC, $wordpress_settings);
		$content = $WordPressCMSBlockHandler->getBlockContent($block_id, $url_query, $wordpress_options);
		
		//Add join point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("WordPress returned content parser", array(
			"EVC" => &$EVC,
			"content" => &$content,
		), "This parser gets executed on the content that the WordPress returned, this is, when the WordPress gets called, the system converts the wordpress outputs into an array order by types of html. This parser gets executed in that array in the \$content var.");
		
		$results = $content ? $content["results"] : null;
		//error_log("\n\n<h1>results:</h1>".print_r($results, 1)."\n\n", 3, "/var/www/html/livingroop/default/tmp/test.log");
		//error_log("\n\n<h1>results[theme_menus]:</h1>".print_r($results["theme_menus"], 1)."\n\n", 3, "/var/www/html/livingroop/default/tmp/test.log");
		//prepare html
		$blocks_html = "";
		
		if ($results && $blocks) {
			$t = count($blocks);
			$widget_index = $side_bar_index = $menu_index = $menu_location_index = 0;
			
			for ($i = 0; $i < $t; $i++) {
				$block = $blocks[$i];
				$block_type = $block["block_type"];
				$block_html = "";
				$block_label_id = null;
				
				switch ($block_type) {
					case "full_page_html":
					case "header":
					case "footer":
					case "content":
						$block_html = $block_type == "content" ? $results["theme_content"] : $results[$block_type];
						
						if ($block_type == "full_page_html") { //This must happen first than the exclude side_bars or menus or comments
							if ($block["exclude_theme_before_header"] && $results["before_header"])
								$block_html = str_replace($results["before_header"], "", $block_html);
							
							if ($block["exclude_theme_header"] && $results["header"])
								$block_html = str_replace($results["header"], "", $block_html);
							
							if ($block["exclude_theme_footer"] && $results["footer"])
								$block_html = str_replace($results["footer"], "", $block_html);
							
							if ($block["exclude_theme_after_footer"] && $results["after_footer"])
								$block_html = str_replace($results["after_footer"], "", $block_html);
						}
						
						if ($block["exclude_theme_side_bars"] && $results["theme_side_bars"])
							foreach ($results["theme_side_bars"] as $side_bar_name => $side_bars_html)
								foreach ($side_bars_html as $side_bar_html)
									$block_html = str_replace($side_bar_html, "", $block_html);
						
						if ($block["exclude_theme_menus"] && $results["theme_menus"])
							foreach ($results["theme_menus"] as $menu_name => $menus_html)
								foreach ($menus_html as $menu_html)
									$block_html = str_replace($menu_html, "", $block_html);
						
						if ($block["exclude_theme_comments"] && $results["theme_comments"])
							foreach ($results["theme_comments"] as $post_id => $post_comments_html)
								foreach ($post_comments_html as $comments_html)
									$block_html = str_replace($comments_html, "", $block_html);
						
						if ($block["convert_html_into_inner_html"]) //must happen only after the exclude
							$block_html = \WordPressHacker::convertHtmlIntoInnerHtml($block_html);
							
						if ($block_type != "full_page_html" && $block["html_type"]) {
							$include_css = in_array($block["html_type"], array("only_css", "only_css_and_js", "only_content_parents_and_css", "only_content_parents_and_css_and_js"));
							$include_js = in_array($block["html_type"], array("only_js", "only_css_and_js", "only_content_parents_and_js", "only_content_parents_and_css_and_js"));
							$css_and_js_html = \WordPressHacker::getCssAndJsFromHtml($block_html, $include_css, $include_js);
							$start_comment = $end_comment = "";
							
							if (in_array($block["html_type"], array("only_content_parents", "only_content_parents_and_css", "only_content_parents_and_js", "only_content_parents_and_css_and_js")))
								$block_html = $css_and_js_html . \WordPressHacker::getContentParentsHtml($block_html, $block_type == "footer" ? "bellow" : "above");
							else
								$block_html = $css_and_js_html;
						}
						
						$block_label = ucwords(str_replace("_", " ", $block_type));
						$block_html = preg_replace("/<!--\s*phpframework:template:region:([^>]+)>/", "", $block_html);
						$block_html = '<!-- phpframework:template:region: "Before ' . $block_label . '" -->' . $block["previous_html"] . $block_html . $block["next_html"] . '<!-- phpframework:template:region: "After ' . $block_label . '" -->';
						break;
					
					case "post_title": 
					case "post_content": 
					case "post_comments": 
						if ($results["posts"])
							foreach ($results["posts"] as $post) 
								$block_html .= $this->printPostBlock($blocks, $i, $post, $final_idx);
						
						$i = $final_idx;
						break;
					
					case "widget":
						$block_label_id = $wordpress_options["widgets_display"][$widget_index]["widget_id"];
						$block_label_id = isset($block_label_id) ? $block_label_id : $widget_index;
						
						$block_html = $block["previous_html"] . $results["widgets"][$widget_index] . $block["next_html"];
						$widget_index++;
						break;
					
					case "side_bar":
						$block_label_id = $wordpress_options["side_bars_name"][$side_bar_index]["name"];
						$block_label_id = isset($block_label_id) ? $block_label_id : $side_bar_index;
						
						$block_html = $block["previous_html"] . $results["side_bars"][$side_bar_index] . $block["next_html"];
						$side_bar_index++;
						break;
					
					case "menu":
						$block_label_id = $wordpress_options["menus_name"][$menu_index]["name"];
						$block_label_id = isset($block_label_id) ? $block_label_id : $menu_index;
						
						$block_html = $block["previous_html"] . $results["menus"][$menu_index] . $block["next_html"];
						$menu_index++;
						break;
					
					case "menu_location":
						$block_label_id = $wordpress_options["menu_locations_name"][$menu_location_index]["name"];
						$block_label_id = isset($block_label_id) ? $block_label_id : $menu_location_index;
						
						$block_html = $block["previous_html"] . $results["menu_locations"][$menu_location_index] . $block["next_html"];
						$menu_location_index++;
						break;
					
					case "code":
						if (strpos($block["code"], "<?") !== false) {
							$external_vars = array("results" => $results);
							$block_html = \PHPScriptHandler::parseContent($block["code"], $external_vars);
							
							$results = $external_vars["results"]; //set new results
						}
						else
							$block_html = $block["code"];
						
						break;
					
					default:
						$block_html = $block["previous_html"] . $results[ $block_type ] . $block["next_html"];
						
						//clean block html from previous phpframework:template:region
						$block_html = preg_replace("/<!--\s*phpframework:template:region:([^>]+)>/", "", $block_html);
						
						//add new phpframework:template:region to block html
						$block_label = ucwords(str_replace("_", " ", $block_type));
						
						$block_html = '<!-- phpframework:template:region: "Before ' . $block_label . '" -->' . $block_html . '<!-- phpframework:template:region: "After ' . $block_label . '" -->';
				}
					
				$blocks_html .= $block_html;
			}
		}
		//error_log(print_r($content, 1)."\n$block_id, $url_query, ".$wordpress_settings["wordpress_folder"]."\n", 3, "/var/www/html/livingroop/default/tmp/test.log");
		
		//Add join point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Generated html blocks parser' html", array(
			"EVC" => &$EVC,
			"blocks_html" => &$blocks_html,
		), "After the WordPress gets called, this module generates the html for each block you defined and then combine it to a main html joining all that blocks' html. This parser gets executed on that main html with all the blocks' html. This main html does NOT contain the main css, js, previous and next html. Only contains the htmls from the blocks that you defined.");
		
		$html = ($settings["css"] ? '<style>' . $settings["css"] . '</style>' : '') . '
		' . ($settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '') . '
		
		<div class="module_wordpress ' . ($settings["block_class"]) . '">
		' . $settings["previous_html"] . '
		' . $blocks_html . '
		' . $settings["next_html"] . '
		</div>';
		
		//Add join point
		$EVC->getCMSLayer()->getCMSJoinPointLayer()->includeJoinPoint("Main html to be returned parser", array(
			"EVC" => &$EVC,
			"html" => &$html,
		), "This parser will execute before return the main html. This is the last action of this module! This main html contains the main previous and next html and main css and js code.");
		
		return $html;
	}
	
	private function printPostBlock($blocks, $idx, $post, &$final_idx) {
		//prepare current block
		$block = $blocks[$idx];
		$block_type = $block["block_type"];
		
		$bt = $block_type == "post_comments" ? ($block["post_comments"] == "pretty" ? "pretty" : "raw") . "_comments" : str_replace("post_", "", $block_type);
		$html = $block["previous_html"] . $post[$bt] . $block["next_html"];
		
		//prepare next block
		$available_arr = array("post_title", "post_content", "post_comments");
		$next_block = $blocks[ $idx + 1 ];
		
		$final_idx = $idx;
		
		if (in_array($next_block["block_type"], $available_arr)) {
			$idx++; //very important: increment $idx so it change in the parent function too
			$html .= $this->printPostBlock($blocks, $idx, $post, $final_idx);
		}
		
		return $html;
	}
}
?>
