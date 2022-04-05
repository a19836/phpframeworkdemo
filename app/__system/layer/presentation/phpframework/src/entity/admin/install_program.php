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

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSProgramInstallationHandler"); include_once get_lib("org.phpframework.util.web.MyCurl"); include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $step = $_POST["step"]; $filter_by_layout = $_GET["filter_by_layout"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); if ($bean_name && $bean_file_name) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $P = $PEVC->getPresentationLayer(); $layer_object_id = LAYER_PATH . WorkFlowBeansFileHandler::getLayerObjFolderName($P) . "/" . $path; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); } else { launch_exception(new Exception("Bean layer doesn't exists!")); die(); } } if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); if ($step >= 3) { $post_data = json_decode($_POST["post_data"], true); $db_drivers = $post_data["db_drivers"]; $layers = $post_data["layers"]; $program_name = $post_data["program_name"]; $extra_settings = array_diff_key($post_data, array("db_drivers" => 0, "layers" => 0, "program_name" => 0, "continue" => 0, "step" => 0)); $program_path = $programs_temp_folder_path . $program_name . "/"; if ($step == 3 && !is_dir($program_path)) $error_message = "Please upload your zip file again...<br>To go back to the upload please click <a href='?" . $_SERVER["QUERY_STRING"] . "'>here</a>"; else if (!$layers && !$db_drivers) $error_message = "Error: No Layers or DB Drivers selected!"; else { if ($P) { $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $P->getBrokers()); $layer_brokers_settings["presentation_brokers"] = array( array( WorkFlowBeansConverter::getBrokerNameFromRawLabel(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P)), $bean_file_name, $bean_name ) ); $layer_brokers_settings["presentation_evc_brokers"] = $layer_brokers_settings["presentation_brokers"]; } else $layer_brokers_settings = WorkFlowTestUnitHandler::getAllLayersBrokersSettings($user_global_variables_file_path, $user_beans_folder_path); $layer_beans_settings = array(); $layer_objs = array(); $layers_brokers_settings = array(); $db_driver_objs = array(); $vendors = array(); $projects = array(); $projects_evcs = array(); if ($layers) { $brokers_db_drivers = array(); $pre_init_configs = array(); $presentation_evc_brokers = $layer_brokers_settings["presentation_evc_brokers"]; $presentation_evc_brokers_by_broker_name = array(); foreach ($presentation_evc_brokers as $bl) $presentation_evc_brokers_by_broker_name[ $bl[0] ] = $bl; foreach ($layers as $layer_type => $items) { if ($layer_type == "vendor") $vendors = array_keys($items); else { foreach ($items as $broker_name => $layer_props) { $brokers_settings = array(); switch ($layer_type) { case "ibatis": $brokers_settings = $layer_brokers_settings["ibatis_brokers"]; break; case "hibernate": $brokers_settings = $layer_brokers_settings["hibernate_brokers"]; break; case "businesslogic": $brokers_settings = $layer_brokers_settings["business_logic_brokers"]; break; case "presentation": $brokers_settings = $layer_brokers_settings["presentation_brokers"]; break; } if ($brokers_settings) { foreach ($brokers_settings as $bl) if ($bl[0] == $broker_name) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bl[1], $user_global_variables_file_path); $layer_obj = $WorkFlowBeansFileHandler->getBeanObject($bl[2]); $layer_brokers_db_drivers = WorkFlowBeansFileHandler::getLayerDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $layer_obj, true); $layer_beans_settings[$broker_name] = $bl; $layer_objs[$broker_name] = $layer_obj; $brokers_db_drivers = array_merge($brokers_db_drivers, $layer_brokers_db_drivers); $layers_brokers_settings[$broker_name] = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $layer_obj->getBrokers()); $layers_brokers_settings[$broker_name]["db_drivers_brokers"] = $layer_brokers_db_drivers; if ($layer_type == "presentation") { foreach ($layer_props as $project => $project_props) { $projects[$broker_name][] = $project; $pre_init_configs[] = $layer_objs[$broker_name]->getLayerPathSetting() . "$project/src/config/pre_init_config.php"; $evc_broker_settings = $presentation_evc_brokers_by_broker_name[$broker_name]; if ($evc_broker_settings) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $evc_broker_settings[1], $user_global_variables_file_path); $projects_evcs[$broker_name][$project] = $WorkFlowBeansFileHandler->getEVCBeanObject($evc_broker_settings[2], $project); } } } break; } } } } } if ($db_drivers && $pre_init_configs) foreach ($pre_init_configs as $pre_init_config) foreach ($brokers_db_drivers as $broker_name => $bl) if (in_array($broker_name, $db_drivers)) { $DBDriverWorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bl[1], array($user_global_variables_file_path, $pre_init_config)); $DBDriverWorkFlowBeansFileHandler->init(); $db_settings = $DBDriverWorkFlowBeansFileHandler->getDBSettings($bl[2]); $db_settings_id = md5(serialize($db_settings)); if (!$db_driver_objs[ $db_settings_id ]) $db_driver_objs[ $db_settings_id ] = $DBDriverWorkFlowBeansFileHandler->getBeanObject($bl[2]); } } else if ($db_drivers) { $db_brokers = $layer_brokers_settings["db_brokers"]; if ($db_brokers) foreach ($db_brokers as $bl) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bl[1], $user_global_variables_file_path); $layer_obj = $WorkFlowBeansFileHandler->getBeanObject($bl[2]); $layer_db_drivers = $layer_obj->getBrokers(); if ($layer_db_drivers) foreach ($layer_db_drivers as $db_driver_name => $db_driver_obj) if (in_array($db_driver_name, $db_drivers)) $db_driver_objs[$db_driver_name] = $db_driver_obj; } } $db_driver_objs = array_values($db_driver_objs); $CMSProgramInstallationHandler = CMSProgramInstallationHandler::createCMSProgramInstallationHandlerObject($EVC, $user_global_variables_file_path, $user_beans_folder_path, $workflow_paths_id, $layer_beans_settings, $layer_objs, $db_driver_objs, $layers_brokers_settings, $vendors, $projects, $projects_evcs, $program_name, $program_path, $post_data); $status = false; if ($CMSProgramInstallationHandler) { if ($step == 3) $status = $CMSProgramInstallationHandler->install($extra_settings); else { $step_post_data = $_POST; unset($step_post_data["post_data"]); unset($step_post_data["step"]); unset($step_post_data["continue"]); $status = $CMSProgramInstallationHandler->installStep($step - 3, $extra_settings, $step_post_data); } } $messages = $CMSProgramInstallationHandler ? $CMSProgramInstallationHandler->getMessages() : array(); if ($status) { $next_step_html = $CMSProgramInstallationHandler->getStepHtml($step - 2, $extra_settings, $step_post_data); $next_step = $step + 1; if (!$next_step_html) { CacheHandlerUtil::deleteFolder($program_path); $status_message = "Program installed successfully!"; if (!$LayoutTypeProjectHandler->createLayoutTypePermissionsForProgramInLayersFromProjectPath($projects, $layer_objs, $program_name)) $messages[] = "There was an error adding the program permission for the selected projects layout types."; } } else { $error_message = "There were some errors to install this program. Please try again..."; $errors = $CMSProgramInstallationHandler ? $CMSProgramInstallationHandler->getErrors() : null; if ($errors && $errors["files"]) { $errors_files = array(); foreach ($errors["files"] as $src_path => $dst_path) { if (is_numeric($src_path)) $errors_files[$src_path] = substr($dst_path, strlen(LAYER_PATH)); else $errors_files[ substr($src_path, strlen($program_path)) ] = substr($dst_path, strlen(LAYER_PATH)); } $errors["files"] = $errors_files; } } } } else if ($step == 2) { $db_drivers = $_POST["db_drivers"]; $layers = $_POST["layers"]; $program_name = $_POST["program_name"]; $overwrite = $_POST["overwrite"]; $program_path = $programs_temp_folder_path . $program_name . "/"; if (!is_dir($program_path)) $error_message = "Please upload your zip file again...<br>To go back to the upload please click <a href='?" . $_SERVER["QUERY_STRING"] . "'>here</a>"; else if (!$layers && !$db_drivers) $error_message = "Error: No Layers or DB Drivers selected!"; else { $all_files = array(); if ($layers) foreach ($layers as $layer_type => $items) { if ($layer_type == "vendor") { foreach ($items as $file_name => $file_props) { if (is_dir("$program_path$layer_type/$file_name")) { $suffix = in_array(strtolower($file_name), array("testunit", "dao")) ? "$program_name/" : ""; $all_files[$file_name] = checkForExistentFiles("$program_path$layer_type/$file_name/", VENDOR_PATH . "$file_name/$suffix", $suffix); } else $all_files[$file_name] = file_exists(VENDOR_PATH . $file_name); } } else foreach ($items as $broker_name => $layer_props) { $layer_folder_name = WorkFlowBeansConverter::getFileNameFromRawLabel($broker_name); $layer_path = LAYER_PATH . "$layer_folder_name/"; if (is_dir($program_path . $layer_type)) { if ($layer_type == "presentation") { $all_files[$broker_name] = array(); $pres_sub_files = array_diff(scandir($program_path . $layer_type), array('..', '.')); foreach ($layer_props as $project => $project_props) { $all_files[$broker_name][$project] = array(); foreach ($pres_sub_files as $pres_sub_file) { $suffix = in_array(strtolower($pres_sub_file), array("entity", "view", "block", "util")) ? "$program_name/" : ""; $project_layer_path = "$layer_path$project/" . (strtolower($pres_sub_file) == "webroot" ? "" : "src/") . "$pres_sub_file/$suffix"; $all_files[$broker_name][$project] = array_merge($all_files[$broker_name][$project], checkForExistentFiles("$program_path$layer_type/$pres_sub_file/", $project_layer_path, "$pres_sub_file/$suffix")); } } } else $all_files[$broker_name] = checkForExistentFiles("$program_path$layer_type/", "{$layer_path}program/$program_name/", "$program_name/"); } } } } } else if ($step == 1) { if ($_FILES["program_file"] || trim($_POST["program_url"])) { $is_program_url = !$_FILES["program_file"] && trim($_POST["program_url"]); if ($is_program_url) { $program_url = $_POST["program_url"]; $fp = tmpfile(); $fp_path = stream_get_meta_data($fp)['uri']; $settings = array( "url" => str_replace(" ","%20", $program_url), "settings" => array( "follow_location" => true, "connection_timeout" => 50, "CURLOPT_TIMEOUT" => 50, "CURLOPT_FILE" => $fp, ) ); $MyCurl = new MyCurl(); $MyCurl->initSingle($settings); $MyCurl->get_contents(); $data = $MyCurl->getData(); $data = $data[0]; if ($data["info"] && $data["info"]["http_code"] == 200 && stripos($data["info"]["content_type"], "zip") !== false) $_FILES["program_file"] = array( "name" => basename(parse_url($program_url, PHP_URL_PATH)), "type" => $data["info"]["content_type"] ? $data["info"]["content_type"] : mime_content_type($fp_path), "tmp_name" => $fp_path, "error" => 0, "size" => $data["info"]["size_download"] ? $data["info"]["size_download"] : filesize($fp_path), ); } if ($_FILES["program_file"] && trim($_FILES["program_file"]["name"])) { $program_file = $_FILES["program_file"]; $name = $program_file["name"]; $zipped_file_path = $programs_temp_folder_path . $name; $extension = strtolower( pathinfo($name, PATHINFO_EXTENSION) ); $dest_file_path = substr($zipped_file_path, 0, -4) . "/"; $program_name = basename($dest_file_path); if ($extension != "zip") $error_message = "Error: File '$name' must be a zip file!"; else if (!is_dir($programs_temp_folder_path) && !mkdir($programs_temp_folder_path, 0755, true)) $error_message = "Error: trying to create tmp folder to upload '$name' file!"; else { $continue = $is_program_url ? rename($program_file["tmp_name"], $zipped_file_path) : move_uploaded_file($program_file["tmp_name"], $zipped_file_path); if ($continue) { CacheHandlerUtil::deleteFolder($dest_file_path); if (CMSProgramInstallationHandler::unzipProgramFile($zipped_file_path, $dest_file_path)) { $info = CMSProgramInstallationHandler::getUnzippedProgramInfo($dest_file_path); if ($info && $info["tag"] && $program_name != $info["tag"]) { $program_name = $info["tag"]; $new_dest_file_path = dirname($dest_file_path) . "/$program_name/"; if (file_exists($new_dest_file_path)) CacheHandlerUtil::deleteFolder($new_dest_file_path); if (!file_exists($new_dest_file_path) && rename($dest_file_path, $new_dest_file_path)) $dest_file_path = $new_dest_file_path; else $error_message = "Error: Could not rename unzipped folder with new program id '$program_name';"; } if (!$error_message) { $program_settings = CMSProgramInstallationHandler::getUnzippedProgramSettingsHtml($program_name, $dest_file_path); $default_db_driver = null; if ($P) { $brokers_db_drivers = WorkFlowBeansFileHandler::getLayerDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $P, true); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers, $filter_by_layout); $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $P->getBrokers()); $layer_brokers_settings["presentation_brokers"][] = array( WorkFlowBeansConverter::getBrokerNameFromRawLabel(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P)), $bean_file_name, $bean_name ); $selected_project_id = $P->getSelectedPresentationId(); $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $default_db_driver = $GLOBALS["default_db_driver"]; $PHPVariablesFileHandler->endUserGlobalVariables(); } else { $layer_brokers_settings = WorkFlowTestUnitHandler::getAllLayersBrokersSettings($user_global_variables_file_path, $user_beans_folder_path); $all_db_driver_brokers = $layer_brokers_settings["db_driver_brokers"]; $db_driver_brokers_filtered = array(); foreach ($all_db_driver_brokers as $db_driver_props) $db_driver_brokers_filtered[ $db_driver_props[0] ] = $db_driver_props; $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($db_driver_brokers_filtered, $filter_by_layout); $db_brokers = $layer_brokers_settings["db_brokers"]; $brokers_db_drivers = array(); if ($db_brokers) foreach ($db_brokers as $bl) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bl[1], $user_global_variables_file_path); $DBData = $WorkFlowBeansFileHandler->getBeanObject($bl[2]); $db_drivers = $DBData->getDBDriversName(); if ($db_drivers) foreach ($db_drivers as $db_driver_name) if ($db_driver_brokers_filtered[$db_driver_name]) $brokers_db_drivers[$db_driver_name] = array(); } } $brokers_db_drivers = $brokers_db_drivers ? array_keys($brokers_db_drivers) : array(); $files = array_diff(scandir($dest_file_path), array('..', '.')); foreach ($files as $file) { $fl = strtolower($file); if (is_dir($dest_file_path . $file)) switch ($fl) { case "ibatis": $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; break; case "hibernate": $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; break; case "businesslogic": $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; break; case "presentation": $presentation_brokers = $layer_brokers_settings["presentation_brokers"]; if ($P) $presentation_projects = array( $presentation_brokers[0][2] => array( "projects" => array( $selected_project_id => array() ) ) ); else { $presentation_projects = CMSPresentationLayerHandler::getPresentationLayersProjectsFiles($user_global_variables_file_path, $user_beans_folder_path); $LayoutTypeProjectHandler->filterPresentationLayersProjectsByUserAndLayoutPermissions($presentation_projects, $filter_by_layout, UserAuthenticationHandler::$PERMISSION_BELONG_NAME); } break; case "vendor": $vendor_brokers = array_diff(scandir($dest_file_path . $file), array('..', '.'));; break; } } if (!$data_access_brokers && !$business_logic_brokers && !$presentation_brokers) $error_message = "Error: Program does not have the correct structure!"; } if ($error_message) CacheHandlerUtil::deleteFolder($dest_file_path); } else $error_message = "Error: could not unzip uploaded file. Please try again..."; unlink($zipped_file_path); } else $error_message = "Error: Could not upload file. Please try again..."; } if ($error_message) $step = null; } if ($is_program_url) fclose($fp); } else $error_message = "Error: Please upload the file with the program you wish to install."; } } function checkForExistentFiles($v98e7bb1b2a, $v569077ab22, $pe3d00634) { $v343604fd3c = array(); $padd0d6c7 = array_diff(scandir($v98e7bb1b2a), array('..', '.')); foreach ($padd0d6c7 as $v7dffdb5a5b) { $v343604fd3c["$pe3d00634$v7dffdb5a5b"] = false; if (file_exists("$v569077ab22$v7dffdb5a5b")) { if (is_dir($v98e7bb1b2a . $v7dffdb5a5b)) { $v343604fd3c = array_merge($v343604fd3c, checkForExistentFiles("$v98e7bb1b2a$v7dffdb5a5b/", "$v569077ab22$v7dffdb5a5b/", "$pe3d00634$v7dffdb5a5b/")); $v343604fd3c["$pe3d00634$v7dffdb5a5b"] = true; } else $v343604fd3c["$pe3d00634$v7dffdb5a5b"] = true; } } return $v343604fd3c; } ?>
