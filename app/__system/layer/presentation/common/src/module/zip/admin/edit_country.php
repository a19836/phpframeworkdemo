<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$country_id = $_GET["country_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"country_id" => $country_id,
				"name" => $_POST["name"],
			);
			$status = $_POST["add"] ? ZipUtil::insertCountry($brokers, $data) : ZipUtil::updateCountry($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ZipUtil::deleteZipsByCountryId($brokers, $country_id) && ZipUtil::deleteZonesByCountryId($brokers, $country_id) && ZipUtil::deleteCitiesByCountryId($brokers, $country_id) && ZipUtil::deleteStatesByCountryId($brokers, $country_id) && ZipUtil::deleteCountry($brokers, $country_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Country ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_country") . "country_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this country. Please try again...";
			}
		}
	}
	
	$data = ZipUtil::getCountriesByConditions($brokers, array("country_id" => $country_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Country '$country_id'" : "Add Country",
		"fields" => array(
			"name" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_country.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
