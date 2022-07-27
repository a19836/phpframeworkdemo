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

include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "getbeanobject"), "Connectors" => array("restconnector", "soapconnector"), "Exception" => array("trycatchexception", "throwexception", "printexception"), "Layers" => array("callpresentationlayerwebservice"), "HTML" => array("inlinehtml", "createform"), "CMS" => array("setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam"), )); $WorkFlowUIHandler->addFoldersTasksToTasksGroups($code_workflow_editor_user_tasks_folders_path); $save_url = $project_url_prefix . 'phpframework/presentation/save_template?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path; $path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path"); $get_workflow_tasks_id = "presentation_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = "presentation_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); $set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}"; $get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}"; $create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId(); $get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}"; $create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}"; $set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url, true); $head .= LayoutTypeProjectUIHandler::getHeader(); $head .= '
<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_template_advanced.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_template_advanced.js"></script>

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

var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml and in the Layout_ui_editor from the taskflowchart->inlinehtml and createform tasks.

ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
ProgrammingTaskUtil.on_programming_task_choose_object_property_callback = onProgrammingTaskChooseObjectProperty;
ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
ProgrammingTaskUtil.on_programming_task_choose_class_name_callback = onProgrammingTaskChooseClassName;
ProgrammingTaskUtil.on_programming_task_choose_file_path_callback = onIncludeFileTaskChooseFile;
ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;

FunctionUtilObj.set_tmp_workflow_file_url = set_tmp_workflow_file_url;
FunctionUtilObj.get_tmp_workflow_file_url = get_tmp_workflow_file_url;
FunctionUtilObj.create_code_from_workflow_file_url = create_code_from_workflow_file_url;
FunctionUtilObj.create_workflow_file_from_code_url = create_workflow_file_from_code_url;

callPresentationLayerWebServiceTaskPropertyObj.on_choose_page_callback = onPresentationTaskChoosePage;
callPresentationLayerWebServiceTaskPropertyObj.brokers_options = ' . json_encode($presentation_brokers_obj) . ';

GetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
SetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
RenderTemplateRegionTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
AddTemplateRegionBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
IncludeBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';
IncludeBlockTaskPropertyObj.projects_options = ' . json_encode($available_projects) . ';
GetBeanObjectTaskPropertyObj.phpframeworks_options = ' . json_encode($phpframeworks_options) . ';
GetBeanObjectTaskPropertyObj.bean_names_options = ' . json_encode($bean_names_options) . ';

CreateFormTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'ui-menu-widgets-backup\';
InlineHTMLTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'ui-menu-widgets-backup\';
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= '</script>'; $query_string = str_replace(array("&edit_template_type=advanced", "&edit_template_type=simple"), "", $_SERVER["QUERY_STRING"]); $main_content = '
	<div class="top_bar">
		<header>
			<div class="title">Edit Template (Code Workspace): ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($file_path, $P) . '</div>
			<ul>
				<li class="show_simple_ui" data-title="Switch to Visual Workspace"><a href="?' . $query_string . '&edit_template_type=simple"><i class="icon show_simple_ui"></i> Switch to Visual Workspace</a></li>
				<li class="save" data-title="Save Template"><a onClick="saveTemplate()"><i class="icon save"></i> Save</a></li>
			</ul>
		</header>
	</div>'; if ($obj_data) { $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers); $common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName()); $ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/view_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/template_editor_widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $main_content .= '
	<div class="template_obj with_top_bar_tab">
		<ul class="tabs tabs_transparent tabs_right tabs_icons">
			<li id="code_editor_tab" title="Code Editor"><a href="#code" onClick="onClickCodeEditorTab(this);return false;"><i class="icon code_editor_tab"></i> Code Editor</a></li>
			<li id="tasks_flow_tab" title="Diagram Editor"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;"><i class="icon tasks_flow_tab"></i> Diagram Editor</a></li>
		</ul>
		
		<div id="code">
			<div class="code_menu top_bar_menu" onClick="openSubmenu(this)">
				' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => "saveTemplate", "show_pretty_print" => false)) . '
			</div>
			<textarea>' . htmlspecialchars($obj_data["code"], ENT_NOQUOTES) . '</textarea>
		</div>
		
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => "saveTemplate")) . '</div>
		
		<div class="ui-menu-widgets-backup hidden">
			' . $ui_menu_widgets_html . '
		</div>
	</div>
	<div class="big_white_panel"></div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected file. Please refresh and try again...</div>'; ?>
