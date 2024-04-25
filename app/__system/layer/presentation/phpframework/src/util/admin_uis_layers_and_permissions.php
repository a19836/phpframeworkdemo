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
 $layers = AdminMenuHandler::getLayersFiles($user_global_variables_file_path); $layers["vendors"]["vendor"]["properties"]["item_label"] = "External Library"; $layers["vendors"]["vendor"]["properties"]["item_title"] = "Folder name: 'vendor'"; $layers["libs"]["lib"] = AdminMenuHandler::getLibObjs(false, 1); $layers["libs"]["lib"]["properties"]["item_label"] = "Internal Library"; $layers["libs"]["lib"]["properties"]["item_title"] = "Folder name: 'lib'"; $layers["others"]["other"] = AdminMenuHandler::getOtherObjs(false, 1); $layers["others"]["other"]["properties"]["item_label"] = "Other Files"; $layers["others"]["other"]["properties"]["item_title"] = "Folder name: 'other'"; $exists_db_drivers = false; if (!empty($layers["db_layers"])) { $aux = array(); foreach ($layers["db_layers"] as $layer_name => $layer) { if ($layer_name != "properties") { foreach ($layer as $driver_name => $driver) { if ($driver_name != "properties") { $db_name = "Tables"; $new_driver = array( $db_name => $driver, "properties" => isset($driver["properties"]) ? $driver["properties"] : null ); $new_driver[$db_name]["properties"]["item_type"] = "db_management"; $layers["db_layers"][$layer_name][$driver_name] = $new_driver; $exists_db_drivers = true; } } } } } if ($filter_by_layout) { if (!$UserAuthenticationHandler->searchLayoutTypes(array("name" => $filter_by_layout, "type_id" => UserAuthenticationHandler::$LAYOUTS_TYPE_FROM_PROJECT_ID))) $filter_by_layout = $filter_by_layout_permission = null; else $UserAuthenticationHandler->loadLayoutPermissions($filter_by_layout, UserAuthenticationHandler::$LAYOUTS_TYPE_FROM_PROJECT_ID); } $layout_types = $UserAuthenticationHandler->getAvailableLayoutTypes(UserAuthenticationHandler::$LAYOUTS_TYPE_FROM_PROJECT_ID); ksort($layout_types); $non_projects_layout_types = $layout_types; $filter_layout_by_layers_type = $filter_layout_by_layers_type ? $filter_layout_by_layers_type : array("presentation_layers", "business_logic_layers", "data_access_layers", "db_layers"); $presentation_projects = array(); $presentation_projects_by_layer_label = array(); $presentation_projects_by_layer_label_organized_by_folders = array(); foreach ($layers as $layer_type_name => $layer_type) foreach ($layer_type as $layer_name => $layer) { $layer_bean_name = isset($layer["properties"]["bean_name"]) ? $layer["properties"]["bean_name"] : null; $layer_bean_file_name = isset($layer["properties"]["bean_file_name"]) ? $layer["properties"]["bean_file_name"] : null; if ($layer_type_name == "vendors" || $layer_type_name == "others" || $layer_type_name == "libs") { $layer_bean_folder_name = $layer_name; $layer_object_id = $layer_name; } else { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer_bean_file_name, $layer_bean_name, $user_global_variables_file_path); $layer_object_id = LAYER_PATH . $layer_bean_folder_name; } $layers[$layer_type_name][$layer_name]["properties"]["layer_bean_folder_name"] = $layer_bean_folder_name; $do_not_filter_layer_by_layout = $do_not_filter_by_layout && $do_not_filter_by_layout["bean_name"] == $layer_bean_name && $do_not_filter_by_layout["bean_file_name"] == $layer_bean_file_name; if (!$UserAuthenticationHandler->isInnerFilePermissionAllowed($layer_object_id, "layer", "access")) { unset($layers[$layer_type_name][$layer_name]); } else if ($layer_type_name == "db_layers" || $layer_type_name == "presentation_layers") { foreach ($layer as $fn => $f) if ($fn != "properties" && $fn != "aliases") { $fn_layer_object_id = "$layer_object_id/$fn"; if (!$UserAuthenticationHandler->isInnerFilePermissionAllowed($fn_layer_object_id, "layer", "access")) unset($layers[$layer_type_name][$layer_name][$fn]); else if ($layer_type_name == "db_layers" && $filter_by_layout) { if (!$UserAuthenticationHandler->isLayoutInnerFilePermissionAllowed($fn_layer_object_id, $filter_by_layout, "layer", $filter_by_layout_permission)) unset($layers[$layer_type_name][$layer_name][$fn]); } } if ($layer_type_name == "presentation_layers" && $layout_types) { $projects = CMSPresentationLayerHandler::getPresentationLayerProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, $layer_bean_file_name, $layer_bean_name); $projs = array(); $projs_by_folders = array(); if ($projects) { $do_not_filter_by_layout_project = isset($do_not_filter_by_layout["project"]) ? $do_not_filter_by_layout["project"] : null; foreach ($projects as $project_name => $project_props) { $fn_layer_object_id = "$layer_object_id/$project_name"; if ($UserAuthenticationHandler->isInnerFilePermissionAllowed($fn_layer_object_id, "layer", "access")) { $proj_id = "$layer_bean_folder_name/$project_name"; if (!empty($layout_types[$proj_id])) { $projs[$proj_id] = $project_name; unset($non_projects_layout_types[$proj_id]); $dirs = explode("/", $project_name); $file_name = array_pop($dirs); $obj = &$projs_by_folders; foreach ($dirs as $dir) { if (!isset($obj[$dir])) $obj[$dir] = array(); $obj = &$obj[$dir]; } $obj[$proj_id] = $file_name; } } else if (!$do_not_filter_by_layout || $do_not_filter_by_layout_project != $project_name) unset($projects[$project_name]); } } $layer_label = isset($layer["properties"]["item_label"]) ? $layer["properties"]["item_label"] : strtolower($layer_name); $presentation_projects[$layer_name] = $projects; $presentation_projects_by_layer_label[$layer_label] = $projs; $presentation_projects_by_layer_label_and_folders[$layer_label] = $projs_by_folders; $presentation_bean_folder_name_by_layer_label[$layer_label] = $layer_bean_folder_name; } } if ($filter_by_layout && !$do_not_filter_layer_by_layout && in_array($layer_type_name, $filter_layout_by_layers_type) && !$UserAuthenticationHandler->isLayoutInnerFilePermissionAllowed($layer_object_id, $filter_by_layout, "layer", $filter_by_layout_permission)) { unset($layers[$layer_type_name][$layer_name]); } } $is_flush_cache_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/flush_cache"), "delete"); $is_manage_modules_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/manage_modules"), "access"); $is_manage_projects_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("presentation/manage_projects"), "access"); $is_manage_users_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("user/manage_users"), "access"); $is_manage_layers_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("setup/layers"), "access"); $is_deployment_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("deployment/index"), "access"); $is_testunits_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("testunit/index"), "access"); $is_program_installation_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/install_program"), "access"); $is_diff_files_allowed = $UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("diff/index"), "access"); include $EVC->getUtilPath("admin_uis_permissions"); ?>
