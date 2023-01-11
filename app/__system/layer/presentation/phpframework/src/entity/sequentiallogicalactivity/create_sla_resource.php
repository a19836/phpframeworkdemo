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

include_once $EVC->getUtilPath("SequentialLogicalActivityResourceCreator"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $filter_by_layout = $_GET["filter_by_layout"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); $status = false; if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); $file_path = $layer_path . $path; if (file_exists($file_path)) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($file_path, "layer", "access"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); $action_type = $_POST["action_type"]; $resource_name = $_POST["resource_name"]; $resource_data = $_POST["resource_data"]; $db_broker = $_POST["db_broker"]; $db_driver = $_POST["db_driver"]; $db_type = $_POST["db_type"]; $db_table = $_POST["db_table"]; $permissions = $_POST["permissions"]; $folder_path = $PEVC->getUtilsPath() . "resource"; if (!is_dir($folder_path)) mkdir($folder_path, 0755, true); if (is_dir($folder_path)) { $selected_db_driver = $db_driver ? $db_driver : $GLOBALS["default_db_driver"]; $selected_db_table = $db_table; if ($action_type == "get_options") { if (array_key_exists("table", $resource_data)) $selected_db_table = $resource_data["table"]; else if (is_array($resource_data[0])) $selected_db_table = $resource_data[0]["table"]; } if ($selected_db_table) { $SequentialLogicalActivityResourceCreator = new SequentialLogicalActivityResourceCreator($EVC, $PEVC, $UserAuthenticationHandler, $workflow_paths_id, $webroot_cache_folder_path, $webroot_cache_folder_url, $user_global_variables_file_path, $user_beans_folder_path, $project_url_prefix, $filter_by_layout, $bean_name, $bean_file_name, $path, $db_broker, $selected_db_driver, $db_type, $selected_db_table); $action_file_method_exists = $SequentialLogicalActivityResourceCreator->createUtilMethod($action_type, $resource_data, $error_message); if (!$error_message && $action_file_method_exists) { $status = true; $actions = $SequentialLogicalActivityResourceCreator->getSLAResourceActions($action_type, $resource_name, $resource_data, $permissions); } } else { launch_exception(new Exception("No db table selected!")); die(); } } else { launch_exception(new Exception("Resource folder not created!")); die(); } } else { launch_exception(new Exception("File Not Found: " . $path)); die(); } $PHPVariablesFileHandler->endUserGlobalVariables(); } else { launch_exception(new Exception("PEVC doesn't exists!")); die(); } } else if (!$path) { launch_exception(new Exception("Undefined path!")); die(); } ?>
