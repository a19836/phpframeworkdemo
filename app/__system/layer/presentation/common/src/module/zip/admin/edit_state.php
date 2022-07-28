<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$state_id = $_GET["state_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"state_id" => $state_id,
				"country_id" => $_POST["country_id"],
				"name" => $_POST["name"],
			);
			$status = $_POST["add"] ? ZipUtil::insertState($brokers, $data) : ZipUtil::updateState($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ZipUtil::deleteZipsByStateId($brokers, $state_id) && ZipUtil::deleteZonesByStateId($brokers, $state_id) && ZipUtil::deleteCitiesByStateId($brokers, $state_id) && ZipUtil::deleteState($brokers, $state_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "State ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_state") . "state_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this state. Please try again...";
			}
		}
	}
	
	$ZipAdminUtil->initZips($brokers);
	$available_countries = $ZipAdminUtil->getAvailableCountries();
	$country_options = $ZipAdminUtil->getCountryOptions();
	
	$data = ZipUtil::getStatesByConditions($brokers, array("state_id" => $state_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit State '$state_id'" : "Add State",
		"fields" => array(
			"country_id" => $data ? array("type" => "label", "available_values" => $available_countries) : array("type" => "select", "options" => $country_options),
			"name" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_state.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
