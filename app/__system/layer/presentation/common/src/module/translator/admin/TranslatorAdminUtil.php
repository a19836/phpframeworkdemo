<?php
include $EVC->getModulePath("translator/TranslatorUtil", $EVC->getCommonProjectName());

class TranslatorAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Categories",
					"menus" => array(
						array(
							"label" => "Categories List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_categories"),
							"title" => "View List of Categories",
							"class" => "",
						),
						array(
							"label" => "Add Category",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_category"),
							"title" => "Add new Category",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Settings",
					"class" => "settings",
					"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_settings"),
					"title" => "Edit this module settings",
				),
			)
		);
	}
}
?>
