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

include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit"), "Exception" => array("trycatchexception", "throwexception", "printexception"), )); $save_url = $project_url_prefix . 'phpframework/presentation/save_config?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path; $path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path"); $get_workflow_tasks_id = "presentation_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = "presentation_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); $set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}"; $get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}"; $create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId(); $get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}"; $create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}"; $set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url); $head .= '
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_config.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_config.js"></script>

<script>
var layer_type = "pres";
var selected_project_id = "' . $selected_project_id . '";
var file_modified_time = ' . $file_modified_time . '; //for version control

var get_workflow_file_url = \'' . $get_workflow_file_url . '\';
var save_object_url = \'' . $save_url . '\';
var create_workflow_file_from_code_url = \'' . $create_workflow_file_from_code_url . '\';
var get_tmp_workflow_file_url = \'' . $get_tmp_workflow_file_url . '\';
var create_code_from_workflow_file_url = \'' . $create_code_from_workflow_file_url . '\';
var set_tmp_workflow_file_url = \'' . $set_tmp_workflow_file_url . '\';

ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
ProgrammingTaskUtil.on_programming_task_choose_object_property_callback = onProgrammingTaskChooseObjectProperty;
ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
ProgrammingTaskUtil.on_programming_task_choose_class_name_callback = onProgrammingTaskChooseClassName;

IncludeFileTaskPropertyObj.on_choose_file_callback = onIncludeFileTaskChooseFile;
SetVarTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;

FunctionUtilObj.set_tmp_workflow_file_url = set_tmp_workflow_file_url;
FunctionUtilObj.get_tmp_workflow_file_url = get_tmp_workflow_file_url;
FunctionUtilObj.create_code_from_workflow_file_url = create_code_from_workflow_file_url;
FunctionUtilObj.create_workflow_file_from_code_url = create_workflow_file_from_code_url;
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $main_content = '<div class="title">Edit Config' . ($config_file_name ? ' "' . $config_file_name . '"' : '') . ' for project "' . $selected_project_id . '"</div>'; if ($obj_data) { $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers); $main_content .= '
	<div class="config_obj">
		<ul>
			<li id="code_editor_tab"><a href="#code" onClick="onClickCodeEditorTab(this);return false;">Code</a></li>
			<li id="tasks_flow_tab"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;">Workflow</a></li>
		</ul>
		<div id="code">
			<div class="code_menu">
				' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => "saveConfig")) . '
			</div>
			<textarea>' . htmlspecialchars($obj_data["code"], ENT_NOQUOTES) . '</textarea>
		</div>
		
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => "saveConfig")) . '</div>
	</div>
	<div class="big_white_panel"></div>
	<div class="hide_show_header minimize" onClick="toggleHeader(this)" title="Minimize/Maximize"></div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; ?>
