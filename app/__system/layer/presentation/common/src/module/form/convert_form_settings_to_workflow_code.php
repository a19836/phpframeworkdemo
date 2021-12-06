<?php
$defined_vars = array_keys(get_defined_vars());

include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("CMSPresentationLayerHandler");
include_once $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName());

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$settings = $_POST["settings"];

if (is_array($settings)) {
	$allowed_tasks = array("createform", "callbusinesslogic", "callibatisquery", "callhibernatemethod", "getquerydata", "setquerydata", "callfunction", "callobjectmethod", "restconnector", "soapconnector");
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks);
	$WorkFlowTaskHandler->initWorkFlowTasks();
	
	MyArray::arrKeysToLowerCase($settings, true);
	
	$head_code = array();
	$settings["actions"] = replaceEscapedVariables($settings["actions"]);
		
	$actions_code = getActionsCode($EVC, $WorkFlowTaskHandler, $settings["actions"], '$results', $head_code);
	
	if ($actions_code || $settings["css"] || $settings["js"]) {
		$code = "<?php\n";
		
		if ($actions_code) {
			replaceEscapedVariables($code);
			
			$head_code = $head_code ? '$common_project_name = $EVC->getCommonProjectName();' . "\n" . implode("\n", array_unique($head_code)) . "\n" : "";
			$results_var_init_code = "\n" . '$results = array();' . "\n";
			$code .= $head_code . $results_var_init_code . $actions_code;
		}
		
		if ($settings["css"])
			$code .= "\n/*** STYLE ***/\n" . 'echo "<style>" . ' . prepareStringValue($settings["css"]) . ' . "</style>";' . "\n";
		
		if ($settings["js"]) 
			$code .= "\n/*** SCRIPT ***/\n" . 'echo "<script>" . ' . prepareStringValue($settings["js"]) . ' . "</script>";' . "\n";
		
		$code .= "\n?>";
	}
	
	preg_match_all("/\\$(\w+)/u", $code, $matches_1, PREG_PATTERN_ORDER); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	preg_match_all("/\\$\{(\w+)/u", $code, $matches_2, PREG_PATTERN_ORDER); //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
	$matches = $matches_1[1] && $matches_2[1] ? array_merge($matches_1[1], $matches_2[1]) : ($matches_1[1] ? $matches_1[1] : $matches_2[1]);
	$external_vars = $matches && $matches[1] ? array_intersect($defined_vars, $matches) : null;
	unset($external_vars["results"]);
	
	if (strpos($code, '$entity_path') !== false && !in_array('entity_path', $external_vars))
		$external_vars[] = 'entity_path';
	
	$external_vars_code = "";
	if ($external_vars)
		foreach ($external_vars as $external_var)
			if ($external_var)
				$external_vars_code .= "\n\t\t\t" . '"' . $external_var . '" => $' . $external_var . ',';
	
	if ($external_vars_code)
		$external_vars_code = "array($external_vars_code\n\t\t)";
	else
		$external_vars_code = "null";
	
	//"\\'" => double back slash is very important otherwise the code conversion won't work if there is javascript already with "\'" inside...
	$code = 'array(
		"code" => \'' . addcslashes($code, "\\'") . '\',
		"external_vars" => ' . $external_vars_code . ',
	)';
	
	header("Content-Type: application/json");
	echo json_encode(array("code" => $code));
}

function getIfCode($condition_type, $condition_value, $result_var_prefix) {
	$code = "";
	
	if ($condition_type) {
		$condition_type = strtolower($condition_type);
		$is_not = strpos($condition_type, "_not_") !== false;
		
		switch($condition_type) {
			case "execute_if_var": //Only execute if variable exists
			case "execute_if_not_var": //Only execute if variable doesn't exists
				$var = trim($condition_value);
				
				if (!empty($var)) {
					$var = substr($var, 0, 1) == '$' ? $var : '$' . $var;
					$code = ($is_not ? "!" : "") . $var;
				}
				break;
			
			case "execute_if_post_button": //Only execute if submit button was clicked via POST
			case "execute_if_not_post_button": //Only execute if submit button was not clicked via POST
			case "execute_if_get_button": //Only execute if submit button was clicked via GET
			case "execute_if_not_get_button": //Only execute if submit button was not clicked via GET
				$button_name = trim($condition_value);
				
				if ($button_name)
					$button_name = prepareStringValue($button_name);
					$code = ($is_not ? "!" : "") . (strpos($condition_type, "_get_") !== false ? '$_GET' : '$_POST') . '[' . $button_name . ']';
				break;
			
			case "execute_if_previous_action": //Only execute if previous action executed correctly
			case "execute_if_not_previous_action": //Only execute if previous action was not executed correctly
				$code = $result_var_prefix . '[count(' . $result_var_prefix . ') - 1]';
				
				if ($is_not)
					$code = "empty($code)";
				break;
			
			case "execute_if_condition": //Only execute if condition is valid
			case "execute_if_not_condition": //Only execute if condition is invalid
			case "execute_if_code": //Only execute if code is valid
			case "execute_if_not_code": //Only execute if code is invalid
				if (is_numeric($condition_value))
					$code = $is_not ? "empty($condition_value)" : $condition_value;
				else if ($condition_value === true)
					$code = $is_not ? "false" : "true";
				else if ($condition_value === false)
					$code = $is_not ? "true" : "false";
				else if (is_array($condition_value) || is_object($condition_value))
					$code = $is_not ? (empty($condition_value) ? "false" : "true") : (empty($condition_value) ? "true" : "false");
				else if (!empty($condition_value))
					$code = $is_not ? "empty($condition_value)" : $condition_value;
				break;
		}
		
		$code = $code ? "if ($code) {" : "";
	}
	
	return $code;
}

function getActionsCode($EVC, $WorkFlowTaskHandler, $actions, $result_var_prefix, &$head_code, $prefix = "") {
	$code = "";
	
	if (is_array($actions))
		foreach ($actions as $idx => $action_settings)
			$code .= getActionCode($EVC, $WorkFlowTaskHandler, $action_settings, $result_var_prefix, $head_code, $prefix);
		
	return $code;
}

function getActionCode($EVC, $WorkFlowTaskHandler, $action_settings, $result_var_prefix, &$head_code, $prefix = "") {
	$code = "";
	
	$result_var_name = trim($action_settings["result_var_name"]);
	$action_type = strtolower($action_settings["action_type"]);
	$action_value = $action_settings["action_value"];
	$condition_type = strtolower($action_settings["condition_type"]);
	$condition_value = $action_settings["condition_value"];
	
	$result_var_code = "";
	if ($result_var_name) 
		$result_var_code = $result_var_prefix . "[" . prepareStringValue($result_var_name) . "] = ";
	
	$if = getIfCode($condition_type, $condition_value, $result_var_prefix);
	
	if ($if) 
		$prefix .= "\t";
	
	switch ($action_type) {
		case "html": //getting design form html settings
			$task = $WorkFlowTaskHandler->getTasksByTag("createform");
			$task = $task[0];
			$task["properties"] = array(
				"form_settings_data_type" => $action_value["form_settings_data_type"], 
				"form_settings_data" => $action_value["form_settings_data"],
				"form_input_data_type" => '',
				"form_input_data" => '$results',
			);
			$task["obj"]->data = $task;
			
			$task_code = trim($task["obj"]->printCode(null, null));
			
			if ($task_code) {
				$head_code[] = 'include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");';
				
				$code .= $prefix . ($result_var_code ? $result_var_code : "echo ") . str_replace("\n", "\n$prefix", $task_code) . "\n";
			}
			
			break;
			
		case "callbusinesslogic":
		case "callibatisquery":
		case "callhibernatemethod":
		case "getquerydata":
		case "setquerydata":
		case "callfunction":
		case "callobjectmethod":
		case "restconnector":
		case "soapconnector":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			$action_value = searchParametersForVariablesWithWrongType($action_value);
			
			$task = $WorkFlowTaskHandler->getTasksByTag($action_type);
			$task = $task[0];
			$task["properties"] = $action_value;
			$task["obj"]->data = $task;
			
			$task_code = trim($task["obj"]->printCode(null, null));
			
			if ($task_code)
				$code .= $prefix . $result_var_code . str_replace("\n", "\n$prefix", $task_code) . "\n";
			
			break;
		
		case "insert":
		case "update":
		case "delete":
		case "select":
		case "procedure":
		case "getinsertedid":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			
			//prepare options
			if ($action_value["options_type"] == "array") {
				$options = is_array($action_value["options"]) ? $action_value["options"] : array();
				
				if ($action_value["db_driver"])
					$options["db_driver"] = array(
						"key" => "db_driver",
						"key_type" => "string",
						"value" => $action_value["db_driver"],
						"value_type" => "string",
					);
				
				$code .= $prefix . '$options = ' . trim(WorkFlowTask::getArrayString($options, $prefix)) . ';' . "\n";
			}
			else if ($action_value["options_type"] == "variable") {
				$code .= $prefix . '$options = ' . CMSPresentationLayerHandler::getArgumentCode($action_value["options"], $action_value["options_type"]) . ';' . "\n";
				
				if ($action_value["db_driver"])
					$code .= $prefix . '$options["db_driver"] = ' . CMSPresentationLayerHandler::getArgumentCode($action_value["db_driver"], "string") . ';' . "\n";
			}
			else if ($action_value["options"] && $action_value["db_driver"]) //if string, overwrites it with db_driver. If no db_driver discards string
				$code .= $prefix . '$options = array("db_driver" => ' . prepareStringValue($action_value["db_driver"]) . ');' . "\n";
			else
				$code .= $prefix . '$options = array();' . "\n";
			
			//prepare sql
			$broker = '$EVC->getBroker(' . prepareStringValue($action_value["dal_broker"]) . ')';
			
			if ($action_type == "getinsertedid")
				$code .= $prefix . $result_var_code . $broker . '->getInsertedId($options);' . "\n";
			else {
				$sql = $action_value["sql"];
				
				if ($action_value["table"] && $action_type != "procedure") {
					$data = array(
						"type" => $action_type,
						"main_table" => $action_value["table"],
						"attributes" => $action_value["attributes"],
						"conditions" => $action_value["conditions"],
					);
					
					$code .= $prefix . '$sql_data = ' . trim(var_export($data, true)) . ';' . "\n";
					$code .= $prefix . '$sql = ' . $broker . '->getFunction("convertObjectToSQL", array($sql_data), $options);' . "\n";
				}
				
				//prepare get or set sql to DB
				if ($action_type == "select" || $action_type == "procedure") {
					$code .= $prefix . 'unset($options["return_type"]); //just in case if it exists' . "\n";
					$code .= $prefix . '$result = ' . $broker . '->getData($sql, $options);' . "\n";
					$code .= $prefix . $result_var_code . '$result["result"];' . "\n";
				}
				else
					$code .= $prefix . $result_var_code . $broker . '->setData($sql, $options);' . "\n";
			}
			break;
		
		case "show_ok_msg":
		case "show_ok_msg_and_stop":
		case "show_ok_msg_and_die":
		case "show_ok_msg_and_redirect":
		case "show_error_msg":
		case "show_error_msg_and_stop":
		case "show_error_msg_and_die":
		case "show_error_msg_and_redirect":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			
			$message = $action_value["message"];
			$ok_message = strpos($action_type, "_ok_") ? $message : null;
			$error_message = strpos($action_type, "_error_") ? $message : null;
			$redirect_url = strpos($action_type, "_redirect") ? $action_value["redirect_url"] : null;
			
			$head_code[] = 'include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);';
			echo "ok_message:$ok_message\nerror_message:$error_message\nredirect_url:$redirect_url\n";
			
			$code .= $prefix . $result_var_code . '\CommonModuleUI::getModuleMessagesHtml($EVC, ' . prepareStringValue($ok_message) . ', ' . prepareStringValue($error_message) . ', ' . prepareStringValue($redirect_url) . ');' . "\n";
			
			if (strpos($action_type, "_die"))
				$code .= $prefix . "die();\n";
			else if (strpos($action_type, "_stop"))
				$code .= $prefix . "return;\n";
			break;
			
		case "alert_msg":
		case "alert_msg_and_stop":
		case "alert_msg_and_redirect":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			$message = $action_value["message"];
			$redirect_url = strpos($action_type, "_redirect") ? $action_value["redirect_url"] : null;
			
			$code .= $prefix . 'echo \'<script>'
				. ($message ? addcslashes('alert("' . addcslashes($message, '"') . '");', "'") : '')
				. ($redirect_url ? addcslashes('document.location="' . addcslashes($redirect_url, '"') . '";', "'") : '')
			. '</script>\';' . "\n";
			
			if (strpos($action_type, "_stop"))
				$code .= "return;\n";
			
			break;
			
		case "redirect": //getting redirect settings
			$code .= $prefix . ($result_var_code ? $result_var_code : "echo ") . '\'<script>document.location="' . addcslashes($action_value, '"') . '";</script>\';' . "\n";
			break;
		
		case "return_previous_record":
		case "return_next_record":
		case "return_specific_record":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			
			$records_variable_name = trim($action_value["records_variable_name"]);
			$index_variable_name = trim($action_value["index_variable_name"]);
			
			//it could be a real variable with already an array inside
			if (substr($records_variable_name, 0, 1) == '$')
				$code .= $prefix . '$records = ' . $records_variable_name . ';' . "\n";
			else
				$code .= $prefix . '$records = $results[' . prepareStringValue($records_variable_name) . '];' . "\n";
			
			$code .= $prefix . "\n";
			$code .= $prefix . 'if (is_array($records)) {' . "\n";
			$code .= $prefix . '	$index = ' . prepareStringValue($index_variable_name) . ";\n";
			$code .= $prefix . '	$index = $index && !is_numeric($index) && is_string($index) ? $_GET[$index] : $index;' . "\n";
			$code .= $prefix . '	$index = is_numeric($index) ? $index : 0;' . "\n";
			$code .= $prefix . "\n";
			
			if ($action_type == "return_previous_record")
				$code .= $prefix . '	$index--;'. "\n";
			else if ($action_type == "return_next_record")
				$code .= $prefix . '	$index++;'. "\n";
			
			$code .= $prefix . "\n";
			$code .= $prefix . "\t" . $result_var_code . '$records[$index];' . "\n";
			$code .= $prefix . "}\n";
			break;
			
		case "check_logged_user_permissions":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			
			$all_permissions_checked = !empty($action_value["all_permissions_checked"]);
			$users_perms = $action_value["users_perms"];
			$entity_path_var_name = trim($action_value["entity_path_var_name"]) ? trim($action_value["entity_path_var_name"]) : '$entity_path';
			$entity_path_var_name = (substr($entity_path_var_name, 0, 1) != '$' ? '$' : '') . $entity_path_var_name;
			$entity_path = $entity_path_var_name;
			$logged_user_id = $action_value["logged_user_id"];
			
			if ($users_perms) {
				//prepare users_perms
				$exists_public_access = false;
				$new_users_perms = array();
				
				foreach ($users_perms as $user_perm) 
					if ($user_perm["user_type_id"] == \UserUtil::PUBLIC_USER_TYPE_ID) {
						$exists_public_access = true;
						break;
					}
					else
						$new_users_perms[] = $user_perm;
				
				if (!$exists_public_access || $all_permissions_checked)  {
					if ($logged_user_id && $new_users_perms && $entity_path) {
						$users_perms = $new_users_perms; 
						
						$head_code[] = 'include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);';
						$head_code[] = 'include_once $EVC->getModulePath("user/UserUtil", $common_project_name);';
						
						$code .= $prefix . '$logged_user_id = ' . prepareStringValue($logged_user_id) . ';' . "\n";
						$code .= $prefix . '$object_id = str_replace(APP_PATH, "", ' . prepareStringValue($entity_path) . ');' . "\n";
						$code .= $prefix . '$user_has_permission = false;' . "\n";
						
						$code .= $prefix . "\n";
						$code .= $prefix . 'if ($logged_user_id && $object_id) {' . "\n";
						$code .= $prefix . '	$user_has_permission = true;' . "\n";
						
						$code .= $prefix . "\n";
						$code .= $prefix . '	$object_type_id = \ObjectUtil::PAGE_OBJECT_TYPE_ID;' . "\n";
						$code .= $prefix . '	$object_id = \HashCode::getHashCodePositive($object_id);' . "\n";
						$code .= $prefix . '	$brokers = $EVC->getBrokers();' . "\n";
						$code .= $prefix . "\n";
						$code .= $prefix . '	$utaos = \UserUtil::getUserTypeActivityObjectsByUserIdAndConditions($brokers, $logged_user_id, array("object_type_id" => $object_type_id, "object_id" => $object_id), null);' . "\n";
						
						$code .= $prefix . "\n";
						$code .= $prefix . '	if ($utaos) {' . "\n";
						$code .= $prefix . '		$entered = false;' . "\n";
						
						$code .= $prefix . "\n";
						$code .= $prefix . '		$users_perms = ' . trim(WorkFlowTask::getArrayString($users_perms, "$prefix\t\t")) . ';' . "\n";
						$code .= $prefix . '		$all_permissions_checked = ' . ($all_permissions_checked ? "true" : "false") . ';' . "\n";
						
						$code .= $prefix . "\n";
						$code .= $prefix . '		foreach ($users_perms as $user_perm) ' . "\n";
						$code .= $prefix . '			if (is_numeric($user_perm["user_type_id"]) && is_numeric($user_perm["activity_id"])) {' . "\n";
						$code .= $prefix . '				if (!$entered && !$all_permissions_checked) //only happens on the first iteration and if $all_permissions_checked is false' . "\n";
						$code .= $prefix . '					$user_has_permission = false;' . "\n";
								
						$code .= $prefix . "\n";
						$code .= $prefix . '				$entered = true;' . "\n";
								
						$code .= $prefix . "\n";
						$code .= $prefix . '				$user_perm_exists = false;' . "\n";
						$code .= $prefix . '				foreach ($utaos as $utao)' . "\n";
						$code .= $prefix . '					if ($utao["user_type_id"] == $user_perm["user_type_id"] && $utao["activity_id"] == $user_perm["activity_id"]) {' . "\n";
						$code .= $prefix . '						$user_perm_exists = true;' . "\n";
						$code .= $prefix . '						break;' . "\n";
						$code .= $prefix . '					}' . "\n";
								
						$code .= $prefix . "\n";
						$code .= $prefix . '				if ($all_permissions_checked && !$user_perm_exists) {' . "\n";
						$code .= $prefix . '					$user_has_permission = false;' . "\n";
						$code .= $prefix . '					break;' . "\n";
						$code .= $prefix . '				}' . "\n";
						$code .= $prefix . '				else if (!$all_permissions_checked && $user_perm_exists) {' . "\n";
						$code .= $prefix . '					$user_has_permission = true;' . "\n";
						$code .= $prefix . '					break;' . "\n";
						$code .= $prefix . '				}' . "\n";
						$code .= $prefix . '			}' . "\n";
						$code .= $prefix . '	}' . "\n";
						$code .= $prefix . '}' . "\n";
						
						if ($result_var_code)
							$code .= $prefix . $result_var_code . '$user_has_permission;' . "\n";
					}
				}
			}
			break;
			
		case "code": //getting code settings
			$action_value = trim($action_value);
			
			if ($action_value) {
				if ($result_var_code)
					$code .= $prefix . "ob_start();\n";
				
				$start = 0;
				do {
					$pos = strpos($action_value, "<?", $start);
					
					if ($pos !== false) {
						$html = substr($action_value, $start, $pos - $start);
						if ($html)
							$html = $prefix . 'echo ' . CMSPresentationLayerHandler::getArgumentCode($html, "string") . ";\n";
						
						$pos += substr($action_value, $pos, 5) == "<?php" ? 5 : 2;
						$end = strpos($action_value, "?>", $pos);
						$end = $end !== false ? $end : strlen($action_value);
						$php = substr($action_value, $pos, $end - $pos);
						$php = $php ? $prefix . str_replace("\n", "\n$prefix", trim($php)) . "\n" : "";
						
						$code .= $html . $php;
						$start = $end + 2;
					}
				}
				while ($pos !== false);
				
				$last_html = substr($action_value, $start);
				if ($last_html)
					$code .= $prefix . 'echo ' . CMSPresentationLayerHandler::getArgumentCode($last_html, "string") . ";\n";
				
				if ($result_var_code) {
					$code .= $prefix . $result_var_code . "ob_get_contents();\n";
					$code .= $prefix . "ob_end_clean();\n";
				}
			}
			break;
			
		case "string": //getting string settings
			if ($result_var_code) {
				$action_value = replaceActionValuesHashTagWithVariables($action_value);
				$action_value = prepareStringValue($action_value);
				
				$code .= "$prefix$result_var_code$action_value;\n";
			}
			break;
			
		case "array": //getting array settings
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			
			$task = $WorkFlowTaskHandler->getTasksByTag("createform");
			$task = $task[0];
			$task["properties"] = array("form_input_data_type" => "array", "form_input_data" => $action_value);
			$task["obj"]->data = $task;
			
			$task_code = trim($task["obj"]->printCode(null, null));
			$task_code = substr($task_code, strlen("HtmlFormHandler::createHtmlForm(null, "), strlen(");") * -1);
			
			if ($task_code)
				$code .= "$prefix$result_var_code" . str_replace("\n", "\n$prefix", $task_code) . ";\n";
			break;
		
		//getting variable settings. It could be a simply variable name, or a variable with $ or something like #foo[bar]# or a composite type like: "#" . $x . "[bar]#"
		case "variable":
		case "sanitize_variable":
			$action_value = replaceActionValuesHashTagWithVariables($action_value);
			$var = trim($action_value);
			
			if ($var) {
				$fc = substr($var, 0, 1);
				$lc = substr($var, -1);
				
				if (($fc == '"' && $lc == '"') || ($fc == "'" && $lc == "'"))
					$var = prepareStringValue($var);
				else if ($fc != '$') {
					$type = CMSPresentationLayerHandler::getValueType($var, array("non_set_type" => "string", "empty_string_type" => "string"));
					
					if ($type == "string")
						$var = '$'. $var;
				}
				
				if ($result_var_code)
					$code .= "$prefix$result_var_code$var;\n";
			}
			break;
			
		case "list_report":
			$var = $action_value["variable"];
			$var = replaceActionValuesHashTagWithVariables($var);
			$var = trim($var);
			
			if ($var) {
				$fc = substr($var, 0, 1);
				$lc = substr($var, -1);
				
				if (($fc == '"' && $lc == '"') || ($fc == "'" && $lc == "'"))
					$var = prepareStringValue($var);
				else if ($fc != '$') {
					$type = CMSPresentationLayerHandler::getValueType($var, array("non_set_type" => "string", "empty_string_type" => "string"));
					
					if ($type == "string")
						$var = '$'. $var;
				}
				
				$type = $action_value["type"];
				$doc_name = $action_value["doc_name"];
				$continue = $action_value["continue"];
				$content_type = $type == "xls" ? "application/vnd.ms-excel" : "text/plain";
				
				$code .= $prefix . 'header("Content-Type: ' . $content_type . '");' . "\n";
				$code .= $prefix . 'header("Content-Disposition: attachment; filename=\'' . $doc_name . '.' . $type . '\'");' . "\n";
				$code .= $prefix . "\n";
				$code .= $prefix . '$list = ' . $var . ';' . "\n";
				$code .= $prefix . '$str = "";' . "\n";
				$code .= $prefix . "\n";
				$code .= $prefix . 'if ($list && is_array($list)) {' . "\n";
				$code .= $prefix . '	$first_row = $list[ array_keys($list)[0] ];' . "\n";
				$code .= $prefix . "	\n";
				$code .= $prefix . '	if (is_array($first_row)) {' . "\n";
				$code .= $prefix . '		$columns = array_keys($first_row);' . "\n";
				$code .= $prefix . '		$columns_length = count($columns);' . "\n";
				$code .= $prefix . "		\n";
				$code .= $prefix . '		//prepare columns' . "\n";
				$code .= $prefix . '		for ($i = 0; $i < $columns_length; $i++)' . "\n";
				$code .= $prefix . '			$str .= ($i > 0 ? "\t" : "") . $columns[$i];' . "\n";
				$code .= $prefix . "		\n";
				$code .= $prefix . '		//prepare rows' . "\n";
				$code .= $prefix . '		if ($str) {' . "\n";
				$code .= $prefix . '			$str .= "\n";' . "\n";
				$code .= $prefix . "			\n";
				$code .= $prefix . '			foreach ($list as $row)' . "\n";
				$code .= $prefix . '				if (is_array($row)) {' . "\n";
				$code .= $prefix . '					for ($i = 0; $i < $columns_length; $i++)' . "\n";
				$code .= $prefix . '						$str .= ($i > 0 ? "\t" : "") . $row[ $columns[$i] ];' . "\n";
				$code .= $prefix . "					\n";
				$code .= $prefix . '					$str .= "\n";' . "\n";
				$code .= $prefix . "				}\n";
				$code .= $prefix . "		}\n";
				$code .= $prefix . "	}\n";
				$code .= $prefix . "}\n";
				
				if ($result_var_code)
						$code .= "$prefix$result_var_code" . "\$str;\n";
				else
					$code .= $prefix . "echo \$str;\n";
				
				if ($continue == "die")
					$code .= $prefix . "die();\n";
				else if ($continue == "stop")
					$code .= $prefix . "return;\n";
			}
			break;
			
		case "call_block":
			$block = trim($action_value["block"]);
			$block = replaceActionValuesHashTagWithVariables($block);
			
			if ($block) {
				$project = trim($action_value["project"]);
				$project = replaceActionValuesHashTagWithVariables($project);
				
				$code .= $prefix . '$block_local_variables = array();' . "\n";
				$code .= $prefix . 'include $EVC->getBlockPath(' . prepareStringValue($block) . ($project ? ', ' . prepareStringValue($project) : '') . ');' . "\n";
				
				if ($result_var_code)
					$code .= "$prefix$result_var_code" . "\$EVC->getCMSLayer()->getCMSBlockLayer()->getCurrentBlock();\n";
				else
					$code .= $prefix . "echo \$EVC->getCMSLayer()->getCMSBlockLayer()->getCurrentBlock();\n";
			}
			break;
			
		case "include_file":
			$path = trim($action_value["path"]);
			$path = replaceActionValuesHashTagWithVariables($path);
			
			if ($path) {
				$path = prepareStringValue($path);
				$once = !empty($action_value["once"]);
				
				$code .= $prefix . ($result_var_code ? $result_var_code : "") . 'include' . ($once ? '_once' : '') . ' ' . $path . ";\n";
			}
			break;
		
		case "draw_graph":
			if (is_array($action_value)) {
				if (array_key_exists("code", $action_value))
					$code .= "?>\n" . replaceActionValuesHashTagWithVariables($action_value["code"]) . "\n<?php\n"; //no prefix here bc is html
				else {
					$include_graph_library = replaceActionValuesHashTagWithVariables($action_value["include_graph_library"]);
					$width = replaceActionValuesHashTagWithVariables($action_value["width"]);
					$height = replaceActionValuesHashTagWithVariables($action_value["height"]);
					$labels_and_values_type = replaceActionValuesHashTagWithVariables($action_value["labels_and_values_type"]);
					$labels_variable = replaceActionValuesHashTagWithVariables($action_value["labels_variable"]);
					
					$labels_variable_code = prepareStringValue($labels_variable);
					$data_sets_code = '';
					$default_type = null;
					
					if ($action_value["data_sets"]) {
						$data_sets = $action_value["data_sets"];
						
						if (isset($data_sets["values_variable"]))
							$data_sets = array($data_sets);
						
						$count = 1;
						$options_names = array(
							"values_variable" => "data",
							"item_label" => "label", 
							"background_colors" => "backgroundColor", 
							"border_colors" => "borderColor", 
							"border_width" => "borderWidth"
						);
						
						foreach ($data_sets as $data_set) {
							if ($data_set) {
								$data_set_code = '';
								
								if (!isset($data_set["order"]))
									$data_set["order"] = $count;
								
								foreach ($data_set as $key => $value) {
									$value = replaceActionValuesHashTagWithVariables($value);
									
									if ($key) {
										$option_name = $options_names[$key] ? $options_names[$key] : $key;
										$is_valid = !empty($value) || is_numeric($value) || !isset($options_names[$key]);
										
										if ($key == "type") {
											if (!$default_type)
												$default_type = $value;
										
											$is_valid = $is_valid && $value != $default_type;
										}
										else if ($key == "border_width")
											$is_valid = $is_valid || is_numeric($value);
										else if ($key == "values_variable" && $labels_and_values_type == "associative") {
											$labels_variable_code = prepareStringValue($value);
											$labels_variable_code = $labels_and_values_type == "associative" ? 'is_array(' . $labels_variable_code . ') ? array_keys(' . $labels_variable_code . ') : null' : $labels_variable_code;
											$labels_and_values_type = null;
										}
										
										if ($is_valid)
											$data_set_code .= ($data_set_code ? ",\n              " : "") . $option_name . ': \' . json_encode(' . prepareStringValue($value) . ') . \'';
									}
									
								}
									
								$data_sets_code .= '
          {
              ' . $data_set_code . '
          },';
          						$count++;
          					}
						}
					}
					
					$rand = rand(0, 1000);
					
					$code .= $prefix . 'echo \'';
					
					if ($include_graph_library == "cdn_even_if_exists")
						$code .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>' . "\n\n";
					else if ($include_graph_library == "cdn_if_not_exists")
						$code .= '<script>
if (typeof Chart != "function")
	document.write("<scr" + "ipt src=\"https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js\"></scr" + "ipt>");
</script>' . "\n\n";
					
					$code .= '
<canvas id="my_chart_' . $rand . '"' . ($width || is_numeric($width) ? ' width="\' . ' . prepareStringValue($width) . ' . \'"' : '') . ($height || is_numeric($height) ? ' height="\' . ' . prepareStringValue($height) . ' . \'"' : '') . '></canvas>

<script>
var ctx = document.getElementById("my_chart_' . $rand . '").getContext("2d");
var myChart = new Chart(ctx, {
    type: "\' . ' . prepareStringValue($default_type) . ' . \'",
    data: {
        ' . ($labels_variable_code || is_numeric($labels_variable_code) ? 'labels: \' . json_encode(' . $labels_variable_code . ') . \',' : '') . '
        datasets: [' . $data_sets_code . '
        ]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>\';';
				}
			}
			break;
		
		case "loop": //getting string settings
			if ($action_value["actions"]) {
				if ($result_var_code)
					$code .= $prefix . "ob_start();\n\n";
				
				$records_variable_name = replaceActionValuesHashTagWithVariables(trim($action_value["records_variable_name"]));
				$records_start_index = replaceActionValuesHashTagWithVariables(trim($action_value["records_start_index"]));
				$records_end_index = replaceActionValuesHashTagWithVariables(trim($action_value["records_end_index"]));
				$array_item_key_variable_name = replaceActionValuesHashTagWithVariables(trim($action_value["array_item_key_variable_name"]));
				$array_item_value_variable_name = replaceActionValuesHashTagWithVariables(trim($action_value["array_item_value_variable_name"]));
				
				//it could be a real variable with already an array inside
				if (substr($records_variable_name, 0, 1) == '$')
					$code .= $prefix . '$records = ' . $records_variable_name . ';' . "\n";
				else
					$code .= $prefix . '$records = $results[' . prepareStringValue($records_variable_name) . '];' . "\n";
				
				$code .= $prefix . "\n";
				$code .= $prefix . 'if (is_array($records)) {' . "\n";
				$code .= $prefix . '	$records_start_index = ' . prepareStringValue($records_start_index) . ";\n";
				$code .= $prefix . '	$records_start_index = is_numeric($records_start_index) ? $records_start_index : 0;' . "\n";
				$code .= $prefix . '	$records_end_index = ' . prepareStringValue($records_end_index) . ";\n";
				$code .= $prefix . '	$records_end_index = is_numeric($records_end_index) ? $records_end_index : count($records);' . "\n";
				
				$code .= $prefix . "\n";
				$code .= $prefix . '	$i = 0;' . "\n";
				$code .= $prefix . '	foreach ($records as $k => $v) {' . "\n";
				$code .= $prefix . '		if ($i >= $records_end_index)' . "\n";
				$code .= $prefix . '			break;' . "\n";
				$code .= $prefix . '		else if ($i >= $records_start_index) {' . "\n";
				
				if ($array_item_key_variable_name)
					$code .= $prefix . '		$' . $array_item_key_variable_name . ' = $k;' . "\n";
				
				if ($array_item_value_variable_name)
					$code .= $prefix . '		$' . $array_item_value_variable_name . ' = $v;' . "\n";
				
				$code .= getActionsCode($EVC, $WorkFlowTaskHandler, $action_value["actions"], $result_var_prefix, $head_code, "$prefix\t\t");
				$code .= $prefix . '		}' . "\n";
				$code .= $prefix . "\n";
				$code .= $prefix . '		++$i;' . "\n";
				$code .= $prefix . '	}' . "\n";
				$code .= $prefix . "}\n";
				
				if ($result_var_code) {
					$code .= $prefix . "\n";
					$code .= $prefix . $result_var_code . "ob_get_contents();\n";
					$code .= $prefix . "ob_end_clean();\n";
				}
			}
			break;
			
		case "group": //getting string settings
			if ($action_value["actions"]) {
				if ($result_var_code)
					$code .= $prefix . "ob_start();\n\n";
				
				$group_name = replaceActionValuesHashTagWithVariables(trim($action_value["group_name"]));
				
				if ($group_name) {
					$group_name = $result_var_prefix . "[" . prepareStringValue($group_name) . "] = ";
					$code .= getActionsCode($EVC, $WorkFlowTaskHandler, $action_value["actions"], $group_name, $head_code, $prefix);
				}
				else
					$code .= getActionsCode($EVC, $WorkFlowTaskHandler, $action_value["actions"], $result_var_prefix, $head_code, $prefix);
				
				if ($result_var_code) {
					$code .= $prefix . "\n";
					$code .= $prefix . $result_var_code . "ob_get_contents();\n";
					$code .= $prefix . "ob_end_clean();\n";
				}
			}
			break;
	}
	
	if ($code) {
		$comment = "/*** ACTION: " . strtoupper($action_type) . "***/\n";
		$result_var_extra_code = $result_var_code ? "$prefix" . '$' . $result_var_name . " = &" . trim(str_replace(" = ", "", $result_var_code)) . ";\n" : "";
		$action_code = $code;
		$code = "";
		
		if ($if) {
			$prefix = substr($prefix, 0, -1);
			
			$code .= "\n$prefix$comment";
			$code .= $prefix . $if . "\n";
			$code .= $action_code;
			$code .= $result_var_extra_code;
			$code .= $prefix . "}\n";
		}
		else
			$code .= "\n$prefix$comment$action_code$result_var_extra_code";
	}
	
	return $code;
}

function replaceEscapedVariables($value) {
	if (is_array($value))
		foreach ($value as $key => $item)
			$value[$key] = replaceEscapedVariables($item);
	else if ($value && is_string($value) && strpos($value, '$') !== false) {
		$odq = $osq = false;
		$ophpt = true;
		$t = strlen($value);
		$new_value = "";
		
		for ($i = 0; $i < $t; $i++) {
			$char = $value[$i];
			
			if ($char == "<" && $value[$i + 1] == "?" && !$odq && !$osq)
				$ophpt = true;
			else if ($char == "?" && $value[$i + 1] == ">" && !$odq && !$osq)
				$ophpt = false;
			else if ($char == '"' && $ophpt && !$osq && !TextSanitizer::isCharEscaped($value, $i))
				$odq = !$odq;
			else if ($char == "'" && $ophpt && !$odq && !TextSanitizer::isCharEscaped($value, $i))
				$osq = !$osq;
			else if ($char == '$' && ($value[$i + 1] == "{" || preg_match("/\w/u", $value[$i + 1])) && $ophpt && TextSanitizer::isCharEscaped($value, $i)) //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
				$new_value = substr($new_value, 0, -1);
			
			$new_value .= $char;
		}
		
		$value = $new_value;
	}
	
	return $value;
}

function replaceActionValuesHashTagWithVariables($value) {
	if (is_array($value))
		foreach ($value as $key => $item)
			$value[$key] = replaceActionValuesHashTagWithVariables($item);
	else if ($value && is_string($value) && strpos($value, "#") !== false) {
		$regex = "/#([\w\"' \-\+\[\]\.\\\$]+)#/u"; //'\w' means all words with '_' and '/u' means with accents and ç too. '/u' converts unicode to accents chars. 
		preg_match_all($regex, $value, $matches, PREG_OFFSET_CAPTURE);//PREG_PATTERN_ORDER 
		
		if ($matches[1]) {
			$global_vars = array("_POST", "_GET", "_GLOBALS", "_ENV");
			$t = count($matches[1]);
			
			for ($i = 0; $i < $t; $i++) {
				$m = $matches[1][$i][0];
				$replacement = "";
				//echo "m($value):$m<br>";
				
				$exists_global_var = false;
				foreach ($global_vars as $gv)
					if (stripos($m, $gv) === 0) {
						$exists_global_var = true;
						break;
					}
				
				if (strpos($m, "[") !== false) { //if value == #[0]name# or #[$idx - 1][name]#, returns $input_data[0]["name"] or $input_data[$idx - 1]["name"]
					preg_match_all("/([^\[\]]+)/u", trim($m), $sub_matches, PREG_PATTERN_ORDER); //'/u' means converts to unicode.
					$sub_matches = $sub_matches[1];
					
					if ($sub_matches) {
						//echo "1:";print_r($sub_matches);
						
						if ($exists_global_var)
							$gv = array_shift($sub_matches);
						
						$t2 = count($sub_matches);
						for ($j = 0; $j < $t2; $j++) {
							$sml = strtolower($sub_matches[$j]);
							$sub_matches[$j] = prepareStringValue($sml);
						}
						
						$replacement = ($exists_global_var ? '$' . strtoupper($gv) : '$results') . '[' . implode('][', $sub_matches) . ']';
					}
				}
				else if ($exists_global_var) //if #_POST# or #_GET#
					$replacement = '$' . $m;
				else //if $value == #name#, returns $input_data["name"]
					$replacement = '$results["' . $m . '"]';
				
				if ($replacement)
					$value = str_replace("#$m#", $replacement, $value);
			}
		}
	}
	
	return $value;
}

function searchParametersForVariablesWithWrongType($value) {
	if (is_array($value))
		foreach ($value as $key => $item) 
			if (is_string($item) && array_key_exists($key . "_type", $value) && $value[$key . "_type"] == "string" && CMSPresentationLayerHandler::isSimpleVariable($item))
				$value[$key . "_type"] = "";
			else if (is_array($item))
				$value[$key] = searchParametersForVariablesWithWrongType($item);
	
	return $value;
}

function prepareStringValue($value) {
	if ($value && substr($value, 0, 2) == '\\$' && CMSPresentationLayerHandler::isSimpleVariable(substr($value, 1))) //is escaped var
		return substr($value, 1);
	
	$type = CMSPresentationLayerHandler::getValueType($value, array("non_set_type" => "string", "empty_string_type" => "string"));
	return CMSPresentationLayerHandler::getArgumentCode($value, $type);
}
?>
