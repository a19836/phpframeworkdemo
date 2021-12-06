<?php
namespace CMSModule\form;

include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler");

class CMSModuleHandlerImpl extends \CMSModuleHandler {
	
	private $xss_sanitize_lib_included = false;
	
	public function execute(&$settings = false) {
		$EVC = $this->getEVC();
		$common_project_name = $EVC->getCommonProjectName();
		
		include_once $EVC->getModulePath("common/CommonModuleUI", $common_project_name);
		include_once $EVC->getModulePath("object/ObjectUtil", $common_project_name);
		include_once $EVC->getModulePath("user/UserUtil", $common_project_name);
		
		$html = '';
		
		//load old form settings - Do not remove this code until all the old forms have the new settings
		if ($settings[0]) {
			$form_settings = $settings[0];
			$input_data = $settings[1];
			
			translateProjectFormSettings($EVC, $form_settings);
			
			$form_settings["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
			
			$html = \HtmlFormHandler::createHtmlForm($form_settings, $input_data);
		}
		else if ($settings) {
			foreach ($settings as $type => $value) {
				switch ($type) {
					case "actions":
						$results = array(
							"EVC" => $EVC,
							"_GET" => $_GET,
							"_POST" => $_POST,
							"_REQUEST" => $_REQUEST,
						);
						
						$html .= $this->executeActions($value, $results);
						break;
					
					case "css":
						$html .= $value ? "<style>$value</style>" : "";
						break;
					
					case "js":
						$html .= $value ? "<script>$value</script>" : "";
						break;
				}
			}
		}
		
		return $html;
	}
	
	private function executeActions($actions, &$results, &$stop = false, &$die = false) {
		$html = '';
		
		if (is_array($actions)) 
			foreach ($actions as $idx => $item_settings) 
				if (!$stop) {
					$status = $this->executeCondition($item_settings["condition_type"], $item_settings["condition_value"], $results);
					
					if ($status) {
						$action_type = strtolower($item_settings["action_type"]);
						$result = $this->executeAction($action_type, $item_settings["action_value"], $results, $stop, $die);
						
						$result_var_name = trim($item_settings["result_var_name"]);
						$result_var_name = $result_var_name && substr($result_var_name, 0, 1) == '$' ? substr($result_var_name, 1) : $result_var_name;
						
						if ($result_var_name) {
							if (substr($result_var_name, -2) == "[]") { //this allows the user to configure multiple groups where the output can be INSIDE OF an array variable. Example: "$result_var_name = 'arr[]';". Thia is very usefull to concatenate multiple outputs by implode this array variable later on...
								$result_var_name = substr($result_var_name, 0, -2);
								
								if (!is_array($results[ $result_var_name ]))
									$results[ $result_var_name ] = array();
								
								$results[ $result_var_name ][] = $result;
							}
							else
								$results[ $result_var_name ] = $result;
						}
						else
							$html .= $result; //only add to html if not result_var_name
						
						if ($die) {
							echo $html;
							die();
						}
						else if ($stop)
							break;
					}
				}
			
		return $html;
	}

	private function executeCondition($condition_type, $condition_value, &$results) {
		$status = true;
		
		if ($condition_type) {
			$condition_type = strtolower($condition_type);
			
			switch($condition_type) {
				case "execute_if_var": //Only execute if variable exists
				case "execute_if_not_var": //Only execute if variable doesn't exists
					$status = false;
					$var = trim($condition_value);
					
					if (!empty($var)) {
						$var = substr($var, 0, 1) == '$' || substr($var, 0, 2) == '\\$' ? $var : '$' . $var;
						
						$code = '<?= ' . $var . ' ? 1 : 0 ?>';
						$result = \PHPScriptHandler::parseContent($code, $results);
						$status = !empty($result);
					}
					
					if (strpos($condition_type, "_not_") !== false)
						$status = !$status;
					
					break;
				
				case "execute_if_post_button": //Only execute if submit button was clicked via POST
					$button_name = trim($condition_value);
					$button_name = substr($button_name, 0, 1) == '$' ? substr($button_name, 1) : $button_name;
					
					$status = $button_name ? !empty($_POST[$button_name]) : false;
					break;
				case "execute_if_not_post_button": //Only execute if submit button was not clicked via POST
					$button_name = trim($condition_value);
					$button_name = substr($button_name, 0, 1) == '$' ? substr($button_name, 1) : $button_name;
					
					$status = $button_name ? empty($_POST[$button_name]) : true;
					break;
				
				case "execute_if_get_button": //Only execute if submit button was clicked via GET
					$button_name = trim($condition_value);
					$button_name = substr($button_name, 0, 1) == '$' ? substr($button_name, 1) : $button_name;
					
					$status = $button_name ? !empty($_GET[$button_name]) : false;
					break;
				case "execute_if_not_get_button": //Only execute if submit button was not clicked via GET
					$button_name = trim($condition_value);
					$button_name = substr($button_name, 0, 1) == '$' ? substr($button_name, 1) : $button_name;
					
					$status = $button_name ? empty($_GET[$button_name]) : true;
					break;
				
				case "execute_if_previous_action": //Only execute if previous action executed correctly
					$status = $results ? !empty($results[count($results) - 1]) : false;
					break;
				case "execute_if_not_previous_action": //Only execute if previous action was not executed correctly
					$status = $results ? empty($results[count($results) - 1]) : true;
					break;
				
				case "execute_if_condition": //Only execute if condition is valid
				case "execute_if_not_condition": //Only execute if condition is invalid
				case "execute_if_code": //Only execute if code is valid
				case "execute_if_not_code": //Only execute if code is invalid
					$status = false;
					
					if (is_numeric($condition_value))
						$status = !empty($condition_value);
					else if ($condition_value === true)
						$status = true;
					else if ((is_array($condition_value) || is_object($condition_value)) && !empty($condition_value))
						$status = true;
					else if (!empty($condition_value)) {
						$code = '<?= ' . $condition_value . ' ?>';
						$result = \PHPScriptHandler::parseContent($code, $results);
						$status = !empty($result);
					}
					
					if (strpos($condition_type, "_not_") !== false)
						$status = !$status;
					
					break;
			}
		}
		
		return $status;
	}

	private function executeAction($action_type, $action_value, &$results, &$stop = false, &$die = false) {
		$result = null;
		
		if ($action_type) {
			$action_type = strtolower($action_type);
			$EVC = $this->getEVC();
			
			switch ($action_type) {
				case "html":
					if (is_array($action_value)) {
						translateProjectFormSettings($EVC, $action_value);
						$action_value["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler");
						
						if (isset($action_value["ptl"]["code"])) {
							if (is_array($action_value["ptl"]["external_vars"]))
								$action_value["ptl"]["external_vars"] = array_merge($results, $action_value["ptl"]["external_vars"]);
							else
								$action_value["ptl"]["external_vars"] = $results;
						}
						
						$result = \HtmlFormHandler::createHtmlForm($action_value, $results);
					}
					else
						$result = $action_value;
					break;
				
				case "callbusinesslogic":
					/*"action_value" => array(
						"method_obj" => $EVC->getBroker("soa"),
						"module_id" => "module.article",
						"service_id" => "ArticleService.getArticle",
						"parameters" => array(
							"article_id" => $_GET['article_id']
						),
						"options" => array(
							"no_cache" => true,
							"db_driver" => "test"
						)
					)
					
					$EVC->getBroker("soa")->callBusinessLogic("module.article", "ArticleService.getArticle", array(
						"article_id" => $_GET['article_id']
					), array(
						"no_cache" => true,
						"db_driver" => "test"
					))*/
					
					$method_obj = $this->getParsedValueFromData($action_value["method_obj"], $results);
					
					if ($method_obj && method_exists($method_obj, "callBusinessLogic")) {
						$module_id = $this->getParsedValueFromData($action_value["module_id"], $results);
						$service_id = $this->getParsedValueFromData($action_value["service_id"], $results);
						$parameters = $this->getParsedValueFromData($action_value["parameters"], $results);
						$options = $this->getParsedValueFromData($action_value["options"], $results);
						
						$result = $method_obj->callBusinessLogic($module_id, $service_id, $parameters, $options);
					}
					else
						launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain callBusinessLogic method!'));
					
					break;
					
				case "callibatisquery":
					/*
					"action_value" => array(
						"method_obj" => $EVC->getBroker("iorm"),
						"module_id" => "condo",
						"service_id" => "insert_ag",
						"service_type" => "insert",
						"parameters" => array(
							"ag_id" => "",
							"condo_id" => "",
							"begin_date" => "",
							"end_date" => "",
							"solicitation" => "",
							"email_worker_id" => "",
							"closed" => "",
							"created_date" => "",
							"modified_date" => ""
						),
						"options" => "dfxcgbgbcfgcf"
					)
					
					$EVC->getBroker("iorm")->callInsert("condo", "insert_ag", array(
						"ag_id" => "",
						"condo_id" => "",
						"begin_date" => "",
						"end_date" => "",
						"solicitation" => "",
						"email_worker_id" => "",
						"closed" => "",
						"created_date" => "",
						"modified_date" => ""
					), array(
						"no_cache" => true
					))
					*/
					
					$exist_method_type = false;
					
					switch($action_value["service_type"]) {
						case "insert":  $method_name = "callInsert"; $exist_method_type = true; break;
						case "update":  $method_name = "callUpdate"; $exist_method_type = true; break;
						case "delete":  $method_name = "callDelete"; $exist_method_type = true; break;
						case "select":  $method_name = "callSelect"; $exist_method_type = true; break;
						case "procedure":  $method_name = "callProcedure"; $exist_method_type = true; break;
						default: $method_name = "callQuery";
					}
					
					$method_obj = $this->getParsedValueFromData($action_value["method_obj"], $results);
					
					if ($method_obj && method_exists($method_obj, $method_name)) {
						$module_id = $this->getParsedValueFromData($action_value["module_id"], $results);
						$service_id = $this->getParsedValueFromData($action_value["service_id"], $results);
						$service_type = $this->getParsedValueFromData($action_value["service_type"], $results);
						$parameters = $this->getParsedValueFromData($action_value["parameters"], $results);
						$options = $this->getParsedValueFromData($action_value["options"], $results);
						
						if ($exist_method_type)
							$result = $method_obj->$method_name($module_id, $service_id, $parameters, $options);
						else
							$result = $method_obj->$method_name($module_id, $service_type, $service_id, $parameters, $options);
					}
					else
						launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain ' . $method_name . ' method!'));
					
					break;
					
				case "callhibernatemethod":
					/*
					"action_value" => array(
						"broker_method_obj_type" => "EVC->getBroker(\"horm\")", //"exists_hbn_var",
						"method_obj" => $EVC->getBroker("horm"),
						"module_id" => "test",
						"service_id" => "City",
						"options" => array(
							"no_cache" => false
						),
						"service_method" => "insert",
						"sma_query_type" => "insert",
						"sma_query_id" => "",
						"sma_data" => array(
							"state_id" => null,
							"name" => "",
							"created_date" => "",
							"modified_date" => ""
						),
						"sma_statuses" => null,
						"sma_ids" => ids,
						"sma_parent_ids" => "",
						"sma_sql" => "",
						"sma_options" => $asdasd
					)
					
					$EVC->getBroker("horm")->callObject("danielgarage", "Car")->insert(array(), null, array(
						"no_cache" => false
					))
					*/
					
					$method_obj = $this->getParsedValueFromData($action_value["method_obj"], $results);
					
					if ($method_obj) {
						if ($action_value["broker_method_obj_type"] != "exists_hbn_var") {
							if (method_exists($method_obj, "callObject")) {
								$module_id = $this->getParsedValueFromData($action_value["module_id"], $results);
								$service_id = $this->getParsedValueFromData($action_value["service_id"], $results);
								$options = $this->getParsedValueFromData($action_value["options"], $results);
						
								$method_obj = $method_obj->callObject($module_id, $service_id, $options);
							}
							else
								launch_exception(new \Exception('$action_value["method_obj"] must contain callObject method that returns a Hibernate Object!'));
						}
						
						$service_method = $action_value["service_method"];
						
						if (method_exists($method_obj, $service_method)) {
							$methods_args = array(
								"insert" => array("data", "ids", "options"),
								"insertAll" => array("data", "statuses", "ids", "options"),
								"update" => array("data", "options"),
								"updateAll" => array("data", "statuses", "options"),
								"insertOrUpdate" => array("data", "ids", "options"),
								"insertOrUpdateAll" => array("data", "statuses", "ids", "options"),
								"delete" => array("data", "options"),
								"deleteAll" => array("data", "statuses", "options"),
								"updatePrimaryKeys" => array("data", "options"),
								"findById" => array("data", "data", "options"),
								"find" => array("data", "options"),
								"count" => array("data", "options"),
								"findRelationships" => array("parent_ids", "options"),
								"findRelationship" => array("rel_name", "parent_ids", "options"),
								"countRelationships" => array("parent_ids", "options"),
								"countRelationship" => array("rel_name", "parent_ids", "options"),
								"callQuerySQL" => array("query_type", "query_id", "data", "options"),
								"callQuery" => array("query_type", "query_id", "data", "options"),
								"callInsertSQL" => array("query_id", "data", "options"),
								"callInsert" => array("query_id", "data", "options"),
								"callUpdateSQL" => array("query_id", "data", "options"),
								"callUpdate" => array("query_id", "data", "options"),
								"callDeleteSQL" => array("query_id", "data", "options"),
								"callDelete" => array("query_id", "data", "options"),
								"callSelectSQL" => array("query_id", "data", "options"),
								"callSelect" => array("query_id", "data", "options"),
								"callProcedureSQL" => array("query_id", "data", "options"),
								"callProcedure" => array("query_id", "data", "options"),
								"getFunction" => array("function_name", "data", "options"),
								"getData" => array("sql", "options"),
								"setData" => array("sql", "options"),
								"getInsertedId" => array("options"),
							);
							
							$method_args = $methods_args[$service_method];
							$args = array();
							$ids = null;
							$var_ids_name = null;
							
							if ($method_args)
								foreach ($method_args as $arg) {
									$v = $this->getParsedValueFromData($action_value["sma_" . $arg], $results);
									
									if ($arg == "ids") { //sma_ids
										$var_ids_name = $v;
										$args[] = &$ids; //passing by reference to $v, so I can get this var later on...
									}
									else
										$args[] = $v;
								}
								
							$result = call_user_func_array(array($method_obj, $service_method), $args);
							
							if ($var_ids_name) //setting the ids from the hibernate insert method and adding them to the $results, so we can use them in other actions...
								$results[$var_ids_name] = $ids;
						}
						else
							launch_exception(new \Exception('$action_value["method_obj"] must contain ' . $service_method . ' method!'));
					}
					else 
						launch_exception(new \Exception('$action_value["method_obj"] cannot be null!'));
					
					break;
					
				case "getquerydata":
				case "setquerydata":
					/*
					"action_value" => array(
						"method_obj" => $EVC->getBroker("iorm"),
						"sql" => "select *from ",
						"options" => null
					)
					
					$EVC->getBroker("iorm")->getData("select * from article")
					*/
					
					$method_obj = $this->getParsedValueFromData($action_value["method_obj"], $results);
					
					if ($method_obj && method_exists($method_obj, "getData")) {
						$sql = $this->getParsedValueFromData($action_value["sql"], $results);
						$options = $this->getParsedValueFromData($action_value["options"], $results);
						
						if ($action_type == "getquerydata")
							$result = $method_obj->getData($sql, $options); //Note that $options already contains (by default) the item "return_type" => "result", so it can return the DB data directly;
						else
							$result = $method_obj->setData($sql, $options);
					}
					else
						launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain getData method!'));
					
					break;
					
				case "callfunction":
					/*
					"action_value" => array(
						"func_name" => "foo",
						"func_args" => array(
							"asd",
							$asd
						)
					)
					
					foo("asd", $asd)
					*/
					
					$func_name = $this->getParsedValueFromData($action_value["func_name"], $results);
					$func_args = $action_value["func_args"] ? $this->getParsedValueFromData($action_value["func_args"], $results) : array();
					
					if ($func_name && function_exists($func_name))
						$result = call_user_func_array($func_name, $func_args);
					else
						launch_exception(new \Exception('$func_name "' . $func_name . '" is not a function!'));
					
					break;
					
				case "callobjectmethod":
					/*
					"action_value" => array(
						"method_obj" => $obj, //Obj
						"method_name" => "foo",
						"method_static" => 0, //1
						"method_args" => array(
							"as",
							"as"?1:0
						)
					)
					
					$obj->foo("as", "as"?1:0)
					Obj::foo("as", "as"?1:0)
					*/
					
					$method_static = $this->getParsedValueFromData($action_value["method_static"], $results);
					$method_obj = $this->getParsedValueFromData($action_value["method_obj"], $results);
					$method_name = $this->getParsedValueFromData($action_value["method_name"], $results);
					$method_args = $action_value["method_args"] ? $this->getParsedValueFromData($action_value["method_args"], $results) : array();
					
					if ($method_static) {
						if (method_exists("\\" . $method_obj, $method_name)) 
							$result = call_user_func_array("\\$method_obj::$method_name", $method_args);
						else
							launch_exception(new \Exception("\\" . $method_obj . ' class must contain ' . $method_name . ' static method!'));
					}
					else if ($method_obj && method_exists($method_obj, $method_name))
						$result = call_user_func_array(array($method_obj, $method_name), $method_args);
					else
						launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain ' . $method_name . ' method!'));
					
					break;
				
				case "restconnector":
					if (is_array($action_value)) {
						include_once get_lib("org.phpframework.connector.RestConnector");
						
						$result = \RestConnector::connect($action_value["data"], $action_value["result_type"]);
					}
					else
						launch_exception(new \Exception('$action_value is not an array with the RestConnector::connect\'s arguments'));
					
					break;
					
				case "soapconnector":
					if (is_array($action_value)) {
						include_once get_lib("org.phpframework.connector.SoapConnector");
						
						$result = \SoapConnector::connect($action_value["data"], $action_value["result_type"]);
					}
					else
						launch_exception(new \Exception('$action_value is not an array with the SoapConnector::connect\'s arguments'));
					
					break;
				
				case "insert":
				case "update":
				case "delete":
				case "select":
				case "procedure":
				case "getinsertedid":
					$dal_broker = $this->getParsedValueFromData($action_value["dal_broker"], $results);
					$db_driver = $this->getParsedValueFromData($action_value["db_driver"], $results);
					
					if (!$dal_broker)
						launch_exception(new \Exception("DAL Broker not selected!"));
					
					$broker = $EVC->getBroker($dal_broker);
					
					if (!$broker) 
						launch_exception(new \Exception("Broker '" . $dal_broker . "' does NOT exist!"));
					
					$options = $this->getParsedValueFromData($action_value["options"], $results);
					
					if ($db_driver) {
						$options = is_array($options) ? $options : ($options ? array($options) : array());
						$options["db_driver"] = $db_driver;
					}
					
					if ($action_type == "getinsertedid") 
						$result = $broker->getInsertedId($options);
					else {
						$table = $this->getParsedValueFromData($action_value["table"], $results);
						$sql = $action_value["sql"];
						
						if ($table && $action_type != "procedure") {
							$attributes = $this->getParsedValueFromData($action_value["attributes"], $results);
							$conditions = $this->getParsedValueFromData($action_value["conditions"], $results);
							
							$attrs = array();
							$conds = array();
							
							if (is_array($attributes)) 
								foreach ($attributes as $attr) 
									if ($attr["column"])
										$attrs[ $attr["column"] ] = $action_type == "select" ? $attr["name"] : $attr["value"];
							
							if (is_array($conditions)) 
								foreach ($conditions as $condition) 
									if ($condition["column"])
										$conds[ $condition["column"] ] = $condition["value"];
							
							switch ($action_type) {
								case "insert":
									$result = $broker->insertObject($table, $attrs, $options);
									break;
								case "update":
									$result = $broker->updateObject($table, $attrs, $conds, $options);
									break;
								case "delete":
									$result = $broker->deleteObject($table, $conds, $options);
									break;
								case "select":
									$result = $broker->findObjects($table, $attrs, $conds, $options);
									break;
							}
						}
						else if (!$sql)
							launch_exception(new \Exception("Sql cannot be empty for '$action_type' action!"));
						else {
							$sql = $this->getParsedValueFromData($sql, $results);
							
							if ($action_type == "select" || $action_type == "procedure") {
								if (is_array($options))
									unset($options["return_type"]); //just in case if it exists
								
								$result = $broker->getData($sql, $options);
								$result = $result["result"];
							}
							else
								$result = $broker->setData($sql, $options);
						}
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
					$message = $this->getParsedValueFromData($action_value["message"], $results);
					$ok_message = strpos($action_type, "_ok_") ? $message : null;
					$error_message = strpos($action_type, "_error_") ? $message : null;
					$redirect_url = strpos($action_type, "_redirect") ? $this->getParsedValueFromData($action_value["redirect_url"], $results) : null;
					$result = \CommonModuleUI::getModuleMessagesHtml($EVC, $ok_message, $error_message, $redirect_url);
					
					if (strpos($action_type, "_die"))
						$die = true;
					else if (strpos($action_type, "_stop"))
						$stop = true;
					
					break;
					
				case "alert_msg":
				case "alert_msg_and_stop":
				case "alert_msg_and_redirect":
					$message = $this->getParsedValueFromData($action_value["message"], $results);
					$redirect_url = strpos($action_type, "_redirect") ? $this->getParsedValueFromData($action_value["redirect_url"], $results) : null;
					
					$result = '<script>
						' . ($message ? 'alert("' . addcslashes($message, '"') . '");' : '') . '
						' . ($redirect_url ? 'document.location="' . addcslashes($redirect_url, '"') . '";' : '') . '
					</script>';
					
					if (strpos($action_type, "_stop"))
						$stop = true;
					
					break;
				
				case "redirect":
					$redirect_url = $this->getParsedValueFromData($action_value, $results);
					
					if ($redirect_url) 
						$result = '<script>document.location="' . addcslashes($redirect_url, '"') . '";</script>';
					break;
				
				case "refresh":
					$result = '<script>var url = document.location; document.location=url;</script>';
					break;
				
				case "return_previous_record":
				case "return_next_record":
				case "return_specific_record":
					$records_variable_name = $this->getParsedValueFromData($action_value["records_variable_name"], $results);
					$records = !is_array($records_variable_name) ? $results[$records_variable_name] : $records_variable_name;
					
					if (is_array($records)) {
						$index_variable_name = $this->getParsedValueFromData($action_value["index_variable_name"], $results);
						$current_index = is_numeric($index_variable_name) ? $index_variable_name : $_GET[$index_variable_name];
						$current_index = is_numeric($current_index) ? $current_index : 0;
						
						if ($action_type == "return_previous_record")
							$current_index--;
						else if ($action_type == "return_next_record")
							$current_index++;
						
						$result = $records[$current_index];
					}
					
					break;
				
				case "check_logged_user_permissions":
					$all_permissions_checked = $this->getParsedValueFromData($action_value["all_permissions_checked"], $results);
					$users_perms = $action_value["users_perms"];
					$entity_path = $action_value["entity_path"];
					$logged_user_id = $action_value["logged_user_id"];
					
					$result = $this->validateUserPermissions($entity_path, $logged_user_id, $users_perms, $all_permissions_checked);
					break;
				
				case "code": //Only execute if code is invalid
					$return_values = array();
					$action_value = $this->getParsedValueFromData($action_value, $results, false);
					$result = \PHPScriptHandler::parseContent($action_value, $results, $return_values);
					
					if (isset($return_values[0]) && $return_values[0] !== false) //bc eval returns false on error and null if no return...
						$result = $return_values[0];
					
					break;
				
				case "sanitize_variable":
					if (!$this->xss_sanitize_lib_included)
						include_once get_lib("org.phpframework.util.web.html.XssSanitizer"); //leave this here, otherwise it could be over-loading for every request to include without need it...
					
					$this->xss_sanitize_lib_included = true;
					
					$result = $this->getParsedValueFromData($action_value, $results);
					$result = \XssSanitizer::sanitizeVariable($result);
					break;
				
				case "list_report":
					$type = $action_value["type"];
					$continue = $action_value["continue"];
					$doc_name = $action_value["doc_name"];
					$var = $action_value["variable"];
					$list = $this->getParsedValueFromData($var, $results);
					
					//set header
					$content_type = $type == "xls" ? "application/vnd.ms-excel" : ($type == "csv" ? "text/csv" : "text/plain");
					header("Content-Type: $content_type");
					header('Content-Disposition: attachment; filename="' . $doc_name . '.' . $type . '"');
					
					//set output
					$str = "";
					
					if ($list && is_array($list)) {
						$first_row = $list[ array_keys($list)[0] ];
						
						if (is_array($first_row)) {
							$columns = array_keys($first_row);
							$columns_length = count($columns);
							
							$rows_delimiter = "\n";
							$columns_delimiter = "\t";
							$enclosed_by = "";
							
							if ($type == "csv") {
								$columns_delimiter = ",";
								$enclosed_by = '"';
								
								$str .= "sep=$columns_delimiter$rows_delimiter"; //Alguns programas, como o Microsoft Excel 2010, requerem ainda um indicador "sep=" na primeira linha do arquivo, apontando o caráter de separação.
							}
							
							//prepare columns
							for ($i = 0; $i < $columns_length; $i++)
								$str .= ($i > 0 ? $columns_delimiter : "") . $enclosed_by . addcslashes($columns[$i], $columns_delimiter . $enclosed_by . "\\") . $enclosed_by;
							
							//prepare rows
							if ($str) {
								$str .= $rows_delimiter;
								
								foreach ($list as $row)
									if (is_array($row)) {
										for ($i = 0; $i < $columns_length; $i++)
											$str .= ($i > 0 ? $columns_delimiter : "") . $enclosed_by . addcslashes($row[ $columns[$i] ], $columns_delimiter . $enclosed_by . "\\") . $enclosed_by;
										
										$str .= $rows_delimiter;
									}
							}
						}
					}
					
					$result = $str;
					
					if ($continue == "die")
						$die = true;
					else if ($continue == "stop")
						$stop = true;
					
					break;
				
				case "call_block":
					$block = trim($action_value["block"]);
					$project = trim($action_value["project"]);
					
					$result = $block ? $this->getBlockHtml($block, $project) : "";
					break;
				
				case "include_file":
					$path = trim($action_value["path"]);
					$path = $this->getParsedValueFromData($path, $results);
					
					if ($path) {
						$once = !empty($action_value["once"]);
						$code = 'include' . ($once ? '_once' : '') . ' "' . addcslashes($path, '\\"') . '";';
						
						$result = eval($code);
					}
					break;
				
				case "draw_graph":
					if (is_array($action_value)) {
						if (array_key_exists("code", $action_value)) {
							$return_values = array();
							$code = $this->getParsedValueFromData($action_value["code"], $results, false);
							
							$result = \PHPScriptHandler::parseContent($code, $results, $return_values);
							
							if (isset($return_values[0]) && $return_values[0] !== false) //bc eval returns false on error and null if no return...
								$result = $return_values[0];
						}
						else {
							$include_graph_library = $this->getParsedValueFromData($action_value["include_graph_library"], $results);
							$width = $this->getParsedValueFromData($action_value["width"], $results);
							$height = $this->getParsedValueFromData($action_value["height"], $results);
							$labels_and_values_type = $this->getParsedValueFromData($action_value["labels_and_values_type"], $results);
							$labels_variable = $this->getParsedValueFromData($action_value["labels_variable"], $results);
							
							$data_sets_result = '';
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
										$data_set_result = '';
										
										if (!isset($data_set["order"]))
											$data_set["order"] = $count;
										
										foreach ($data_set as $key => $value) {
											$value = $this->getParsedValueFromData($value, $results);
											
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
												else if ($key == "values_variable" && $labels_and_values_type == "associative" && is_array($value)) {
													$labels_variable = array_keys($value);
													$labels_and_values_type = null;
												}
												
												if ($is_valid)
													$data_set_result .= ($data_set_result ? ",\n              " : "") . $option_name . ': ' . json_encode($value);
											}
										}
										
										$data_sets_result .= '
		     {
		         ' . $data_set_result . '
		     },';
		     							
		     							$count++;
									}
								}
							}
							
							$rand = rand(0, 1000);
							
							$result = '';
							
							if ($include_graph_library == "cdn_even_if_exists")
								$result .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>' . "\n\n";
							else if ($include_graph_library == "cdn_if_not_exists")
								$result .= '<script>
if (typeof Chart != "function")
	document.write(\'<scr\' + \'ipt src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></scr\' + \'ipt>\');
</script>' . "\n\n";
							
							$result .= '<canvas id="my_chart_' . $rand . '"' . (is_numeric($width) ? ' width="' . $width . '"' : '') . (is_numeric($height) ? ' height="' . $height . '"' : '') . '></canvas>

<script>
var ctx = document.getElementById("my_chart_' . $rand . '").getContext("2d");
var myChart = new Chart(ctx, {
    type: "' . $default_type . '",
    data: {
        ' . ($labels_variable ? 'labels: ' . json_encode($labels_variable) . ',' : '') . '
        datasets: [' . $data_sets_result . '
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
</script>';
						}
					}
					break;
				
				case "loop":
					$records_variable_name = $this->getParsedValueFromData($action_value["records_variable_name"], $results);
					$records = !is_array($records_variable_name) ? $results[$records_variable_name] : $records_variable_name;
					
					if (is_array($records)) {
						$records_start_index = $this->getParsedValueFromData($action_value["records_start_index"], $results);
						$records_end_index = $this->getParsedValueFromData($action_value["records_end_index"], $results);
						$array_item_key_variable_name = $this->getParsedValueFromData($action_value["array_item_key_variable_name"], $results);
						$array_item_value_variable_name = $this->getParsedValueFromData($action_value["array_item_value_variable_name"], $results);
						
						$records_start_index = is_numeric($records_start_index) ? $records_start_index : 0;
						$records_end_index = is_numeric($records_end_index) ? $records_end_index : count($records);
						
						$sub_actions = $action_value["actions"];
						$result = '';
						$i = 0;
						
						foreach ($records as $k => $v) {
							if ($i >= $records_end_index || $stop)
								break;
							else if ($i >= $records_start_index) {
								$results[ $array_item_key_variable_name ] = $k;
								$results[ $array_item_value_variable_name ] = $v;
								
								$result .= $this->executeActions($sub_actions, $results, $stop, $die);
							}
							
							++$i;
						}
					}
					
					break;
				
				case "group":
					//Preparing sub-actions
					$sub_actions = $action_value["actions"];
					
					$results_aux = $results;
					$result = $this->executeActions($sub_actions, $results_aux, $stop, $die);
					
					//Preparing the new results vars according with the $group_name. This result will contain all the local variables
					$group_name = $this->getParsedValueFromData($action_value["group_name"], $results);
					if ($group_name) {
						$group_name_result_vars = array_diff_key($results_aux, $results);
					
						//Preparing to overwrite the results with the same results array, but with the updated values if they were changed inside of the group...
						$results = array_intersect_key($results_aux, $results); //$results will be with last updated values.
						
						//Adding new group name vars to $results if group_name exists!
						$results[$group_name] = $group_name_result_vars;
					}
					else 
						$results = $results_aux; //if no $group_name adds the vars created in the group to the $results
					
					break;
				
				default:
					$result = $this->getParsedValueFromData($action_value, $results);
			}
		}
		
		return $result;
	}
	
	private function getParsedValueFromData($value, &$data, $parse_php = true) {
		if (is_array($value))
			foreach ($value as $key => $item)
				$value[$key] = $this->getParsedValueFromData($item, $data);
		else if ($value && is_string($value)) {
			//parse #_POST["activity"][$row_index]#
			if ($parse_php && $value && is_string($value) && strpos($value, '$') !== false) { 
				$code = '<?= "' . addcslashes($value, '"') . '" ?>';
				$value = \PHPScriptHandler::parseContent($code, $data);
				//echo "code:$code\n";
				//echo "value:$value\n";
			}
			
			//parse #some[thing][0]#
			if (strpos($value, '#') !== false) {
				$HtmlFormHandler = new \HtmlFormHandler();
				$value = $HtmlFormHandler->getParsedValueFromData($value, $data);
			}
			
			//parse {$some[thing][0]}. Not sure about this
			if ($parse_php && $value && is_string($value) && strpos($value, '$') !== false) { //Not well tested...
				$code = '<?= "' . addcslashes($value, '"') . '" ?>';
				$value = \PHPScriptHandler::parseContent($code, $data);
				//echo "code:$code\n";
				//echo "value:$value\n";
			}
		}
		
		return $value;
	}
	
	private function validateUserPermissions($entity_path, $logged_user_id, $users_perms, $all_permissions_checked) {
		if (!$users_perms)
			return true;
		
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
		
		//if public and only need 1 check
		if ($exists_public_access && !$all_permissions_checked) 
			return true;
		
		//if no logged user
		if (!$logged_user_id) 
			return false;
		
		//if no new_users_perms it means there is notjing to check so there is no permissions and everything is allowed!
		if (!$new_users_perms)
			return true;
		
		//set users_perms with new_users_perms without the public perms
		$users_perms = $new_users_perms; 
		
		//get user type current page activities
		$object_type_id = \ObjectUtil::PAGE_OBJECT_TYPE_ID;
		$object_id = $entity_path;
		
		if (!$object_id)
			return false;
		
		$object_id = str_replace(APP_PATH, "", $object_id);
		$object_id = \HashCode::getHashCodePositive($object_id);
		
		$brokers = $this->getEVC()->getBrokers();
		$utaos = \UserUtil::getUserTypeActivityObjectsByUserIdAndConditions($brokers, $logged_user_id, array(
			"object_type_id" => $object_type_id, 
			"object_id" => $object_id
		), null);
		
		//if no logged user permissions, returns false
		if (!$utaos)
			return false;
		
		//check user permssions
		$entered = false;
		$result = true;
		
		foreach ($users_perms as $user_perm) 
			if (is_numeric($user_perm["user_type_id"]) && is_numeric($user_perm["activity_id"])) {
				if (!$entered && !$all_permissions_checked) //only happens on the first iteration and if $all_permissions_checked is false
					$result = false;
				
				$entered = true;
				
				$user_perm_exists = false;
				foreach ($utaos as $utao)
					if ($utao["user_type_id"] == $user_perm["user_type_id"] && $utao["activity_id"] == $user_perm["activity_id"]) {
						$user_perm_exists = true;
						break;
					}
				
				if ($all_permissions_checked && !$user_perm_exists)
					return false;
				else if (!$all_permissions_checked && $user_perm_exists)
					return true;
			}
		
		return $result;
	}
	
	private function getBlockHtml($block, $project) {
		$EVC = $this->getEVC();
		$bfp = $EVC->getBlockPath($block, $project);
		
		if (file_exists($bfp)) {
			$block_local_variables = array();
			include $bfp;
			
			return $EVC->getCMSLayer()->getCMSBlockLayer()->getCurrentBlock();
		}
		
		return "";
	}
}
?>
