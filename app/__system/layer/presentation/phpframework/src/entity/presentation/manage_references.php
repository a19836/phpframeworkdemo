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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $popup = $_GET["popup"]; $on_success_js_func = $_GET["on_success_js_func"]; $path = str_replace("../", "", $path); if ($bean_name && $bean_file_name && $path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); $file_path = $layer_path . $path; if (file_exists($file_path)) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($file_path, "layer", "access"); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); if (!$LayoutTypeProjectHandler->isPathAPresentationProjectPath($file_path)) $error_message = "Error: This path is not a presentation project! Only presentation project paths are allowed!"; else if ($LayoutTypeProjectHandler->existsLayoutFromProjectPath($file_path) || $LayoutTypeProjectHandler->createNewLayoutFromProjectPath($file_path, false)) { $layout_type_data = $LayoutTypeProjectHandler->getLayoutFromProjectPath($file_path); if ($layout_type_data && $layout_type_data["layout_type_id"]) { $layout_type_id = $layout_type_data["layout_type_id"]; if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $permissions_by_objects = $_POST["permissions_by_objects"]; if ($layout_type_id && $UserAuthenticationHandler->updateLayoutTypesByObjectsPermissions($layout_type_id, $permissions_by_objects)) $status_message = "Layout Type Permissions were saved correctly"; else $error_message = "There was an error trying to save the layout type permissions. Please try again..."; } $permissions = $UserAuthenticationHandler->getAvailablePermissions(); $object_types = $UserAuthenticationHandler->getAvailableObjectTypes(); $layer_object_type_id = $object_types["layer"]; $raw_layers = f5a138641be($EVC, $UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path); unset($raw_layers["others"]); unset($raw_layers["vendors"]); unset($raw_layers["db_layers"]); $layer_object_id_prefix = str_replace(APP_PATH, "", LAYER_PATH); $layer_object_id_prefix = substr($layer_object_id_prefix, -1) == "/" ? substr($layer_object_id_prefix, 0, -1) : $layer_object_id_prefix; $layers = array(); $layers_label = array(); $layers_object_id = array(); $layers_props = array(); $layers_to_show = array("presentation_layers", "business_logic_layers", "data_access_layers", "db_layers"); $presentation_projects = array(); foreach ($layers_to_show as $layer_type_name) { $layer_type = $raw_layers[$layer_type_name]; if ($layer_type) foreach ($layer_type as $layer_name => $layer) { $lln = strtolower($layer_name); $layers[$layer_type_name][$lln] = array(); $layers_label[$layer_type_name][$lln] = isset($layer["properties"]["item_label"]) ? $layer["properties"]["item_label"] : $lln; $layers_object_id[$layer_type_name][$lln] = $layer["properties"]["layer_bean_folder_name"]; $layers_props[$layer_type_name][$lln] = $layer["properties"]; } } $layers_to_be_referenced = $layers; $layer_brokers_settings = WorkFlowTestUnitHandler::getAllLayersBrokersSettings($user_global_variables_file_path, $user_beans_folder_path); $presentation_brokers = $layer_brokers_settings["presentation_brokers"]; $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; } else $error_message = "Error trying to get layout type for this project. Please try again..."; } else $error_message = "Error trying to create new layout type for this project. Please try again..."; } $PHPVariablesFileHandler->endUserGlobalVariables(); } } function f5a138641be($EVC, $UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path) { include $EVC->getUtilPath("admin_uis_layers_and_permissions"); return $v2635bad135; } ?>
