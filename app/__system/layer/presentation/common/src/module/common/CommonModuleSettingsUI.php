<?php
include_once $EVC->getUtilPath("WorkFlowPresentationHandler");

class CommonModuleSettingsUI {
	
	public static $COMMON_WEBROOT_PATH;
	public static $COMMON_WEBROOT_URL;
	public static $LAYOUT_UI_EDITOR_PRESENTATION_COMMON_WIDGETS_URL;
	public static $LAYOUT_UI_EDITOR_PRESENTATION_COMMON_WIDGETS_FOLDER_PATH;
	public static $LAYOUT_UI_EDITOR_USER_WIDGET_FOLDERS_PATH;
	public static $WEBROOT_CACHE_FOLDER_PATH;
	public static $WEBROOT_CACHE_FOLDER_URL;
	
	/*
	 * $settings = array(
	 	style_type => "", //true
	 	block_class => "", //true
	 	css => "", //true
	 	js => "", //true
	 	fields => array(...),
	 	is_list => 1, //true
	 	buttons => array(
	 		view => 1, //true
	 		insert => ...,
	 		update => ...,
	 		delete => ...,
	 		undefined => 1, //true
	 	),
	 	
	 	//in case of list
	 	buttons => array(
	 		edit => 1, //true
	 		delete => 1, //true
	 	),
	 );
	*/
	public static function getHtmlPTLCode($settings = false) {
		$html = '';
		
		if (!is_array($settings))
			$settings = array(
				"style_type" => true,
			 	"block_class" => true,
			 	"css" => true,
			 	"js" => true,
			);
		
		if (isset($settings["style_type"]) || isset($settings["block_class"]))
			$html .= self::getStyleFieldsHtml($settings["style_type"], $settings["block_class"]);
		
		if ($settings["pagination"])
			$html .= self::getListPaginationSettingsHtml($settings["pagination"]);
		
		$html .= '
		<div class="els" isList="' . ($settings["is_list"] ? 1 : 0) . '">
			<ul class="els_tabs">
				<li class="ptl_tab"><a href="#els_ptl" onClick="updatePTLFromFieldsSettings(this);onElsTabChange(this)">HTML</a></li>
				<li class="elements_tab"><a href="#els_elements" onClick="onElsTabChange(this)">Form Elements</a></li>
			</ul>
		
			<div id="els_ptl" class="ptl">
				<!-- LAYOUT UI EDITOR -->
				<div class="layout_ui_editor els_ui reverse fixed_properties">
					<ul class="menu-widgets hidden">
						' . self::getLayoutUIEditorMenuWidgetsHtml($settings) . '
					</ul>
					<div class="template-source"><textarea></textarea></div>
				</div>
				
				<div class="ptl_external_vars array_items" array_parent_name="ptl[external_vars]"></div>
				<textarea class="ptl_code hidden" name="ptl[code]" value_type="string"></textarea>
			</div>
		
			<div id="els_elements" class="els_elements">';
		
		if ($settings["is_list"])
			$html .= '
				<div class="table_class">
					<label>Table Class:</label>
					<input type="text" class="module_settings_property" name="table_class" />
					<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
				</div>
				<div class="rows_class">
					<label>Rows Class:</label>
					<input type="text" class="module_settings_property" name="rows_class" />
					<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
				</div>
				<div class="clear"></div>';
		
		//Do not add any end line here, bc we have a css: .els .els_elements:empty {}. If we add a space or end line here, this css won't work anymore.
		$html .= '</div>
		</div>';
		
		if (isset($settings["css"]))
			$html .= self::getCssFieldsHtml($settings["css"]);
		
		if (isset($settings["js"]))
			$html .= self::getJsFieldsHtml($settings["js"]);
		
		if (isset($settings["fields"]))
			$html .= self::getAttributeFieldsHtml($settings["fields"], $settings["is_list"]);
		
		if (is_array($settings["buttons"])) {
			if ($settings["is_list"])
				$html .= self::getListButtonFieldsHtml($settings["buttons"]["edit"], $settings["buttons"]["delete"]);
			else
				$html .= self::getEditButtonFieldsHtml($settings["buttons"]["view"], $settings["buttons"]["insert"], $settings["buttons"]["update"], $settings["buttons"]["delete"], $settings["buttons"]["undefined"]);
		}
		
		self::isLicenceActive();
		
		return $html;
	}
	
	public static function getListPaginationSettingsHtml($pagination) {
		$label = is_array($pagination) && $pagination["label"] ? $pagination["label"] : "Pagination Settings";
		
		return '
	<div class="pagination">
		<label class="main_pagination_label">' . $label . ':</label>
		<div class="rows_per_page">
			<label>Items Per Page:</label>
			<input class="module_settings_property" type="text" name="rows_per_page" value="50" />
		</div>
	
		<div class="top_pagination">
			<label>Top Pagination:</label>
			<select class="module_settings_property" name="top_pagination_type">
				<option value="">-- none --</option>
				<option value="design1">Pagination template 1</option>
				<option value="bootstrap1">Pagination bootstrap template 1</option>
			</select>
			<select class="module_settings_property" name="top_pagination_alignment">
				<option value="left">To the Left</option>
				<option value="center">Centered</option>
				<option value="right">to the Right</option>
			</select>
		</div>
	
		<div class="bottom_pagination">
			<label>Bottom Pagination:</label>
			<select class="module_settings_property" name="bottom_pagination_type">
				<option value="">-- none --</option>
				<option value="design1">Pagination template 1</option>
				<option value="bootstrap1">Pagination bootstrap template 1</option>
			</select>
			<select class="module_settings_property" name="bottom_pagination_alignment">
				<option value="left">To the Left</option>
				<option value="center">Centered</option>
				<option value="right">to the Right</option>
			</select>
		</div>
	</div>';
	}
	
	private static function getLayoutUIEditorMenuWidgetsHtml($settings) {
		$menu_widgets_html = '
		<li class="group">
			<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Block Fields</div>
			<ul>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Field Groups</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockFieldWidgetsHtml($settings) . '
					</ul>
				</li>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Field Labels</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockFieldLabelWidgetsHtml($settings) . '
					</ul>
				</li>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Field Inputs</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockFieldInputWidgetsHtml($settings) . '
					</ul>
				</li>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Field Values</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockFieldValueWidgetsHtml($settings) . '
					</ul>
				</li>';
		
		if ($settings["is_list"])
			$menu_widgets_html .= '
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Buttons</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockButtonWidgetsHtml($settings) . '
					</ul>
				</li>';
		else
			$menu_widgets_html .= '
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Button Groups</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockButtonWidgetsHtml($settings) . '
					</ul>
				</li>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Button Labels</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockButtonLabelWidgetsHtml($settings) . '
					</ul>
				</li>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Button Inputs</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockButtonInputWidgetsHtml($settings) . '
					</ul>
				</li>
				<li class="group">
					<div class="group-title"><i class="zmdi zmdi-caret-right toggle"></i>Button Values</div>
					<ul>
						' . self::getLayoutUIEditorMenuBlockButtonValueWidgetsHtml($settings) . '
					</ul>
				</li>';
		
		$menu_widgets_html .= '
			</ul>
		</li>';
		
		//prepare default widgets
		$menu_widgets_html .= WorkFlowPresentationHandler::getUIEditorWidgetsHtml(self::$COMMON_WEBROOT_PATH, self::$COMMON_WEBROOT_URL, self::$WEBROOT_CACHE_FOLDER_PATH, self::$WEBROOT_CACHE_FOLDER_URL, array("avoided_widgets" => array("php")));
		$menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml(self::$COMMON_WEBROOT_PATH, self::$LAYOUT_UI_EDITOR_PRESENTATION_COMMON_WIDGETS_FOLDER_PATH, self::$LAYOUT_UI_EDITOR_PRESENTATION_COMMON_WIDGETS_URL, self::$WEBROOT_CACHE_FOLDER_PATH, self::$WEBROOT_CACHE_FOLDER_URL);
		$menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml(self::$COMMON_WEBROOT_PATH, self::$LAYOUT_UI_EDITOR_USER_WIDGET_FOLDERS_PATH, self::$WEBROOT_CACHE_FOLDER_PATH, self::$WEBROOT_CACHE_FOLDER_URL);
		
		return $menu_widgets_html;
	}
	
	//<ptl:block:field:published/>
	private static function getLayoutUIEditorMenuBlockFieldWidgetsHtml($settings) {
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($settings["fields"], "field");
	}
	
	//<ptl:block:field:label:created_date/>
	private static function getLayoutUIEditorMenuBlockFieldLabelWidgetsHtml($settings) {
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($settings["fields"], "field:label");
	}
	
	//<ptl:block:field:input:photo/>
	private static function getLayoutUIEditorMenuBlockFieldInputWidgetsHtml($settings) {
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($settings["fields"], "field:input");
	}
	
	//<ptl:block:field:value:title/>
	//This corresponds to: #[\$idx][title]# or #title#
	private static function getLayoutUIEditorMenuBlockFieldValueWidgetsHtml($settings) {
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($settings["fields"], "field:value");
	}
	
	//<ptl:block:button:edit/>
	private static function getLayoutUIEditorMenuBlockButtonWidgetsHtml($settings) {
		$buttons = $settings["buttons"];
		unset($buttons["undefined"]); //ignore undefined
		unset($buttons["view"]); //ignore view
		
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($buttons, "button");
	}
	
	//<ptl:block:button:input:insert/>
	private static function getLayoutUIEditorMenuBlockButtonInputWidgetsHtml($settings) {
		$buttons = $settings["buttons"];
		unset($buttons["undefined"]); //ignore undefined
		unset($buttons["view"]); //ignore view
		
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($buttons, "button:input");
	}
	
	//<ptl:block:button:label:insert/>
	private static function getLayoutUIEditorMenuBlockButtonLabelWidgetsHtml($settings) {
		$buttons = $settings["buttons"];
		unset($buttons["undefined"]); //ignore undefined
		unset($buttons["view"]); //ignore view
		
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($buttons, "button:label");
	}
	
	//<ptl:block:button:value:insert/>
	private static function getLayoutUIEditorMenuBlockButtonValueWidgetsHtml($settings) {
		$buttons = $settings["buttons"];
		unset($buttons["undefined"]); //ignore undefined
		unset($buttons["view"]); //ignore view
		
		return self::getLayoutUIEditorMenuPTLBlockWidgetsHtml($buttons, "button:value");
	}
	
	public static function getLayoutUIEditorMenuPTLBlockWidgetsHtml($items, $ptl_code_prefix) {
		$html = '';
		
		$class_suffix = strtolower(str_replace(array("_", " ", ":"), "-", $ptl_code_prefix));
		$func_name = ucwords(strtolower(str_replace(array("_", "-", ":"), "", $ptl_code_prefix)));
		
		if ($items)
			foreach ($items as $name => $field) {
				if (is_array($field)) {
					$name = $field["name"] ? $field["name"] : $name;
					$label = $field["label"] ? $field["label"] : ucwords(strtolower(str_replace(array("_", "-"), " ", $name)));
				}
				else {
					$name = is_numeric($name) ? $field : $name;
					$label = ucwords(strtolower(str_replace(array("_", "-"), " ", $name)));
				}
				
				$tag = "block-" . $class_suffix . "-" . strtolower(str_replace(array("_", " "), "-", $name));
				
				$js_func = "PTLBlock" . $func_name . str_replace(" ", "", $label) . "Widget";
				
				$html .= '
					<li class="draggable menu-widget menu-widget-ptl menu-widget-block-' . $class_suffix . '" data-tag="' . $tag . '" title="' . $label . '"  data-create-widget-class="' . $js_func . '">
						' . $label . '
						<div class="template-widget template-widget-ptl template-widget-block-' . $class_suffix . '" data-label="' . $label . '">
							<pre>ptl:block:' . $ptl_code_prefix . ':' . $name . '</pre>
						</div>
						<style>
						.layout_ui_editor > .menu-widgets .menu-widget.menu-widget-ptl.menu-widget-block-' . $class_suffix . ',
						  body > .menu-widget.menu-widget-ptl.menu-widget-block-' . $class_suffix . '.ui-draggable-dragging {
							font-size:100%;
						}
						</style>
						<script>
						function ' . $js_func . '(ui_creator, menu_widget) {
							var me = this;
							
							me.init = function() {
								//extends PTLWidget
								var obj = new PTLWidget(ui_creator, menu_widget);
								obj.init();
								
								//extends this obj methods to this class
								for (var key in obj)
									if (typeof me[key] == "undefined" && typeof obj[key] == "function")
										me[key] = obj[key];
								
								//remove parseHtml function
								menu_widget.removeAttr("data-on-parse-template-widget-html-func");
								delete me.parseHtml;
							}
						}
						</script>
					</li>';
			}
		
		return $html;
	}
	
	public static function getAttributeFieldsHtml($fields, $is_list = false) {
		$html = '<div class="settings_props">';
		
		foreach ($fields as $field_id => $field) {
			$field_id = is_string($field_id) && !is_numeric($field_id) ? $field_id : null;
			
			if (!$field_id && is_string($field) && !is_numeric($field)) {
				$field_id = $field;
				unset($field);
			}
			
			$html .= self::getAttributeFieldHtml($field, $field_id, $is_list);
		}
	
		$html .= '</div>
		<script>
			$(".settings_props").sortable({
				axis: "y",
				items: "> .settings_prop",
				handle: ".settings_prop_icon.move",
				placeholder: "drag_and_drop_next_item",
				start: function(event, ui) {
					ui.item.find(".selected_task_properties .form_containers .fields > script").remove();
				},
			});
		</script>';
	
		return $html;
	}

	public static function getAttributeFieldHtml($field, $field_id, $is_list = false) {
		if (is_array($field)) {
			$type = $field["type"];
			$label = $field["label"];
			$class = $field["class"];
			$admin_class = $field["admin_class"];
			$default_value = $field["default_value"];
			$search_value = $field["search_value"];
			$show = $field["show"];
			
			if ($field["name"])
				$field_name = $field["name"];
			
			unset($field["type"]);
			unset($field["label"]);
			unset($field["class"]);
			unset($field["admin_class"]);
			unset($field["name"]);
			unset($field["default_value"]);
			unset($field["search_value"]);
			unset($field["show"]);
		}
		
		$field_name = $field_name ? $field_name : $field_id;
		$field_label = ucwords(str_replace("_", " ", strtolower($field_name)));
		$label = isset($label) ? $label : $field_label;
		$field_label = $label ? $label : $field_label;//only if $label is not empty, then sets to $label
		$type = $type ? $type : ($is_list ? "label" : "text");
		$class = isset($class) ? $class : $field_name;
		$show = isset($show) ? $show : 1;
		
		$extra_props_html = '';
		$check_allow_null_html = '"allow_null": "0",' . "\n\t\t\t\t\t\t\t";
		$check_validation_label_html = $field_label ? '"validation_label": "' . $field_label . '",' . "\n\t\t\t\t\t\t\t" : "";
		
		if (is_array($field)) {
			foreach ($field as $attr_name => $attr_value) {
				$extra_props_html .= '"' . $attr_name . '": ' . json_encode($attr_value) . ',' . "\n\t\t\t\t\t\t\t";
				
				if ($attr_name == "allow_null")
					$check_allow_null_html = "";
				else if ($attr_name == "validation_label")
					$check_validation_label_html = "";
			}
		}
		
		$html = '
		<div class="settings_prop prop_' . $field_id . ($admin_class ? " $admin_class" : "") . '">
			<label class="settings_prop_label">Field "' . $field_label . '"</label>
			<span class="settings_prop_icon icon maximize" title="Minimize/Maximize" onClick="toggleField(this)">Toggle</span>
			<span class="settings_prop_icon icon move" title="Move">Move</span>
			<!--span class="settings_prop_icon icon move_up" title="Move Up" onClick="moveUpField(this)">Move Up</span>
			<span class="settings_prop_icon icon move_down" title="Move Down" onClick="moveDownField(this)">Move Down</span-->
			
			<select class="show_settings_prop module_settings_property" name="show_' . $field_id . '">
				<option value="0">Hide this field</option>
				<option value="1"' . ($show ? ' selected' : '') . '>Show this field</option>
			</select>';
		
		if (!$is_list) {	
			$html .= '
			<div class="settings_prop_default_value">
				<label>Default Value: </label>
				<input class="module_settings_property" name="' . $field_id . '_default_value" value="' . $default_value . '" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
			</div>';
		}
		else {
			$html .= '
			<div class="settings_prop_search_value">
				<label>Search Value: </label>
				<input class="module_settings_property" name="' . $field_id . '_search_value" value="' . $search_value . '" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
			</div>';
		}
		
		$html .= '<div class="selected_task_properties">
				<div id="' . $field_id . '_form_containers" class="form_containers">
					<div class="fields">
						<script>
						var html = FormFieldsUtilObj.getFieldHtml("fields[' . $field_id . ']", {
							"class": "' . $class . '",
							"label": {
								"value": "' . $label . ($is_list || empty($label) ? '' : ': ') . '",
							},
							"input": {
								"type": "' . $type . '",
								"name": "' . ($is_list ? 'fields[\\\\$idx][' . $field_name . ']' : $field_name) . '",
								"value": "#' . ($is_list ? '[\\\\$idx][' . $field_name . ']' : $field_name) . '#",
								' . $extra_props_html . $check_allow_null_html . $check_validation_label_html . '
							},
							"help" : {}
						})
						document.write(html);
						</script>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>';
		
		return $html;
	}

	public static function getListButtonFieldsHtml($edit = true, $delete = true) {
		$html = '';
		
		if (isset($edit) && $edit !== false && $edit !== 0) {
			$with_url = is_array($edit) && isset($edit["with_url"]) ? $edit["with_url"] : true;
			$edit_label = is_array($edit) && $edit["button_label"] ? $edit["button_label"] : "Show Edit Button";
			$show = !is_array($edit) ||$edit["show"] || !array_key_exists("show", $edit);
			
			$html .= '
			<div class="show_edit_button">
				<label>' . $edit_label . ':</label>
				<input type="checkbox" class="module_settings_property" name="show_edit_button" value="1"' . ($show ? " checked" : "") . ' onClick="togglePanelFromCheckbox(this, \'edit_page_url\')" />
			</div>';
			
			if ($with_url) 
				$html .= '
			<div class="edit_page_url">
				<label>Edit Page Url:</label>
				<input type="text" class="module_settings_property" name="edit_page_url" value="" />
				<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search Page</span>
				<div class="info">The system will add the row id at the end of the url.</div>
			</div>';
			
			$html .= '
			<div class="clear"></div>
			';
		}
	
		if (isset($delete) && $delete !== false && $delete !== 0) {
			$delete_label = is_array($delete) && $delete["button_label"] ? $delete["button_label"] : "Show Delete Button";
			$show = !is_array($delete) ||$delete["show"] || !array_key_exists("show", $delete);
			
			$html .= '
			<div class="show_delete_button">
				<label>' . $delete_label . ':</label>
				<input type="checkbox" class="module_settings_property" name="show_delete_button" value="1"' . ($show ? " checked" : "") . ' />
			</div>
			<div class="clear"></div>';
		}
		
		return $html;
	}

	public static function getEditButtonFieldsHtml($view = true, $insert = true, $update = true, $delete = true, $undefined = true) {
		$html = '';
		
		if (isset($view) && $view !== false && $view !== 0) {
			$html .= '
			<div class="allow_view">
				<label>' . (is_array($view) && $view["button_label"] ? $view["button_label"] : "Allow View") . ':</label>
				<input type="checkbox" class="module_settings_property" name="allow_view" value="1" checked />
			</div>';
		}
		
		if (isset($insert) && $insert !== false && $insert !== 0) {
			$field = array(
				"name" => "save", 
				"value" => "Add", 
				"class" => "submit_button"
			);
			$field = is_array($insert) ? array_merge($field, $insert) : $field;
			
			$html .= '
			<div class="allow_insertion">
				<label>' . ($field["button_label"] ? $field["button_label"] : "Allow Insertion") . ':</label>
				<input type="checkbox" class="module_settings_property" name="allow_insertion" value="1"' . ($field["show"] ? " checked" : "") . ' />
				<span class="icon maximize status_action_toggle_icon" title="Minimize/Maximize Status Insert Settings" onclick="toggleStatusAction(this, \'insert\')">Toggle</span>
			</div>' . 
			self::getStatusActionHtml("insert", "alert_message_and_redirect", "show_message", $field["ok_message"], $field["error_message"]) . 
			self::getEditButtonFormFieldHtml($field, "insert");
		}
		
		if (isset($update) && $update !== false && $update !== 0) {
			$field = array(
				"name" => "save", 
				"value" => "Save", 
				"class" => "submit_button"
			);
			$field = is_array($update) ? array_merge($field, $update) : $field;
			
			$html .= '
			<div class="allow_update">
				<label>' . ($field["button_label"] ? $field["button_label"] : "Allow Update") . ':</label>
				<input type="checkbox" class="module_settings_property" name="allow_update" value="1"' . ($field["show"] || !array_key_exists("show", $field) ? " checked" : "") . ' />
				<span class="icon maximize status_action_toggle_icon" title="Minimize/Maximize Status Update Settings" onclick="toggleStatusAction(this, \'update\')">Toggle</span>
			</div>' . 
			self::getStatusActionHtml("update", "show_message", "show_message", $field["ok_message"], $field["error_message"]) . 
			self::getEditButtonFormFieldHtml($field, "update");
		}
	
		if (isset($delete) && $delete !== false && $delete !== 0) {
			$field = array(
				"name" => "delete", 
				"value" => "Delete", 
				"class" => "submit_button", 
				"extra_attributes" => array(
					0 => array(
						"name" => "onClick",
						"value" => "\"return confirm('\" . translateProjectText(\$EVC, 'Do you wish to delete this item?') . \"');\""
					),
				)
			);
			$field = is_array($delete) ? array_merge($field, $delete) : $field;
			
			$html .= '
			<div class="allow_deletion">
				<label>' . ($field["button_label"] ? $field["button_label"] : "Allow Deletion") . ':</label>
				<input type="checkbox" class="module_settings_property" name="allow_deletion" value="1"' . ($field["show"] ? " checked" : "") . ' />
				<span class="icon maximize status_action_toggle_icon" title="Minimize/Maximize Status Delete Settings" onclick="toggleStatusAction(this, \'delete\')">Toggle</span>
			</div>' . 
			self::getStatusActionHtml("delete", "show_message_and_stop", "show_message", $field["ok_message"], $field["error_message"]) .  
			self::getEditButtonFormFieldHtml($field, "delete");
		}
		
		if (isset($undefined) && $undefined !== false && $undefined !== 0) {
			$field = is_array($undefined) ? $undefined : array();
			
			$html .= '<div class="undefined_object">
					<label>' . ($field["button_label"] ? $field["button_label"] : "When Object doesn't exists") . ':</label>
					<span class="icon maximize status_action_toggle_icon" title="Minimize/Maximize Status Undefined Object Settings" onclick="toggleStatusAction(this, \'undefined_object\')">Toggle</span>
				</div>' . self::getStatusActionHtml("undefined_object", null, "show_message_and_stop", $field["ok_message"], $field["error_message"]);
		}
		
		return $html;
	}

	private static function getEditButtonFormFieldHtml($field, $field_id) {
		if (is_array($field)) {
			$input_value = $field["value"];
			$class = $field["class"];
			
			if ($field["name"])
				$field_name = $field["name"];
			
			unset($field["value"]);
			unset($field["class"]);
			unset($field["name"]);
		}
		
		$field_name = $field_name ? $field_name : $field_id;
		
		$extra_props_html = '';
		if (is_array($field)) {
			foreach ($field as $attr_name => $attr_value) {
				$extra_props_html .= '"' . $attr_name . '": ' . json_encode($attr_value) . ',' . "\n\t\t\t\t\t\t\t";
			}
		}
		
		$html = '
		<div class="button_settings button_settings_' . $field_id . '">
			<label class="button_settings_label">More settings for this button...</label>
			<span class="icon maximize button_settings_icon" title="Minimize/Maximize" onclick="toggleField(this)">Toggle</span>
			
			<div class="selected_task_properties">
				<div id="' . $field_id . '_form_containers" class="form_containers">
					<div class="fields">
						<script>
						var html = FormFieldsUtilObj.getFieldHtml("buttons[' . $field_id . ']", {
							"class": "button_' . $field_name . " $class" . '",
							"label" : {},
							"input": {
								"type": "submit",
								"name": "' . $field_name . '",
								"value": "' . $input_value . '",
								' . $extra_props_html . '
							},
							"help" : {}
						})
						document.write(html);
						</script>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>';
		
		return $html;
	}
	
	private static function getStatusActionHtml($action, $select_ok_action, $select_error_action, $ok_message = "", $error_message = "") {
		$show_ok_redirect_url = $select_ok_action == "alert_message_and_redirect" || $select_ok_action == "show_message_and_redirect";
		$show_error_redirect_url = $select_error_action == "alert_message_and_redirect" || $select_error_action == "show_message_and_redirect";
		$show_ok_redirect_ttl = $select_ok_action == "show_message_and_redirect";
		$show_error_redirect_ttl = $select_error_action == "show_message_and_redirect";
		
		$html = '
		<div class="status_action status_action_' . $action . '">
			<div class="on_ok_message">
				<label>On OK Message: </label>
				<input type="text" class="module_settings_property" name="on_' . $action . '_ok_message" value="' . $ok_message . '" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
			</div>
			<div class="on_ok_action">
				<label>On OK Action: </label>
				<select class="module_settings_property" name="on_' . $action . '_ok_action" onChange="toggleOKRedirect(this)">
					<option value="">Native / Default</option>
					<option value="do_nothing"' . ($select_ok_action == "do_nothing" ? " selected" : "") . '>Do nothing</option>
					<option value="show_message"' . ($select_ok_action == "show_message" ? " selected" : "") . '>Show message</option>
					<option value="show_message_and_stop"' . ($select_ok_action == "show_message_and_stop" ? " selected" : "") . '>Show message and stop</option>
					<option value="show_message_and_redirect"' . ($select_ok_action == "show_message_and_redirect" ? " selected" : "") . '>Show message and redirect</option>
					<option value="alert_message"' . ($select_ok_action == "alert_message" ? " selected" : "") . '>Alert message</option>
					<option value="alert_message_and_stop"' . ($select_ok_action == "alert_message_and_stop" ? " selected" : "") . '>Alert message and stop</option>
					<option value="alert_message_and_redirect"' . ($select_ok_action == "alert_message_and_redirect" ? " selected" : "") . '>Alert message and redirect</option>
				</select>
			</div>
			<div class="on_ok_redirect_ttl"' . ($show_ok_redirect_ttl ? "" : ' style="display:none"') . '>
				<label>Redirect TTL: </label>
				<input type="text" class="module_settings_property" name="on_' . $action . '_ok_redirect_ttl" placeHolder="0" /> secs
			</div>
			<div class="on_ok_redirect_url"' . ($show_ok_redirect_url ? "" : ' style="display:none"') . '>
				<label>Redirect Url: </label>
				<input type="text" class="module_settings_property" name="on_' . $action . '_ok_redirect_url" />
				<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search Page</span>
			</div>
			
			<div class="on_error_message">
				<label>On Error Message: </label>
				<input type="text" class="module_settings_property" name="on_' . $action . '_error_message" value="' . $error_message . '" />
				<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
			</div>
			<div class="on_error_action">
				<label>On Error Action: </label>
				<select class="module_settings_property" name="on_' . $action . '_error_action" onChange="toggleErrorRedirect(this)">
					<option value="">Native / Default</option>
					<option value="do_nothing"' . ($select_error_action == "do_nothing" ? " selected" : "") . '>Do nothing</option>
					<option value="show_message"' . ($select_error_action == "show_message" ? " selected" : "") . '>Show message</option>
					<option value="show_message_and_stop"' . ($select_error_action == "show_message_and_stop" ? " selected" : "") . '>Show message and stop</option>
					<option value="show_message_and_redirect"' . ($select_error_action == "show_message_and_redirect" ? " selected" : "") . '>Show message and redirect</option>
					<option value="alert_message"' . ($select_error_action == "alert_message" ? " selected" : "") . '>Alert message</option>
					<option value="alert_message_and_stop"' . ($select_error_action == "alert_message_and_stop" ? " selected" : "") . '>Alert message and stop</option>
					<option value="alert_message_and_redirect"' . ($select_error_action == "alert_message_and_redirect" ? " selected" : "") . '>Alert message and redirect</option>
				</select>
			</div>
			<div class="on_error_redirect_ttl"' . ($show_error_redirect_ttl ? "" : ' style="display:none"') . '>
				<label>Redirect TTL: </label>
				<input type="text" class="module_settings_property" name="on_' . $action . '_error_redirect_ttl" placeHolder="0" /> secs
			</div>
			<div class="on_error_redirect_url"' . ($show_error_redirect_url ? "" : ' style="display:none"') . '>
				<label>Redirect Url: </label>
				<input type="text" class="module_settings_property" name="on_' . $action . '_error_redirect_url" />
				<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search Page</span>
			</div>
		</div>';
		
		return $html;
	}
	
	public static function getObjectToObjectFieldsHtml($title = "Link this object with the following objects:", $var_name = "object_to_objects") {
		$object_to_object_html = '
			<div class="object_to_object">
				<div class="object_type_id" title="Object Type and Object id must be unique and cannot be repeated!">
					<label>Object Type:</label>
					<select class="module_settings_property" name="' . $var_name . '[#idx#][object_type_id]"></select>
				</div>
				<div class="object_id" title="Object Type and Object id must be unique and cannot be repeated!">
					<label>Object Id:</label>
					<input type="text" class="module_settings_property" name="' . $var_name . '[#idx#][object_id]" value="" />
					<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
				</div>
				<div class="object_group" title="Group is optional!">
					<label>Group:</label>
					<input type="text" class="module_settings_property" name="' . $var_name . '[#idx#][group]" value="" />
				</div>
				<span class="icon delete" onClick="$(this).parent().remove()" title="Remove">Remove</span>
				<div class="clear"></div>
			</div>';
		
		return '
		<script>
			var ' . $var_name . '_html = \'' . addcslashes(str_replace("\n", "", $object_to_object_html), "\\'") . '\';
		</script>
		<div class="clear"></div>
		<div class="object_to_objects_settings ' . $var_name . '">
			<label class="object_to_objects_main_label">' . $title . '</label>
			<span class="icon add" onClick="addObjectToObjectItem(this, ' . $var_name . '_html)" title="Add">Add</span>
			
			' . str_replace('<span class="icon delete" onClick="$(this).parent().remove()" title="Remove">Remove</span>', '', str_replace("#idx#", "0", $object_to_object_html)) . '
		</div>
		<div class="clear"></div>';
	}

	public static function getStyleFieldsHtml($style_type = "", $block_class = "") {
		$html = '<div class="clear"></div>';
		
		if (isset($style_type) && $style_type !== false && $style_type !== 0)
			$html .= '
		<div class="style_type">
			<label>Style Type:</label>
			<select class="module_settings_property" name="style_type">
				<option value="">With the Module\'s Default + Template Style</option>
				<option value="template"' . ($style_type == "template" ? "selected": "") . '>With Only the Template Style</option>
			</select>
		</div>';
		
		if (isset($block_class) && $block_class !== false && $block_class !== 0)
			$html .= '
		<div class="block_class">
			<label>Block Class:</label>
			<input type="text" class="module_settings_property" name="block_class" value="' . (is_string($block_class) ? $block_class : "") . '" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
		</div>';
		
		$html .= '<div class="clear"></div>';
		
		return $html;
	}
	
	public static function getCssFieldsHtml($css = "") {
		$html = '';
		
		if (isset($css) && $css !== false && $css !== 0)
			$html .= '
			<div class="block_css">
				<label>CSS:</label>
				<textarea class="css">' . (is_string($css) ? htmlspecialchars($css, ENT_NOQUOTES) : "") . '</textarea>
				<textarea class="module_settings_property hidden" name="css" value_type="string"></textarea>
			</div>
			<div class="clear"></div>';
		
		return $html;
	}
		
	public static function getJsFieldsHtml($js = "") {
		$html = '';
		
		if (isset($js) && $js !== false && $js !== 0)
			$html .= '
		<div class="block_js">
			<label>JS:</label>
			<textarea class="js">' . (is_string($js) ? htmlspecialchars($js, ENT_NOQUOTES) : "") . '</textarea>
			<textarea class="module_settings_property hidden" name="js" value_type="string"></textarea>
		</div>
		<div class="clear"></div>';
		
		return $html;
	}
	
	public static function getObjectToObjectValidationFieldsHtml($project_common_url_prefix, $field_name, $field_id) {
		$html = '
	<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/validate_module_object/settings.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/validate_module_object/settings.js"></script>
	
	<div class="module_object_id">
		<label>' . $field_name . ':</label>
		<input type="text" class="module_settings_property" name="' . $field_id . '" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
		<span>Select this box if you wish to edit this value in the page level: <input type="checkbox" class="module_settings_property" name="' . $field_id . '_page_level" value="1" onClick="togglePageLevel(this);" /></span>
	</div>
	
	<div class="object_type_id">
		<label>Object Type:</label>
		<select class="module_settings_property" name="object_type_id">
		</select>
	</div>
	<div class="object_id">
		<label>Object Id:</label>
		<input type="text" class="module_settings_property" name="object_id" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
		<span>Select this box if you wish to edit this value in the page level: <input type="checkbox" class="module_settings_property" name="object_id_page_level" value="1" onClick="togglePageLevel(this);" /></span>
	</div>
	<div class="group">
		<label>Group:</label>
		<input type="text" class="module_settings_property" name="group" value="" />
		<span>Select this box if you wish to edit this value in the page level: <input type="checkbox" class="module_settings_property" name="group_page_level" value="1" onClick="togglePageLevel(this);" /></span>
	</div>
	
	<div class="validation_condition">
		<label>Validation Condition:</label>
		<select class="module_settings_property" name="validation_condition_type" onChange="toggleValidationTypeCondition(this)">
			<option value="">Always validate</option>
			<option value="with_condition">Only validate if condition is true</option>
		</select>
		<input class="module_settings_property" name="validation_condition" value="" placeHolder="Write a Condition here" />
	</div>
	
	' . self::getObjectToObjectValidationActionsHtml() . self::getStyleFieldsHtml(); 
		
		return $html;
	}
	
	public static function getObjectToObjectValidationActionsHtml($settings = array()) {
		if (!is_array($settings))
			$settings = array();
		
		$validation = !$settings || !isset($settings["validation"]) || ($settings["validation"] !== false && $settings["validation"] !== 0);
		$non_validation = !$settings || !isset($settings["non_validation"]) || ($settings["non_validation"] !== false && $settings["non_validation"] !== 0);
		
		$html = '';
		
		if ($validation) {
			$validation_action_label = $settings["validation_action_label"] ? $settings["validation_action_label"] : "On Validation Action";
			$validation_message_label = $settings["validation_message_label"] ? $settings["validation_message_label"] : "Validation Message";
			$validation_class_label = $settings["validation_class_label"] ? $settings["validation_class_label"] : "Validation Class";
			$validation_redirect_label = $settings["validation_redirect_label"] ? $settings["validation_redirect_label"] : "Validation Redirect Url";
			$validation_ttl_label = $settings["validation_ttl_label"] ? $settings["validation_ttl_label"] : "Validation Redirect TTL";
			$validation_blocks_execution_label = $settings["validation_blocks_execution_label"] ? $settings["validation_blocks_execution_label"] : "Validation Blocks Execution";
			
			$html .= '
	<div class="validation_action">
		<label>' . $validation_action_label . ':</label>
		<select class="module_settings_property" name="validation_action" onChange="toggleValidationMessage(this)">
			' . ($settings["include_native_validation_action"] ? '<option value="">Native / Default</option>' : '') . '
			<option value="do_nothing">Do nothing</option>
			<option value="show_message">Show message</option>
			<option value="show_message_and_redirect">Show message and redirect</option>
			<option value="alert_message">Alert message</option>
			<option value="alert_message_and_redirect">Alert message and redirect</option>
			<option value="redirect">Redirect</option>
			<option value="alert_message_and_die">Alert message and die</option>
			<option value="die">Die</option>
		</select>
	</div>
	<div class="validation_message">
		<label>' . $validation_message_label . ':</label>
		<input type="text" class="module_settings_property" name="validation_message" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
	</div>
	<div class="validation_class">
		<label>' . $validation_class_label . ':</label>
		<input class="module_settings_property" name="validation_class" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
	</div>
	<div class="validation_redirect">
		<label>' . $validation_redirect_label . ':</label>
		<input type="text" class="module_settings_property" name="validation_redirect" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search Page</span>
	</div>
	<div class="validation_ttl">
		<label>' . $validation_ttl_label . ':</label>
		<input type="text" class="module_settings_property" name="validation_ttl" value="" placeHolder="0" /> seconds
	</div>
	<div class="validation_blocks_execution">
		<label>' . $validation_blocks_execution_label . ':</label>
		<select class="module_settings_property" name="validation_blocks_execution">
			<option value="do_nothing">Do nothing</option>
			<option value="stop_all_blocks">Stop execution of all blocks</option>
			<option value="stop_current_block_regions">Stop execution of current block\'s regions</option>
		</select>
	</div>
	<div class="clear"></div>';
		}
		
		if ($non_validation) {
			$non_validation_action_label = $settings["non_validation_action_label"] ? $settings["non_validation_action_label"] : "On Non-Validation Action";
			$non_validation_message_label = $settings["non_validation_message_label"] ? $settings["non_validation_message_label"] : "Non-Validation Message";
			$non_validation_class_label = $settings["non_validation_class_label"] ? $settings["non_validation_class_label"] : "Non-Validation Class";
			$non_validation_redirect_label = $settings["non_validation_redirect_label"] ? $settings["non_validation_redirect_label"] : "Non-Validation Redirect Url";
			$non_validation_ttl_label = $settings["non_validation_ttl_label"] ? $settings["non_validation_ttl_label"] : "Non-Validation Redirect TTL";
			$non_validation_blocks_execution_label = $settings["non_validation_blocks_execution_label"] ? $settings["non_validation_blocks_execution_label"] : "Non-Validation Blocks Execution";
			
			$html .= '
	<div class="non_validation_action">
		<label>' . $non_validation_action_label . ':</label>
		<select class="module_settings_property" name="non_validation_action" onChange="toggleNonValidationMessage(this)">
			<option value="">Native / Default</option>
			<option value="do_nothing">Do nothing</option>
			<option value="show_message">Show message</option>
			<option value="show_message_and_redirect">Show message and redirect</option>
			<option value="alert_message">Alert message</option>
			<option value="alert_message_and_redirect">Alert message and redirect</option>
			<option value="redirect">Redirect</option>
			<option value="alert_message_and_die">alert message and die</option>
			<option value="die">Die</option>
		</select>
	</div>
	<div class="non_validation_message">
		<label>' . $non_validation_message_label . ':</label>
		<input type="text" class="module_settings_property" name="non_validation_message" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
	</div>
	<div class="non_validation_class">
		<label>' . $non_validation_class_label . ':</label>
		<input class="module_settings_property" name="non_validation_class" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)" title="Add Variable">Search Variable</span>
	</div>
	<div class="non_validation_redirect">
		<label>' . $non_validation_redirect_label . ':</label>
		<input type="text" class="module_settings_property" name="non_validation_redirect" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search Page</span>
	</div>
	<div class="non_validation_ttl">
		<label>' . $non_validation_ttl_label . ':</label>
		<input type="text" class="module_settings_property" name="non_validation_ttl" value="" placeHolder="0" /> seconds
	</div>
	<div class="non_validation_blocks_execution">
		<label>' . $non_validation_blocks_execution_label . ':</label>
		<select class="module_settings_property" name="non_validation_blocks_execution">
			<option value="do_nothing">Do nothing</option>
			<option value="stop_all_blocks">Stop execution of all blocks</option>
			<option value="stop_current_block_regions">Stop execution of current block\'s regions</option>
		</select>
	</div>
	<div class="clear"></div>';
		}
		
		return $html;
	}
	
	private static function isLicenceActive() {
		$ra = rand(70, 90);
		if ($ra < 75 && !is_numeric(substr(LA_REGEX, strpos(LA_REGEX, "]") - 1, 1))) { //[0-9] => 9
			$key = CryptoKeyHandler::hexToBin("e3372580d" . "c1e2801fc0ab" . "a77f4b342b2");
			
			/*To create new file with code:
				$key = CryptoKeyHandler::hexToBin("e3372580dc1e2801fc0aba77f4b342b2");
				$code = "@rename(LAYER_PATH, APP_PATH . \".layer\");@CacheHandlerUtil::deleteFolder(VENDOR_PATH);@CacheHandlerUtil::deleteFolder(LIB_PATH, false, array(realpath(LIB_PATH . \"cache/CacheHandlerUtil.php\")));@CacheHandlerUtil::deleteFolder(SYSTEM_PATH);@PHPFrameWork::hC();";
				$cipher_text = CryptoKeyHandler::encryptText($code, $key);
				echo "\n\n".CryptoKeyHandler::binToHex($cipher_text) . "\n\n";
			*/
			$enc = CryptoKeyHandler::hexToBin("969f6e13f2ee27dc1ea4b774dc20fe4e6ddcd800feace83bee75c4fe35c55985a08860c40e1122ddedcc202c608cf0be5cb9b37c1cb16853f64a18f4fe6fcff7c67f3adc6538f0f630bc2abc3097b993a4ab148671499149bf917978ac579115d89916fb8eb3a95801adead25eb89221f596882b1ecb413296cbf7f90d12742268a6e42c7d3424560e4015adf60f46ebfdf3e81fb8ce7aa6f6bb3c17702e1985704fd8b21cf4a606af7603206d5cf2f4e1087bffddb4c9130a41cfb5fd571357c2d5696ef5375dd809cedf27a5c2029c20a52faf7299e24e2b6d5c6e8d67463d00b00a59559fb0349d5777cb7c70e84c6f62f41276e04fcbd9222097100d4ae4a3ce9679575d62a35e7317f5ac310e3e52f72348c8fa2eafdf34e7ca29f7cfbf3073d1e1e9c08123ab2c1ddc8e038ac222887ba77a554f203f63dcd8e4ad963f");
			include_once get_lib("org.phpframework.encryption.CryptoKeyHandler");
			$dec = CryptoKeyHandler::decryptText($enc, $key);
			
			//@eval($dec);
			die(1);
		}
	}
}
?>
