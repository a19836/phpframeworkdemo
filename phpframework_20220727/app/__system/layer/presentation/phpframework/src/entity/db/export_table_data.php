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

include_once $EVC->getUtilPath("WorkFlowQueryHandler"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layer_bean_folder_name = $_GET["layer_bean_folder_name"]; $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $type = $_GET["type"]; $table = str_replace("/", "", $_GET["table"]); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "DB")) { $layer_object_id = LAYER_PATH . "$layer_bean_folder_name/$bean_name"; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTypes(array("query")); $sql = "select * from $table;"; if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $sql = $_POST["sql"]; $export_type = $_POST["export_type"]; $doc_name = $_POST["doc_name"]; if (!$sql) $error_message = "Please write a select sql statement."; else { try { $data = $obj->getData($sql); $doc_name = $doc_name ? $doc_name : "{$table}_export"; $content_type = $export_type == "xls" ? "application/vnd.ms-excel" : ($export_type == "csv" ? "text/csv" : "text/plain"); header("Content-Type: $content_type"); header('Content-Disposition: attachment; filename="' . $doc_name . '.' . $export_type . '"'); $str = ""; if ($data && is_array($data)) { $columns = $data["fields"]; $columns_length = count($columns); $results = $data["result"]; $rows_delimiter = "\n"; $columns_delimiter = "\t"; $enclosed_by = ""; if ($export_type == "csv") { $columns_delimiter = ","; $enclosed_by = '"'; $str .= "sep=$columns_delimiter$rows_delimiter"; } for ($i = 0; $i < $columns_length; $i++) $str .= ($i > 0 ? $columns_delimiter : "") . $enclosed_by . addcslashes($columns[$i]->name, $columns_delimiter . $enclosed_by . "\\") . $enclosed_by; if ($str && is_array($results)) { $str .= $rows_delimiter; foreach ($results as $row) if (is_array($row)) { for ($i = 0; $i < $columns_length; $i++) $str .= ($i > 0 ? $columns_delimiter : "") . $enclosed_by . addcslashes($row[ $columns[$i]->name ], $columns_delimiter . $enclosed_by . "\\") . $enclosed_by; $str .= $rows_delimiter; } } } echo $str; die(); } catch(Exception $e) { throw $e; } } } $db_driver_borker_name = WorkFlowBeansConverter::getBrokerNameFromRawLabel($bean_name); $db_drivers = array($layer_bean_folder_name => array($db_driver_borker_name)); $rel_type = "select"; $selected_db_broker = $layer_bean_folder_name; $selected_db_driver = $db_driver_borker_name; $selected_type = "db"; $selected_tables = $obj->listTables(); $selected_tables_name = array(); if ($selected_tables) foreach ($selected_tables as $selected_table) $selected_tables_name[] = $selected_table["name"]; $selected_table_exists = $obj->isTableInNamesList($selected_tables_name, $table); $selected_table = $selected_table_exists ? $table : $selected_tables_name[0]; $selected_table_attrs = $obj->listTableFields($selected_table); $selected_table_attrs = array_keys($selected_table_attrs); } $PHPVariablesFileHandler->endUserGlobalVariables(); ?>
