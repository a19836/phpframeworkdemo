<?php
/*
 * Copyright (c) 2007 PHPMyFrameWork - Joao Pinto
 * AUTHOR: Joao Paulo Lopes Pinto -- http://jplpinto.com
 * 
 * The use of this code must be allowed first by the creator Joao Pinto, since this is a private and proprietary code.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT 
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN 
 * AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

include_once get_lib("org.phpframework.workflow.WorkFlowTask"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $filter_by_layout = $_GET["filter_by_layout"]; $popup = $_GET["popup"]; $class_id = $_GET["class"]; $method_id = $_GET["method"]; $data = $_POST["data"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); $data = json_decode($data, true); if ($bean_name && $bean_file_name && $path && $data) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj) { $PEVC = is_a($obj, "PresentationLayer") ? $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path) : null; $ugvfps = array($user_global_variables_file_path); if ($PEVC) $ugvfps[] = $PEVC->getConfigPath("pre_init_config"); $system_project_url_prefix = $project_url_prefix; $old_defined_vars = get_defined_vars(); $PHPVariablesFileHandler = new PHPVariablesFileHandler($ugvfps); $PHPVariablesFileHandler->startUserGlobalVariables(); try { $new_defined_vars = get_defined_vars(); $external_vars = array_diff_key($new_defined_vars, $old_defined_vars); unset($external_vars["PHPVariablesFileHandler"]); if (is_a($obj, "BusinessLogicLayer")) { $bean_objs = $obj->getPHPFrameWork()->getObjects(); $vars = is_array($bean_objs["vars"]) ? array_merge($bean_objs["vars"], $obj->settings) : $obj->settings; $external_vars["vars"] = $vars; } else if (is_a($obj, "PresentationLayer")) $external_vars["EVC"] = $PEVC; $method_obj = $data["method_obj"]; if ($method_obj) { $static_pos = strpos($method_obj, "::"); $non_static_pos = strpos($method_obj, "->"); $method_obj = substr($method_obj, 0, 1) != '$' && (!$static_pos || ($non_static_pos && $static_pos > $non_static_pos)) ? '$' . $method_obj : $method_obj; $task_layer_obj = parseCode($path, $class_id, $method_id, $obj, $external_vars, $method_obj); if ($task_layer_obj) { if (is_a($task_layer_obj, "LocalBrokerClient")) { $layer_props = WorkFlowBeansFileHandler::getLocalBeanLayerFromBroker($user_global_variables_file_path, $user_beans_folder_path, $task_layer_obj); $task_layer_bean_name = $layer_props[0]; $task_layer_bean_file_name = $layer_props[1][0]; $task_layer_obj = $task_layer_obj->getBrokerServer()->getBrokerLayer(); } else if (is_a($task_layer_obj, "BrokerClient")) $task_layer_obj = null; else { $task_layer_bean_name = WorkFlowBeansFileHandler::getBeanName($user_global_variables_file_path, $user_beans_folder_path, $task_layer_obj); $task_layer_bean_file_name = WorkFlowBeansFileHandler::getBeanFilePath($user_global_variables_file_path, $user_beans_folder_path, $task_layer_bean_name); } if ($task_layer_obj) { $task_layer_bean_file_name = basename($task_layer_bean_file_name); $task_layer_path = $task_layer_obj->getLayerPathSetting(); } } } $task_tag = $data["task_tag"]; $edit_type = $data["edit_type"]; $task_edit_url = null; switch ($task_tag) { case "includefile": $file_path = $data["file_path"]; $file_path_type = $data["type"]; if ($file_path) { $file_path_code = WorkFlowTask::getVariableValueCode($file_path, $file_path_type); $file_path = parseCode($path, $class_id, $method_id, $obj, $external_vars, $file_path_code); $props = getFilePathLayerProps($user_global_variables_file_path, $user_beans_folder_path, $file_path); $task_edit_url = getFilePathLayerPropsUrl($system_project_url_prefix, $filter_by_layout, $props); } break; case "callfunction": $include_file_path = $data["include_file_path"]; $include_file_path_type = $data["include_file_path_type"]; if ($include_file_path) { $include_file_path_code = WorkFlowTask::getVariableValueCode($include_file_path, $include_file_path_type); $include_file_path = parseCode($path, $class_id, $method_id, $obj, $external_vars, $include_file_path_code); $props = getFilePathLayerProps($user_global_variables_file_path, $user_beans_folder_path, $include_file_path); if ($props) { if ($edit_type == "file") $task_edit_url = getFilePathLayerPropsUrl($system_project_url_prefix, $filter_by_layout, $props); else if ($edit_type == "function") { $func_name = $data["func_name"]; $query_string = "bean_name=" . $props["bean_name"] . "&bean_file_name=" . $props["bean_file_name"] . "&filter_by_layout=$filter_by_layout&item_type=" . $props["item_type"] . "&path=" . $props["path"]; if ($props["item_type"] == "businesslogic") $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_function?$query_string&function=$func_name"; else $task_edit_url = $system_project_url_prefix . "phpframework/admin/edit_file_function?$query_string&function=$func_name"; } } } break; case "callobjectmethod": $include_file_path = $data["include_file_path"]; $include_file_path_type = $data["include_file_path_type"]; if ($include_file_path) { $include_file_path_code = WorkFlowTask::getVariableValueCode($include_file_path, $include_file_path_type); $include_file_path = parseCode($path, $class_id, $method_id, $obj, $external_vars, $include_file_path_code); } if (!$include_file_path && is_a($obj, "PresentationLayer") && $data["method_obj"]) { $method_obj = $data["method_obj"]; if (preg_match("/ResourceUtil$/", $method_obj)) $include_file_path = $PEVC->getUtilPath("resource/$method_obj"); if (!file_exists($include_file_path)) $include_file_path = findsClassPath( $PEVC->getUtilsPath(), $method_obj); } $props = getFilePathLayerProps($user_global_variables_file_path, $user_beans_folder_path, $include_file_path); if ($props) { $query_string = "bean_name=" . $props["bean_name"] . "&bean_file_name=" . $props["bean_file_name"] . "&filter_by_layout=$filter_by_layout&item_type=" . $props["item_type"] . "&path=" . $props["path"]; if ($edit_type == "file") $task_edit_url = getFilePathLayerPropsUrl($system_project_url_prefix, $filter_by_layout, $props); else if ($edit_type == "class") { $method_obj = $data["method_obj"]; if ($props["item_type"] == "businesslogic") $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_service?$query_string&service=$method_obj"; else $task_edit_url = $system_project_url_prefix . "phpframework/admin/edit_file_class?$query_string&class=$method_obj"; } else if ($edit_type == "method") { $method_obj = $data["method_obj"]; $method_name = $data["method_name"]; if ($props["item_type"] == "businesslogic") $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_method?$query_string&service=$method_obj&method=$method_name"; else $task_edit_url = $system_project_url_prefix . "phpframework/admin/edit_file_class_method?$query_string&class=$method_obj&method=$method_name"; } } break; case "callbusinesslogic": $module_id = $data["module_id"]; $module_id_type = $data["module_id_type"]; $service_id = $data["service_id"]; $service_id_type = $data["service_id_type"]; if ($task_layer_obj && $module_id && $service_id) { $module_id_code = WorkFlowTask::getVariableValueCode($module_id, $module_id_type); eval('$module_id = ' . $module_id_code . ';'); $service_id_code = WorkFlowTask::getVariableValueCode($service_id, $service_id_type); eval('$service_id = ' . $service_id_code . ';'); if ($module_id && $service_id) { $props = $task_layer_obj->getBusinessLogicServiceProps($module_id, $service_id); $class_name = $props["class_name"]; $method_name = $props["method_name"]; $function_name = $props["function_name"]; $service_file_path = $props["service_file_path"]; $task_layer_file_path = substr($service_file_path, strlen($task_layer_path)); $query_string = "bean_name=" . $task_layer_bean_name . "&bean_file_name=" . $task_layer_bean_file_name . "&filter_by_layout=$filter_by_layout&item_type=businesslogic&path=" . $task_layer_file_path; if ($edit_type == "file") $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_file?$query_string"; else if ($edit_type == "class") $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_service?$query_string&service=$class_name"; else if ($edit_type == "service") { if ($class_name && $method_name) $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_method?$query_string&service=$class_name&method=$method_name"; else if ($function_name) $task_edit_url = $system_project_url_prefix . "phpframework/businesslogic/edit_function?$query_string&function=$function_name"; } } } break; case "callibatisquery": $module_id = $data["module_id"]; $module_id_type = $data["module_id_type"]; $service_type = $data["service_type"]; $service_type_type = $data["service_type_type"]; $service_id = $data["service_id"]; $service_id_type = $data["service_id_type"]; if ($task_layer_obj && $module_id && $service_type && $service_id) { $module_id_code = WorkFlowTask::getVariableValueCode($module_id, $module_id_type); eval('$module_id = ' . $module_id_code . ';'); $service_type_code = WorkFlowTask::getVariableValueCode($service_type, $service_type_type); eval('$service_type = ' . $service_type_code . ';'); $service_id_code = WorkFlowTask::getVariableValueCode($service_id, $service_id_type); eval('$service_id = ' . $service_id_code . ';'); if ($module_id && $service_type && $service_id) { $props = $task_layer_obj->getQueryProps($module_id, $service_type, $service_id); $query_path = $props["query_path"]; $query_id = $props["query_id"]; $task_layer_file_path = substr($query_path, strlen($task_layer_path)); $query_string = "bean_name=" . $task_layer_bean_name . "&bean_file_name=" . $task_layer_bean_file_name . "&filter_by_layout=$filter_by_layout&item_type=ibatis&path=" . $task_layer_file_path; if ($edit_type == "file") $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_file?$query_string"; else if ($edit_type == "query") $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_query?$query_string&obj=&query_id=$query_id&query_type=$service_type&relationship_type=queries"; } } break; case "callhibernateobject": $module_id = $data["module_id"]; $module_id_type = $data["module_id_type"]; $service_id = $data["service_id"]; $service_id_type = $data["service_id_type"]; if ($task_layer_obj && $module_id && $service_id) { $module_id_code = WorkFlowTask::getVariableValueCode($module_id, $module_id_type); eval('$module_id = ' . $module_id_code . ';'); $service_id_code = WorkFlowTask::getVariableValueCode($service_id, $service_id_type); eval('$service_id = ' . $service_id_code . ';'); if ($module_id && $service_id) { $props = $task_layer_obj->getObjectProps($module_id, $service_id); $obj_path = $props["obj_path"]; $obj_name = $props["obj_name"]; $task_layer_file_path = substr($obj_path, strlen($task_layer_path)); $query_string = "bean_name=" . $task_layer_bean_name . "&bean_file_name=" . $task_layer_bean_file_name . "&filter_by_layout=$filter_by_layout&item_type=hibernate&path=" . $task_layer_file_path; if ($edit_type == "file") $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_file?$query_string"; else if ($edit_type == "object") $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_hbn_obj?$query_string&obj=$obj_name"; } } break; case "callhibernatemethod": $module_id = $data["module_id"]; $module_id_type = $data["module_id_type"]; $service_id = $data["service_id"]; $service_id_type = $data["service_id_type"]; if ($task_layer_obj && $module_id && $service_id) { $module_id_code = WorkFlowTask::getVariableValueCode($module_id, $module_id_type); eval('$module_id = ' . $module_id_code . ';'); $service_id_code = WorkFlowTask::getVariableValueCode($service_id, $service_id_type); eval('$service_id = ' . $service_id_code . ';'); if ($module_id && $service_id) { $props = $task_layer_obj->getObjectProps($module_id, $service_id); $obj_path = $props["obj_path"]; $obj_name = $props["obj_name"]; $task_layer_file_path = substr($obj_path, strlen($task_layer_path)); $query_string = "bean_name=" . $task_layer_bean_name . "&bean_file_name=" . $task_layer_bean_file_name . "&filter_by_layout=$filter_by_layout&item_type=hibernate&path=" . $task_layer_file_path; if ($edit_type == "file") $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_file?$query_string"; else if ($edit_type == "object") $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_hbn_obj?$query_string&obj=$obj_name"; else if ($edit_type == "query") { $service_method = $data["service_method"]; $service_method_type = $data["service_method_type"]; $service_method_code = WorkFlowTask::getVariableValueCode($service_method, $service_method_type); eval('$service_method = ' . $service_method_code . ';'); switch ($service_method) { case "callQuerySQL": case "callQuery": $sma_query_type = $data["sma_query_type"]; $sma_query_type_type = $data["sma_query_type_type"]; $sma_query_type_code = WorkFlowTask::getVariableValueCode($sma_query_type, $sma_query_type_type); eval('$sma_query_type = ' . $sma_query_type_code . ';'); break; case "callInsertSQL": case "callInsert": $sma_query_type = "insert"; break; case "callUpdateSQL": case "callUpdate": $sma_query_type = "update"; break; case "callDeleteSQL": case "callDelete": $sma_query_type = "delete"; break; case "callSelectSQL": case "callSelect": $sma_query_type = "select"; break; case "callProcedureSQL": case "callProcedure": $sma_query_type = "procedure"; break; } if ($sma_query_type) { $sma_query_id = $data["sma_query_id"]; $sma_query_id_type = $data["sma_query_id_type"]; $sma_query_id_code = WorkFlowTask::getVariableValueCode($sma_query_id, $sma_query_id_type); eval('$sma_query_id = ' . $sma_query_id_code . ';'); if ($sma_query_id) $task_edit_url = $system_project_url_prefix . "phpframework/dataaccess/edit_query?$query_string&obj=$obj_name&query_id=$sma_query_id&query_type=$sma_query_type&relationship_type=queries"; } } } } break; } } catch (Error $e) { $error_message = "PHP error: " . $e->getMessage(); debug_log("[__system/layer/presentation/phpframework/src/entity/edit_task_source.php] $error_message"); } catch(ParseError $e) { $error_message = "Parse error: " . $e->getMessage(); debug_log("[__system/layer/presentation/phpframework/src/entity/edit_task_source.php] $error_message"); } catch(ErrorException $e) { $error_message = "Error exception: " . $e->getMessage(); debug_log("[__system/layer/presentation/phpframework/src/entity/edit_task_source.php] $error_message"); } catch(Exception $e) { $error_message = $e->getMessage(); debug_log("[__system/layer/presentation/phpframework/src/entity/edit_task_source.php] $error_message"); } $PHPVariablesFileHandler->endUserGlobalVariables(); if ($task_edit_url) { $task_edit_url .= "&popup=$popup"; header("Location: $task_edit_url"); echo "<script>document.location='$task_edit_url';</script>"; die(); } } } function getFilePathLayerProps($v3d55458bcd, $v5039a77f9d, $pf3dc0762) { if (!$pf3dc0762) return null; $v5f37aa718c = WorkFlowBeansFileHandler::getAllLayersBeanObjs($v3d55458bcd, $v5039a77f9d); if ($v5f37aa718c) foreach ($v5f37aa718c as $v8ffce2a791 => $v972f1a5c2b) { $pa2bba2ac = $v972f1a5c2b->getLayerPathSetting(); if (strpos($pf3dc0762, $pa2bba2ac) === 0) { $v8773b3a63a = ""; if (is_a($v972f1a5c2b, "DataAccessLayer")) $v8773b3a63a = is_a($v972f1a5c2b, "HibernateDataAccessLayer") ? "hibernate" : "ibatis"; else if (is_a($v972f1a5c2b, "BusinessLogicLayer")) $v8773b3a63a = "businesslogic"; else if (is_a($v972f1a5c2b, "PresentationLayer")) $v8773b3a63a = "presentation"; if ($v8773b3a63a) { $pa0462a8e = WorkFlowBeansFileHandler::getBeanFilePath($v3d55458bcd, $v5039a77f9d, $v8ffce2a791); $pa0462a8e = basename($pa0462a8e); $pa32be502 = substr($pf3dc0762, strlen($pa2bba2ac)); if ($v8773b3a63a == "presentation") { $v6b46aeb158 = $v972f1a5c2b->getCacheLayer(); $pa47fac06 = $v972f1a5c2b->getCommonProjectName(); $v60e201d1bb = substr($pa32be502, 0, strlen("$pa47fac06/")) == "$pa47fac06/"; if ($v6b46aeb158 && $v6b46aeb158->settings["presentation_caches_path"] && strpos($pa32be502, $v6b46aeb158->settings["presentation_caches_path"]) !== false && !$v60e201d1bb) $v0c98cda30e = "cache"; else if (strpos($pa32be502, "/src/config/") !== false) $v0c98cda30e = "config"; else if (strpos($pa32be502, "/src/entity/") !== false) $v0c98cda30e = "entity"; else if (strpos($pa32be502, "/src/template/") !== false) $v0c98cda30e = "template"; else if (strpos($pa32be502, "/src/view/") !== false) $v0c98cda30e = "view"; else if (strpos($pa32be502, "/src/block/") !== false) $v0c98cda30e = "block"; else if (strpos($pa32be502, "/src/util/") !== false) $v0c98cda30e = "util"; else if (strpos($pa32be502, "/src/controller/") !== false) $v0c98cda30e = "controller"; else if (strpos($pa32be502, "/src/module/") !== false) $v0c98cda30e = "module"; else if (strpos($pa32be502, "/webroot/") !== false) $v0c98cda30e = "webroot"; } return array( "path" => $pa32be502, "bean_file_name" => $pa0462a8e, "bean_name" => $v8ffce2a791, "item_type" => $v8773b3a63a, "folder_type" => $v0c98cda30e ); } } } if (strpos($pf3dc0762, LIB_PATH) === 0) { $v8773b3a63a = "lib"; $pa32be502 = substr($pf3dc0762, strlen(LIB_PATH)); } if (strpos($pf3dc0762, DAO_PATH) === 0) { $v8773b3a63a = "dao"; $pa32be502 = substr($pf3dc0762, strlen(DAO_PATH)); } else if (strpos($pf3dc0762, VENDOR_PATH) === 0) { $v8773b3a63a = "vendor"; $pa32be502 = substr($pf3dc0762, strlen(VENDOR_PATH)); } else if (strpos($pf3dc0762, TEST_UNIT_PATH) === 0) { $v8773b3a63a = "test_unit"; $pa32be502 = substr($pf3dc0762, strlen(TEST_UNIT_PATH)); } else if (strpos($pf3dc0762, OTHER_PATH) === 0) { $v8773b3a63a = "other"; $pa32be502 = substr($pf3dc0762, strlen(OTHER_PATH)); } if ($v8773b3a63a) return array( "path" => $pa32be502, "bean_file_name" => "", "bean_name" => $v8773b3a63a, "item_type" => $v8773b3a63a, ); return null; } function getFilePathLayerPropsUrl($pfde6442c, $pb154d332, $v9073377656) { if ($v9073377656 && $v9073377656["item_type"]) { $pc2defd39 = "bean_name=" . $v9073377656["bean_name"] . "&bean_file_name=" . $v9073377656["bean_file_name"] . "&filter_by_layout=$pb154d332&item_type=" . $v9073377656["item_type"] . "&path=" . $v9073377656["path"]; switch ($v9073377656["item_type"]) { case "lib": return $pfde6442c . "phpframework/docbook/file_code?path=lib/" . $v9073377656["path"]; case "ibatis": case "hibernate": return $pfde6442c . "phpframework/dataaccess/edit_file?$pc2defd39"; case "businesslogic": return $pfde6442c . "phpframework/businesslogic/edit_file?$pc2defd39"; case "presentation": switch ($v9073377656["folder_type"]) { case "config": return $pfde6442c . "phpframework/presentation/edit_config?$pc2defd39"; case "entity": return $pfde6442c . "phpframework/presentation/edit_entity?$pc2defd39"; case "template": return $pfde6442c . "phpframework/presentation/edit_template?$pc2defd39"; case "view": return $pfde6442c . "phpframework/presentation/edit_view?$pc2defd39"; case "block": return $pfde6442c . "phpframework/presentation/edit_block?$pc2defd39"; case "util": return $pfde6442c . "phpframework/presentation/edit_util?$pc2defd39"; } } return $pfde6442c . "phpframework/admin/edit_raw_file?$pc2defd39"; } return null; } function parseCode($pa32be502, $pfef14f0b, $v834146ce8d, $v972f1a5c2b, $pc5a892eb, $v067674f4e4) { $v9ad1385268 = null; if ($pfef14f0b && $v834146ce8d) { if (is_a($v972f1a5c2b, "BusinessLogicLayer")) { $pcd8c70bc = dirname($pa32be502); $v9073377656 = $v972f1a5c2b->getBusinessLogicServiceProps($pcd8c70bc, "$pfef14f0b.$v834146ce8d"); $v0b196f001b = $v9073377656["obj"]; } else if (is_a($v972f1a5c2b, "PresentationLayer")) { $pf3dc0762 = $v972f1a5c2b->getLayerPathSetting() . $pa32be502; if (file_exists($pf3dc0762)) { include $pf3dc0762; $v0b196f001b = new $pfef14f0b(); } } if ($v0b196f001b) { eval('$getBrokerDummyFunc = function($external_vars) {
				if ($external_vars)
					foreach ($external_vars as $k => $v)
						${$k} = $v;
				
				return ' . $v067674f4e4 . ';
			};'); $pfbee2175 = Closure::bind($v753cb81726, $v0b196f001b, $pfef14f0b); $v9ad1385268 = $pfbee2175($pc5a892eb); } } else { if ($pc5a892eb) foreach ($pc5a892eb as $pe5c5e2fe => $v956913c90f) ${$pe5c5e2fe} = $v956913c90f; eval('$result = ' . $v067674f4e4 . ';'); } return $v9ad1385268; } function findsClassPath($pdd397f0a, $v1335217393) { if ($pdd397f0a && is_dir($pdd397f0a)) { $v6ee393d9fb = array_diff(scandir($pdd397f0a), array('..', '.')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pf3dc0762 = $pdd397f0a . $v7dffdb5a5b; if (!is_dir($pf3dc0762) && pathinfo($v7dffdb5a5b, PATHINFO_FILENAME) == $v1335217393) return $pf3dc0762; } foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pf3dc0762 = $pdd397f0a . $v7dffdb5a5b; if (is_dir($pf3dc0762)) { $v50890f6f30 = findsClassPath($pf3dc0762 . "/", $v1335217393); if ($v50890f6f30) return $v50890f6f30; } } } return null; } ?>
