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

include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layer_bean_folder_name = $_GET["layer_bean_folder_name"]; $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $table = $_GET["table"]; $db_type = $_GET["db_type"] ? $_GET["db_type"] : "diagram"; $action = $_GET["action"]; $popup = $_GET["popup"]; if ($bean_name && $table) { $layer_object_id = LAYER_PATH . "$layer_bean_folder_name/$bean_name"; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); $WorkFlowDBHandler = new WorkFlowDBHandler($user_beans_folder_path, $user_global_variables_file_path); $DBDriver = $WorkFlowDBHandler->getBeanObject($bean_file_name, $bean_name); $existent_tables = $DBDriver->listTables(); $table_exists = $DBDriver->isTableInNamesList($existent_tables, $table); if ($table_exists) { $table_fields = $DBDriver->listTableFields($table); if ($table_fields) { $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); if ($db_type == "diagram") { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $bean_name); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); } else { $fks = $DBDriver->listForeignKeys($table); $tables_data = array( $table => array($table_fields, $fks) ); $tasks = WorkFlowDBHandler::getUpdateTaskDBDiagramFromTablesData($tables_data); $WorkFlowDataAccessHandler->setTasks($tasks); } $tasks_tables = $WorkFlowDataAccessHandler->getTasksAsTables(); $table_fields = $tasks_tables[$table] ? $tasks_tables[$table] : $table_fields; $pks = array(); foreach ($table_fields as $field_name => $field) if ($field["primary_key"]) $pks[] = $field_name; $conditions = $_GET["conditions"]; if ($conditions) foreach ($conditions as $field_name => $field_value) if (!$table_fields[$field_name]) unset($conditions[$field_name]); if ($conditions) { $results = $DBDriver->findObjects($table, null, $conditions); $results = $results[0]; } $table_fields_types = array(); $numeric_types = $DBDriver->getDBColumnNumericTypes(); $date_types = $DBDriver->getDBColumnDateTypes(); $text_types = $DBDriver->getDBColumnTextTypes(); foreach ($table_fields as $field_name => $field) { $field_type = $field["type"]; $options = array(); if ($field["fk"] && $field["fk"][0]) { $fk = $field["fk"][0]; $fk_table = $fk["table"]; $fk_attribute = $fk["attribute"]; $fk_count = $DBDriver->countObjects($fk_table); if ($fk_count < 1000) { $fk_table_attributes = $tasks_tables[$fk_table]; $fk_label = $fk_table_attributes["name"] ? "name" : ($fk_table_attributes["title"] ? "title" : ($fk_table_attributes["label"] ? "label" : $fk_attribute)); $fk_results = $DBDriver->findObjects($fk_table, array($fk_attribute, $fk_label), null); if ($fk_results) foreach ($fk_results as $fk_result) $options[ $fk_result[$fk_attribute] ] = ($fk_label != $fk_attribute ? $fk_result[$fk_attribute] . " - " : "") . $fk_result[$fk_label]; } } if ($options) { $table_fields_types[$field_name] = array( "type" => "select", "options" => $options ); } else if (($field_type == "smallint" || $field_type == "tinyint") && $field["length"] == 1) $table_fields_types[$field_name] = "checkbox"; else if (in_array($field_type, $numeric_types)) $table_fields_types[$field_name] = "number"; else if (in_array($field_type, $date_types)) { if ($field_type == "date") $table_fields_types[$field_name] = "date"; else if ($field_type == "datetime" || $field_type == "timestamp") $table_fields_types[$field_name] = "datetime"; else if ($field_type == "time") $table_fields_types[$field_name] = "time"; else $table_fields_types[$field_name] = "text"; } else if (in_array($field_type, $text_types) && preg_match("/(text|blob)/i", $field_type)) $table_fields_types[$field_name] = "textarea"; else $table_fields_types[$field_name] = "text"; } } } } ?>
