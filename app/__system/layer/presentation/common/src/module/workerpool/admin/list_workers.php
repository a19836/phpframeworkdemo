<?php
$UserAuthenticationHandler->checkPresentationFileAuthentication($module_path, "access");

$path_to_filter = $_GET["path_to_filter"]; //this comes from the amin/admin_citizen.php UI.

$common_project_name = $EVC->getCommonProjectName();
include $EVC->getModulePath("common/admin/start_project_module_admin_file", $common_project_name);

if ($PEVC) {
	include $EVC->getModulePath("workerpool/admin/WorkerPoolAdminUtil", $common_project_name);
	
	$WorkerPoolAdminUtil = new WorkerPoolAdminUtil($CommonModuleAdminUtil);
	
	include $EVC->getModulePath("common/admin/init_project_module_admin_list", $common_project_name);
	
	$pks = "worker_id=#[\$idx][worker_id]#";
	$data = WorkerPoolUtil::getAllWorkers($brokers, $options, true);
	$worker_available_statuses = $WORKER_AVAILABLE_STATUSES = WorkerPoolUtil::getConstantVariable("WORKER_AVAILABLE_STATUSES");
	
	if ($data) 
		foreach ($data as &$item) {
			if ($item["begin_time"])
				$item["begin_date"] = date("Y-m-d H:i:s", $item["begin_time"]);
			
			if ($item["end_time"])
				$item["end_date"] = date("Y-m-d H:i:s", $item["end_time"]);
		}
	
	$url_suffix = $path_to_filter ? "&path_to_filter=" . $path_to_filter : "";
	
	$list_settings = array(
		"title" => "Workers List",
		"edit_url" => $CommonModuleAdminUtil->getAdminFileUrl("edit_worker") . $pks . $url_suffix,
		"delete_url" => $CommonModuleAdminUtil->getAdminFileUrl("delete_worker") . $pks,
		"fields" => array(
			"worker_id", 
			"class", 
			"status" => array("available_values" => $worker_available_statuses), 
			"thread_id", 
			"begin_date", 
			"end_date", 
			"failed_attempts", 
			"created_date", 
			"modified_date"
		),
		"total" => WorkerPoolUtil::countAllWorkers($brokers, true),
		"data" => $data,
		"rows_class" => "status_#[idx][status]#",
		"rows_per_page" => $rows_per_page,
		"current_page" => $current_page,
	);
	
	$head = '<link rel="stylesheet" href="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_workers.css" type="text/css" charset="utf-8" />
	<script type="text/javascript" src="' . $CommonModuleAdminUtil->getWebrootAdminFolderUrl() . 'list_workers.js"></script>
	<script>
		var add_worker_url = \'' . $CommonModuleAdminUtil->getAdminFileUrl("edit_worker") . $url_suffix . '\';
	</script>';
	$menu_settings = $WorkerPoolAdminUtil->getMenuSettings($url_suffix);
	$main_content = $CommonModuleAdminUtil->getListContent($list_settings);
}

include $EVC->getModulePath("common/admin/end_project_module_admin_file", $common_project_name);
?>
