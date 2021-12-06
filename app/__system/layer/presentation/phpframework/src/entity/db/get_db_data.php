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

include_once $EVC->getUtilPath("WorkFlowDBHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layer_bean_folder_name = $_GET["layer_bean_folder_name"]; $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $type = $_GET["type"]; $table = str_replace("/", "", $_GET["table"]); $layer_object_id = LAYER_PATH . "$layer_bean_folder_name/$bean_name"; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); $WorkFlowDBHandler = new WorkFlowDBHandler($user_beans_folder_path, $user_global_variables_file_path); $db_data = array( "properties" => array( "bean_file_name" => $bean_file_name, "bean_name" => $bean_name, ) ); if (empty($table)) { $db_data["properties"]["item_type"] = "dbdriver"; if ($type == "diagram") { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $bean_name); $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); $tasks = $WorkFlowDataAccessHandler->getTasks(); if ($tasks["tasks"]) foreach ($tasks["tasks"] as $table_name => $task) $db_data[ $table_name ] = array("properties" => array( "bean_file_name" => $bean_file_name, "bean_name" => $bean_name, "item_type" => "table", "name" => $table_name, )); } else { $tables = $WorkFlowDBHandler->getDBTables($bean_file_name, $bean_name); $t = count($tables); for ($i = 0; $i < $t; $i++) $db_data[ $tables[$i]["name"] ] = array("properties" => array( "bean_file_name" => $bean_file_name, "bean_name" => $bean_name, "item_type" => "table", "name" => $tables[$i]["name"], )); } } else { $db_data["properties"]["item_type"] = "table"; if ($type == "diagram") { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $bean_name); $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); $tables = $WorkFlowDataAccessHandler->getTasksAsTables(); $attrs = WorkFlowDataAccessHandler::getTableFromTables($tables, $table); } else $attrs = $WorkFlowDBHandler->getDBTableAttributes($bean_file_name, $bean_name, $table); if (is_array($attrs)) foreach ($attrs as $name => $attr) { $attr["bean_file_name"] = $bean_file_name; $attr["bean_name"] = $bean_name; $attr["item_type"] = "attribute"; $attr["table"] = $table; $db_data[$name] = array("properties" => $attr); } } $error = $WorkFlowDBHandler->getError(); if (!empty($error)) { $db_data = false; echo $error; } else { } ?>
