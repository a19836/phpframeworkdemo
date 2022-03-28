<div class="module_workflow_settings">
<?php
include $EVC->getConfigPath("config");
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("WorkFlowUIHandler");
include_once $EVC->getUtilPath("WorkFlowPresentationHandler");

$common_project_name = $EVC->getCommonProjectName();
$purlp = $project_url_prefix;
$pcurlp = $project_common_url_prefix;

include $EVC->getModulePath("common/start_project_module_file", $common_project_name);
$project_url_prefix = $purlp;
$project_common_url_prefix = $pcurlp;

if ($PEVC) {
	$selected_project_id = $P->getSelectedPresentationId();
	
	//PREPARING BROKERS
	$layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker');
	
	$presentation_brokers = array();
	$presentation_brokers[] = array(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P) . " (Self)", $bean_file_name, $bean_name);
	$presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()');

	$business_logic_brokers = $layer_brokers_settings["business_logic_brokers"];
	$business_logic_brokers_obj = $layer_brokers_settings["business_logic_brokers_obj"];

	$data_access_brokers = $layer_brokers_settings["data_access_brokers"];
	$data_access_brokers_obj = $layer_brokers_settings["data_access_brokers_obj"];

	$ibatis_brokers = $layer_brokers_settings["ibatis_brokers"];
	$ibatis_brokers_obj = $layer_brokers_settings["ibatis_brokers_obj"];

	$hibernate_brokers = $layer_brokers_settings["hibernate_brokers"];
	$hibernate_brokers_obj = $layer_brokers_settings["hibernate_brokers_obj"];
	
	$db_brokers = $layer_brokers_settings["db_brokers"];
	$db_brokers_obj = $layer_brokers_settings["db_brokers_obj"];
	
	//PREPARING getbeanobject
	$phpframeworks_options = array("default" => '$EVC->getPresentationLayer()->getPHPFrameWork()');
	$bean_names_options = array_keys($P->getPHPFrameWork()->getObjects());
	
	//PREPARING brokers db drivers
	$brokers_db_drivers = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $brokers, true);
	$db_drivers_options = array_keys($brokers_db_drivers);
	
	//PREPARING WORKFLOW TASKS
	$allowed_tasks_tag = array(
		"definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "restconnector", "soapconnector", "getbeanobject",
		"trycatchexception", "throwexception", "printexception",
		"callpresentationlayerwebservice",
		"inlinehtml", "createform",
	);

	if ($data_access_brokers_obj) {
		$allowed_tasks_tag[] = "setquerydata";
		$allowed_tasks_tag[] = "getquerydata";
		$allowed_tasks_tag[] = "dbdaoaction";

		if ($ibatis_brokers_obj) 
			$allowed_tasks_tag[] = "callibatisquery";
		
		if ($hibernate_brokers_obj) {
			$allowed_tasks_tag[] = "callhibernateobject";
			$allowed_tasks_tag[] = "callhibernatemethod";
		}
	}
	else if ($db_brokers_obj) {
		$allowed_tasks_tag[] = "setquerydata";
		$allowed_tasks_tag[] = "getquerydata";
		$allowed_tasks_tag[] = "dbdaoaction";
	}
	
	if ($db_brokers_obj)
		$allowed_tasks_tag[] = "getdbdriver";
	
	if ($business_logic_brokers_obj) 
		$allowed_tasks_tag[] = "callbusinesslogic";
	
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag);
	$WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path);
	$WorkFlowTaskHandler->addAllowedTaskTagsFromFolders($code_workflow_editor_user_tasks_folders_path);
	
	//PREPARING HEAD
	$WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowUIHandler->setTasksGroupsByTag(array(
		"Logic" => array("definevar", "setvar", "setarray", "setdate", "ns", "createfunction", "createclass", "setobjectproperty", "createclassobject", "callobjectmethod", "callfunction", "addheader", "if", "switch", "loop", "foreach", "includefile", "echo", "code", "break", "return", "exit", "geturlcontents", "getbeanobject"),
		"Connectors" => array("restconnector", "soapconnector"),
		"Exception" => array("trycatchexception", "throwexception", "printexception"),
		"DB" => array("getdbdriver", "setquerydata", "getquerydata", "dbdaoaction", "callibatisquery", "callhibernateobject", "callhibernatemethod"),
		"Layers" => array("callbusinesslogic", "callpresentationlayerwebservice"),
		"HTML" => array("inlinehtml", "createform"),
	));
	$WorkFlowUIHandler->addFoldersTasksToTasksGroups($code_workflow_editor_user_tasks_folders_path);
	
	$choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#";
	$choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#";
	$choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#";
	$choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#";

	$get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#";
	$get_query_properties_url = $project_url_prefix . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#";
	$get_business_logic_properties_url = $project_url_prefix . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#";
	$get_broker_db_drivers_url = $project_url_prefix . "phpframework/db/get_broker_db_drivers?bean_name=$bean_name&bean_file_name=$bean_file_name&broker=#broker#&item_type=presentation";
	$get_broker_db_data_url = $project_url_prefix . "phpframework/dataaccess/get_broker_db_data?bean_name=$bean_name&bean_file_name=$bean_file_name";
		
	$path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path");
	$get_workflow_tasks_id = "presentation_block_workflow&path_extra=_$path_extra";
	$get_tmp_workflow_tasks_id = "presentation_block_workflow_tmp&path_extra=_${path_extra}_" . rand(0, 1000);
	
	$set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}";
	$get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}";
	$create_workflow_file_from_code_url = $project_url_prefix . "workflow/create_workflow_file_from_code?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId();
	$get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}";
	$create_code_from_workflow_file_url = $project_url_prefix . "workflow/create_code_from_workflow_file?path=${get_tmp_workflow_tasks_id}";
	$set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}";

	
	$head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url, true);
	
	//must have the layout after this, bc the getHeader method loads the common/vendor/jquerytaskflowchart/css/style.css which will overwrite the previous loaded layout.css styles. So we must re-add it here again.
	$head .= '
<!-- Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
';

	$head .= '
	<link rel="stylesheet" href="' . $module["webroot_url"] . 'workflow.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $module["webroot_url"] . 'workflow.js"></script>
	
	<script>
	var layer_type = "pres";
	var selected_project_id = "' . $selected_project_id . '";

	var show_edit_block_advanced_url = \'?' . http_build_query($_GET) . '&edit_block_type=advanced\';
	var create_workflow_settings_code_url = \'' . $project_url_prefix . 'module/workflow/create_workflow_settings_code\';
	var get_workflow_file_url = \'' . $get_workflow_file_url . '\';
	var create_workflow_file_from_code_url = \'' . $create_workflow_file_from_code_url . '\';
	var get_tmp_workflow_file_url = \'' . $get_tmp_workflow_file_url . '\';
	var create_code_from_workflow_file_url = \'' . $create_code_from_workflow_file_url . '\';
	var set_tmp_workflow_file_url = \'' . $set_tmp_workflow_file_url . '\';
	
	var get_query_properties_url = \'' . $get_query_properties_url . '\';
	var get_business_logic_properties_url = \'' . $get_business_logic_properties_url . '\';
	var get_broker_db_drivers_url = \'' . $get_broker_db_drivers_url . '\';
var get_broker_db_data_url = \'' . $get_broker_db_data_url . '\';

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

	LayerOptionsUtilObj.on_choose_db_driver_callback = onChooseDBDriver;
	callPresentationLayerWebServiceTaskPropertyObj.on_choose_page_callback = onPresentationTaskChoosePage;
	callPresentationLayerWebServiceTaskPropertyObj.brokers_options = ' . json_encode($presentation_brokers_obj) . ';
	
	GetBeanObjectTaskPropertyObj.phpframeworks_options = ' . json_encode($phpframeworks_options) . ';
	GetBeanObjectTaskPropertyObj.bean_names_options = ' . json_encode($bean_names_options) . ';
	
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
	';

	$head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
	$head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url);
	$head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
	$head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url);
	$head .= '</script>';
	
	//PREPARING HTML
	echo $head;
	
	echo WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers);
	
	$common_webroot_path = $EVC->getWebrootPath($EVC->getCommonProjectName());
	$ui_menu_widgets_html = WorkFlowPresentationHandler::getUIEditorWidgetsHtml($common_webroot_path, $project_common_url_prefix, $webroot_cache_folder_path, $webroot_cache_folder_url);
	$ui_menu_widgets_html .= WorkFlowPresentationHandler::getExtraUIEditorWidgetsHtml($common_webroot_path, $EVC->getViewsPath() . "presentation/common_editor_widget/", $project_url_prefix . "widget/", $webroot_cache_folder_path, $webroot_cache_folder_url);
	$ui_menu_widgets_html .= WorkFlowPresentationHandler::getUserUIEditorWidgetsHtml($common_webroot_path, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	
	echo '
	<div class="module_workflow_content">
		<ul class="tabs tabs_transparent tabs_right">
			<li id="code_editor_tab"><a href="#code" onClick="onClickCodeEditorTab(this);return false;">Code</a></li>
			<li id="tasks_flow_tab"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;">Workflow</a></li>
			<li id="external_vars_tab"><a href="#external_vars">External Vars</a></li>
		</ul>
	
		<div id="code">
			<div class="code_menu top_bar_menu">
				' . WorkFlowPresentationHandler::getCodeEditorMenuHtml(array("save_func" => "saveModuleWorkflowSettings")) . '
			</div>
			<textarea></textarea>
		</div>
		
		<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array("save_func" => "saveModuleWorkflowSettings")) . '</div>
		
		<div class="ui-menu-widgets-backup hidden">
			' . $ui_menu_widgets_html . '
		</div>
		<script>
			var mwb = $(".module_workflow_settings > .module_workflow_content > .ui-menu-widgets-backup");
			$(".module_workflow_settings > .module_workflow_content > #ui > .taskflowchart > .tasks_properties > .task_properties > .create_form_task_html > .ptl_settings > .layout_ui_editor > .menu-widgets").append( mwb.contents().clone() );
			$(".module_workflow_settings > .module_workflow_content > #ui > .taskflowchart > .tasks_properties > .task_properties > .inlinehtml_task_html > .layout_ui_editor > .menu-widgets").append( mwb.contents() );
			mwb.remove();
		</script>
		
		<div id="external_vars">
			<label>External Vars:</label>
			<table>
				<thead>
					<th class="variable_name">Variable Name</th>
					<th class="variable_value">Variable Value</th>
					<th class="action">
						<i class="icon add" onClick="addExternalVar(this)"></i>
					</th>
				</thead>
				<tbody index_prefix="external_vars">
					<tr class="no_external_variables"><td colspan="3">No enternal variables...</td></tr>
				</tbody>
			</table>
		</div>
	</div>';
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
</div>
