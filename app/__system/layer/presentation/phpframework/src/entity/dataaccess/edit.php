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

include_once $EVC->getUtilPath("WorkFlowQueryHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $hbn_obj_id = $_GET["obj"]; $query_id = $_GET["query_id"]; $map_id = $_GET["map"]; $query_type = $_GET["query_type"]; $relationship_type = $_GET["relationship_type"]; $filter_by_layout = $_GET["filter_by_layout"]; $selected_db_driver = $_GET["selected_db_driver"]; $path = str_replace("../", "", $path); $is_import_file = $relationship_type == "import"; $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "DataAccessLayer")) { $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTypes(array("query")); $layer_path = $obj->getLayerPathSetting(); $file_path = $layer_path . $path; if ($path && file_exists($file_path)) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($file_path, "layer", "access"); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); if ($obj->getType() == "hibernate") { switch ($file_type) { case "edit_obj": $obj_data = WorkFlowDataAccessHandler::getXmlHibernateObjData($file_path, $hbn_obj_id); $selected_table = WorkFlowDataAccessHandler::getNodeValue($obj_data, "table"); $hbn_class_objs = WorkFlowDataAccessHandler::getDAOObjectsLibPath("HibernateModel"); break; case "edit_query": case "edit_relationship": case "edit_map": $id = $file_type == "edit_map" ? $map_id : $query_id; if ($is_import_file) $obj_data = WorkFlowDataAccessHandler::getXmlQueryOrMapData($file_path, $id, array($query_type)); else $obj_data = WorkFlowDataAccessHandler::getXmlHibernateObjQueryOrMapData($file_path, $hbn_obj_id, $id, array($query_type), $relationship_type); break; case "edit_includes": $obj_data = WorkFlowDataAccessHandler::getXmlHibernateImportsData($file_path); break; } } else { switch ($file_type) { case "edit_query": $obj_data = WorkFlowDataAccessHandler::getXmlQueryOrMapData($file_path, $query_id, array($query_type)); break; case "edit_map": $obj_data = WorkFlowDataAccessHandler::getXmlQueryOrMapData($file_path, $map_id, array($query_type)); break; case "edit_includes": $obj_data = WorkFlowDataAccessHandler::getXmlHibernateImportsData($file_path); break; } } if ($file_type == "edit_query") { $sql = XMLFileParser::getValue($obj_data); $data = $sql ? $obj->getFunction("convertSQLToObject", $sql) : array(); $selected_table = $data["attributes"][0]["table"]; $rel_type = $data["type"]; $name = $obj_data["@"]["id"]; $parameter_class = $obj_data["@"]["parameter_class"]; $parameter_map = $obj_data["@"]["parameter_map"]; $result_class = $obj_data["@"]["result_class"]; $result_map = $obj_data["@"]["result_map"]; } $selected_data = WorkFlowQueryHandler::getSelectedDBBrokersDriversTablesAndAttributes($obj, $tasks_file_path, $workflow_paths_id, $selected_table, $selected_db_driver, $filter_by_layout, $LayoutTypeProjectHandler); $brokers = $selected_data["brokers"]; $db_drivers = $selected_data["db_drivers"]; $selected_db_broker = $selected_data["selected_db_broker"]; $selected_db_driver = $selected_data["selected_db_driver"]; $selected_type = $selected_data["selected_type"]; $selected_table = $selected_data["selected_table"]; $selected_tables_name = $selected_data["selected_tables_name"]; $selected_table_attrs = $selected_data["selected_table_attrs"]; $obj_type_objs = WorkFlowDataAccessHandler::getDAOObjectsLibPath("objtype"); $map_php_types = WorkFlowDataAccessHandler::getMapPHPTypes(); $map_db_types = WorkFlowDataAccessHandler::getMapDBTypes(); } else { launch_exception(new Exception("File Not Found: " . $path)); die(); } } $PHPVariablesFileHandler->endUserGlobalVariables(); ?>
