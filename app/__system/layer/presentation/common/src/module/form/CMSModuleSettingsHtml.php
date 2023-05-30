<div class="module_form_settings">
<?php
include $EVC->getConfigPath("config");
include_once $EVC->getUtilPath("SequentialLogicalActivityUIHandler");
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
	$selected_db_vars = WorkFlowBrokersSelectedDBVarsHandler::getBrokersSelectedDBVars( $P->getBrokers() );
	
	$opts = array(
		"main_div_selector" => ".module_form_settings",
		"workflow_tasks_id" => "presentation_block_form_sla",
		"path_extra" => hash('crc32b', "$bean_file_name/$bean_name/$path"),
	);
	$sla = SequentialLogicalActivityUIHandler::getHeader($EVC, $PEVC, $UserAuthenticationHandler, $bean_name, $bean_file_name, $path, $project_url_prefix, $project_common_url_prefix, $gpl_js_url_prefix, $proprietary_js_url_prefix, $user_global_variables_file_path, $user_beans_folder_path, $webroot_cache_folder_path, $webroot_cache_folder_url, $filter_by_layout, $opts);
	$sla_head = $sla["head"];
	$sla_js_head = $sla["js_head"];
	$tasks_contents = $sla["tasks_contents"];
	$layer_brokers_settings = $sla["layer_brokers_settings"];
	$presentation_projects = $sla["presentation_projects"];
	$db_drivers = $sla["db_drivers"];
	$WorkFlowTaskHandler = $sla["WorkFlowTaskHandler"];
	$WorkFlowUIHandler = $sla["WorkFlowUIHandler"];
	$set_workflow_file_url = $sla["set_workflow_file_url"];
	$get_workflow_file_url = $sla["get_workflow_file_url"];
	
	//prepare brokers
	$presentation_brokers = $layer_brokers_settings["presentation_brokers"];
	$business_logic_brokers = $layer_brokers_settings["business_logic_brokers"];
	$data_access_brokers = $layer_brokers_settings["data_access_brokers"];
	$ibatis_brokers = $layer_brokers_settings["ibatis_brokers"];
	$hibernate_brokers = $layer_brokers_settings["hibernate_brokers"];
	$db_brokers = $layer_brokers_settings["db_brokers"];
	
	//preparing generic urls
	$choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#";
	$upload_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/upload_file?bean_name=#bean_name#&bean_file_name=#bean_file_name#$filter_by_layout_url_query&path=#path#";
	$choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#";
	$choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#";
	$choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#";
	$get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#";
	
	//preparing head
	$head = $sla_head;
	$head .= WorkFlowPresentationHandler::getHeader($project_url_prefix, $project_common_url_prefix, $WorkFlowUIHandler, $set_workflow_file_url, true, true);
	$head .= LayoutTypeProjectUIHandler::getHeader();
	
	//must have the layout after this, bc the WorkFlowPresentationHandler::getHeader method loads the common/vendor/jquerytaskflowchart/css/style.css which will overwrite the previous loaded layout.css styles. So we must re-add it here again.
	$head .= '
<!-- Layout CSS file -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/layout.css" type="text/css" charset="utf-8" />
';
	
	//preparing javascript head
	$js_head = WorkFlowBrokersSelectedDBVarsHandler::printSelectedDBVarsJavascriptCode($project_url_prefix, $bean_name, $bean_file_name, $selected_db_vars);
	$js_head .= $sla_js_head;
	$js_head .= '
	var get_form_wizard_settings_url = \'' . $project_url_prefix . "module/form/get_form_wizard_settings?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path" . '\';
	var get_input_data_method_settings_url = \'' . $project_url_prefix . 'module/form/get_input_data_method_settings\';
	var convert_form_settings_to_workflow_code_url = \'' . $project_url_prefix . "module/form/convert_form_settings_to_workflow_code?bean_name=$bean_name&bean_file_name=$bean_file_name&path=$path" . '\';
	var create_form_settings_code_url = \'' . $project_url_prefix . "module/form/create_form_settings_code" . '\';
	
	var choose_from_file_manager_popup_html = \'' . str_replace("\n", "", addcslashes(WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $db_brokers, $data_access_brokers, $ibatis_brokers, $hibernate_brokers, $business_logic_brokers, $presentation_brokers), "\\'")) . '\';';
	
	//check if workflow module is installed
	$workflow_module_installed_and_enabled = $PEVC->getCMSLayer()->getCMSModuleLayer()->existsModule("workflow");
	$js_head .= '
	var workflow_module_installed_and_enabled = ' . ($workflow_module_installed_and_enabled ? 1 : 0) . ';';
	
	//prepare layers brokers settings (Must be after the '$WorkFlowQueryHandler->getDataAccessJavascript(...)' bc it must overwrite the main_layers_properties.Presentation javascript var with the right settings...
	$js_head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url, $upload_bean_layer_files_from_file_manager_url);
	$js_head .= WorkFlowPresentationHandler::getBusinessLogicBrokersHtml($business_logic_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
	$js_head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url);
	$js_head .= WorkFlowPresentationHandler::getDataAccessBrokersHtml($data_access_brokers, $choose_bean_layer_files_from_file_manager_url);
	
	//preparing html
	echo '
		' . $head . '
		<script>' . $js_head . '</script>

		<link rel="stylesheet" href="' . $project_common_url_prefix . 'module/common/settings.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_common_url_prefix . 'module/common/settings.js"></script>

		<link rel="stylesheet" href="' . $project_url_prefix . 'css/sequentiallogicalactivity/sla.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="' . $project_url_prefix . 'js/sequentiallogicalactivity/sla.js"></script>';
	?>
		<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>form.css" type="text/css" charset="utf-8" />
		<script type="text/javascript" src="<?= $module["webroot_url"]; ?>form.js"></script>
		
		<div class="module_form_contents">
			<?php
			echo SequentialLogicalActivityUIHandler::getSLAHtml($EVC, $project_url_prefix, $project_common_url_prefix, $layout_ui_editor_user_widget_folders_path, $webroot_cache_folder_path, $webroot_cache_folder_url, $tasks_contents, $db_drivers, $presentation_projects, $WorkFlowUIHandler, array(
				"extra_short_actions_html" => $db_drivers ? '<a class="open_form_wizard" onClick="openFormWizard()">Open Wizard <i class="icon wizard"></i></a>' : '',
				"save_func" => "saveModuleFormSettings",
			));
		
			echo CommonModuleSettingsUI::getCssFieldsHtml();
			echo CommonModuleSettingsUI::getJsFieldsHtml();
			?>
		</div>
				
		<div class="myfancypopup form_wizard">
			<div class="title">Form Wizard</div>
			<ul class="steps">
				<li class="step panel_type_selection">
					<label>Please choose the panel type: </label>
					<select class="panel_type" onChange="toggleFormWizardPanelType(this)">
						<option value="list_table">Table List of Items</option>
						<option value="list_form">Form List of Items</option>
						<option value="single_form">Single Form Item</option>
						<option value="multiple_form">Multiple Form Item</option>
					</select>
					<select class="form_type">
						<option value="settings">With Form Settings</option>
						<option value="ptl">With PTL</option>
					</select>
				</li>
				<li class="step table_selection">
					<select class="dal_broker" onChange="onChangeDALBrokerFormWizard(this)"></select>
					<select class="db_driver" onChange="onChangeDBDriverOrTypeFormWizard(this)"></select>
					<select class="db_type" onChange="onChangeDBDriverOrTypeFormWizard(this)">
						<option value="db">From DB Server</option>
						<option value="diagram">From DB Diagram</option>
					</select>
					
					<div class="clear"></div>
					
					<label>Please choose a table: </label>
					<select class="db_table" onChange="onChangeDBTableFormWizard(this)"></select>
					<a class="toggle_table_options" onClick="toggleFormWizardTableOptions(this)">Show/Hide more table options</a>
					
					<div class="table_options">
						<div class="attributes"><label>Attributes: </label><ul></ul></div>
						<div class="conditions"><label>Conditions: </label><ul></ul></div>
					</div>
				</li>
				<li class="step actions_selection">
					<label>Please choose which actions do you wish: </label>
					<div class="single_actions">
						<label>Item individual actions:</label>
						<?php
						echo getActionHtml("single_insert");
						echo getActionHtml("single_update");
						echo getActionHtml("single_delete");
						?>
					</div>
					<div class="multiple_actions">
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
				<li class="step create_selection">
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
