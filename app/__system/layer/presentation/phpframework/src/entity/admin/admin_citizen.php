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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $choose_available_project_url = "{$project_url_prefix}admin/choose_available_project?redirect_path=admin"; if (!empty($_GET["bean_name"])) { $bean_name = $_GET["bean_name"]; UserAuthenticationHandler::setEternalRootCookie("selected_bean_name", $bean_name, 0, "/"); } else if (!empty($_COOKIE["selected_bean_name"])) $bean_name = $_COOKIE["selected_bean_name"]; if (!empty($_GET["bean_file_name"])) { $bean_file_name = $_GET["bean_file_name"]; UserAuthenticationHandler::setEternalRootCookie("selected_bean_file_name", $bean_file_name, 0, "/"); } else if (!empty($_COOKIE["selected_bean_file_name"])) $bean_file_name = $_COOKIE["selected_bean_file_name"]; if (!empty($_GET["project"])) { $project = $_GET["project"]; $project = preg_replace("/[\/]+/", "/", $project); $project = preg_replace("/^\//", "", $project); $project = preg_replace("/\/$/", "", $project); UserAuthenticationHandler::setEternalRootCookie("selected_project", $project, 0, "/"); } else if (!empty($_COOKIE["selected_project"])) $project = $_COOKIE["selected_project"]; if (!$bean_name || !$bean_file_name || !$project) { header("Location: $choose_available_project_url"); die("<script>document.location = '$choose_available_project_url';</script>"); } $layers_beans = AdminMenuHandler::getLayers($user_global_variables_file_path); if ($bean_name && $bean_file_name && $project && $layers_beans && $layers_beans["presentation_layers"] && $layers_beans["presentation_layers"][$bean_name] == $bean_file_name) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bean_file_name, $bean_name, $user_global_variables_file_path); $filter_by_layout = "$layer_bean_folder_name/" . preg_replace("/\/+$/", "", $project); $filter_by_layout_permission = UserAuthenticationHandler::$PERMISSION_BELONG_NAME; } $filter_layout_by_layers_type = array("presentation_layers", "business_logic_layers", "data_access_layers"); $do_not_filter_by_layout = array( "bean_name" => $bean_name, "bean_file_name" => $bean_file_name, "project" => $project ); include $EVC->getUtilPath("admin_uis_layers_and_permissions"); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $project); $P = $PEVC->getPresentationLayer(); $projects = null; if ($layers["presentation_layers"]) foreach ($layers["presentation_layers"] as $layer_name => $layer) if ($layer["properties"]["bean_name"] == $bean_name && $layer["properties"]["bean_file_name"] == $bean_file_name) { $projects = $presentation_projects[$layer_name]; break; } if (!$projects || !$projects[$project]) { header("Location: $choose_available_project_url"); echo "<script>document.location='$choose_available_project_url';</script>"; die(); } else { $util_path = $PEVC->getUtilsPath($project); $util_exists = $util_path && is_dir($util_path); } if ($projects) { $new_projects = array(); foreach ($projects as $fp => $project_props) { $fp = preg_replace("/[\/]+/", "/", $fp); $fp = preg_replace("/^\//", "", $fp); $fp = preg_replace("/\/$/", "", $fp); $dirs = explode("/", $fp); $file_name = array_pop($dirs); $obj = &$new_projects; foreach ($dirs as $dir) { if (!isset($obj[$dir])) $obj[$dir] = array(); $obj = &$obj[$dir]; } if ($project == $fp) $project_props["is_selected"] = true; $project_props["is_project"] = true; $obj[$file_name] = $project_props; } $projects = $new_projects; } if ($layers["db_layers"]) { $pres_db_drivers = WorkFlowBeansFileHandler::getLayerDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $P, true); $pres_db_drivers_bn = array_keys($pres_db_drivers); $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $db_driver_broker_name = $GLOBALS["default_db_driver"] ? $GLOBALS["default_db_driver"] : $pres_db_drivers_bn[0]; $PHPVariablesFileHandler->endUserGlobalVariables(); $db_driver_props = $pres_db_drivers[$db_driver_broker_name]; if ($db_driver_broker_name && $db_driver_props) { $db_data_layer = WorkFlowBeansFileHandler::getLayerLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, $P, $db_driver_broker_name, $found_broker_obj, $found_broker_props); if ($db_data_layer) { $is_db_layer_allowed = false; foreach ($layers["db_layers"] as $layer_name => $layer) { $db_data_bean_name = $layer["properties"]["bean_name"]; $db_data_bean_file_name = $layer["properties"]["bean_file_name"]; $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $db_data_bean_file_name, $user_global_variables_file_path); $db_data_obj = $WorkFlowBeansFileHandler->getBeanObject($db_data_bean_name); $db_data_layer_name = WorkFlowBeansFileHandler::getLayerNameFromBeanObject($db_data_bean_name, $db_data_obj); $db_data_broker_name = WorkFlowBeansConverter::getBrokerNameFromRawLabel($db_data_layer_name); if ($db_data_broker_name == $db_data_layer) { if ($layer[$db_driver_props[2]]) $is_db_layer_allowed = true; else { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerObjFolderName($db_data_obj); $layer_object_id = LAYER_PATH . $layer_bean_folder_name; $fn_layer_object_id = "$layer_object_id/" . $db_driver_props[2]; $is_db_layer_allowed = $UserAuthenticationHandler->isInnerFilePermissionAllowed($fn_layer_object_id, "layer", "access"); } break; } } if ($is_db_layer_allowed) { $db_driver_bean_name = $db_driver_props[2]; $db_driver_bean_file_name = $db_driver_props[1]; $db_driver_layer_bean_name = $db_data_bean_name; $db_driver_layer_bean_file_name = $db_data_bean_file_name; $db_driver_layer_folder_name = $layer_bean_folder_name; $BeanFactory = new BeanFactory(); $BeanFactory->init(array("file" => $user_beans_folder_path . $db_driver_layer_bean_file_name)); $bean = $BeanFactory->getBean($db_driver_layer_bean_name); $bean_objs = AdminMenuHandler::getBeanDBObjs($bean, $BeanFactory->getBeans(), $BeanFactory->getObjects()); $menu_item_properties = array( $db_driver_bean_name => $bean_objs[$db_driver_bean_name]["properties"]["item_menu"] ); } } } } ?>
