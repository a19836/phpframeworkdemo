<?php
include_once get_lib("org.phpframework.db.DB");
include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationBLNamespaceHandler");
include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSProgramExtraTableInstallationUtil");
include_once $EVC->getUtilPath("WorkFlowBeansFileHandler");
include_once $EVC->getUtilPath("WorkFlowBusinessLogicHandler");
include_once $EVC->getUtilPath("FlushCacheHandler");

class CommonModuleAdminTableExtraAttributesUtil {
	private $EVC;
	private $PEVC;
	private $module_path;
	private $user_global_variables_file_path;
	private $user_beans_folder_path;
	private $brokers;
	private $default_db_driver;
	private $main_attributes_table_name;
	private $extra_attributes_table_name;
	private $extra_attributes_table_alias;
	
	private $group_module_id;
	private $project_url_prefix;
	private $project_common_url_prefix;
	private $db_driver;
	private $available_tables = array();
	private $main_attributes = array();
	private $main_pks = array();
	private $extra_attributes = array();
	private $extra_pks = array();
	private $errors = array();
	private $sql_statements = array();
	private $sql_statements_labels = array();
	private $saved_data = array();
	private $step = array();
	private $error_message;
	private $status_message;
	
	private $layers;
	private $presentation_module_path;
	private $business_logic_module_paths;
	private $ibatis_module_paths;
	private $hibernate_module_paths;
	
	public function __construct($EVC, $PEVC, $module_path, $user_global_variables_file_path, $user_beans_folder_path, $default_db_driver, $main_attributes_table_name, $main_attributes_table_alias = false, $extra_attributes_table_name = false, $extra_attributes_table_alias = false) {
		$this->EVC = $EVC;
		$this->PEVC = $PEVC;
		$this->module_path = $module_path;
		$this->user_global_variables_file_path = $user_global_variables_file_path;
		$this->user_beans_folder_path = $user_beans_folder_path;
		$this->brokers = $PEVC->getPresentationLayer()->getBrokers();
		$this->default_db_driver = $default_db_driver;
		$this->main_attributes_table_name = $main_attributes_table_name;
		$main_attributes_table_alias = $main_attributes_table_alias ? $main_attributes_table_alias : $main_attributes_table_name;
		$this->extra_attributes_table_name = $extra_attributes_table_name ? $extra_attributes_table_name : $main_attributes_table_name . "_" . CMSProgramExtraTableInstallationUtil::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX;
		$this->extra_attributes_table_alias = $extra_attributes_table_alias ? $extra_attributes_table_alias : $main_attributes_table_alias . "_" . CMSProgramExtraTableInstallationUtil::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX;
		
		$this->initGroupModuleId();
		$this->initLayersPaths();
		$this->initUrlPrefixes();
		
		$this->db_driver = $this->getDBDriver();
		
		if (!$this->db_driver)
			throw new Exception("DB Driver could not be found in this module!");
		
		$this->loadData();
	}
	
	public function getHead() {
		$column_types_ignored_props = $this->db_driver->getDBColumnTypesIgnoredProps();
		$valid_allow_javascript_types = $this->db_driver->getDBColumnTextTypes();
		
		$column_types_ignored_props = is_array($column_types_ignored_props) ? $column_types_ignored_props : array();
		
		$admin_common_url = $this->project_common_url_prefix . "module/" . $this->EVC->getCommonProjectName() . "/";
		
		$html = '
		<!-- Add ACE Editor JS files -->
		<script src="' . $this->project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
		<script src="' . $this->project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
		
		<!-- Add Fontawsome Icons CSS -->
		<link rel="stylesheet" href="' . $this->project_common_url_prefix . 'vendor/fontawesome/css/all.min.css">
		
		<!-- Add Icons CSS files -->
		<link rel="stylesheet" href="' . $this->project_url_prefix . 'css/icons.css" type="text/css" charset="utf-8" />
		
		<!-- Add Globals JS files -->
		<script language="javascript" type="text/javascript" src="' . $this->project_common_url_prefix . 'js/global.js"></script>

		<!-- Add Local JS and CSS files -->
		<link rel="stylesheet" href="' . $admin_common_url . 'admin.css" type="text/css" charset="utf-8" />
		
		<link rel="stylesheet" href="' . $this->project_url_prefix . 'css/db/edit_table.css" type="text/css" charset="utf-8" />
		<script language="javascript" type="text/javascript" src="' . $this->project_url_prefix . 'js/db/edit_table.js"></script>
		
		<link rel="stylesheet" href="' . $admin_common_url . 'manage_table_extra_attributes.css" type="text/css" charset="utf-8" />
		<script language="javascript" type="text/javascript" src="' . $admin_common_url . 'manage_table_extra_attributes.js"></script>

		<script>
		var column_types_ignored_props = ' . json_encode($column_types_ignored_props) . ';
		var valid_allow_javascript_types = ' . json_encode($valid_allow_javascript_types) . ';

		var attribute_html = \'' . str_replace("'", "\\'", str_replace("\n", "", $this->getTableExtraAttributeHtml("#idx#"))) . '\';
		var step = ' . ($this->step ? $this->step : 0) . ';
		</script>';
		
		return $html;
	}
	
	public function getContent() {
		$column_types_hidden_props = $this->db_driver->getDBColumnTypesHiddenProps();
		
		$html = '
	<div class="manage_table_exta_attributes edit_table">
		<h3>Table Settings <a class="icon refresh" href="javascript:void(0);" onClick="document.location=document.location+\'\';" title="Refresh">Refresh</a></h3>
		<div class="table_settings">
			<form method="post">
				<input type="hidden" name="step" value="1"/>
				
				<div class="attributes">
					<label>Attributes for table: "' . $this->extra_attributes_table_name . '" <a class="icon add" onClick="addTableAttribute(this)" title="Add">Add</a></label>
				</div>
				
				<table>
					<thead>
						<tr>
							<th class="table_attr_name table_header">Name</th>
							<th class="table_attr_type table_header">Type</th>
							<th class="table_attr_length table_header"' . (in_array("length", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Length</th>
							<th class="table_attr_null table_header"' . (in_array("null", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Null</th>
							<th class="table_attr_unsigned table_header"' . (in_array("unsigned", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Unsigned</th>
							<th class="table_attr_unique table_header"' . (in_array("unique", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Unique</th>
							<th class="table_attr_auto_increment table_header"' . (in_array("auto_increment", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Auto Increment</th>
							<th class="table_attr_allow_javascript table_header">Allow Javascript</th>
							<th colspan="2" class="table_attr_default table_header"' . (in_array("default", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Default</th>
							<th class="table_attr_extra table_header"' . (in_array("extra", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Extra</th>
							<th class="table_attr_charset table_header"' . (in_array("charset", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Charset</th>
							<th class="table_attr_collation table_header"' . (in_array("collation", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Collation</th>
							<th class="table_attr_file_type table_header">File Type</th>
							<th class="table_attr_comment table_header"' . (in_array("comment", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>Comments</th>
							<th class="table_attr_icons">
								<a class="icon add" onClick="addTableAttribute(this)" title="Add">Add</i></a>
							</th>
						</tr>
					</thead>
					<tbody index_prefix="attributes">';

		if ($this->extra_attributes)
			foreach ($this->extra_attributes as $idx => $attr)
				$html .= $this->getTableExtraAttributeHtml($idx, $attr);
		
		$colspan = 15 - count($column_types_hidden_props);
		
		$html .= '		<tr class="no_attributes"' . ($this->extra_attributes ? ' style="display:none"' : '') . '><td colspan="' . $colspan . '">No extra attributes added yet...</td></tr>
					</tbody>
				</table>
				
				<div class="buttons">
					<div class="submit_button submit_button_save">
						<input type="submit" name="save" value="SAVE" />
					</div>
				</div>
			</form>
		</div>
		
		<h3>Table SQLs</h3>
		<div class="table_sql_statements">
			<form method="post">
				<input type="hidden" name="step" value="2"/>
				<textarea class="hidden" name="data">' . json_encode($this->saved_data) . '</textarea>
				';
			
		if ($this->sql_statements) {
			foreach ($this->sql_statements as $idx => $sql)
				$html .= '<div class="sql_statement">
					<label>' . $this->sql_statements_labels[$idx] . '</label>
					<textarea class="hidden" name="sql_statements[]">' . htmlspecialchars($sql, ENT_NOQUOTES) . '</textarea>
					<textarea class="editor">' . htmlspecialchars($sql, ENT_NOQUOTES) . '</textarea>
				</div>';
			
			$html .= '		
				<div class="save_button">
					<input class="back" type="button" name="back" value="BACK" onClick="return onBackButton(this, 0);" />
					<input class="execute" type="submit" name="execute" value="EXECUTE" onClick="return onExecuteButton(this);" />
				</div>';
		}
		else 
			$html .= '<div>' . $this->status_message . '</div>		
				<div class="save_button">
					<input class="back" type="button" name="back" value="BACK" onClick="return onBackButton(this, 0);" />
				</div>';
		
		$html .= '
			</form>
		</div>
		
		<h3>Execution Errors</h3>
		<div class="table_errors">';
		
		if ($this->error_message)
			$html .= '<div class="module_error_message">' . $this->error_message . ($this->errors ? '<br/>Please see errors bellow...' : '') . '</div>';
		else if ($this->status_message)
			$html .= '<div class="module_status_message">' . $this->status_message . '</div>
			
			<div class="info">
				Note that in case of have Layers remotely installed, this is, Layers that are not locally installed and are remotely accessable, and if you wish to access this new data from these Layers, you must then, upload the following files individually into that Layers too:<br/>
				<ul>
					<li>Upload the <a href="?' . $_SERVER["QUERY_STRING"] . '&download=businesslogic&file=generic" target="_blank">' . $this->getGenericExtraAttributesTableObjectName() . 'Service.php</a> and <a href="?' . $_SERVER["QUERY_STRING"] . '&download=businesslogic" target="_blank">' . $this->getExtraAttributesTableObjectName() . 'Service.php</a> files to your remotely Business-Logic Layers</li>
					<li>For remotely Ibatis Data-Access Layers, you should upload the <a href="?' . $_SERVER["QUERY_STRING"] . '&download=ibatis" target="_blank">' . $this->getExtraAttributesTableQueryName() . '.xml</a></li>
					<li>For remotely Hibernate Data-Access Layers, you should upload the <a href="?' . $_SERVER["QUERY_STRING"] . '&download=hibernate" target="_blank">' . $this->getExtraAttributesTableQueryName() . '.xml</a></li>
				</ul>
			</div>';
		
		if ($this->errors)
			$html .= '<div class="errors">
				<label>Errors:</label>
				<ul>
					<li>' . implode('</li><li>', $this->errors) . '</li>
				</ul>
			</div>';
		
		$html .= '
			<div class="save_button">
				<input class="back" type="button" name="back" value="BACK" onClick="return onBackButton(this, 1);" />
			</div>
		</div>';
		
		$html .= '
	</div>';
		
		return $html;
	}
	
	public function saveData($post_data) {
		$table_attrs = $this->extra_attributes;
		
		$this->step = $post_data["step"] ? $post_data["step"] : 1;
		
		if ($this->step >= 2) {
			$this->sql_statements = $post_data["sql_statements"];
			$this->saved_data = json_decode($post_data["data"], true);
			$this->errors = array();
			
			if ($this->sql_statements)
				foreach ($this->sql_statements as $idx => $sql)
					if (!$sql)
						unset($this->sql_statements[$idx]);
			
			if (!$this->sql_statements) 
				$this->error_message = "No sql to execute!";
			else {
				foreach ($this->sql_statements as $sql) {
					$e = $this->db_driver->setData($sql);
				
					if ($e !== true)
						$this->errors[] = (is_a($e, "Exception") ? $e-getMessage() . "\n\n" : "") . $sql;
				}
				
				$changed = false;
				
				if (!$this->errors && !$this->saveDataToFile($this->saved_data, $changed))
					$this->errors[] = "Could not save attributes settings to files!";
				
				//flush cache bc of the cached rules and services
				$this->flushCache();
				
				if ($this->errors)
					$this->error_message = "There were some errors trying to update this table.";
				else
					$this->status_message = $changed ? "Changes made successfully!" : "No changes to be made!";
			}
		}
		else if ($this->step == 1) {
			$data = $post_data;
			
			//remove POST buttons
			unset($data["save"]);
			unset($data["step"]);
			//echo "<pre>";print_r($data);die();
			//echo "<pre>";print_r($data["attributes"][0]);die();
			
			$sql_options = $this->db_driver->getOptions();
			$this->sql_statements = array();
			$this->sql_statements_labels = array();
			
			$attributes_to_add = array();
			$attributes_to_modify = array();
			$attributes_to_rename = array();
			$attributes_to_delete = array();
			$changed = false;
			
			//check first if table exists in DB and if not create it
			if (!$this->db_driver->isTableInNamesList($this->available_tables, $this->extra_attributes_table_name)) {
				$changed = true;
				
				$this->extra_pks = $this->main_pks; //set the extra_pks, bc they were not set yet!
				$new_attributes = array_values($this->extra_pks);
				$new_attributes = $data["attributes"] ? array_merge($new_attributes, $data["attributes"]) : $new_attributes;
				
				$table_data = array(
					"table_name" => $this->extra_attributes_table_name, 
					"attributes" => $new_attributes,
				);
				$this->sql_statements[] = $this->db_driver->getCreateTableStatement($table_data, $sql_options);
				$this->sql_statements_labels[] = "Create table " . $table_data["table_name"];
				/*$e = $this->db_driver->setData($sql);
				
				if ($e !== true)
					$this->errors[] = (is_a($e, "Exception") ? $e-getMessage() . "\n\n" : "") . $sql;*/
			}
			else { //get attributes to add and modify
				$column_types_ignored_props = $this->db_driver->getDBColumnTypesIgnoredProps();
			
				if ($data["attributes"]) {
					foreach ($data["attributes"] as $idx => $new_attr) {
						$new_attr["name"] = $data["attributes"][$idx]["name"] = trim($new_attr["name"]);
						
						if ($new_attr["name"] && !array_key_exists($new_attr["name"], $this->extra_pks) && !array_key_exists($new_attr["name"], $this->main_attributes)) {
							$exists = false;
							$is_different = false;
							
							if ($table_attrs)
								foreach ($table_attrs as $old_attr)
									if (strtolower($new_attr["old_name"]) == strtolower($old_attr["name"])) {
										$exists = true;
										
										if ($new_attr["name"] != $old_attr["name"]) //in case the user change the case of some letter.
											$attributes_to_rename[ $old_attr["name"] ] = $new_attr["name"];
										 
										//prepare new name with old_name, just in case the user changed the lettering case.
										$new_attr["name"] = $old_attr["name"];
										
										//prepare non-editable attributes in $new_attr. Sets the defaults from $old_attr.
										if (is_array($column_types_ignored_props[ $old_attr["type"] ]))
											foreach ($column_types_ignored_props[ $old_attr["type"] ] as $attr_to_ignore)
												$new_attr[$attr_to_ignore] = $old_attr[$attr_to_ignore];
										
										//check if there is something different
										if ($new_attr["type"] != $old_attr["type"] || $new_attr["length"] != $old_attr["length"] || $new_attr["null"] != $old_attr["null"] || $new_attr["unique"] != $old_attr["unique"] || $new_attr["auto_increment"] != $old_attr["auto_increment"] || $new_attr["unsigned"] != $old_attr["unsigned"] || $new_attr["default"] != $old_attr["default"] || $new_attr["extra"] != $old_attr["extra"] || ($new_attr["charset"] && $new_attr["charset"] != $old_attr["charset"]) || ($new_attr["collation"] && $new_attr["collation"] != $old_attr["collation"]) || $new_attr["comment"] != $old_attr["comment"])
											$is_different = true;
										
										break;
									}
							
							if (!$exists)
								$attributes_to_add[] = $new_attr;
							else if ($is_different)
								$attributes_to_modify[] = $new_attr;
						}
						else
							unset($data["attributes"][$idx]);
					}
				}
				
				//get attributes to delete
				if ($table_attrs)
					foreach ($table_attrs as $old_attr) {
						$exists = false;
						
						if ($data["attributes"])
							foreach ($data["attributes"] as $new_attr) 
								if (strtolower($new_attr["old_name"]) == strtolower($old_attr["name"])) {
									$exists = true;
									break;
								}
						
						if (!$exists)
							$attributes_to_delete[] = $old_attr;
					}
				
				//update attributes
				if ($attributes_to_add || $attributes_to_modify || $attributes_to_rename || $attributes_to_delete) {
					$changed = true;
					
					//echo "<pre>attributes_to_add:".print_r($attributes_to_add, 1)."\nattributes_to_modify:".print_r($attributes_to_modify, 1)."\nattributes_to_rename:".print_r($attributes_to_rename, 1)."\nattributes_to_delete:".print_r($attributes_to_delete, 1); die();
					
					foreach ($attributes_to_add as $attr) {
						$this->sql_statements[] = $this->db_driver->getAddTableAttributeStatement($this->extra_attributes_table_name, $attr, $sql_options);
						$this->sql_statements_labels[] = "Add attribute " . $attr["name"] . " to table " . $this->extra_attributes_table_name;
					}
					
					foreach ($attributes_to_modify as $attr) {
						$this->sql_statements[] = $this->db_driver->getModifyTableAttributeStatement($this->extra_attributes_table_name, $attr, $sql_options);
						$this->sql_statements_labels[] = "Modify attribute " . $attr["name"] . " in table " . $this->extra_attributes_table_name;
					}
					
					//remove attrs must be first than the rename, so we can remove an attribute and then rename another one to the same name of the attribute that we removed.
					foreach ($attributes_to_delete as $attr) {
						$this->sql_statements[] = $this->db_driver->getDropTableAttributeStatement($this->extra_attributes_table_name, $attr["name"], $sql_options);
						$this->sql_statements_labels[] = "Drop attribute " . $attr["name"] . " in table " . $this->extra_attributes_table_name;
					}
					
					foreach ($attributes_to_rename as $old_name => $new_name) {
						$this->sql_statements[] = $this->db_driver->getRenameTableAttributeStatement($this->extra_attributes_table_name, $old_name, $new_name, $sql_options);
						$this->sql_statements_labels[] = "Rename attribute $old_name in table " . $this->extra_attributes_table_name;
					}
				}
			}
			
			if (!$changed)
				$this->status_message = "No changes to be made!";
			
			//updates extra attributes bc of the saveDataToFile
			$this->extra_attributes = $data["attributes"] ? $data["attributes"] : array();
			$this->saved_data = $data;
		}
	}
	
	/* PRIVATE METHODS */
	
	private function getTableExtraAttributeHtml($idx, $data = null) {
		//console.debug(data);
		
		$charsets = $this->db_driver->getColumnCharsets();
		$collations = $this->db_driver->getColumnCollations();
		$types = $this->db_driver->getDBColumnTypes();
		$column_types_ignored_props = $this->db_driver->getDBColumnTypesIgnoredProps();
		$column_types_hidden_props = $this->db_driver->getDBColumnTypesHiddenProps();
		$file_types = array("" => "-- Not a file --", "file" => "File", "image" => "Image");
		
		$charsets = is_array($charsets) ? $charsets : array();
		$collations = is_array($collations) ? $collations : array();
		$types = is_array($types) ? $types : array();
		$column_types_ignored_props = is_array($column_types_ignored_props) ? $column_types_ignored_props : array();
		$column_type_ignored_props = is_array($column_types_ignored_props[ $data["type"] ]) ? $column_types_ignored_props[ $data["type"] ] : array();
		
		$is_null = $data["primary_key"] ? false : $data["null"];
		$is_unique = $data["primary_key"] ? true : $data["unique"];
		$auto_increment = $data["auto_increment"];
		$has_default = strlen($data["default"]) ? true : $data["has_default"];
		
		$is_length_disabled = !$data["type"] || in_array("length", $column_type_ignored_props);
		$is_unsigned_disabled = !$data["type"] || in_array("unsigned", $column_type_ignored_props);
		$is_null_disabled = in_array("null", $column_type_ignored_props);
		$is_auto_increment_disabled = in_array("auto_increment", $column_type_ignored_props);
		$is_default_disabled = in_array("default", $column_type_ignored_props);
		$is_extra_disabled = in_array("extra", $column_type_ignored_props);
		$is_charset_disabled = in_array("charset", $column_type_ignored_props);
		$is_collation_disabled = in_array("collation", $column_type_ignored_props);
		$is_comment_disabled = in_array("comment", $column_type_ignored_props);
		$is_allow_javascript_disabled = !$data["type"] || !ObjTypeHandler::isDBTypeText($data["type"]);
		$file_type_disabled = $data["type"] != "bigint";
		
		$html = '
		<tr>
			<td class="table_attr_name">
				<input type="hidden" name="attributes[' . $idx . '][old_name]" value="' . $data["name"] . '" />
				<input type="text" name="attributes[' . $idx . '][name]" value="' . $data["name"] . '" onBlur="onBlurTableAttributeInputBox(this)" />
			</td>
			<td class="table_attr_type">
				<select name="attributes[' . $idx . '][type]" onChange="onChangeSelectBoxExtra(this)"><option></option>';
		
		$selected_type = $data["type"] == "bigint" && $data["file_type"] ? "attachment" : $data["type"];
		
		foreach ($types as $type_id => $type_name)
			$html .= '<option value="' . $type_id . '" ' . ($type_id == $selected_type ? "selected" : "") . '>' . $type_name . '</option>';
		
		$html .= '	<option value="bigint"' . ($selected_type == "attachment" ? "selected" : "") . '><strong>Attachment</strong></option>';
		
		if ($selected_type && !array_key_exists($selected_type, $types))
			$html .= '<option value="' . $selected_type . '">' . $selected_type . ' - NON DEFAULT</option>';
		
		$html .= '
				</select>
			</td>
			<td class="table_attr_length"' . (in_array("length", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="text" name="attributes[' . $idx . '][length]" value="' . $data["length"] . '" ' . ($is_length_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_null"' . (in_array("null", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="checkbox" name="attributes[' . $idx . '][null]" ' . ($is_null ? 'checked="checked"' : '') . ' value="1" ' . ($is_null_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_unsigned"' . (in_array("unsigned", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="checkbox" name="attributes[' . $idx . '][unsigned]" ' . ($data["unsigned"] ? 'checked="checked"' : '') . ' value="1" ' . ($is_unsigned_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_unique"' . (in_array("unique", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="checkbox" name="attributes[' . $idx . '][unique]" ' . ($is_unique ? 'checked="checked"' : '') . ' value="1" />
			</td>
			<td class="table_attr_auto_increment"' . (in_array("auto_increment", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="checkbox" name="attributes[' . $idx . '][auto_increment]" ' . ($auto_increment ? 'checked="checked"' : '') . ' value="1" ' . ($is_auto_increment_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_allow_javascript">
				<input type="checkbox" name="attributes[' . $idx . '][allow_javascript]" ' . ($data["allow_javascript"] ? 'checked="checked"' : '') . ' value="1" ' . ($is_allow_javascript_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_has_default"' . (in_array("default", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="checkbox" name="attributes[' . $idx . '][has_default]" ' . ($has_default ? 'checked="checked"' : '') . ' value="1" ' . ($is_default_disabled ? 'disabled="disabled"' : '') . ' onClick="onClickCheckBox(this)" title="Enable/Disable Default value" />
			</td>
			<td class="table_attr_default"' . (in_array("default", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="text" name="attributes[' . $idx . '][default]" value="' . $data["default"] . '" ' . ($has_default && !$is_default_disabled ? '' : 'disabled="disabled"') . ' />
			</td>
			<td class="table_attr_extra"' . (in_array("extra", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="text" name="attributes[' . $idx . '][extra]" value="' . $data["extra"] . '" ' . ($is_extra_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_charset"' . (in_array("charset", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<select name="attributes[' . $idx . '][charset]" ' . ($is_charset_disabled ? 'disabled="disabled"' : '') . '>
					<option value="">-- Default --</option>';
		
		$charset_exists = false;
		$charset_lower = $data["charset"] ? strtolower($data["charset"]) : "";
		
		foreach ($charsets as $charset_id => $charset_label) {
			$selected = strtolower($charset_id) == $charset_lower;
			$html .= '<option value="' . $charset_id . '" ' . ($selected ? "selected" : "") . '>' . $charset_label . '</option>';
			
			if ($selected)
				$charset_exists = true;
		}
		
		if ($data["charset"] && !$charset_exists)
			$html .= '<option value="' . $data["charset"] . '" selected>' . $data["charset"] . ' - NON DEFAULT</option>';
		
		$html .= '
				</select>
			</td>
			<td class="table_attr_collation"' . (in_array("collation", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<select name="attributes[' . $idx . '][collation]" ' . ($is_collation_disabled ? 'disabled="disabled"' : '') . '>
					<option value="">-- Default --</option>';
		
		$collation_exists = false;
		$collation_lower = $data["collation"] ? strtolower($data["collation"]) : "";
		
		foreach ($collations as $collation_id => $collation_label) {
			$selected = strtolower($collation_id) == $collation_lower;
			$html .= '<option value="' . $collation_id . '" ' . ($selected ? "selected" : "") . '>' . $collation_label . '</option>';
			
			if ($selected)
				$collation_exists = true;
		}
		
		if ($data["collation"] && !$collation_exists)
			$html .= '<option value="' . $data["collation"] . '" selected>' . $data["collation"] . ' - NON DEFAULT</option>';
		
		$html .= '
				</select>
			</td>
			<td class="table_attr_file_type">
				<select name="attributes[' . $idx . '][file_type]" title="In order to be activated, please change the attribute type to Bigint or Attachment" ' . ($file_type_disabled ? 'disabled="disabled"' : '') . '>
					<option></option>';
		
		foreach ($file_types as $type_id => $type_name)
			$html .= '<option value="' . $type_id . '" ' . ($type_id == $data["file_type"] ? "selected" : "") . '>' . $type_name . '</option>';
		
		if ($data["file_type"] && !array_key_exists($data["file_type"], $file_types))
			$html .= '<option value="' . $data["file_type"] . '">' . $data["file_type"] . ' - NON DEFAULT</option>';
		
		$html .= '
				</select>
			</td>
			<td class="table_attr_comment"' . (in_array("comment", $column_types_hidden_props) ? ' style="display:none;"' : '') . '>
				<input type="text" name="attributes[' . $idx . '][comment]" value="' . $data["comment"] . '" ' . ($is_comment_disabled ? 'disabled="disabled"' : '') . ' />
			</td>
			<td class="table_attr_icons">
				<a class="icon delete" onClick="removeTableAttribute(this)" ' . ($data ? 'confirm="1"' : "") . ' title="Remove">Remove</a>
			</td>
		</tr>';
		
		return $html;
	}
	
	private function loadData() {
		//load available tables
		$tables = $this->db_driver->listTables();
		$this->available_tables = array();
		
		foreach ($tables as $t)
			if ($t["name"])
				$this->available_tables[] = $t["name"];
		
		if (!$this->db_driver->isTableInNamesList($this->available_tables, $this->main_attributes_table_name)) {
			throw new Exception("DB table '" . $this->main_attributes_table_name . "' does not exist!");
			return;
		}
		
		//load main table attributes
		$this->main_attributes = $this->db_driver->listTableFields($this->main_attributes_table_name);
		$this->main_pks = array();
		
		if ($this->main_attributes)
			foreach ($this->main_attributes as $attr_name => $attr)
				if ($attr["primary_key"])
					$this->main_pks[ $attr_name ] = $attr;
		
		if (!$this->main_pks) {
			throw new Exception("DB table '" . $this->main_attributes_table_name . "' must have a primary key!");
			return;
		}
		
		//load extra table attributes
		if ($this->db_driver->isTableInNamesList($this->available_tables, $this->extra_attributes_table_name)) {
			$attributes = $this->db_driver->listTableFields($this->extra_attributes_table_name);
			$pks = array();
			
			if ($attributes) {
				foreach ($attributes as $attr_name => $attr)
					if ($attr["primary_key"]) {
						$pks[ $attr_name ] = $attr;
						unset($attributes[$attr_name]);
					}
				
				$fp = $this->presentation_module_path . $this->getExtraAttributesTableQueryName() . "_attributes_settings.php";
				
				//get local file and merge attributes data
				if (file_exists($fp)) {
					include $fp;
					
					if ($table_extra_attributes_settings)
						foreach ($table_extra_attributes_settings as $attr_name => $attr) {
							$attributes[$attr_name]["allow_javascript"] = $attr["allow_javascript"];
							$attributes[$attr_name]["file_type"] = $attr["file_type"];
						}
				}
			}
		}
		
		$this->extra_attributes = $attributes ? array_values($attributes) : array();
		$this->extra_pks = $pks ? $pks : array();
		
		//check if download files
		$download_action = $_GET["download"];
		
		if ($download_action) {
			$fn = "undefined_file";
			$code = "";
			
			if ($download_action == "businesslogic" && $_GET["file"] == "generic") {
				$fn = $this->getGenericExtraAttributesTableObjectName() . "Service.php";
				$code = $this->getGenericExtraAttributesTableBusinessLogicServiceCode();
			}
			else if ($download_action == "businesslogic") {
				$fn = $this->getExtraAttributesTableObjectName() . "Service.php";
				$code = $this->getExtraAttributesTableBusinessLogicServiceCode();
			}
			else if ($download_action == "ibatis") {
				$fn = $this->getExtraAttributesTableQueryName() . ".xml";
				$code = $this->getExtraAttributesTableIbatisRuleCode();
			}
			else if ($download_action == "hibernate") {
				$fn = $this->getExtraAttributesTableQueryName() . ".xml";
				$code = $this->getExtraAttributesTableHibernateRuleCode();
			}
			
			header('Content-Type: text/' . pathinfo($fn, PATHINFO_EXTENSION));
			header('Content-Disposition: attachment; filename="' . $fn . '"');
			
			echo $code;
			die();
		}
	}
	
	private function saveDataToFile($data, &$changed) {
		$settings_to_save = array();
		$last_modified_time = null;
		
		//prepare attributes settings to __system layer
		$column_numeric_types = $this->db_driver->getDBColumnNumericTypes();
		
		if ($data["attributes"])
			foreach ($data["attributes"] as $attr)
				if ($attr["name"] && !array_key_exists($attr["name"], $this->extra_pks) && !array_key_exists($attr["name"], $this->main_attributes)) {
					$db_attr = $attr;
					unset($db_attr["allow_javascript"]);
					unset($db_attr["file_type"]);
					
					$setting = array(
						"show" => 0,
						"admin_class" => "extra_attribute",
						"allow_javascript" => $attr["allow_javascript"],
						"file_type" => $attr["file_type"],
						"db_attribute" => $attr,
					);
					
					if ($attr["has_default"])
						$setting["default_value"] = $attr["default"];
					
					if (in_array($attr["type"], $column_numeric_types)) //if numeric
						$setting["validation_type"] = $attr["type"];
					
					if ($attr["null"])
						$setting["allow_null"] = $attr["null"];
					
					$settings_to_save[ $attr["name"] ] = $setting;
				}
		
		//save attributes settings
		$fp = $this->presentation_module_path . $this->getExtraAttributesTableQueryName() . "_attributes_settings.php";
		
		if (file_exists($fp)) {
			$last_modified_time = filemtime($fp);
			
			include $fp;
			
			$setting_name_to_ignore = array("type", "default_value", "validation_type", "allow_null");
			
			if ($table_extra_attributes_settings)
				foreach ($settings_to_save as $attr_name => $attr_settings)
					if ($table_extra_attributes_settings[$attr_name])
						foreach ($table_extra_attributes_settings[$attr_name] as $settings_name => $settings_value)
							if (!in_array($settings_name, $setting_name_to_ignore) && (!$settings_to_save[$attr_name] || !array_key_exists($settings_name, $settings_to_save[$attr_name])))
								$settings_to_save[$attr_name][$settings_name] = $settings_value;
		}
		
		$code = "<?php\n\$table_extra_attributes_settings = " . var_export($settings_to_save, true) . ";\n?>";
		
		if (!file_exists($fp) || file_get_contents($fp) != $code)
			$changed = true;
		
		$status_1 = file_put_contents($fp, $code) !== false;
		
		//save other files
		$status_2 = true;
		$rand = rand(0, 1000000);
		$last_modified_time = $last_modified_time ? $last_modified_time + 2 : null; //adds 2 seconds bc the pc can take 2 seconds maximum to execute the code bellow.
		
		//save business logic generic file to all related business-logic layers
		$object_name = $this->getGenericExtraAttributesTableObjectName();
		$fn = $object_name . "Service.php";
		$code = $this->getGenericExtraAttributesTableBusinessLogicServiceCode();
		
		if ($this->business_logic_module_paths)
			foreach ($this->business_logic_module_paths as $path) {
				$fp = $path . $fn;
				
				if (!file_exists($fp))
					$changed = true;
				else {
					$old_code = file_get_contents($fp);
					$old_code = preg_replace("/\s+extends\s+[\\\\\w]+\\CommonService\s*\{/", " extends \\CommonService {", $old_code);
					
					if ($old_code != $code) {
						$changed = true;
						
						//it means it was changed manually by the user, so backups old file
						if ($last_modified_time && filemtime($fp) > $last_modified_time) {
							$fp_bkp = $path . $object_name . $rand . "Service.php";
							$old_code = preg_replace("/class\s+" . $object_name . "Service(\s+|\{)/", "class " . $object_name . $rand . 'Service$1', $old_code);
							file_put_contents($fp_bkp, $old_code);
						}
					}
				}
				
				if (file_put_contents($fp, $code) === false)
					$status_2 = false;
			}
		
		//save business logic file to all related business-logic layers
		$object_name = $this->getExtraAttributesTableObjectName();
		$fn = $object_name . "Service.php";
		$code = $this->getExtraAttributesTableBusinessLogicServiceCode();
		
		if ($this->business_logic_module_paths)
			foreach ($this->business_logic_module_paths as $path) {
				$fp = $path . $fn;
				
				if (!file_exists($fp))
					$changed = true;
				else {
					$old_code = file_get_contents($fp);
					$old_code = preg_replace("/\s+extends\s+[\\\\\w]+\\CommonService\s*\{/", " extends \\CommonService {", $old_code);
					
					if ($old_code != $code) {
						$changed = true;
						
						//it means it was changed manually by the user, so backups old file
						if ($last_modified_time && filemtime($fp) > $last_modified_time) {
							$fp_bkp = $path . $object_name . $rand . "Service.php";
							$old_code = preg_replace("/class\s+" . $object_name . "Service(\s+|\{)/", "class " . $object_name . $rand . 'Service$1', $old_code);
							file_put_contents($fp_bkp, $old_code);
						}
					}
				}
				
				if (file_put_contents($fp, $code) === false)
					$status_2 = false;
			}
		
		//update namespaces in business logic files
		if (!CMSModuleInstallationBLNamespaceHandler::updateExtendedCommonServiceCodeInBusinessLogicPHPFiles($this->layers, $this->business_logic_module_paths))
			$status_2 = false;
		
		//save xml file to all related ibatis layers
		$fn = $this->getExtraAttributesTableQueryName() . ".xml";
		$code = $this->getExtraAttributesTableIbatisRuleCode();
		
		if ($this->ibatis_module_paths)
			foreach ($this->ibatis_module_paths as $path) {
				$fp = $path . $fn;
				
				if (!file_exists($fp))
					$changed = true;
				else {
					$old_code = file_get_contents($fp);
					
					if ($old_code != $code) {
						$changed = true;
						
						//it means it was changed manually by the user, so backups old file
						if ($last_modified_time && filemtime($fp) > $last_modified_time) {
							$fp_bkp = $path . $this->getExtraAttributesTableQueryName() . $rand . ".xml";
							file_put_contents($fp_bkp, $old_code);
						}
					}
				}
				
				if (file_put_contents($fp, $code) === false)
					$status_2 = false;
			}
		
		//save xml file to all related hibernate layers
		$fn = $this->getExtraAttributesTableQueryName() . ".xml";
		$code = $this->getExtraAttributesTableHibernateRuleCode();
		
		if ($this->hibernate_module_paths)
			foreach ($this->hibernate_module_paths as $path) {
				$fp = $path . $fn;
				
				if (!file_exists($fp))
					$changed = true;
				else {
					$old_code = file_get_contents($fp);
					
					if ($old_code != $code) {
						$changed = true;
						
						//it means it was changed manually by the user, so backups old file
						if ($last_modified_time && filemtime($fp) > $last_modified_time) {
							$fp_bkp = $path . $this->getExtraAttributesTableQueryName() . $rand . ".xml";
							file_put_contents($fp_bkp, $old_code);
						}
					}
				}
				
				if (file_put_contents($fp, $code) === false)
					$status_2 = false;
			}
		
		//return status
		return $status_1 && $status_2;	
	}
	
	private function flushCache() {
		$EVC = $this->EVC;
		include $EVC->getConfigPath("config");
		
		return FlushCacheHandler::flushCache($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $workflow_paths_id, $user_global_variables_file_path, $user_beans_folder_path, $css_and_js_optimizer_webroot_cache_folder_path, $deployments_temp_folder_path, $programs_temp_folder_path);
	}
	
	private function initGroupModuleId() {
		$modules_folder_path = $this->EVC->getCMSLayer()->getCMSModuleLayer()->getModulesFolderPath();
		$group_module_id = str_replace($modules_folder_path, "", $this->module_path);
		$pos = strpos($group_module_id, "/");
		$this->group_module_id = $pos > 0 ? substr($group_module_id, 0, $pos) : $group_module_id;
	}
	
	private function initUrlPrefixes() {
		$EVC = $this->EVC;
		include $EVC->getConfigPath("config");
		
		$this->project_url_prefix = $project_url_prefix;
		$this->project_common_url_prefix = $project_common_url_prefix;
	}
	
	private function initLayersPaths() {
		$pre_init_config = $this->PEVC->getConfigPath("pre_init_config");
		$user_global_variables_file_paths = $pre_init_config ? array($this->user_global_variables_file_path, $pre_init_config) : $this->user_global_variables_file_path;
		$PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_paths);
		$PHPVariablesFileHandler->startUserGlobalVariables();
		
		//only get the layers that the $bean_name has access to
		$this->layers = WorkFlowBeansFileHandler::getLocalBeanLayersFromBrokers($user_global_variables_file_paths, $this->user_beans_folder_path, $this->brokers, true);
		
		$this->presentation_module_path = $this->PEVC->getModulesPath($this->PEVC->getCommonProjectName()) . $this->group_module_id . "/";
		$this->business_logic_module_paths = $this->getLayerModulePaths($this->group_module_id, "BusinessLogicLayer");
		$this->ibatis_module_paths = $this->getLayerModulePaths($this->group_module_id, "IbatisDataAccessLayer");
		$this->hibernate_module_paths = $this->getLayerModulePaths($this->group_module_id, "HibernateDataAccessLayer");
		
		$PHPVariablesFileHandler->endUserGlobalVariables();
	}
	
	private function getLayerModulePaths($module_id, $layer_type, $webroot = false) {
		$paths = array();
		
		if (is_array($this->layers))
			foreach ($this->layers as $Layer)
				if (is_a($Layer, $layer_type)) {
					$module_path = $Layer->getLayerPathSetting() . "module/$module_id/";
					
					if ($module_path)
						$paths[] = $module_path;
				}
		
		return array_unique($paths);
	}
	
	private function getDBDriver() {
		$brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($this->user_global_variables_file_path, $this->user_beans_folder_path, $this->brokers, true);
				
		if (isset($brokers_db_drivers[$this->default_db_driver]))
			$db_driver_props = $brokers_db_drivers[$this->default_db_driver];
		else {
			$keys = array_keys($brokers_db_drivers);
			$db_driver_props = $brokers_db_drivers[ $keys[0] ];
		}
		
		if ($db_driver_props) {
			$WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($this->user_beans_folder_path . $db_driver_props[1], $this->user_global_variables_file_path);
			
			return $WorkFlowBeansFileHandler->getBeanObject($db_driver_props[2]);
		}
		
		return null;
	}
	
	private function getExtraAttributesTableQueryName() {
		return ($this->default_db_driver ? $this->default_db_driver : "default") . "_" . $this->extra_attributes_table_alias;
	}
	
	private function getExtraAttributesTableObjectName() {
		return $this->getObjectName( $this->getExtraAttributesTableQueryName() );
	}
	
	private function getGenericExtraAttributesTableObjectName() {
		return $this->getObjectName( $this->extra_attributes_table_alias );
	}
	
	private function getObjectName($name) {
		return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($name))));
	}
	
	private function getGenericExtraAttributesTableBusinessLogicServiceCode() {
		$namespace = $this->getObjectName($this->group_module_id);
		$service_name = $this->getGenericExtraAttributesTableObjectName() . "Service";
		
		$code = '<?php
namespace Module\\' . $namespace . ';

include_once $vars["business_logic_modules_service_common_file_path"];

class ' . $service_name . ' extends \\CommonService {
	
	private function callExtraAttributesTableService($data, $method) {
		$db_driver = $data["options"] && $data["options"]["db_driver"] ? $data["options"]["db_driver"] : ($GLOBALS["default_db_driver"] ? $GLOBALS["default_db_driver"] : "default");
		
		if ($db_driver) {
			$prefix = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($db_driver))));;
			
			return $this->getBusinessLogicLayer()->callBusinessLogic("module/' . $this->group_module_id . '", "{$prefix}' . $service_name . '.{$method}", $data, $this->getOptions());
		}
		
		return null;
	}
	
	public function insert($data) {
		return $this->callExtraAttributesTableService($data, "insert");
	}
	
	public function update($data) {
		return $this->callExtraAttributesTableService($data, "update");
	}
	
	public function delete($data) {
		return $this->callExtraAttributesTableService($data, "delete");
	}
	
	public function deleteAll($data) {
		return $this->callExtraAttributesTableService($data, "deleteAll");
	}
	
	public function get($data) {
		return $this->callExtraAttributesTableService($data, "get");
	}
	
	public function getAll($data) {
		return $this->callExtraAttributesTableService($data, "getAll");
	}

	public function countAll($data) {
		return $this->callExtraAttributesTableService($data, "countAll");
	}
}
?>';
		
		return $code;
	}
	
	private function getExtraAttributesTableBusinessLogicServiceCode() {
		$namespace = $this->getObjectName($this->group_module_id);
		$service_name = $this->getExtraAttributesTableObjectName() . "Service";
		$attributes = $this->extra_pks;
		
		if ($this->extra_attributes)
			foreach ($this->extra_attributes as $attr) {
				$attr["add_sql_slashes"] = false;
				$attr["null"] = $attr["null"] ? true : false; //must set this bc if not selected in UI, it won't exist, so we must set it to false.
				$attr["sanitize_html"] = empty($attr["allow_javascript"]); //must set this bc if not selected in UI, it won't exist, so we must set it to false.
				$attributes[ $attr["name"] ] = $attr;
			}
		
		$addslashes_vars_code = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($attributes);
		$pks_addslashes_vars_code = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($this->extra_pks);
		
		$insert_update_ibatis_prepare_vars_code = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($attributes, true);
		$insert_update_hibernate_prepare_vars_code = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($attributes, false);
		
		$insert_update_annotations = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($attributes, true, true, true, true, true);
		$delete_get_annotations = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($attributes, true, false, true, false, true);
		$conditions_annotations = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($attributes, true, true, false, false, true);
		$conditions_annotations = str_replace("* @param (name=data[", "* @param (name=data[conditions][", $conditions_annotations);
		$conditions_annotations = str_replace("], type=", "], type=mixed|", $conditions_annotations);
		
		$code = '<?php
namespace Module\\' . $namespace . ';

include_once $vars["business_logic_modules_service_common_file_path"];

class ' . $service_name . ' extends \\CommonService {
	private $' . $service_name . ';
	
	private function get' . $service_name . 'HbnObj($b, $options) {
		if (!$this->' . $service_name . ')
			$this->' . $service_name . ' = $b->callObject("module/' . $this->group_module_id . '", "' . $service_name . '", $options);
		
		return $this->' . $service_name . ';
	}
	
	
' . $insert_update_annotations . '
	public function insert($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		$b = $this->getBroker($options);
		
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			' . trim($addslashes_vars_code) . '
			' . $insert_update_ibatis_prepare_vars_code . '
			
			return $b->callInsert("module/' . $this->group_module_id . '", "insert_' . $this->getExtraAttributesTableQueryName() . '", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			' . $insert_update_hibernate_prepare_vars_code . '
			
			$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
			return $' . $service_name . '->insert($data, $ids, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			' . $insert_update_hibernate_prepare_vars_code . '
			
			$attributes = array_filter($data, function($k) {
				return $k && in_array($k, array("' . implode('", "', array_keys($attributes)) . '")); 
    			}, ARRAY_FILTER_USE_KEY);
			
			return $b->insertObject("' . $this->extra_attributes_table_name . '", $attributes, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.insert", $data, $options);
	}
	
' . $insert_update_annotations . '
	public function update($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		$b = $this->getBroker($options);
		
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			' . trim($addslashes_vars_code) . '
			' . $insert_update_ibatis_prepare_vars_code . '
		
			return $b->callUpdate("module/' . $this->group_module_id . '", "update_' . $this->getExtraAttributesTableQueryName() . '", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			' . $insert_update_hibernate_prepare_vars_code . '
			
			$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
			return $' . $service_name . '->update($data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			' . $insert_update_hibernate_prepare_vars_code . '
			
			$attributes = array_filter($data, function($k) {
				return $k && in_array($k, array("' . implode('", "', array_keys(array_diff_key($attributes, $this->extra_pks))) . '")); 
    			}, ARRAY_FILTER_USE_KEY);
			$conditions = array_filter($data, function($k) {
				return $k && in_array($k, array("' . implode('", "', array_keys($this->extra_pks)) . '")); 
    			}, ARRAY_FILTER_USE_KEY);
			
			return $conditions && $b->updateObject("' . $this->extra_attributes_table_name . '", $attributes, $conditions, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.update", $data, $options);
	}
	
' . $delete_get_annotations . '
	public function delete($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		$b = $this->getBroker($options);
		
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			' . trim($pks_addslashes_vars_code) . '
			
			return $b->callDelete("module/' . $this->group_module_id . '", "delete_' . $this->getExtraAttributesTableQueryName() . '", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
			return $' . $service_name . '->delete($data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$conditions = array_filter($data, function($k) {
				return $k && in_array($k, array("' . implode('", "', array_keys($this->extra_pks)) . '")); 
    			}, ARRAY_FILTER_USE_KEY);
    			
			return $conditions && $b->deleteObject("' . $this->extra_attributes_table_name . '", $conditions, $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.delete", $data, $options);
	}
	
' . $conditions_annotations . '
	public function deleteAll($data) {
		if ($data && ($data["conditions"] || $data["all"])) {
			$options = $data["options"];
			$this->mergeOptionsWithBusinessLogicLayer($options);
			unset($data["options"]);
			
			$b = $this->getBroker($options);
			
			if (is_a($b, "IIbatisDataAccessBrokerClient")) {
				self::prepareInputData($data);
				
				return $b->callDelete("module/' . $this->group_module_id . '", "delete_all_' . $this->getExtraAttributesTableQueryName() . '_items", $data, $options);
			}
			else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
				$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
				return $' . $service_name . '->deleteByConditions($data, $options);
			}
			else if (is_a($b, "IDBBrokerClient")) {
				$options = $options ? $options : array();
				$options["conditions_join"] = $data["conditions_join"];
				$options["all"] = $data["all"];
				return $b->deleteObject("' . $this->extra_attributes_table_name . '", $data["conditions"], $options);
			}
			else if (is_a($b, "IBusinessLogicBrokerClient"))
				return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.deleteAll", $data, $options);
		}
	}
	
' . $delete_get_annotations . '
	public function get($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		$b = $this->getBroker($options);
		
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			' . trim($pks_addslashes_vars_code) . '
			
			$result = $b->callSelect("module/' . $this->group_module_id . '", "get_' . $this->getExtraAttributesTableQueryName() . '", $data, $options);
			return $result[0];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
			return $' . $service_name . '->findById($data);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$conditions = array_filter($data, function($k) {
				return $k && in_array($k, array("' . implode('", "', array_keys($this->extra_pks)) . '")); 
    			}, ARRAY_FILTER_USE_KEY);
    			
			$result = $conditions && $b->findObjects("' . $this->extra_attributes_table_name . '", null, $conditions, $options);
			return $result ? $result[0] : null;
		}
		else if (is_a($b, "IBusinessLogicBrokerClient"))
			return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.get", $data, $options);
	}
	
' . $conditions_annotations . '
	public function getAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		$b = $this->getBroker($options);
		
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			self::prepareInputData($data);
			
			return $b->callSelect("module/' . $this->group_module_id . '", "get_' . $this->getExtraAttributesTableQueryName() . '_items", $data, $options);
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
			return $' . $service_name . '->find($data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$options = $options ? $options : array();
			$options["conditions_join"] = $data["conditions_join"];
			return $b->findObjects("' . $this->extra_attributes_table_name . '", null, $data["conditions"], $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.getAll", $data, $options);
	}

' . $conditions_annotations . '
	public function countAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		$b = $this->getBroker($options);
		
		if (is_a($b, "IIbatisDataAccessBrokerClient")) {
			self::prepareInputData($data);
			
			$result = $b->callSelect("module/' . $this->group_module_id . '", "count_' . $this->getExtraAttributesTableQueryName() . '_items", $data, $options);
			return $result[0]["total"];
		}
		else if (is_a($b, "IHibernateDataAccessBrokerClient")) {
			$' . $service_name . ' = $this->get' . $service_name . 'HbnObj($b, $options);
			return $' . $service_name . '->count($data, $options);
		}
		else if (is_a($b, "IDBBrokerClient")) {
			$options = $options ? $options : array();
			$options["conditions_join"] = $data["conditions_join"];
			return $b->countObjects("' . $this->extra_attributes_table_name . '", $data["conditions"], $options);
		}
		else if (is_a($b, "IBusinessLogicBrokerClient")) 
			return $b->callBusinessLogic("module/' . $this->group_module_id . '", "' . $service_name . '.countAll", $data, $options);
	}
}
?>';
		
		return $code;
	}
	
	private function getExtraAttributesTableIbatisRuleCode() {
		$table_name = $this->extra_attributes_table_name;
		$column_numeric_types = $this->db_driver->getDBColumnNumericTypes();
		
		//prepare sqls
		$insert_attributes = $update_attributes = $conditions = $columns = array();
		$all_attributes = array();
		
		if ($this->extra_pks) {
			$all_attributes = array_values($this->extra_pks);
			
			foreach ($this->extra_pks as $attr_name => $attr) {
				$insert_attributes[$attr_name] = "#$attr_name#";
				
				$conditions[$attr_name] = "#$attr_name#";
				$columns[$attr_name] = $attr_name;
			}
		}
		
		if ($this->extra_attributes) {
			$all_attributes = array_merge($all_attributes, array_values($this->extra_attributes));
			
			foreach ($this->extra_attributes as $idx => $attr) {
				$attr_name = $attr["name"];
				
				$columns[$attr_name] = $attr_name;
				$insert_attributes[$attr_name] = "#$attr_name#";
				$update_attributes[$attr_name] = "#$attr_name#";
			}
		}
		
		$insert_sql = $this->db_driver->buildTableInsertSQL($table_name, $insert_attributes);
		$update_sql = $this->db_driver->buildTableUpdateSQL($table_name, $update_attributes, $conditions);
		$delete_sql = $this->db_driver->buildTableDeleteSQL($table_name, $conditions);
		$delete_all_sql = $this->db_driver->buildTableDeleteSQL($table_name, null, array("all" => true));
		$get_sql = $this->db_driver->buildTableFindSQL($table_name, $columns, $conditions);
		$get_all_sql = $this->db_driver->buildTableFindSQL($table_name, $columns);
		$count_sql = $this->db_driver->buildTableCountSQL($table_name);
		
		//remove single quotes in sqls for numeric attributes, this is, replace "'#attr_name#'" by "#attr_name#"
		foreach ($all_attributes as $idx => $attr) {
			$attr_name = $attr["name"];
			
			if (in_array($attr["type"], $column_numeric_types)) {
				$insert_sql = str_replace("'#$attr_name#'", "#$attr_name#", $insert_sql);
				$update_sql = str_replace("'#$attr_name#'", "#$attr_name#", $update_sql);
				$delete_sql = str_replace("'#$attr_name#'", "#$attr_name#", $delete_sql);
				$get_sql = str_replace("'#$attr_name#'", "#$attr_name#", $get_sql);
			}
		}
		
		//prepare xml
		$query_id = $this->getExtraAttributesTableQueryName();
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE sqlMap PUBLIC "-//iBATIS.com//DTD SQL Map 2.0//EN" "http://www.ibatis.com/dtd/sql-map-2.dtd">

<sql_mapping>
	<insert id="insert_' . $query_id . '"><![CDATA[
		' . $insert_sql . '
	]]></insert>
	<update id="update_' . $query_id . '"><![CDATA[
		' . $update_sql . '
	]]></update>
	<delete id="delete_' . $query_id . '"><![CDATA[
		' . $delete_sql . '
	]]></delete>
	<delete id="delete_all_' . $query_id . '_items"><![CDATA[
		' . $delete_all_sql . ' WHERE 1=1 #searching_condition#
	]]></delete>
	<select id="get_' . $query_id . '"><![CDATA[
		' . $get_sql . '
	]]></select>
	<select id="get_' . $query_id . '_items"><![CDATA[
		' . $get_all_sql . ' WHERE 1=1 #searching_condition#
	]]></select>
	<select id="count_' . $query_id . '_items"><![CDATA[
		' . $count_sql . ' WHERE 1=1 #searching_condition#
	]]></select>
</sql_mapping>';
		
		return $xml;
	}
	
	private function getExtraAttributesTableHibernateRuleCode() {
		$table_name = $this->extra_attributes_table_name;
		$table_obj_name = $this->getExtraAttributesTableObjectName();
		
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE hibernate-mapping PUBLIC "-//Hibernate/Hibernate Mapping DTD 3.0//EN" "http://hibernate.sourceforge.net/hibernate-mapping-3.0.dtd">

<sql_mapping>
	<class name="' . $table_obj_name . '" table="' . $table_name . '">';
		
		if ($this->extra_pks)
			foreach ($this->extra_pks as $attr_name => $attr)
				$xml .= '
		<id column="' . $attr_name . '"/>';
		
		$xml .= '
	</class>
</sql_mapping>';
		
		return $xml;
	}
}
?>
