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

include_once $EVC->getModulePath("object/ObjectUtil", $EVC->getCommonProjectName());

class ObjectAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Object Types",
					"menus" => array(
						array(
							"label" => "Object Types List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_types"),
							"title" => "View List of Object Types",
							"class" => "",
						),
						array(
							"label" => "Add Object Type",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_type"),
							"title" => "Add new Object Type",
							"class" => "",
						),
					)
				),
			)
		);
	}
}
?>
