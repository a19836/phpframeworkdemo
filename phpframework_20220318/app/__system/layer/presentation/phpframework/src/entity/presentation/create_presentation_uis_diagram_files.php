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

include $EVC->getUtilPath("CMSPresentationUIDiagramFilesHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $overwrite = $_GET["overwrite"]; $users_perms_relative_folder = $_GET["users_perms_relative_folder"]; $list_and_edit_users = $_GET["list_and_edit_users"]; $non_authenticated_template = $_GET["non_authenticated_template"]; $files_date_simulation = $_GET["files_date_simulation"]; $files_code_validation = $_GET["files_code_validation"]; $do_not_save_vars_file = $_GET["do_not_save_vars_file"]; $do_not_check_if_path_exists = $_GET["do_not_check_if_path_exists"]; if ($bean_name) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $UserCacheHandler = $PHPFrameWork->getObject("UserCacheHandler"); $P = $PEVC->getPresentationLayer(); $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); $extension = $P->getPresentationFileExtension(); $folder_path = $layer_path . $path; if ($path && (is_dir($folder_path) || $do_not_check_if_path_exists)) { $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($folder_path, "layer", "access"); $relative_path = str_replace($PEVC->getEntitiesPath(), "", $folder_path); $settings = htmlspecialchars_decode( file_get_contents("php://input") ); $settings = json_decode($settings, true); if (strtoupper($_SERVER['REQUEST_METHOD']) === "POST" && is_array($settings) && $settings) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $UserAuthenticationHandler->incrementUsedActionsTotal(); $table_statuses = array(); $files_creation_type = $files_code_validation ? 3 : ($files_date_simulation ? 2 : 1); $users_perms_relative_folder .= $users_perms_relative_folder && substr($users_perms_relative_folder, -1) != "/" ? "/" : ""; $vars_file_id = "{$users_perms_relative_folder}vars"; $vars_file_include_code = '$EVC->getConfigPath("' . $vars_file_id . '")'; if ($settings && $settings["tasks_details"]) foreach ($settings["tasks_details"] as $task) if ($task["tag"] == "page") { $template = $task["properties"]["template"]; $authenticated_template = $template; CMSPresentationUIDiagramFilesHandler::createPageFile($PEVC, $UserCacheHandler, $cms_page_cache_path_prefix, $user_global_variables_file_path, $user_beans_folder_path, $workflow_paths_id, $webroot_cache_folder_path, $webroot_cache_folder_url, $bean_name, $bean_file_name, $path, $relative_path, $overwrite, $files_creation_type, $vars_file_include_code, $users_perms_relative_folder, $list_and_edit_users, $authenticated_template, $non_authenticated_template, $template, $table_statuses, $js_funcs = array(), $js_code = "", $settings["tasks_details"], $task); } $auth_page_and_block_ids = CMSPresentationUIDiagramFilesHandler::getAuthPageAndBlockIds(); if (!$do_not_save_vars_file) { $vars = array( "admin_url" => "{\$project_url_prefix}$users_perms_relative_folder", "logout_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["logout_page_id"], "login_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["login_page_id"], "register_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["register_page_id"], "forgot_credentials_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["forgot_credentials_page_id"], "edit_profile_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["edit_profile_page_id"], "list_and_edit_users_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["list_and_edit_users_page_id"], "list_users_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["list_users_page_id"], "edit_user_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["edit_user_page_id"], "add_user_url" => "{\$project_url_prefix}" . $auth_page_and_block_ids["add_user_page_id"], ); CMSPresentationUIDiagramFilesHandler::addVarsFile($PEVC, $vars_file_id, $vars); CMSPresentationUIDiagramFilesHandler::addUserAccessControlToVarsFile($PEVC, $vars_file_id, $list_and_edit_users, array("list_and_edit_users_url", "list_users_url", "edit_user_url", "add_user_url")); } $statuses = array(); foreach ($table_statuses as $task_id => $task_statuses) foreach ($task_statuses as $file_path => $status) { $fp = str_replace($layer_path, "", $file_path); $fp = substr($fp, 0, strlen($fp) - (strlen($extension) + 1)); $is_auth_reserved_file = false; if ($auth_page_and_block_ids) foreach ($auth_page_and_block_ids as $k => $v) if ($k != "access_id" && $k != "object_type_page_id") { $path = $selected_project_id . (strpos($k, "_page_id") > 0 ? "/src/entity/" : "/src/block/") . $v; if ($fp == $path) { $is_auth_reserved_file = true; break; } } if ($is_auth_reserved_file) $statuses["*"][$fp] = $status; else $statuses[$task_id][$fp] = $status; } } } $PHPVariablesFileHandler->endUserGlobalVariables(); } } ?>
