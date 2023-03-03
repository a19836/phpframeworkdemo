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

include $EVC->getUtilPath("WorkFlowUIHandler"); include $EVC->getUtilPath("WorkFlowPresentationHandler"); include $EVC->getUtilPath("BreadCrumbsUIHandler"); $filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout); $ft = str_replace("edit_file_", "", $file_type); $is_obj_valid = $obj_data || (!$method_id && $ft == "class_method") || (!$function_id && $ft == "function"); if ($ft == "class_method") $title = ($method_id ? "Edit Class Method" : "Add Method") . ' <span class="class_name">' . $class_id . '-&gt;</span>'; else $title = $function_id ? "Edit Function" : "Add Function"; if ($is_obj_valid) { $WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url); if ($item_type == "presentation") $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "validator", "geturlcontents", "getbeanobject"), "Connectors" => array("restconnector", "soapconnector"), "Exception" => array("trycatchexception", "throwexception", "printexception"), "DB" => array("getdbdriver", "setquerydata", "getquerydata", "dbdaoaction", "callibatisquery", "callhibernateobject", "callhibernatemethod"), "Layers" => array("callbusinesslogic", "callpresentationlayerwebservice", "setpresentationview", "addpresentationview", "setpresentationtemplate"), "HTML" => array("inlinehtml", "createform"), "CMS" => array("setblockparams", "settemplateregionblockparam", "includeblock", "addtemplateregionblock", "rendertemplateregion", "settemplateparam", "gettemplateparam"), )); else if ($item_type == "businesslogic") $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "validator", "geturlcontents", "getbeanobject"), "Connectors" => array("restconnector", "soapconnector"), "Exception" => array("trycatchexception", "throwexception", "printexception"), "DB" => array("getdbdriver", "setquerydata", "getquerydata", "dbdaoaction", "callibatisquery", "callhibernateobject", "callhibernatemethod"), "Layers" => array("callbusinesslogic"), )); else $WorkFlowUIHandler->setTasksGroupsByTag(array( "Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "validator"), "Exception" => array("trycatchexception", "throwexception", "printexception"), )); $WorkFlowUIHandler->addFoldersTasksToTasksGroups($code_workflow_editor_user_tasks_folders_path); $js_save_func_name = $js_save_func_name ? $js_save_func_name : "saveFileClassMethod"; $class_id_for_js = addcslashes(preg_replace("/\\+/", "\\", $class_id), '\\'); if ($ft == "class_method") $save_url = $project_url_prefix . 'phpframework/admin/save_file_class_method?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&class=' . $class_id_for_js . '&method=#method_id#'; else $save_url = $project_url_prefix . 'phpframework/admin/save_file_function?bean_name=' . $bean_name . '&bean_file_name=' . $bean_file_name . '&path=' . $path . '&item_type=' . $item_type . '&function=#method_id#'; $path_extra = hash('crc32b', "$bean_file_name/$bean_name/$item_type/$path/$class_id/" . ($ft == "class_method" ? $method_id : $function_id) ); $get_workflow_tasks_id = $get_workflow_tasks_id ? $get_workflow_tasks_id : "php_file_workflow&path_extra=_$path_extra"; $get_tmp_workflow_tasks_id = $get_tmp_workflow_tasks_id ? $get_tmp_workflow_tasks_id : "php_file_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000); $set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}"; $get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}"; $create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId(); $get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}"; $create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}"; $set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}"; $choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#"; $choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#"; $choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#"; $choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#"; $get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#"; if ($item_type == "presentation" || $item_type == "businesslogic") { $get_query_properties_url = $project_url_prefix . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#"; $get_business_logic_properties_url = $project_url_prefix . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#"; $get_broker_db_drivers_url = $project_url_prefix . "phpframework/db/get_broker_db_drivers?bean_name=$bean_name&bean_file_name=$bean_file_name&broker=#broker#&item_type=$item_type"; $get_broker_db_data_url = $project_url_prefix . "phpframework/dataaccess/get_broker_db_data?bean_name=$bean_name&bean_file_name=$bean_file_name"; $edit_task_source_url = $project_url_prefix . "phpframework/admin/edit_task_source?bean_name=$bean_name&bean_file_name=$bean_file_name$filter_by_layout_url_query&path=$path&" . ($ft == "class_method" ? "class=$class_id&method=$method_id" : "function=$function_id"); if ($item_type == "presentation") { $create_page_module_block_url = $project_url_prefix . "phpframework/presentation/create_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; $add_block_url = $project_url_prefix . "phpframework/presentation/edit_page_module_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path&module_id=#module_id#&edit_block_type=simple"; $edit_block_url = $project_url_prefix . "phpframework/presentation/edit_block?bean_name=$bean_name&bean_file_name=$bean_file_name&path=#path#&edit_block_type=simple"; $get_module_info_url = $project_url_prefix . "phpframework/presentation/get_module_info?module_id=#module_id#"; $templates_regions_html_url = $project_url_prefix . "phpframework/presentation/templates_regions_html?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path"; } } $head .= WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url); $head .= LayoutTypeProjectUIHandler::getHeader(); } $head .= '
<!-- Add Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />

<!-- Add local CSS and JS -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/edit_file_class_method.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/edit_file_class_method.js"></script>

<script>
var is_obj_valid = ' . ($is_obj_valid ? "true" : "false") . ';
</script>'; if ($is_obj_valid) { $head .= '
	<script>
	' . WorkFlowBrokersSelectedDBVarsHandler::printSelectedDBVarsJavascriptCode($project_url_prefix, $bean_name, $bean_file_name, $selected_db_vars) . '
	var layer_type = "' . ($item_type == "presentation" ? "pres" : ($item_type == "businesslogic" ? "bl" : $item_type)) . '";
	var selected_project_id = "' . $selected_project_id . '";
	var original_method_id = \'' . ($ft == "class_method" ? $method_id : $function_id) . '\';
	var file_modified_time = ' . $file_modified_time . '; //for version control
	var js_save_func_name = "' . $js_save_func_name . '";
	var class_id = \'' . $class_id . '\';

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
	var edit_task_source_url = \'' . $edit_task_source_url . '\';

	var create_page_module_block_url = \'' . $create_page_module_block_url . '\';
	var add_block_url = \'' . $add_block_url . '\';
	var edit_block_url = \'' . $edit_block_url . '\';
	var get_module_info_url = \'' . $get_module_info_url . '\';
	var templates_regions_html_url = \'' . $templates_regions_html_url . '\'; //used in widget: src/view/presentation/common_editor_widget/template_region/import_region_html.xml which is used in the Layout_ui_editor from the taskflowchart->inlinehtml and createform tasks.

	var new_argument_html = \'' . str_replace("'", "\\'", str_replace("\n", "", WorkFlowPHPFileHandler::getArgumentHTML())) .'\';
	var new_annotation_html = \'' . str_replace("'", "\\'", str_replace("\n", "", WorkFlowPHPFileHandler::getAnnotationHTML())) .'\';
	
	var brokers_db_drivers = ' . json_encode($brokers_db_drivers) . ';
	
	ProgrammingTaskUtil.on_programming_task_edit_source_callback = onProgrammingTaskEditSource;
	ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
	ProgrammingTaskUtil.on_programming_task_choose_file_path_callback = onIncludeFileTaskChooseFile;
	ProgrammingTaskUtil.on_programming_task_choose_object_property_callback = onProgrammingTaskChooseObjectProperty;
	ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
	ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
	ProgrammingTaskUtil.on_programming_task_choose_class_name_callback = onProgrammingTaskChooseClassName;

	FunctionUtilObj.on_function_task_edit_method_code_callback = onFunctionTaskEditMethodCode;
	FunctionUtilObj.set_tmp_workflow_file_url = set_tmp_workflow_file_url;
	FunctionUtilObj.get_tmp_workflow_file_url = get_tmp_workflow_file_url;
	FunctionUtilObj.create_code_from_workflow_file_url = create_code_from_workflow_file_url;
	FunctionUtilObj.create_workflow_file_from_code_url = create_workflow_file_from_code_url;

	if (typeof CreateFormTaskPropertyObj != "undefined" && CreateFormTaskPropertyObj) {
		CreateFormTaskPropertyObj.editor_ready_func = initLayoutUIEditorWidgetResourceOptions;
		CreateFormTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'.ui-menu-widgets-backup\';
	}
	
	if (typeof InlineHTMLTaskPropertyObj != "undefined" && InlineHTMLTaskPropertyObj) {
		InlineHTMLTaskPropertyObj.editor_ready_func = initLayoutUIEditorWidgetResourceOptions;
		InlineHTMLTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'.ui-menu-widgets-backup\';
	}
	'; if ($item_type == "presentation") $head .= '
	ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
	ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;

	if (typeof IncludeBlockTaskPropertyObj != "undefined" && IncludeBlockTaskPropertyObj)
		IncludeBlockTaskPropertyObj.on_choose_file_callback = onIncludeBlockTaskChooseFile;

	if (typeof callPresentationLayerWebServiceTaskPropertyObj != "undefined" && callPresentationLayerWebServiceTaskPropertyObj) {
		callPresentationLayerWebServiceTaskPropertyObj.on_choose_page_callback = onPresentationTaskChoosePage;
		callPresentationLayerWebServiceTaskPropertyObj.brokers_options = ' . json_encode($presentation_brokers_obj) . ';
	}

	if (typeof SetPresentationTemplateTaskPropertyObj != "undefined" && SetPresentationTemplateTaskPropertyObj)
		SetPresentationTemplateTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';

	if (typeof SetPresentationViewTaskPropertyObj != "undefined" && SetPresentationViewTaskPropertyObj)
		SetPresentationViewTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';

	if (typeof AddPresentationViewTaskPropertyObj != "undefined" && AddPresentationViewTaskPropertyObj)
		AddPresentationViewTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';

	if (typeof GetTemplateParamTaskPropertyObj != "undefined" && GetTemplateParamTaskPropertyObj)
		GetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';

	if (typeof SetTemplateParamTaskPropertyObj != "undefined" && SetTemplateParamTaskPropertyObj) {
		SetTemplateParamTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';
	}

	if (typeof RenderTemplateRegionTaskPropertyObj != "undefined" && RenderTemplateRegionTaskPropertyObj)
		RenderTemplateRegionTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';

	if (typeof AddTemplateRegionBlockTaskPropertyObj != "undefined" && AddTemplateRegionBlockTaskPropertyObj)
		AddTemplateRegionBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC->getCMSLayer()->getCMSTemplateLayer()')) . ';

	if (typeof IncludeBlockTaskPropertyObj != "undefined" && IncludeBlockTaskPropertyObj)
		IncludeBlockTaskPropertyObj.brokers_options = ' . json_encode(array("default" => '$EVC')) . ';

	if (typeof IncludeBlockTaskPropertyObj != "undefined" && IncludeBlockTaskPropertyObj)
		IncludeBlockTaskPropertyObj.projects_options = ' . json_encode($available_projects) . ';
	'; if ($item_type == "presentation" || $item_type == "businesslogic") $head .= '
	if (typeof GetBeanObjectTaskPropertyObj != "undefined" && GetBeanObjectTaskPropertyObj) {
		GetBeanObjectTaskPropertyObj.phpframeworks_options = ' . json_encode($phpframeworks_options) . ';
		GetBeanObjectTaskPropertyObj.bean_names_options = ' . json_encode($bean_names_options) . ';
	}

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

	if (typeof GetQueryDataTaskPropertyObj != "undefined" && GetQueryDataTaskPropertyObj)
		GetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';

	if (typeof SetQueryDataTaskPropertyObj != "undefined" && SetQueryDataTaskPropertyObj)
		SetQueryDataTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';

	if (typeof DBDAOActionTaskPropertyObj != "undefined" && DBDAOActionTaskPropertyObj){
		DBDAOActionTaskPropertyObj.on_choose_table_callback = onChooseDBTableAndAttributes;
		DBDAOActionTaskPropertyObj.brokers_options = ' . json_encode(array_merge($db_brokers_obj, $data_access_brokers_obj)) . ';
	}

	if (typeof GetDBDriverTaskPropertyObj != "undefined" && GetDBDriverTaskPropertyObj) {
		GetDBDriverTaskPropertyObj.brokers_options = ' . json_encode($db_brokers_obj) . ';
		GetDBDriverTaskPropertyObj.db_drivers_options = ' . json_encode($db_drivers_options) . ';
	}
	'; $head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url); $head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url); $head .= '</script>'; $main_content = '
		<div class="top_bar' . ($popup ? " in_popup" : "") . '">
			<header>
				<div class="title" title="' . $path . '">
					' . $title . ' <input class="name" type="text" value="' . ($obj_data ? $obj_data["name"] : "") . '" placeHolder="Name" title="Function/Method Name" /> in ' . BreadCrumbsUIHandler::getFilePathBreadCrumbsHtml($is_class_equal_to_file_name ? dirname($file_path) : $file_path, $obj, $is_class_equal_to_file_name) . '
				</div>
				<ul>
					<li class="save" data-title="Save"><a onClick="' . $js_save_func_name . '()"><i class="icon save"></i> Save</a></li>
				</ul>
			</header>
		</div>'; $main_content .= WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers); $common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName()); $ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url); $ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url); $main_content .= '
	<div class="file_class_method_obj with_top_bar_tab">
		<ul class="tabs tabs_transparent tabs_right tabs_icons">
			<li id="code_editor_tab" title="Code Editor"><a href="#code" onClick="onClickCodeEditorTab(this);return false;"><i class="icon code_editor_tab"></i> Code Editor</a></li>
			<li id="tasks_flow_tab" title="Diagram Editor"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;"><i class="icon tasks_flow_tab"></i> Diagram Editor</a></li>
		</ul>
		
		<div id="settings" class="' . $ft . '_settings collapsed">
			<div class="settings_header">
				Main Settings
				<div class="icon maximize" onClick="toggleSettingsPanel(this)">Toggle</div>
			</div>'; if ($file_type == "edit_file_class_method") { $main_content .= '
			<div class="type">
				<label>Type:</label>
				<select>'; $types = array("public", "private", "protected"); $t = count($types); for ($i = 0; $i < $t; $i++) $main_content .= '<option' . ($types[$i] == $obj_data["type"] ? " selected" : "") . '>' . $types[$i] . '</option>'; $main_content .= '
				</select>
			</div>
			<div class="abstract">
				<label>Is Abstract:</label>
				<input type="checkbox" value="1" ' . ($obj_data["abstract"] ? "checked" : "") . ' />
			</div>
			<div class="static">
				<label>Is Static:</label>
				<input type="checkbox" value="1" ' . ($obj_data["static"] || (!$method_id && $_GET["static"]) ? "checked" : "") . ' />
			</div>'; } $main_content .= '
			<div class="arguments">
				<label>Arguments:</label>
				<span class="icon add" onClick="addNewArgument(this)" title="Add Property">Add</span>
				<table>
					<thead>
						<tr>
							<th class="name table_header">Name</th>
							<th class="value table_header">Value</th>
							<th class="var_type table_header">Var Type</th>
							<th class="icon_cell table_header"><span class="icon add" onClick="addNewArgument(this)" title="Add Property">Add</span></th>
						</tr>
					</thead>
					<tbody class="fields">'; $arguments = $obj_data["arguments"]; if (is_array($arguments)) foreach ($arguments as $arg_name => $arg_value) $main_content .= WorkFlowPHPFileHandler::getArgumentHTML($arg_name, $arg_value); $main_content .= '
					</tbody>
				</table>
			</div>
		'; if ($include_annotations) { $main_content .= '
			<div class="annotations">
				<label>Annotations:</label>
				<span class="icon add" onClick="addNewAnnotation(this)" title="Add Annotation">Add</span>
				<table>
					<thead>
						<tr>
							<th class="annotation_type table_header">In/Out</th>
							<th class="name table_header">Name</th>
							<th class="type table_header">Type</th>
							<th class="not_null table_header">Not Null</th>
							<th class="default table_header">Default Value</th>
							<th class="description table_header">Description</th>
							<th class="others table_header">Others</th>
							<th class="icon_cell table_header"><span class="icon add" onClick="addNewAnnotation(this)" title="Add Annotation">Add</span></th>
						</tr>
					</thead>
					<tbody class="fields">'; if (is_array($params)) foreach ($params as $param) { $attrs = $param->getArgs(); if ($obj_data["is_business_logic_service"] && is_array($attrs) && substr($attrs["name"], 0, 5) == "data[") { $attrs["name"] = substr($attrs["name"], 5); $attrs["name"] = substr($attrs["name"], -1) == "]" ? substr($attrs["name"], 0, -1) : $attrs["name"]; $attrs["name"] = str_replace(array('"', "'"), "", $attrs["name"]); preg_match_all("/([^\[\]]+)/u", $attrs["name"], $matches, PREG_PATTERN_ORDER); $first = array_shift($matches[1]); $attrs["name"] = $first . ($matches[1] ? "[" . implode('][', $matches[1]) . "]" : ""); } $main_content .= WorkFlowPHPFileHandler::getAnnotationHTML($attrs, "param"); } if (is_array($returns)) foreach ($returns as $return) $main_content .= WorkFlowPHPFileHandler::getAnnotationHTML($return->getArgs(), "return"); $main_content .= '
					</tbody>
				</table>
			</div>'; } $main_content .= '
			<div class="comments">
				<label>Comments:</label>
				<textarea>' . htmlspecialchars($comments, ENT_NOQUOTES) . '</textarea>
			</div>
		</div>
		
		<div id="code">
			<div class="code_menu top_bar_menu" onClick="openSubmenu(this)">
				' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => $js_save_func_name)) . '
			</div>
			<textarea>' . "<?php\n" . htmlspecialchars($obj_data["code"], ENT_NOQUOTES) . "\n?>" . '</textarea>
		</div>
		
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => $js_save_func_name)) . '</div>
		
		<div class="ui-menu-widgets-backup hidden">
			' . $ui_menu_widgets_html . '
		</div>
	</div>
	<div class="big_white_panel"></div>'; } else { $main_content = '
		<div class="top_bar' . ($popup ? " in_popup" : "") . '">
			<header>
				<div class="title" title="' . $path . '">' . $title . ($ft == "class_method" ? $method_id : $function_id) . '</div>
			</header>
		</div>'; $main_content .= '<div class="error">Error: The system couldn\'t detect the selected object. Please refresh and try again...</div>'; } ?>
