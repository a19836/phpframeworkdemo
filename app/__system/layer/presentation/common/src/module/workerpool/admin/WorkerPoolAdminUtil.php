<?php
include $EVC->getModulePath("workerpool/WorkerPoolUtil", $EVC->getCommonProjectName());

class WorkerPoolAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings($query_string = "") {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Workers",
					"menus" => array(
						array(
							"label" => "Workers List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_workers") . $query_string,
							"title" => "View List of Workers",
							"class" => "",
						),
						array(
							"label" => "Add Worker",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_worker") . $query_string,
							"title" => "Add new Worker",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Settings",
					"class" => "settings",
					"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_settings") . $query_string,
					"title" => "Edit this module settings",
				),
			)
		);
	}
}
?>
