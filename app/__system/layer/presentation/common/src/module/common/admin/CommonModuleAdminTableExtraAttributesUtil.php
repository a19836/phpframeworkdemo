<?php
include_once get_lib("org.phpframework.db.DB");
include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationBLNamespaceHandler");
include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSProgramExtraTableInstallationUtil");
include_once $EVC->getUtilPath("WorkFlowTasksFileHandler");
include_once $EVC->getUtilPath("WorkFlowBeansFileHandler");
include_once $EVC->getUtilPath("WorkFlowDBHandler");
include_once $EVC->getUtilPath("WorkFlowBusinessLogicHandler");
include_once $EVC->getUtilPath("FlushCacheHandler");
include_once $EVC->getUtilPath("WorkFlowUIHandler");

class CommonModuleAdminTableExtraAttributesUtil {
	private $EVC;
	private $PEVC;
	private $module_path;
	private $brokers;
	private $default_db_driver;
	private $main_attributes_table_name;
	private $extra_attributes_table_name;
	private $extra_attributes_table_alias;
	private $UserAuthenticationHandler;
	
	private $project_url_prefix;
	private $project_common_url_prefix;
	private $user_global_variables_file_path;
	private $user_beans_folder_path;
	private $gpl_js_url_prefix;
	private $proprietary_js_url_prefix;
	private $webroot_cache_folder_path;
	private $webroot_cache_folder_url;
	
	private $group_module_id;
	private $db_driver;
	private $layers;
	private $presentation_module_path;
	private $business_logic_module_paths;
	private $ibatis_module_paths;
	private $hibernate_module_paths;
	
	private $WorkFlowTaskHandler;
	
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
	
	private $available_file_types = array("" => "-- Not a file --", "file" => "File", "image" => "Image");
	
	public function __construct($EVC, $PEVC, $UserAuthenticationHandler, $module_path, $default_db_driver, $main_attributes_table_name, $main_attributes_table_alias = false, $extra_attributes_table_name = false, $extra_attributes_table_alias = false) {
		$this->EVC = $EVC;
		$this->PEVC = $PEVC;
		$this->UserAuthenticationHandler = $UserAuthenticationHandler;
		$this->module_path = $module_path;
		$this->brokers = $PEVC->getPresentationLayer()->getBrokers();
		$this->default_db_driver = $default_db_driver;
		$this->main_attributes_table_name = $main_attributes_table_name;
		$main_attributes_table_alias = $main_attributes_table_alias ? $main_attributes_table_alias : $main_attributes_table_name;
		$this->extra_attributes_table_name = $extra_attributes_table_name ? $extra_attributes_table_name : $main_attributes_table_name . "_" . CMSProgramExtraTableInstallationUtil::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX;
		$this->extra_attributes_table_alias = $extra_attributes_table_alias ? $extra_attributes_table_alias : $main_attributes_table_alias . "_" . CMSProgramExtraTableInstallationUtil::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX;
		
		$this->initGroupModuleId();
		$this->initLayersPaths();
		$this->initSystemGlobalVars();
		
		$this->db_driver = $this->getDBDriver();
		
		if (!$this->db_driver)
			throw new Exception("DB Driver could not be found in this module!");
		
		$this->loadData();
	}
	
	public function getHead() {
		$admin_common_url = $this->project_common_url_prefix . "module/" . $this->EVC->getCommonProjectName() . "/";
		
		//prepare WorkFlowTaskHandler 
		//Do not call this in the construct bc we are flushing the cache on the saveData method, and when this happens we must recreate the cache for the WorkFlowTaskHandler. If this is here, the cache will always be recreated
		$this->WorkFlowTaskHandler = new WorkFlowTaskHandler($this->webroot_cache_folder_path, $this->webroot_cache_folder_url);
		$this->WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
		$this->WorkFlowTaskHandler->setAllowedTaskTypes(array("table"));
		
		//get task table workflow settings
		$WorkFlowUIHandler = new WorkFlowUIHandler($this->WorkFlowTaskHandler, $this->project_url_prefix, $this->project_common_url_prefix, $this->gpl_js_url_prefix, $this->proprietary_js_url_prefix, $this->user_global_variables_file_path, $this->webroot_cache_folder_path, $this->webroot_cache_folder_url);
		
		//prepare DBTableTaskPropertyObj properties 
		$charsets = $this->db_driver->getTableCharsets();
		$collations = $this->db_driver->getTableCollations();
		$storage_engines = $this->db_driver->getStorageEngines();
		$column_charsets = $this->db_driver->getColumnCharsets();
		$column_collations = $this->db_driver->getColumnCollations();
		$column_column_types = $this->db_driver->getDBColumnTypes();
		$column_column_simple_types = $this->db_driver->getDBColumnSimpleTypes();
		$column_numeric_types = $this->db_driver->getDBColumnNumericTypes();
		$column_mandatory_length_types = $this->db_driver->getDBColumnMandatoryLengthTypes();
		$column_types_ignored_props = $this->db_driver->getDBColumnTypesIgnoredProps();
		$column_types_hidden_props = $this->db_driver->getDBColumnTypesHiddenProps();
		$valid_allow_javascript_types = $this->db_driver->getDBColumnTextTypes();
		
		$charsets = is_array($charsets) ? $charsets : array();
		$collations = is_array($collations) ? $collations : array();
		$storage_engines = is_array($storage_engines) ? $storage_engines : array();
		$column_charsets = is_array($column_charsets) ? $column_charsets : array();
		$column_collations = is_array($column_collations) ? $column_collations : array();
		$column_column_types = is_array($column_column_types) ? $column_column_types : array();
		$column_column_simple_types = is_array($column_column_simple_types) ? $column_column_simple_types : array();
		$column_numeric_types = is_array($column_numeric_types) ? $column_numeric_types : array();
		$column_mandatory_length_types = is_array($column_mandatory_length_types) ? $column_mandatory_length_types : array();
		$column_types_ignored_props = is_array($column_types_ignored_props) ? $column_types_ignored_props : array();
		$valid_allow_javascript_types = is_array($valid_allow_javascript_types) ? $valid_allow_javascript_types : array();
		
		$column_column_types["attachment"] = "Attachment";
		$column_numeric_types[] = "attachment";
		$column_types_ignored_props["attachment"] = $column_types_ignored_props["bigint"];
		
		foreach ($column_types_ignored_props as $type => $ignored_props) {
			if (!in_array($type, $valid_allow_javascript_types)) {
				$ignored_props = is_array($ignored_props) ? $ignored_props : ($ignored_props ? array($ignored_props) : array());
				$ignored_props[] = "allow_javascript";
				$column_types_ignored_props[$type] = $ignored_props;
			}
			
			if ($type != "bigint" && $type != "attachment") {
				$ignored_props = is_array($ignored_props) ? $ignored_props : ($ignored_props ? array($ignored_props) : array());
				$ignored_props[] = "file_type";
				$column_types_ignored_props[$type] = $ignored_props;
			}
		}
		
		//get table data
		$data = $this->saved_data ? $this->saved_data : array(
			"table_name" => $this->extra_attributes_table_name,
			"table_storage_engine" => "",
			"table_charset" => "",
			"table_collation" => "",
			"attributes" => $this->extra_attributes
		);
		
		if ($data && $data["attributes"]) {
			foreach ($data["attributes"] as $idx => $attr)
				foreach ($attr as $k => $v) {
					$data["table_attr_" . $k . "s"][$idx] = $v;
					
					if ($k == "default")
						$data["table_attr_has_" . $k . "s"][$idx] = strlen($v) > 0;
				}
			
			//echo "<pre>";print_r($data["attributes"]);die();
			unset($data["attributes"]);
		}
		//echo "<pre>";print_r($data);die();
		
		//prepare html
		$html = '
		<!-- Add Globals JS files -->
		<script language="javascript" type="text/javascript" src="' . $this->project_common_url_prefix . 'js/global.js"></script>
		
		<!-- Add ACE Editor JS files -->
		<script src="' . $this->project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ace.js"></script>
		<script src="' . $this->project_common_url_prefix . 'vendor/acecodeeditor/src-min-noconflict/ext-language_tools.js"></script>
		';
		$html .= $WorkFlowUIHandler->getHeader();
		$html .= '
		<!-- Add Layout CSS file -->
		<link rel="stylesheet" href="' . $this->project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
		
		<!-- Add Local JS and CSS files -->
		<link rel="stylesheet" href="' . $admin_common_url . 'admin.css" type="text/css" charset="utf-8" />
		
		<!-- Add Local JS and CSS files -->
		<link rel="stylesheet" href="' . $this->project_url_prefix . 'css/db/edit_table.css" type="text/css" charset="utf-8" />
		<script language="javascript" type="text/javascript" src="' . $this->project_url_prefix . 'js/db/edit_table.js"></script>
		
		<link rel="stylesheet" href="' . $admin_common_url . 'manage_table_extra_attributes.css" type="text/css" charset="utf-8" />
		<script language="javascript" type="text/javascript" src="' . $admin_common_url . 'manage_table_extra_attributes.js"></script>
		
		<script>
		DBTableTaskPropertyObj.column_types = ' . json_encode($column_column_types) . ';
		DBTableTaskPropertyObj.column_simple_types = ' . json_encode($column_column_simple_types) . ';
		DBTableTaskPropertyObj.column_numeric_types = ' . json_encode($column_numeric_types) . ';
		DBTableTaskPropertyObj.column_mandatory_length_types = ' . json_encode($column_mandatory_length_types) . ';
		DBTableTaskPropertyObj.column_types_ignored_props = ' . json_encode($column_types_ignored_props) . ';
		DBTableTaskPropertyObj.column_types_hidden_props = ' . json_encode($column_types_hidden_props) . ';
		DBTableTaskPropertyObj.table_charsets = ' . json_encode($charsets) . ';
		DBTableTaskPropertyObj.table_collations = ' . json_encode($collations) . ';
		DBTableTaskPropertyObj.table_storage_engines = ' . json_encode($storage_engines) . ';
		DBTableTaskPropertyObj.column_charsets = ' . json_encode($column_charsets) . ';
		DBTableTaskPropertyObj.column_collations = ' . json_encode($column_collations) . ';
		
		DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.push("allow_javascript");
		DBTableTaskPropertyObj.task_property_values_table_attr_prop_names.push("file_type");
		
		DBTableTaskPropertyObj.on_update_simple_attributes_html_with_table_attributes_callback = onUpdateExtraSimpleAttributesHtmlWithTableAttributes;
		DBTableTaskPropertyObj.on_update_table_attributes_html_with_simple_attributes_callback = onUpdateExtraTableAttributesHtmlWithSimpleAttributes;
		DBTableTaskPropertyObj.on_add_simple_attribute_callback = onAddExtraSimpleAttribute;
		DBTableTaskPropertyObj.on_add_table_attribute_callback = onAddExtraTableAttribute;
		
		var task_property_values = ' . json_encode($data) . ';
		
		var step = ' . ($this->step ? $this->step : 0) . ';
		var available_file_types = ' . json_encode($this->available_file_types) . ';
		</script>';
		
		return $html;
	}
	
	public function getContent() {
		//get task table workflow settings
		$tasks_settings = $this->WorkFlowTaskHandler->getLoadedTasksSettings();
		$task_contents = array();
		
		foreach ($tasks_settings as $group_id => $group_tasks)
			foreach ($group_tasks as $task_type => $task_settings)
				if (is_array($task_settings))
					$task_contents = $task_settings["task_properties_html"];
		
		//$allow_sort = $this->db_driver->allowTableAttributeSorting() && !$this->extra_attributes; //if no extra attributes, then allow sort attributes
		$allow_sort = $this->db_driver->allowTableAttributeSorting();
		
		$html = '
	<div class="manage_table_exta_attributes edit_table">
		<h3>Table Settings <a class="icon refresh" href="javascript:void(0);" onClick="document.location=document.location+\'\';" title="Refresh">Refresh</a></h3>
		<div class="table_settings' . ($allow_sort ? " allow_sort" : "") . '">
			<div class="attributes_title">Attributes for table: "' . $this->extra_attributes_table_name . '"</div>
			
			<div class="selected_task_properties">
			' . $task_contents . '
			</div>
			
			<form method="post">
				<input type="hidden" name="step" value="1"/>
				<textarea class="hidden" name="data"></textarea>
				
				<div class="save_button">
					<input type="submit" name="save" value="SAVE" onClick="return onSaveButton(this);" />
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
						$this->errors[] = (is_a($e, "Exception") ? $e->getMessage() . "\n\n" : "") . $sql;
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
			$data = json_decode($post_data["data"], true);
			//$data = $post_data;
			
			//echo "<pre>";print_r($post_data);die();
			//echo "<pre>";print_r($data);die();
			//echo "<pre>";print_r($data["attributes"][0]);die();
			
			$this->sql_statements = array();
			$this->sql_statements_labels = array();
			
			//replace attachment type by bigint, remove empty attributes and trim names
			if ($data && $data["attributes"])
				foreach ($data["attributes"] as $idx => $attr) {
					if (!trim($attr["name"]))
						unset($data["attributes"][$idx]);
					else {
						$data["attributes"][$idx]["name"] = trim($attr["name"]); //trim name
						
						if ($attr["type"] == "attachment")
							$data["attributes"][$idx]["type"] = "bigint";
					}
				}
				
			//save this table into our internal register so we can detect later if a db table belongs to a module. Leave this code before the next if method, bc if the table already exists it won't not insert it in the UserAuthenticationHandler.
			if ($this->UserAuthenticationHandler)
				$this->UserAuthenticationHandler->insertModuleDBTableNameIfNotExistsYet(array("name" => $this->extra_attributes_table_name));
			
			//check first if table exists in DB and if not create it
			if (!$this->db_driver->isTableInNamesList($this->available_tables, $this->extra_attributes_table_name)) {
				$this->extra_pks = $this->main_pks; //set the extra_pks, bc they were not set yet!
				$new_attributes = array_values($this->extra_pks);
				$new_attributes = $data["attributes"] ? array_merge($new_attributes, $data["attributes"]) : $new_attributes;
				
				$main_table_name = $this->db_driver->getTableInNamesList($this->available_tables, $this->main_attributes_table_name);
				$main_table_data = array();
				
				$t = count($this->available_tables);
				for ($i = 0; $i < $t; $i++)
					if ($this->available_tables[$i]["name"] == $main_table_name) {
						$main_table_data = $this->available_tables[$i];
						break;
					}
				
				$table_data = array(
					"table_name" => $this->extra_attributes_table_name, 
					"table_storage_engine" => $main_table_data["engine"],
					"table_charset" => $main_table_data["charset"],
					"table_collation" => $main_table_data["collation"],
					"attributes" => $new_attributes,
				);
				$this->sql_statements[] = $this->db_driver->getCreateTableStatement($table_data, $this->db_driver->getOptions());
				$this->sql_statements_labels[] = "Create table " . $table_data["table_name"];
				
				/*$e = $this->db_driver->setData($sql);
				
				if ($e !== true)
					$this->errors[] = (is_a($e, "Exception") ? $e-getMessage() . "\n\n" : "") . $sql;*/
			}
			else { //get attributes to add and modify
				//remove primary keys or already existent attributes in $this->main_attributes
				if ($data["attributes"])
					foreach ($data["attributes"] as $idx => $new_attr) {
						if (!$new_attr["name"] || array_key_exists($new_attr["name"], $this->extra_pks) || array_key_exists($new_attr["name"], $this->main_attributes))
							unset($data["attributes"][$idx]);
						else if ($new_attr["primary_key"]) {
							$new_attr["primary_key"] = false;
							$new_attr["unique"] = false;
							$new_attr["auto_increment"] = false;
							$new_attr["extra"] = preg_replace("/(^|\s)auto_increment($|\s)/i", "", $new_attr["extra"]);
							
							$data["attributes"][$idx] = $new_attr;
						}
					}
				
				if ($table_attrs)
					foreach ($table_attrs as $idx => $attr)
						if ($attr["primary_key"])
							unset($table_attrs[$idx]);
				
				$statements = WorkFlowDBHandler::getTableUpdateSQLStatements($this->db_driver, $this->extra_attributes_table_name, $table_attrs, $data["attributes"]);
				$this->sql_statements = $statements["sql_statements"];
				$this->sql_statements_labels = $statements["sql_statements_labels"];
			}
			
			if (empty($this->sql_statements))
				$this->status_message = "No changes to be made!";
			
			//updates extra attributes bc of the saveDataToFile
			$this->extra_attributes = $data["attributes"] ? $data["attributes"] : array();
			$this->saved_data = $data;
		}
	}
	
	/* PRIVATE METHODS */
	
	private function loadData() {
		//load available tables
		$tables = $this->db_driver->listTables();
		$this->available_tables = array();
		
		foreach ($tables as $t)
			if ($t["name"])
				$this->available_tables[] = $t;
		
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
	
	private function initSystemGlobalVars() {
		$EVC = $this->EVC;
		include $EVC->getConfigPath("config");
		
		$this->project_url_prefix = $project_url_prefix;
		$this->project_common_url_prefix = $project_common_url_prefix;
		$this->user_global_variables_file_path = $user_global_variables_file_path;
		$this->user_beans_folder_path = $user_beans_folder_path;
		$this->gpl_js_url_prefix = $gpl_js_url_prefix;
		$this->proprietary_js_url_prefix = $proprietary_js_url_prefix;
		$this->webroot_cache_folder_path = $webroot_cache_folder_path;
		$this->webroot_cache_folder_url = $webroot_cache_folder_url;
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
