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

include_once $EVC->getModulePath("message/MessageUtil", $EVC->getCommonProjectName());

class MessageAdminUtil {
	private $CommonModuleAdminUtil;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Messages",
					"menus" => array(
						array(
							"label" => "Messages List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_messages"),
							"title" => "View List of Messages",
							"class" => "",
						),
						array(
							"label" => "Add Message",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_message"),
							"title" => "Add new Message",
							"class" => "",
						),
					)
				),
			)
		);
	}
}
?>
