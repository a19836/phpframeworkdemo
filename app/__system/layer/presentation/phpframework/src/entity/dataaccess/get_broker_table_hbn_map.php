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

include_once get_lib("org.phpframework.util.xml.MyXML"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); if ($_POST) { $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $db_broker = $_POST["db_broker"]; $db_driver = $_POST["db_driver"]; $type = $_POST["type"]; $db_table = $_POST["db_table"]; $map_type = $_POST["map_type"]; $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "DataAccessLayer") && $db_table) { if ($type == "diagram") { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $db_driver); $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); $tasks = $WorkFlowDataAccessHandler->getTasks(); $table_attr_names = $tasks["tasks"][$db_table]["properties"]["table_attr_names"]; $table_attr_types = $tasks["tasks"][$db_table]["properties"]["table_attr_types"]; } else { $fields = $obj->getBroker($db_broker)->getFunction("listTableFields", $db_table, array("db_driver" => $db_driver)); if ($fields) { $table_attr_names = array(); $table_attr_types = array(); foreach ($fields as $field) { $table_attr_names[] = $field["name"]; $table_attr_types[] = $field["type"]; } } } if ($table_attr_names && $table_attr_types) { if ($map_type == "parameter") $xml = WorkFlowDataAccessHandler::getTableParameterMap($table_attr_names, $table_attr_types); else $xml = WorkFlowDataAccessHandler::getTableResultMap($table_attr_names, $table_attr_types); if ($xml) { $MyXML = new MyXML("<main_node>$xml</main_node>"); $arr = $MyXML->toArray(); $new_arr = $MyXML->complexArrayToBasicArray($arr); $items = $new_arr["main_node"]; } } } $PHPVariablesFileHandler->endUserGlobalVariables(); } ?>
