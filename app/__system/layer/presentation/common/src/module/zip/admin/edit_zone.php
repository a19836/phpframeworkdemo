<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$zone_id = $_GET["zone_id"];
	
	if ($_POST) {
		if ($_POST["add"] || $_POST["save"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"zone_id" => $zone_id,
				"city_id" => $_POST["city_id"],
				"name" => $_POST["name"],
			);
			$status = $_POST["add"] ? ZipUtil::insertZone($brokers, $data) : ZipUtil::updateZone($brokers, $data);
		}
		else if ($_POST["delete"]) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ZipUtil::deleteZipsByZoneId($brokers, $zone_id) && ZipUtil::deleteZone($brokers, $zone_id);
		}
		
		if ($action) {
			if ($status) {
				$status_message = "Zone ${action}d successfully!";
				
				if ($_POST["add"]) {
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_zone") . "zone_id=$status";
					die("<script>alert('$status_message');document.location='$url';</script>");
				}
			}
			else {
				$error_message = "There was an error trying to $action this zone. Please try again...";
			}
		}
	}
	
	$data = ZipUtil::getZonesByConditions($brokers, array("zone_id" => $zone_id), null, null, true);
	$data = $data[0];
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || ($_POST["delete"] && !$error_message) ? "Edit Zone '$zone_id'" : "Add Zone",
		"fields" => array(
			"city_id" => "text",
			"name" => "text",
		),
		"data" => $data,
		"status_message" => $status_message,
		"error_message" => $error_message,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_zone.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
