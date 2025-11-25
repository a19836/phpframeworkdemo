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

include $EVC->getModulePath("comment/CommentUtil", $EVC->getCommonProjectName());

class CommentAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Comments",
					"menus" => array(
						array(
							"label" => "Comments List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_comments"),
							"title" => "View List of Comments",
							"class" => "",
						),
						array(
							"label" => "Add Comment",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_comment"),
							"title" => "Add new Comment",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Comments",
					"menus" => array(
						array(
							"label" => "Object Comments List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_comments"),
							"title" => "View List of Object Comments",
							"class" => "",
						),
						array(
							"label" => "Add Object Comment",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_comment"),
							"title" => "Add new Object Comment",
							"class" => "",
						),
					)
				),
			)
		);
	}
}
?>
