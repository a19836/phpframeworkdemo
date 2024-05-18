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
include_once get_lib("org.phpframework.util.web.html.pagination.PaginationLayout"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layer_bean_folder_name = $_GET["layer_bean_folder_name"]; $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $table = $_GET["table"]; $db_type = $_GET["db_type"] ? $_GET["db_type"] : "diagram"; $popup = $_GET["popup"]; if ($bean_name && $table) { $layer_object_id = LAYER_PATH . "$layer_bean_folder_name/$bean_name"; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); $WorkFlowDBHandler = new WorkFlowDBHandler($user_beans_folder_path, $user_global_variables_file_path); $DBDriver = $WorkFlowDBHandler->getBeanObject($bean_file_name, $bean_name); $existent_tables = $DBDriver->listTables(); $table_exists = $DBDriver->isTableInNamesList($existent_tables, $table); if ($table_exists) { $table_fields = $DBDriver->listTableFields($table); if ($table_fields) { $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); $exists = false; if ($db_type == "diagram") { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $bean_name); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); $tasks_tables = $WorkFlowDataAccessHandler->getTasksAsTables(); $task_table_name = $DBDriver->getTableInNamesList(array_keys($tasks_tables), $table); $exists = $task_table_name && $tasks_tables[$task_table_name]; } if (!$exists) { $fks = $DBDriver->listForeignKeys($table); $tables_data = array( $table => array($table_fields, $fks) ); $t = count($fks); for ($i = 0; $i < $t; $i++) { $fk_table = $fks[$i]["parent_table"]; $attrs = $DBDriver->listTableFields($fk_table); $tables_data[$fk_table] = array($attrs, null); } $tasks = WorkFlowDBHandler::getUpdateTaskDBDiagramFromTablesData($tables_data); $WorkFlowDataAccessHandler->setTasks($tasks); $tasks_tables = $WorkFlowDataAccessHandler->getTasksAsTables(); $task_table_name = $DBDriver->getTableInNamesList(array_keys($tasks_tables), $table); } $tasks_tables = $WorkFlowDataAccessHandler->getTasksAsTables(); $task_table_name = $DBDriver->getTableInNamesList(array_keys($tasks_tables), $table); if ($task_table_name && $tasks_tables[$task_table_name]) $table_fields = $tasks_tables[$task_table_name]; if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $selected_rows = $_POST["selected_rows"]; if ($selected_rows) { $selected_pks = $_POST["selected_pks"]; $status = true; foreach ($selected_rows as $idx) { $conditions = $selected_pks[$idx]; if ($conditions && !$DBDriver->deleteObject($table, $conditions)) $status = false; } if ($status) $status_message = "Records deleted successfully!"; else $error_message = "Error: Records not deleted successfully!"; } else $error_message = "You must select at least one row."; } $fks = array(); $extra_fks = array(); foreach ($table_fields as $field_name => $field) if ($field["fk"]) foreach ($field["fk"] as $fk) { $fk_table = $fk["table"]; $fk_attribute = $fk["attribute"]; $fks[$fk_table][$fk_attribute] = $field_name; } foreach ($tasks_tables as $task_tn => $task_table_attributes) { if ($task_tn != $task_table_name) foreach ($task_table_attributes as $attribute_name => $attribute_props) if ($attribute_props["fk"]) foreach ($attribute_props["fk"] as $fk) if ($fk["table"] == $task_table_name) { $extra_fks[$task_tn][$attribute_name] = $fk["attribute"]; } } $pks = array(); foreach ($table_fields as $field_name => $field) if ($field["primary_key"]) $pks[] = $field_name; $conditions = $_GET["conditions"]; $conditions_operators = $_GET["conditions_operators"]; if ($conditions) foreach ($conditions as $field_name => $field_value) if (!$table_fields[$field_name]) unset($conditions[$field_name]); $conds = $conditions; if ($conditions_operators) foreach ($conditions_operators as $field_name => $operator) { if ($operator == "like" || $operator == "not like") $conds[$field_name] = array("operator" => $operator, "value" => "%" . $conditions[$field_name] . "%"); else if ($operator != "=") $conds[$field_name] = array("operator" => $operator, "value" => $conditions[$field_name]); } $count = $DBDriver->countObjects($table, $conds); $settings = array( "pg" => $_GET["pg"], ); $PaginationLayout = new PaginationLayout($count, 100, $settings, "pg"); $pagination_data = $PaginationLayout->data; $sorts = $_GET["sorts"]; if ($sorts) foreach ($sorts as $field_name => $field_value) if (!$table_fields[$field_name]) unset($sorts[$field_name]); $options = array( "start" => $pagination_data["start"], "limit" => $pagination_data["limit"], "sort" => $sorts, ); $results = $DBDriver->findObjects($table, null, $conds, $options); $table_fields_types = array(); $numeric_types = $DBDriver->getDBColumnNumericTypes(); $date_types = $DBDriver->getDBColumnDateTypes(); $text_types = $DBDriver->getDBColumnTextTypes(); $blob_types = $DBDriver->getDBColumnBlobTypes(); $boolean_types = $DBDriver->getDBColumnBooleanTypes(); foreach ($table_fields as $field_name => $field) { $field_type = $field["type"]; $options = array(); if ($field["fk"] && $field["fk"][0]) { $fk = WorkFlowDataAccessHandler::getTableAttributeFKTable($field["fk"], $tasks_tables); $fk_table = $fk["table"]; $fk_attribute = $fk["attribute"]; $fk_count = $DBDriver->countObjects($fk_table); if ($fk_count < 1000) { $fk_table_attributes = $tasks_tables[$fk_table]; $title_attr = WorkFlowDataAccessHandler::getTableAttrTitle($fk_table_attributes, $fk_table); $title_attr = $title_attr ? $title_attr : $fk_attribute; $fk_results = $DBDriver->findObjects($fk_table, array($fk_attribute, $title_attr), null); if ($fk_results) { if ($field["null"]) $options[""] = ""; foreach ($fk_results as $fk_result) $options[ $fk_result[$fk_attribute] ] = ($title_attr != $fk_attribute ? $fk_result[$fk_attribute] . " - " : "") . $fk_result[$title_attr]; } } } if ($options) { $table_fields_types[$field_name] = array( "type" => "select", "options" => $options ); } else if (in_array($field_type, $boolean_types) || (($field_type == "smallint" || $field_type == "tinyint") && $field["length"] == 1)) $table_fields_types[$field_name] = "checkbox"; else if (in_array($field_type, $numeric_types)) $table_fields_types[$field_name] = "number"; else if (in_array($field_type, $date_types)) { if ($field_type == "date") $table_fields_types[$field_name] = "date"; else if ($field_type == "datetime" || $field_type == "timestamp") $table_fields_types[$field_name] = "datetime"; else if ($field_type == "time") $table_fields_types[$field_name] = "time"; else $table_fields_types[$field_name] = "text"; } else if (in_array($field_type, $text_types) && preg_match("/text/i", $field_type)) $table_fields_types[$field_name] = "textarea"; else if (in_array($field_type, $blob_types) && preg_match("/blob/i", $field_type)) $table_fields_types[$field_name] = "file"; else $table_fields_types[$field_name] = "text"; } } } } ?>
