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

include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "getbeanobject"), "Connectors" => array("restconnector", "soapconnector"), "Exception" => array("trycatchexception", "throwexception", "printexception"), "DB" => array("getdbdriver", "setquerydata", "getquerydata", "dbdaoaction", "callibatisquery", "callhibernateobject", "callhibernatemethod"), "Layers" => array("callbusinesslogic", "callpresentationlayerwebservice", "setpresentationview", "setpresentationtemplate"), "HTML" => array("inlinehtml", "createform"), "CMS" => array("setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam"), )); $WorkFlowUIHandler->addFoldersTasksToTasksGroups($code_workflow_editor_user_tasks_folders_path); $view_project_url = $project_url_prefix . "phpframework/presentation/view_project?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $save_url = $project_url_prefix . "phpframework/presentation/save_entity_advanced?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path"); $get_workflow_tasks_id = "presentation_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = "presentation_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); $set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}"; $get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}"; $create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId(); $get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}"; $create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}"; $set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_db_data_url = $project_url_prefix . "db/get_db_data?bean_name=#bean_name#&bean_file_name=#bean_file_name#&type=#type#"; $modules_path = $EVC->getCommonProjectName() . "/" . $EVC->getPresentationLayer()->settings["presentation_modules_path"]; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; $get_query_properties_url = $project_url_prefix . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#"; $get_business_logic_properties_url = $project_url_prefix . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#"; $get_broker_db_drivers_url = $project_url_prefix . "phpframework/db/get_broker_db_drivers?bean_name=$bean_name&bean_file_name=$bean_file_name&broker=#broker#&item_type=presentation"; $get_broker_db_data_url = $project_url_prefix . "phpframework/dataaccess/get_broker_db_data?bean_name=$bean_name&bean_file_name=$bean_file_name"; $create_page_presentation_uis_diagram_block_url = $project_url_prefix . "phpframework/presentation/create_page_presentation_uis_diagram_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $create_page_module_block_url = $project_url_prefix . "phpframework/presentation/create_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $add_block_url = $project_url_prefix . "phpframework/presentation/edit_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&module_id=#module_id#&edit_block_type=simple"; $edit_block_url = $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&edit_block_type=simple"; $get_module_info_url = $project_url_prefix . "phpframework/presentation/get_module_info?module_id=#module_id#"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $edit_view_file_url = $project_url_prefix . "phpframework/presentation/edit_view?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$view_file_path"; $add_view_file_url = $project_url_prefix . "phpframework/presentation/save_view?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$view_file_path"; $head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url, true); $head .= '
<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_page_and_template.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_page_and_template.js"></script>

<link rel="stylesheet" href="' . $project_url_prefix . 'css/presentation/edit_entity_advanced.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/presentation/edit_entity_advanced.js"></script>

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
var get_query_properties_url = \'' . $get_query_properties_url . '\';
var get_business_logic_properties_url = \'' . $get_business_logic_properties_url . '\';
var get_broker_db_drivers_url = \'' . $get_broker_db_drivers_url . '\';
var get_broker_db_data_url = \'' . $get_broker_db_data_url . '\';

var create_page_module_block_url = \'' . $create_page_module_block_url . '\';
var add_block_url = \'' . $add_block_url . '\';
var edit_block_url = \'' . $edit_block_url . '\';
var get_module_info_url = \'' . $get_module_info_url . '\';

var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml

ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
ProgrammingTaskUtil.on_programming_task_choose_object_property_callback = onProgrammingTaskChooseObjectProperty;
ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
ProgrammingTaskUtil.on_programming_task_choose_class_name_callback = onProgrammingTaskChooseClassName;

IncludeFileTaskPropertyObj.on_choose_file_callback = onIncludeFileTaskChooseFile;

FunctionUtilObj.set_tmp_workflow_file_url = set_tmp_workflow_file_url;
FunctionUtilObj.get_tmp_workflow_file_url = get_tmp_workflow_file_url;
FunctionUtilObj.create_code_from_workflow_file_url = create_code_from_workflow_file_url;
FunctionUtilObj.create_workflow_file_from_code_url = create_workflow_file_from_code_url;

GetUrlContentsTaskPropertyObj.on_choose_page_callback = onIncludePageUrlTaskChooseFile;
SoapConnectorTaskPropertyObj.on_choose_page_callback = onIncludePageUrlTaskChooseFile;

IncludeBlockTaskPropertyObj.on_choose_file_callback = onIncludeBlockTaskChooseFile;
SetVarTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
CreateFormTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
CreateFormTaskPropertyObj.on_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;
InlineHTMLTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
InlineHTMLTaskPropertyObj.on_choose_image_url_callback = onIncludeImageUrlTaskChooseFile
callPresentationLayerWebServiceTaskPropertyObj.on_choose_file_callback = onIncludeFileTaskChooseFile;
callPresentationLayerWebServiceTaskPropertyObj.on_choose_page_callback = onPresentationTaskChoosePage;
callPresentationLayerWebServiceTaskPropertyObj.brokers_options = ' . json_encode($presentation_brokers_obj) . ';

SetPresentationTemplateTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';
SetPresentationViewTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';
GetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
SetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
SetTemplateParamTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
SetBlockParamsTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
SetTemplateRegionBlockParamTaskPropertyObj.on_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
RenderTemplateRegionTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
AddTemplateRegionBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
IncludeBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';
IncludeBlockTaskPropertyObj.projects_options = ' . json_encode($available_projects) . ';
GetBeanObjectTaskPropertyObj.phpframeworks_options = ' . json_encode($phpframeworks_options) . ';
GetBeanObjectTaskPropertyObj.bean_names_options = ' . json_encode($bean_names_options) . ';

if (typeof LayerOptionsUtilObj != "undefined" && LayerOptionsUtilObj)
	LayerOptionsUtilObj.on_choose_db_driver_callback = onChooseDBDriver;

if (typeof CallBusinessLogicTaskPropertyObj != "undefined" && CallBusinessLogicTaskPropertyObj) {
	CallBusinessLogicTaskPropertyObj.on_choose_business_logic_callback = onBusinessLogicTaskChooseBusinessLogic;
	CallBusinessLogicTaskPropertyObj.brokers_options = ' . json_encode($business_logic_brokers_obj) . ';
}

if (typeof CallIbatisQueryTaskPropertyObj != "undefined" && CallIbatisQueryTaskPropertyObj) {
	CallIbatisQueryTaskPropertyObj.on_choose_query_callback = onChooseIbatisQuery;
	CallIbatisQueryTaskPropertyObj.brokers_options = ' . json_encode($ibatis_brokers_obj) . ';
}

if (typeof CallHibernateObjectTaskPropertyObj != "undefined" && CallHibernateObjectTaskPropertyObj) {
	CallHibernateObjectTaskPropertyObj.on_choose_hibernate_object_callback = onChooseHibernateObject;
	CallHibernateObjectTaskPropertyObj.brokers_options = ' . json_encode($hibernate_brokers_obj) . ';
}

if (typeof CallHibernateMethodTaskPropertyObj != "undefined" && CallHibernateMethodTaskPropertyObj) {
	CallHibernateMethodTaskPropertyObj.on_choose_hibernate_object_method_callback = onChooseHibernateObjectMethod;
	CallHibernateMethodTaskPropertyObj.brokers_options = ' . json_encode($hibernate_brokers_obj) . ';
}

if (typeof GetQueryDataTaskPropertyObj != "undefined" && GetQueryDataTaskPropertyObj) {
	GetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
}

if (typeof SetQueryDataTaskPropertyObj != "undefined" && SetQueryDataTaskPropertyObj) {
	SetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
}

if (typeof DBDAOActionTaskPropertyObj != "undefined" && DBDAOActionTaskPropertyObj){
	DBDAOActionTaskPropertyObj.on_choose_table_callback = onChooseDBTableAndAttributes;
	DBDAOActionTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
}

if (typeof GetDBDriverTaskPropertyObj != "undefined" && GetDBDriverTaskPropertyObj) {
	GetDBDriverTaskPropertyObj.brokers_options = ' . json_encode($db_brokers_obj) . ';
	GetDBDriverTaskPropertyObj.db_drivers_options = ' . json_encode($db_drivers_options) . ';
}
'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '</script>'; $main_content = '<div class="title">Edit Entity "' . $entity_view_code . '" <a class="icon view" href="' . $view_project_url . '" target="project" title="View project page">View project page</a></div>'; if ($obj_data) { $query_string = str_replace(array("&edit_entity_type=advanced", "&edit_entity_type=simple"), "", $_SERVER["QUERY_STRING"]); $main_content .= '<div class="sub_title">(<a href="?' . $query_string . '&edit_entity_type=simple&show_templates_only=1">Switch to Template Editor</a>)</div>'; $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers); $common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName()); $ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/view_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $view_file_tab = ''; if ($project_with_auto_view) $view_file_tab = '<li id="view_tab"' . (!$view_file_exists ? ' class="hidden"' : '') . '><a href="#view" onClick="onClickViewTab(this, \'' . $edit_view_file_url . '\');return false;">View</a></li>
		' . (!$view_file_exists ? '<li id="add_view_tab"><a href="#view" onClick="onClickNewViewTab(this, \'' . $add_view_file_url . '\');return false;"><i class="icon add"></i> View</a></li>' : ''); $main_content .= '
	<div class="entity_obj">
		<ul>
			<li id="code_editor_tab"><a href="#code" onClick="onClickCodeEditorTab(this);return false;">Code</a></li>
			<li id="tasks_flow_tab"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;">Workflow</a></li>
			' . $view_file_tab . '
		</ul>
		
		<div id="code" class="code_layout_ui_editor">
			' . WorkFlowPresentationHandler::getCodeEditorHtml($obj_data["code"], array("save_func" => "saveEntity"), $ui_menu_widgets_html, $user_global_variables_file_path, $user_beans_folder_path, $PEVC, $bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $get_db_data_url, $create_page_presentation_uis_diagram_block_url, "chooseCodeLayoutUIEditorModuleBlockFromFileManagerTreeRightContainer", true) . '
		</div>
	
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => "saveEntity")) . '</div>
		
		<div class="ui-menu-widgets-backup hidden">
			' . $ui_menu_widgets_html . '
		</div>
		<script>
			var mwb = $(".entity_obj > .ui-menu-widgets-backup");
			$(".entity_obj > #ui > .taskflowchart > .tasks_properties > .task_properties > .create_form_task_html > .ptl_settings > .layout_ui_editor > .menu-widgets").append( mwb.contents().clone() );
			$(".entity_obj > #ui > .taskflowchart > .tasks_properties > .task_properties > .inlinehtml_task_html > .layout_ui_editor > .menu-widgets").append( mwb.contents() );
			mwb.remove();
		</script>'; $main_content .= '
		<div id="view"></div>
	</div>
	<div class="big_white_panel"></div>
	<div class="hide_show_header minimize" onClick="toggleEntityHeader(this)" title="Minimize/Maximize"></div>'; } else $main_content .= '<div class="error">Error: The system couldn\'t detect the selected file. Please refresh and try again...</div>'; ?>
