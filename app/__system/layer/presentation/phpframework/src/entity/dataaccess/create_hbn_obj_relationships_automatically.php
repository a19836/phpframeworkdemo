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

include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); include_once $EVC->getUtilPath("WorkFlowDBHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once get_lib("org.phpframework.util.MyArray"); include_once $EVC->getUtilPath("FlushCacheHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "HibernateDataAccessLayer")) { $db_broker = $_POST["db_broker"]; $db_driver = $_POST["db_driver"]; $type = $_POST["type"]; $selected_tables = $_POST["st"]; $with_maps = $_POST["with_maps"] == "true" || $_POST["with_maps"] == "1"; $rel_type = $_POST["rel_type"]; $selected_tables = $selected_tables ? $selected_tables : array(); $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); if ($type == "diagram") { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $db_driver); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); } else { $tables = $obj->getBroker($db_broker)->getFunction("listTables", null, array("db_driver" => $db_driver)); $tables_data = array(); $t = count($tables); for ($i = 0; $i < $t; $i++) { $table = $tables[$i]; if (!empty($table)) { $attrs = $obj->getBroker($db_broker)->getFunction("listTableFields", $table["name"], array("db_driver" => $db_driver)); $fks = $obj->getBroker($db_broker)->getFunction("listForeignKeys", $table["name"], array("db_driver" => $db_driver)); $tables_data[ $table["name"] ] = array($attrs, $fks, $table); } } $tasks = WorkFlowDBHandler::getUpdateTaskDBDiagramFromTablesData($tables_data); $WorkFlowDataAccessHandler->setTasks($tasks); } $results = array(); $t = count($selected_tables); for ($i = 0; $i < $t; $i++) { $table_name = $selected_tables[$i]; if ($rel_type == "relationships") { $arr = $WorkFlowDataAccessHandler->getHibernateObjectArrayFromDBTaskFlow($table_name, false, $with_maps); $relationships = $arr["class"][0]["childs"]["relationships"][0]["childs"]; } else { $arr = $WorkFlowDataAccessHandler->getQueryObjectsArrayFromDBTaskFlow($table_name, $with_maps); $relationships = $arr["queries"][0]["childs"]; } if ($relationships) { $relationships = MyXML::complexArrayToBasicArray($relationships, array("convert_attributes_to_childs" => true)); MyArray::arrKeysToLowerCase($relationships, true); $results[$table_name] = $relationships; } } FlushCacheHandler::flushCache($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $workflow_paths_id, $user_global_variables_file_path, $user_beans_folder_path, $css_and_js_optimizer_webroot_cache_folder_path, $deployments_temp_folder_path, $programs_temp_folder_path); } $PHPVariablesFileHandler->endUserGlobalVariables(); ?>
