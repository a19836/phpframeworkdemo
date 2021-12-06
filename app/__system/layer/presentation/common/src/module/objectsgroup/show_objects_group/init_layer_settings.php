<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/start_project_module_file", $common_project_name);
include $EVC->getUtilPath("WorkFlowPresentationHandler");

if ($PEVC) {
	//PREPARING BROKERS
	$layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers, '$EVC->getBroker');
	
	$presentation_brokers = array();
	$presentation_brokers[] = array(WorkFlowBeansFileHandler::getLayerNameFromBeanObject($bean_name, $P) . " (Self)", $bean_file_name, $bean_name);
	$presentation_brokers_obj = array("default" => '$EVC->getPresentationLayer()');
}

include $EVC->getModulePath("common/end_project_module_file", $common_project_name);

$choose_bean_layer_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#";
$choose_dao_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=dao&path=#path#";
$choose_lib_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=lib&path=#path#";
$choose_vendor_files_from_file_manager_url = $project_url_prefix . "admin/get_sub_files?item_type=vendor&path=#path#";

$get_file_properties_url = $project_url_prefix . "phpframework/admin/get_file_properties?bean_name=#bean_name#&bean_file_name=#bean_file_name#&path=#path#&class_name=#class_name#&type=#type#";

$js_head = '
ProgrammingTaskUtil.on_programming_task_choose_created_variable_callback = onProgrammingTaskChooseCreatedVariable;

var choose_from_file_manager_popup_html = \'' . str_replace("\n", "", addcslashes(WorkFlowPresentationHandler::getChooseFromFileManagerPopupHtml($bean_name, $bean_file_name, $choose_bean_layer_files_from_file_manager_url, $choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, null, null, null, null, null, $presentation_brokers), "\\'")) . '\';
';
$js_head .= WorkFlowPresentationHandler::getPresentationBrokersHtml($presentation_brokers, $choose_bean_layer_files_from_file_manager_url, $get_file_properties_url);
$js_head .= WorkFlowPresentationHandler::getDaoLibAndVendorBrokersHtml($choose_dao_files_from_file_manager_url, $choose_lib_files_from_file_manager_url, $choose_vendor_files_from_file_manager_url, $get_file_properties_url);

header("Content-Type: application/javascript");
echo $js_head;
?>
