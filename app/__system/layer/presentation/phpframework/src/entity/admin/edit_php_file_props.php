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

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("WorkFlowPHPFileHandler"); include_once $EVC->getUtilPath("WorkFlowBrokersSelectedDBVarsHandler"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once get_lib("org.phpframework.phpscript.docblock.DocBlockParser"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $item_type = $_GET["item_type"]; $class_id = $_GET["class"]; $method_id = $_GET["method"]; $function_id = $_GET["function"]; $filter_by_layout = $_GET["filter_by_layout"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); if ($item_type == "dao") $layer_path = DAO_PATH; else if ($item_type == "vendor") $layer_path = VENDOR_PATH; else if ($item_type == "test_unit") $layer_path = TEST_UNIT_PATH; else if ($item_type == "other") $layer_path = OTHER_PATH; else { if ($item_type != "presentation") $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); else { $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); $obj = $PEVC ? $PEVC->getPresentationLayer() : null; } $layer_path = null; if ($obj) $layer_path = $obj->getLayerPathSetting(); } if ($layer_path) { $file_path = $layer_path . $path; if ($path && file_exists($file_path)) { $layer_object_id = $item_type == "dao" ? "vendor/dao/$path" : ($item_type == "vendor" || $item_type == "other" ? "$item_type/$path" : ($item_type == "test_unit" ? "vendor/testunit/$path" : $file_path)); $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); $CacheHandler = $PHPFrameWork->getObject("UserCacheHandler"); if ($CacheHandler) $CacheHandler->config(false, true); $file_modified_time = filemtime($file_path); switch ($file_type) { case "edit_file_class": $obj_data = WorkFlowPHPFileHandler::getClassData($file_path, $class_id, $CacheHandler); break; case "edit_file_class_method": $obj_data = WorkFlowPHPFileHandler::getClassMethodData($file_path, $class_id, $method_id, $CacheHandler); break; case "edit_file_function": $obj_data = WorkFlowPHPFileHandler::getFunctionData($file_path, $function_id, $CacheHandler); break; case "edit_file_includes": $obj_data = WorkFlowPHPFileHandler::getIncludesAndNamespacesAndUsesData($file_path, $CacheHandler); break; } if ($file_type == "edit_file_class" || $file_type == "edit_file_class_method" || $file_type == "edit_file_function" || $file_type == "edit_file_includes") { $is_class_equal_to_file_name = $class_id && pathinfo($path, PATHINFO_FILENAME) == $class_id; $obj_data["code"] = str_replace(chr(194) . chr(160), ' ', $obj_data["code"]); $ugvfps = array($user_global_variables_file_path); if ($PEVC) { $ugvfps[] = $PEVC->getConfigPath("pre_init_config"); $selected_project_id = $obj->getSelectedPresentationId(); } $PHPVariablesFileHandler = new PHPVariablesFileHandler($ugvfps); $PHPVariablesFileHandler->startUserGlobalVariables(); if ($item_type == "presentation" || $item_type == "businesslogic") { $brokers = $obj->getBrokers(); $selected_db_vars = WorkFlowBrokersSelectedDBVarsHandler::getBrokersSelectedDBVars($brokers); $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, $item_type == "presentation" ? '$EVC->getBroker' : '$this->getBusinessLogicLayer()->getBroker'); if ($PEVC) { $presentation_brokers = array(); $presentation_brokers[] = array(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $obj) . " (Self)", $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); $available_projects = $PEVC->getProjectsId(); $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $business_logic_brokers_obj = $layer_brokers_settings["business_logic_brokers_obj"]; $phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()'); } else { $business_logic_brokers = array(); $business_logic_brokers[] = array(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $obj) . " (Self)", $bean_file_name, $bean_name); $business_logic_brokers = array_merge($business_logic_brokers, $layer_brokers_settings["business_logic_brokers"]); $business_logic_brokers_obj = array("default" => '$this->getBusinessLogicLayer()'); $business_logic_brokers_obj = array_merge($business_logic_brokers_obj, $layer_brokers_settings["business_logic_brokers_obj"]); $phpframeworks_options = array("default" => '$this->getBusinessLogicLayer()->getPHPFrameWork()'); } $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $data_access_brokers_obj = $layer_brokers_settings["data_access_brokers_obj"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $ibatis_brokers_obj = $layer_brokers_settings["ibatis_brokers_obj"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $hibernate_brokers_obj = $layer_brokers_settings["hibernate_brokers_obj"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $db_brokers_obj = $layer_brokers_settings["db_brokers_obj"]; $bean_names_options = array_keys($obj->getPHPFrameWork()->getObjects()); $brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $brokers, true); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers, $filter_by_layout); $db_drivers_options = array_keys($brokers_db_drivers); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject", "trycatchexception", "throwexception", "printexception", "callpresentationlayerwebservice", "setpresentationview", "setpresentationtemplate", "inlinehtml", "createform", "setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam", ); if ($data_access_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; if ($ibatis_brokers_obj) $allowed_tasks_tag[] = "callibatisquery"; if ($hibernate_brokers_obj) { $allowed_tasks_tag[] = "callhibernateobject"; $allowed_tasks_tag[] = "callhibernatemethod"; } } else if ($db_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; } if ($db_brokers_obj) $allowed_tasks_tag[] = "getdbdriver"; if ($business_logic_brokers_obj) $allowed_tasks_tag[] = "callbusinesslogic"; } else { $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "trycatchexception", "throwexception", "printexception", ); } $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path); $PHPVariablesFileHandler->endUserGlobalVariables(); $comments = $obj_data["doc_comments"] ? implode("\n", $obj_data["doc_comments"]) : ""; if ($include_annotations) { $DocBlockParser = new DocBlockParser(); $DocBlockParser->ofComment($comments); $objects = $DocBlockParser->getObjects(); $method_comments = $DocBlockParser->getDescription(); $returns = $objects["return"]; $params = $DocBlockParser->getTagParams(); } else { $method_comments = trim($comments); $method_comments = str_replace("\r", "", $method_comments); $method_comments = preg_replace("/^\/[*]+\s*/", "", $method_comments); $method_comments = preg_replace("/\s*[*]+\/\s*$/", "", $method_comments); $method_comments = preg_replace("/\s*\n\s*[*]*\s*/", "\n", $method_comments); $method_comments = preg_replace("/^\s*[*]*\s*/", "", $method_comments); $method_comments = trim($method_comments); } $comments = is_array($obj_data["comments"]) ? trim(implode("\n", $obj_data["comments"])) : ""; $comments .= $method_comments ? "\n" . trim($method_comments) : ""; $comments = str_replace(array("/*", "*/", "//"), "", $comments); $comments = trim($comments); } } else { launch_exception(new Exception("File Not Found: " . $path)); die(); } } ?>
