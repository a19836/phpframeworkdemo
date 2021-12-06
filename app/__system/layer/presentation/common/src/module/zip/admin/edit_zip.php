<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$zip_id = $_GET["zip_id"];
	$country_id = $_GET["country_id"];
	
	if ($_POST) {
		if ($_POST["add"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"zip_id" => $_POST["zip_id"],
				"country_id" => $_POST["country_id"],
				"zone_id" => $_POST["zone_id"],
			);
			$status = ZipUtil::insertZip($brokers, $data);
		}
		if ($_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"zip_id" => $zip_id,
				"country_id" => $country_id,
				"zone_id" => $_POST["zone_id"],
			);
			$status = ZipUtil::updateZip($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ZipUtil::deleteZip($brokers, $zip_id, $country_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Zip ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_zip") . "zip_id={$_POST['zip_id']}&country_id={$_POST['country_id']}";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this zip. Please try again...";
			}
		}
	}
	
	$ZipAdminUtil->initZips($brokers);
	$available_countries = $ZipAdminUtil->getAvailableCountries();
	$country_options = $ZipAdminUtil->getCountryOptions();
	
	$data = ZipUtil::getZipsByConditions($brokers, array("zip_id" => $zip_id, "country_id" => $country_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Zip '$zip_id'" : "Add Zip",
		"fields" => array(
			"zip_id" => $data ? "label" : "text",
			"country_id" => $data ? array("type" => "label", "available_values" => $available_countries) : array("type" => "select", "options" => $country_options),
			"zone_id" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_zip.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
