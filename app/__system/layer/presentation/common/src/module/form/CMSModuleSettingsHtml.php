<div class="module_form_settings">
<?php
include $EVC->getConfigPath("config");
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler");
include_once $EVC->getUtilPath("WorkFlowUIHandler");
include_once $EVC->getUtilPath("WorkFlowPresentationHandler");
include_once $EVC->getUtilPath("WorkFlowQueryHandler");
include_once $EVC->getUtilPath("CMSPresentationUIAutomaticFilesHandler");
include_once $EVC->getUtilPath("LayoutTypeProjectHandler");
include_once $EVC->getUtilPath("LayoutTypeProjectUIHandler");
include_once $EVC->getModulePath("form/utils", $common_project_name);
include_once $EVC->getModulePath("common/CommonModuleSettingsUI", $common_project_name);

$filter_by_layout = $_GET["filter_by_layout"]; //optional
$filter_by_layout_url_query = LayoutTypeProjectUIHandler::getFilterByLayoutURLQuery($filter_by_layout);

$common_project_name = $EVC->getCommonProjectName();
$purlp = $project_url_prefix;
$pcurlp = $project_common_url_prefix;

include $EVC->getModulePath("common/start_project_module_file", $common_project_name);
$project_url_prefix = $purlp;
$project_common_url_prefix = $pcurlp;

if ($PEVC) {
	//getting available projects
	$presentation_projects = CMSPresentationLayerHandler::getPresentationLayerProjectsFiles($user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name);
	
	$LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name);
	$LayoutTypeProjectHandler->filterPresentationLayerProjectsByUserAndLayoutPermissions($presentation_projects, $filter_by_layout, null, array(
			"do_not_filter_by_layout" => array(
				"bean_name" => $bean_name,
				"bean_file_name" => $bean_file_name,
				"project" => $P->getSelectedPresentationId(),
			)
		));
	//echo "<pre>";print_r($presentation_projects);die();
	$presentation_projects = array_keys($presentation_projects);
	
	//prepare layers settings and brokers
	$layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker');
	$presentation_layer_label = WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P) . " (Self)";
	
	$presentation_brokers = array();
	$presentation_brokers[] = array($presentation_layer_label, $bean_file_name, $bean_name);
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
	
	$brokers_settings = array(
		$presentation_layer_label => $presentation_brokers[0]
	);
	$brokers_name_by_obj_code = array(
		$presentation_brokers_obj["default"] => $presentation_layer_label,
	);
	
	foreach ($business_logic_brokers as $b) {
		$broker_name = $b[0];
		$brokers_settings[$broker_name] = $b;
		$brokers_name_by_obj_code[ $business_logic_brokers_obj[$broker_name] ] = $broker_name;
	}
	
	foreach ($data_access_brokers as $b) {
		$broker_name = $b[0];
		$brokers_settings[$broker_name] = $b;
		$brokers_name_by_obj_code[ $data_access_brokers_obj[$broker_name] ] = $broker_name;
	}
	
	//prepare tasks
	$allowed_tasks_tag = array("createform", "callfunction", "callobjectmethod", "formitemsingle", "formitemgroup", "restconnector", "soapconnector");

	if ($data_access_brokers_obj) {
		$allowed_tasks_tag[] = "query";
		$allowed_tasks_tag[] = "getquerydata";
		$allowed_tasks_tag[] = "setquerydata";

		if ($ibatis_brokers_obj) 
			$allowed_tasks_tag[] = "callibatisquery";
		
		if ($hibernate_brokers_obj)
			$allowed_tasks_tag[] = "callhibernatemethod";
	}
	else if ($db_brokers_obj) {
		$allowed_tasks_tag[] = "query";
		$allowed_tasks_tag[] = "getquerydata";
		$allowed_tasks_tag[] = "setquerydata";
	}
	
	if ($business_logic_brokers_obj) 
		$allowed_tasks_tag[] = "callbusinesslogic";
	
	$WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
	$form_tasks_folder_path = $EVC->getModulesPath($common_project_name) . "form/tasks/";
	$WorkFlowTaskHandler->addTasksFolderPath($form_tasks_folder_path);
	$WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag);
	
	//preparing task settings
	$WorkFlowUIHandler = new WorkFlowUIHandler($WorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
	$WorkFlowUIHandler->setTasksGroupsByTag(array(
		"Form Groups" => array("formitemsingle", "formitemgroup"),
		"disabled" => array("createform", "callfunction", "callobjectmethod", "query", "getquerydata", "setquerydata", "callibatisquery", "callhibernatemethod", "callbusinesslogic", "restconnector", "soapconnector"),
	));
	$tasks_settings = $WorkFlowTaskHandler->getLoadedTasksSettings();

	$contents = array();
	$js_load_functions = array();

	foreach ($tasks_settings as $group_id => $group_tasks) {
		foreach ($group_tasks as $task_type => $task_settings) {
			if (is_array($task_settings)) {
				$tag = $task_settings["tag"];
				$contents[$tag] = $task_settings["task_properties_html"];
				$js_load_functions[$tag] = $task_settings["settings"]["callback"]["on_load_task_properties"];
			}
		}
	}

	//preparing head
	$choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#";
	$choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#";
	$choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#";
	$choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#";

	$get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#";
	$get_query_properties_url = $project_url_prefix . "phpframework/dataaccess/get_query_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=#db_driver#&db_type=#db_type#&path=#path#&query_type=#query_type#&query=#query#&obj=#obj#&relationship_type=#relationship_type#";
	$get_query_result_properties_url = $project_url_prefix . "phpframework/dataaccess/get_query_result_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&db_driver=" . $GLOBALS["default_db_driver"] . "&module_id=#module_id#&query_type=#query_type#&query=#query#&rel_name=#rel_name#&obj=#obj#&relationship_type=#relationship_type#";
	$get_business_logic_properties_url = $project_url_prefix . "phpframework/businesslogic/get_business_logic_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&service=#service#";
	$get_business_logic_result_properties_url = $project_url_prefix . "phpframework/businesslogic/get_business_logic_result_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&module_id=#module_id#&service=#service#";
	$get_broker_db_drivers_url = $project_url_prefix . "phpframework/db/get_broker_db_drivers?bean_name=$bean_name&bean_file_name=$bean_file_name&broker=#broker#&item_type=presentation";
	
	$path_extra = hash('crc32b', "$bean_file_name/$bean_name/$path");
	$get_workflow_tasks_id = "presentation_block_form&path_extra=_$path_extra";
	$get_tmp_workflow_tasks_id = "presentation_block_form_tmp&path_extra=_${path_extra}_" . rand(0, 1000);
	
	$set_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_workflow_tasks_id}";
	$get_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_workflow_tasks_id}";
	$create_workflow_file_from_settings_url = $project_url_prefix . "module/form/create_workflow_file_from_settings?path=${get_tmp_workflow_tasks_id}&loaded_tasks_settings_cache_id=" . $WorkFlowTaskHandler->getLoadedTasksSettingsCacheId();
	$get_tmp_workflow_file_url = $project_url_prefix . "workflow/get_workflow_file?path=${get_tmp_workflow_tasks_id}";
	$create_settings_from_workflow_file_url = $project_url_prefix . "module/form/create_settings_from_workflow_file?path=${get_tmp_workflow_tasks_id}";
	$set_tmp_workflow_file_url = $project_url_prefix . "workflow/set_workflow_file?path=${get_tmp_workflow_tasks_id}";
	
	$head = WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url, true, true);
	$head .= LayoutTypeProjectUIHandler::getHeader();
	
	//must have the layout after this, bc the getHeader method loads the common/vendor/jquerytaskflowchart/css/style.css which will overwrite the previous loaded layout.css styles. So we must re-add it here again.
	$head .= '
<!-- Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
';
	
	$js_head = '
	var create_form_settings_code_url = \'' . $project_url_prefix . 'module/form/create_form_settings_code\';
	var convert_form_settings_to_workflow_code_url = \'' . $project_url_prefix . "module/form/convert_form_settings_to_workflow_code?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path" . '\';
	var get_input_data_method_settings_url = \'' . $project_url_prefix . 'module/form/get_input_data_method_settings\';
	var get_form_wizard_settings_url = \'' . $project_url_prefix . "module/form/get_form_wizard_settings?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path" . '\';
	var get_form_action_result_properties_url = \'' . $project_url_prefix . 'module/form/get_form_action_result_properties\';
	
	var get_workflow_file_url = \'' . $get_workflow_file_url . '\';
	var create_workflow_file_from_settings_url = \'' . $create_workflow_file_from_settings_url . '\';
	var get_tmp_workflow_file_url = \'' . $get_tmp_workflow_file_url . '\';
	var create_settings_from_workflow_file_url = \'' . $create_settings_from_workflow_file_url . '\';
	var set_tmp_workflow_file_url = \'' . $set_tmp_workflow_file_url . '\';
	
	var get_query_properties_url = \'' . $get_query_properties_url . '\';
	var get_query_result_properties_url = \'' . $get_query_result_properties_url . '\';
	var get_business_logic_properties_url = \'' . $get_business_logic_properties_url . '\';
	var get_business_logic_result_properties_url = \'' . $get_business_logic_result_properties_url . '\';
	var get_broker_db_drivers_url = \'' . $get_broker_db_drivers_url . '\';
	
	var js_load_functions = ' . json_encode($js_load_functions) . ';
	
	ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;
	ProgrammingTaskUtil.on_programming_task_choose_object_method_callback = onProgrammingTaskChooseObjectMethod;
	ProgrammingTaskUtil.on_programming_task_choose_function_callback = onProgrammingTaskChooseFunction;
	ProgrammingTaskUtil.on_programming_task_choose_page_url_callback = onIncludePageUrlTaskChooseFile;
	ProgrammingTaskUtil.on_programming_task_choose_image_url_callback = onIncludeImageUrlTaskChooseFile;
	
	CreateFormTaskPropertyObj.layout_ui_editor_menu_widgets_elm_selector = \'.ui-menu-widgets-backup\';
	
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
	
	if (typeof DBQueryTaskPropertyObj != "undefined" && DBQueryTaskPropertyObj) {
		DBQueryTaskPropertyObj.show_properties_on_connection_drop = true;
	}
	
	var brokers_settings = ' . json_encode($brokers_settings) . ';
	var brokers_name_by_obj_code = ' . json_encode($brokers_name_by_obj_code) . ';

	var choose_from_file_manager_popup_html = \'' . str_replace("\n", "", addcslashes(WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers), "\\'")) . '\';';

	//prepare query task settings
	$db_drivers = array();
	$selected_type = "db"; // db or diagram
	$selected_dal_broker = $selected_db_driver = null;
	
	if ($brokers)
		foreach ($brokers as $broker_name => $broker)
			if (is_a($broker, "IDataAccessBrokerClient") || is_a($broker, "IDBBrokerClient")) {
				$db_drivers[$broker_name] = is_a($broker, "IDBBrokerClient") ? $broker->getDBDriversName() : $broker->getBrokersDBDriversName();
				
				if (empty($selected_dal_broker)) {
					$selected_dal_broker = $broker_name;
					
				 	if ($GLOBALS["default_db_driver"] && in_array($GLOBALS["default_db_driver"], $db_drivers[$broker_name]))
						$selected_db_driver = $GLOBALS["default_db_driver"];
					else if (!$selected_db_driver)
						$selected_db_driver = $db_drivers[$broker_name][0];
				}
			}
	
	if ($db_drivers) {
		$QueryWorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url);
		$QueryWorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH);
		$QueryWorkFlowTaskHandler->setAllowedTaskTags(array("query"));
		
		$QueryWorkFlowUIHandler = new WorkFlowUIHandler($QueryWorkFlowTaskHandler, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $webroot_cache_folder_path, $webroot_cache_folder_url);
		
		$WorkFlowQueryHandler = new WorkFlowQueryHandler($QueryWorkFlowUIHandler, $project_url_prefix, $project_common_url_prefix, $db_drivers, $selected_dal_broker, $selected_db_driver, $selected_type, "", array(), array(), array(), array());
		
		//prepare query task settings - Add Javascript
		$query_js_head .= $WorkFlowQueryHandler->getDataAccessJavascript($bean_name, $bean_file_name, $path, "presentation", null, null);
		$js_head .= str_replace('<script>', '', str_replace('</script>', '', $query_js_head));
		$js_head .= 'get_broker_db_data_url += "&global_default_db_driver_broker=' . $GLOBALS["default_db_broker"] . '";'; //$GLOBALS["default_db_broker"] corresponds to the default broker name of the DBLayer inside of the DataAccessLayer brokers.
		
		//prepare query task settings - Add taskworkflow html
		$html = $WorkFlowQueryHandler->getGlobalTaskFlowChar();
		$html .= $WorkFlowQueryHandler->getQueryBlockHtml();
		$js_head .= 'var query_task_html = \'' . addcslashes(str_replace("\n", "", $html), "\\'") . '\';';
		
		//prepare query task settings - Add choose_db_table_or_attribute html
		$html = $WorkFlowQueryHandler->getChooseQueryTableOrAttributeHtml("choose_db_table_or_attribute");
		$js_head .= '
		var choose_db_table_or_attribute_html = $( \'' . addcslashes(str_replace("\n", "", $html), "\\'") . '\' );
		$(".module_settings > .settings").append(choose_db_table_or_attribute_html);
		
		var default_dal_broker = "' . $selected_dal_broker . '";
		var default_db_driver = "' . $selected_db_driver . '";
		
		getDBTables("' . $selected_dal_broker . '", "' . $selected_db_driver . '", "' . $selected_type . '");
		
		var db_tables = db_brokers_drivers_tables_attributes["' . $selected_dal_broker . '"] && db_brokers_drivers_tables_attributes["' . $selected_dal_broker . '"]["' . $selected_db_driver . '"] ? db_brokers_drivers_tables_attributes["' . $selected_dal_broker . '"]["' . $selected_db_driver . '"]["' . $selected_type . '"] : null;
		
		if (db_tables) {
			var html = "<option></option>";
			for (var db_table in db_tables) {
				html += "<option>" + db_table + "</option>";
			}
			choose_db_table_or_attribute_html.find(".db_table select").html(html);
		}
		
		choose_db_table_or_attribute_html.find(".db_broker > select").change(function() {
			onChangePopupDBBrokers(this);
		});
		
		choose_db_table_or_attribute_html.find(".db_driver > select").change(function() {
			onChangePopupDBDrivers(this);
		});
		
		choose_db_table_or_attribute_html.find(".type > select").change(function() {
			onChangePopupDBTypes(this);
		});
		';
	}
		
	//prepare layers brokers settings (Must be after the '$WorkFlowQueryHandler->getDataAccessJavascript(...)' bc it must overwrite the main_layers_properties.Presentation javascript var with the right settings...
	$js_head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
	$js_head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
	$js_head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url);
	$js_head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url);
	
	//prepare available activities and user types
	$available_user_types = CMSPresentationUIAutomaticFilesHandler::getAvailableUserTypes($PEVC);
	$available_activities = CMSPresentationUIAutomaticFilesHandler::getAvailableActivities($PEVC);
	
	$workflow_module_installed_and_enabled = $PEVC->getCMSLayer()->getCMSModuleLayer()->existsModule("workflow");
	
	$js_head .= '
	var available_user_types = ' . json_encode($available_user_types) . ';
	var available_activities = ' . json_encode($available_activities) . ';

	var workflow_module_installed_and_enabled = ' . ($workflow_module_installed_and_enabled ? 1 : 0) . ';';
	
	//prepare layout
	if ($db_drivers) 
		echo '
		<!-- DBQUERY TASK - Add Edit-Query JS and CSS files -->
		<link rel="stylesheet" href="' . $project_url_prefix . 'css/dataaccess/edit_query.css" type="text/css" charset="utf-8" />
		<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/dataaccess/edit_query.js"></script>
		';
	
	echo '
		' . $head . '
		<script>' . $js_head . '</script>

		<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/settings.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/settings.js"></script>

		<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/form/brokers_settings.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'module/form/brokers_settings.js"></script>';
	?>
		<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>form.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="<?= $module["webroot_url"]; ?>form.js"></script>
		
		<div class="module_form_contents">
			<ul class="tabs tabs_transparent">
				<li id="groups_flow_tab"><a href="#groups_flow">By Groups - Sequential Logic</a></li>
				<li id="tasks_flow_tab"><a href="#ui" onClick="onClickTaskWorkflowTab(this);return false;">By Diagram</a></li>
			</ul>
			
			<div id="groups_flow">
				<nav>
					<a class="add_form_group" onClick="addAndInitNewFormGroup(this)">Add Group <i class="icon add"></i></a>
					<a class="collapse_form_groups" onClick="collapseFormGroups(this)">Collapse Groups <i class="icon collapse_content"></i></a>
					<a class="expand_form_groups" onClick="expandFormGroups(this)">Expand Groups <i class="icon expand_content"></i></a>
	
	<? 
		if ($db_drivers) 
			echo '	<a class="open_form_wizard" onClick="openFormWizard()">Open Wizard <i class="icon wizard"></i></a>';
	?>
	
				</nav>
				
				<ul class="form-groups">
					<li class="form-group-item form-group-default">
						<?php echo getFormGroupItemHtml($EVC, $project_url_prefix, $project_common_url_prefix, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url, $contents, $db_drivers, $presentation_projects); ?>
					</li>
				</ul>
			</div>
		
	<?php
	echo '	<div id="ui">' . WorkFlowPresentationHandler::getTaskFlowContentHtml($WorkFlowUIHandler, array(
		"save_func" => "saveModuleFormSettings", 
		"generate_code_from_tasks_flow_label" => "Generate Groups from Diagram", 
		"generate_code_from_tasks_flow_func" => "generateGroupsFromTasksFlow", 
		"generate_tasks_flow_from_code_label" => "Generate Diagram from Groups",
		"generate_tasks_flow_from_code_func" => "generateTasksFlowFromGroups", 
	)) . '</div>';
	
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
		</div>
				
		<div class="myfancypopup form-wizard">
			<div class="title">Form Wizard</div>
			<ul class="steps">
				<li class="step panel-type-selection">
					<label>Please choose the panel type: </label>
					<select class="panel-type" onChange="toggleFormWizardPanelType(this)">
						<option value="list_table">Table List of Items</option>
						<option value="list_form">Form List of Items</option>
						<option value="single_form">Single Form Item</option>
						<option value="multiple_form">Multiple Form Item</option>
					</select>
					<select class="form-type">
						<option value="settings">With Form Settings</option>
						<option value="ptl">With PTL</option>
					</select>
				</li>
				<li class="step table-selection">
					<select class="dal-broker" onChange="onChangeDALBrokerFormWizard(this)"></select>
					<select class="db-driver" onChange="onChangeDBDriverOrTypeFormWizard(this)"></select>
					<select class="db-type" onChange="onChangeDBDriverOrTypeFormWizard(this)">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
					
					<div class="clear"></div>
					
					<label>Please choose a table: </label>
					<select class="db-table" onChange="onChangeDBTableFormWizard(this)"></select>
					<a class="toggle-table-options" onClick="toggleFormWizardTableOptions(this)">Show/Hide more table options</a>
					
					<div class="table-options">
						<div class="attributes"><label>Attributes: </label><ul></ul></div>
						<div class="conditions"><label>Conditions: </label><ul></ul></div>
					</div>
				</li>
				<li class="step actions-selection">
					<label>Please choose which actions do you wish: </label>
					<div class="single-actions">
						<label>Item individual actions:</label>
						<?php
						echo getActionHtml("single_insert");
						echo getActionHtml("single_update");
						echo getActionHtml("single_delete");
						?>
					</div>
					<div class="multiple-actions">
						<label>Multiple actions:</label>
						<?php
						echo getActionHtml("multiple_insert");
						echo getActionHtml("multiple_update");
						echo getActionHtml("multiple_insert_update");
						echo getActionHtml("multiple_delete");
						?>
					</div>
					<?php
					echo getActionHtml("links", 2);
					?>
				</li>
				<li class="step create-selection">
					<label>To finish this process, please click in one of the following buttons: </label>
					<input type="button" value="Create Html and replace previous Html (if exists)" onClick="createFormWizard(this, true)">
					<input type="button" value="Create Html and add to the previous Html (if exists)" onClick="createFormWizard(this, false)">
				</li>
			</ul>
			<div class="buttons">
				<input type="button" class="previous" value="Previous" onClick="previousFormWizard(this)" />
				<input type="button" class="next" value="Next" onClick="nextFormWizard(this)" />
			</div>
		</div>
<?
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);
?>
</div>
