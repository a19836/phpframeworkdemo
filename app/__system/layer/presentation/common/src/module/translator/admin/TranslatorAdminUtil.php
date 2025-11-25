<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */

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
