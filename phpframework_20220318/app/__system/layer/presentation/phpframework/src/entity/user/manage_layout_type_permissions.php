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

include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $type_id = $_GET["type_id"]; if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $layout_type_id = $_POST["layout_type_id"]; $permissions_by_objects = $_POST["permissions_by_objects"]; if ($layout_type_id && $UserAuthenticationHandler->updateLayoutTypesByObjectsPermissions($layout_type_id, $permissions_by_objects)) $status_message = "Layout Type Permissions were saved correctly"; else $error_message = "There was an error trying to save the layout type permissions. Please try again..."; } $available_types = UserAuthenticationHandler::$AVAILABLE_LAYOUTS_TYPES; $type_id = is_numeric($type_id) ? $type_id : key($available_types); $layout_types = $UserAuthenticationHandler->getAvailableLayoutTypes($type_id); ksort($layout_types); $permissions = $UserAuthenticationHandler->getAvailablePermissions(); $object_types = $UserAuthenticationHandler->getAvailableObjectTypes(); $layer_object_type_id = $object_types["layer"]; $raw_layers = AdminMenuHandler::getLayersFiles($user_global_variables_file_path); unset($raw_layers["others"]); unset($raw_layers["vendors"]); $layer_object_id_prefix = str_replace(APP_PATH, "", LAYER_PATH); $layer_object_id_prefix = substr($layer_object_id_prefix, -1) == "/" ? substr($layer_object_id_prefix, 0, -1) : $layer_object_id_prefix; $layers = array(); $layers_label = array(); $layers_object_id = array(); $layers_props = array(); $layers_to_show = array("presentation_layers", "business_logic_layers", "data_access_layers", "db_layers"); $presentation_projects = array(); foreach ($layers_to_show as $layer_type_name) { $layer_type = $raw_layers[$layer_type_name]; if ($layer_type) foreach ($layer_type as $layer_name => $layer) { $lln = strtolower($layer_name); $layers[$layer_type_name][$lln] = array(); $layers_label[$layer_type_name][$lln] = isset($layer["properties"]["item_label"]) ? $layer["properties"]["item_label"] : $lln; $layers_object_id[$layer_type_name][$lln] = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $layer["properties"]["bean_file_name"], $layer["properties"]["bean_name"], $user_global_variables_file_path); $layers_props[$layer_type_name][$lln] = $layer["properties"]; if ($layer_type_name == "db_layers") { foreach ($layer as $driver_name => $driver) if ($driver_name != "properties" && $driver_name != "aliases") $layers[$layer_type_name][$lln][$driver_name] = array(); } else if ($layer_type_name == "presentation_layers" && $type_id == 0 && $layout_types) { $projects = CMSPresentationLayerHandler::getPresentationLayerProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, $layer["properties"]["bean_file_name"], $layer["properties"]["bean_name"]); $projs = array(); if ($projects) foreach ($projects as $project_name => $project_props) { $proj_id = $layers_object_id[$layer_type_name][$lln] . "/$project_name"; if ($layout_types[$proj_id]) { $lt_id = $layout_types[$proj_id]; $projs[$lt_id] = $project_name; unset($layout_types[$proj_id]); } } $layer_label = $layers_label[$layer_type_name][$lln]; $presentation_projects[$layer_label] = $projs; } } } $layers_to_be_referenced = $layers; unset($layers_to_be_referenced["db_layers"]); $layer_brokers_settings = WorkFlowTestUnitHandler::getAllLayersBrokersSettings($user_global_variables_file_path, $user_beans_folder_path); $presentation_brokers = $layer_brokers_settings["presentation_brokers"]; $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; ?>
