<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */

$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if (!empty($PEVC)) {
	include $EVC->getModulePath("zip/admin/ZipAdminUtil", $common_project_name);
	
	$ZipAdminUtil = new ZipAdminUtil($CommonModuleAdminUtil);
	
	//Preparing Data
	$zip_id = isset($_GET["zip_id"]) ? $_GET["zip_id"] : null;
	$country_id = isset($_GET["country_id"]) ? $_GET["country_id"] : null;
	
	if (!empty($_POST)) {
		if (!empty($_POST["add"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"zip_id" => isset($_POST["zip_id"]) ? $_POST["zip_id"] : null,
				"country_id" => isset($_POST["country_id"]) ? $_POST["country_id"] : null,
				"zone_id" => isset($_POST["zone_id"]) ? $_POST["zone_id"] : null,
			);
			$status = ZipUtil::insertZip($brokers, $data);
		}
		if (!empty($_POST["save"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "write");
			$action = "save";
			
			$data = array(
				"zip_id" => $zip_id,
				"country_id" => $country_id,
				"zone_id" => isset($_POST["zone_id"]) ? $_POST["zone_id"] : null,
			);
			$status = ZipUtil::updateZip($brokers, $data);
		}
		else if (!empty($_POST["delete"])) {
			$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "delete");
			$action = "delete";
			$status = ZipUtil::deleteZip($brokers, $zip_id, $country_id);
		}
		
		if (!empty($action)) {
			if (!empty($status)) {
				$status_message = "Zip ${action}d successfully!";
				
				if (!empty($_POST["add"])) {
					$data_zip_id = isset($_POST["zip_id"]) ? $_POST["zip_id"] : null;
					$data_country_id = isset($_POST["country_id"]) ? $_POST["country_id"] : null;
					
					$url = $CommonModuleAdminUtil->getAdminFileUrl("edit_zip") . "zip_id=$data_zip_id&country_id=$data_country_id";
					echo "<script>alert('$status_message');document.location='$url';</script>";
					die();
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
	$data = isset($data[0]) ? $data[0] : null;
	
	//Preparing HTML
	$form_settings = array(
		"title" => $data || (!empty($_POST["delete"]) && empty($error_message)) ? "Edit Zip '$zip_id'" : "Add Zip",
		"fields" => array(
			"zip_id" => $data ? "label" : "text",
			"country_id" => $data ? array("type" => "label", "available_values" => $available_countries) : array("type" => "select", "options" => $country_options),
			"zone_id" => "text",
		),
		"data" => $data,
		"status_message" => isset($status_message) ? $status_message : null,
		"error_message" => isset($error_message) ? $error_message : null,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'edit_zip.css" type="text/css" charset="utf-8" />';
	$menu_settings = $ZipAdminUtil->getMenuSettings();
	$main_content = $CommonModuleAdminUtil->getFormContent($form_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
