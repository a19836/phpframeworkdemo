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

include_once $EVC->getUtilPath("WorkFlowDBHandler"); include_once $EVC->getUtilPath("FlushCacheHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); include $EVC->getEntityPath("/layer/diagram"); $tasks_file_path = $workflow_paths_id[$workflow_path_id]; if ($_POST["create_layers_workflow"]) { $tasks_folders = $_POST["tasks_folders"]; $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); if (file_exists($tasks_file_path)) { $WorkFlowDBHandler = new WorkFlowDBHandler($user_beans_folder_path, $user_global_variables_file_path); $post_global_vars = array("default_db_driver" => ""); if ((file_exists($user_global_variables_file_path) && filesize($user_global_variables_file_path) > 0) || PHPVariablesFileHandler::saveVarsToFile($user_global_variables_file_path, $post_global_vars, true)) { $WorkFlowBeansConverter = new WorkFlowBeansConverter($tasks_file_path, $user_beans_folder_path, $user_global_variables_file_path, $user_global_settings_file_path); $WorkFlowBeansConverter->init(); if ($WorkFlowBeansConverter->createBeans(array("tasks_folders" => $tasks_folders))) { FlushCacheHandler::flushCache($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $workflow_paths_id, $user_global_variables_file_path, $user_beans_folder_path, $css_and_js_optimizer_webroot_cache_folder_path, $deployments_temp_folder_path, $programs_temp_folder_path); if ($WorkFlowDBHandler->areTasksDBDriverValid($tasks_file_path, true, true, $invalid_task_label)) { $msg = "Layers created successfully."; $extra_attributes_files_to_check = $WorkFlowBeansConverter->renameExtraAttributesFiles(array("tasks_folders" => $tasks_folders), $extra_attributes_files_changed); $deprecated_folders = $WorkFlowBeansConverter->getDeprecatedLayerFolders(); $wordpress_installations_to_check = $WorkFlowBeansConverter->getWordPressInstallationsWithoutDBDrivers(); if ($extra_attributes_files_to_check) $msg .= "\\n\\nHowever there are some extra attributes files in the modules folder that correspondent to the changed DB Drivers and that could NOT be renamed with the new DB Driver name.\\nPlease rename the following files manually: '" . implode("', '", $extra_attributes_files_to_check) . "'.\\n"; if ($extra_attributes_files_changed) $msg .= "\\n\\nNote that some of extra attributes files were changed in Layers, but were not updated in the projects files. This means you need to update the correspondent project files manually.\\n"; if ($wordpress_installations_to_check) $msg .= "\\n\\nThe system detected some WordPress installations with different DB credentials than the credentials saved in the current DB Drivers.\\nPlease check the following WordPress installations: '" . implode("', '", $wordpress_installations_to_check) . "'.\\n"; if ($deprecated_folders) $msg .= "\\n\\nHowever there are some deprecated folders in your LAYERS directory, this is, probably these folders correspond to some deleted layers.\\nPlease talk with your sysadmin to remove them permanently.\\n\\nDEPRECATED FOLDERS: '" . implode("','", $deprecated_folders) . "'\\n"; if ($is_inside_of_iframe) echo '<script>
							alert("' . $msg . '\nCMS will now be reloaded...\n\nNote that if you created any new layer, you must now set the proper permissions in the \'User Management\' panel.");
							
							var url = window.top.location;
							url = ("" + url);
							url = url.indexOf("#") != -1 ? url.substr(0, url.indexOf("#")) : url;
							window.top.location = url;
							//window.parent.location = url;
						</script>'; else { header("location: ?step=4"); echo '<script>
							alert("' . $msg . '\nCMS will now be reloaded...");
							window.location = "?step=4";
						</script>'; } die(); } else $error_message = "DataBase settings are wrong for task: '$invalid_task_label'. " . $WorkFlowDBHandler->getError(); } else $error_message = "Error trying to create some folders. Please try again or talk with the system administrator."; } else $error_message = "There was an error saving the DB settings. Please try again..."; } else $error_message = "Error trying to read file path: '$tasks_file_path'."; } else { $WorkFlowTasksFileHandler = new WorkFlowTasksFileHandler($tasks_file_path); $WorkFlowTasksFileHandler->init(); $tasks = $WorkFlowTasksFileHandler->getTasks(); $tasks_folders = array(); if ($tasks["task"]) foreach ($tasks["task"] as $task) $tasks_folders[ $task["id"] ] = WorkFlowBeansConverter::getVariableNameFromRawLabel($task["label"]); } ?>
