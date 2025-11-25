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

include $EVC->getModulePath("article/ArticleUtil", $EVC->getCommonProjectName());

class ArticleAdminUtil {
	private $CommonModuleAdminUtil;
	private $object_types;
	
	public function __construct($CommonModuleAdminUtil) {
		$this->CommonModuleAdminUtil = $CommonModuleAdminUtil;
	}
	
	public function getMenuSettings() {
		return array(
			"class" => "",
			"menus" => array(
				array(
					"label" => "Articles",
					"menus" => array(
						array(
							"label" => "Articles List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_articles"),
							"title" => "View List of Articles",
							"class" => "",
						),
						array(
							"label" => "Add Article",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_article"),
							"title" => "Add new Article",
							"class" => "",
						),
						array(
							"label" => "Extra Attributes",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("manage_article_extra_attributes"),
							"title" => "Manage Article Extra Attributes",
							"class" => "",
						),
					)
				),
				array(
					"label" => "Object Articles",
					"class" => "large",
					"menus" => array(
						array(
							"label" => "Object Articles List",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("list_object_articles"),
							"title" => "View List of Object Articles",
							"class" => "",
						),
						array(
							"label" => "Add Object Article",
							"url" => $this->CommonModuleAdminUtil->getAdminFileUrl("edit_object_article"),
							"title" => "Add new Object Article",
							"class" => "",
						),
					)
				),
			)
		);
	}
	
	public function initObjectArticles($brokers) {
		$this->object_types = ObjectUtil::getAllObjectTypes($brokers, true);
		$this->object_types = $this->object_types ? $this->object_types : array();
	}
	
	public function getAvailableObjectTypes() {
		$available_object_types = array();
		foreach ($this->object_types as $object_type) 
			if (isset($object_type["object_type_id"]))
				$available_object_types[ $object_type["object_type_id"] ] = isset($object_type["name"]) ? $object_type["name"] : null;
		
		return $available_object_types;
	}
	
	public function getObjectTypeOptions() {
		$object_type_options = array();
		foreach ($this->object_types as $object_type)
			$object_type_options[] = array(
				"value" => isset($object_type["object_type_id"]) ? $object_type["object_type_id"] : null, 
				"label" => isset($object_type["name"]) ? $object_type["name"] : null
			);
		
		return $object_type_options;
	}
}
?>
