<?php
namespace CMSModule\menu;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$common_module_path = dirname($this->system_presentation_settings_module_path) . "/common";
		if (!is_dir($common_module_path))
			launch_exception(new \Exception("You must install the Common Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/MenuUtil.php", "MenuUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile( array("menu_group", "menu_item") )) {
			//Preparing DB Tables
			$tables_data = array();
			
			$tables_data[] = array(
				"table_name" => "mmenu_group",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "group_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "name",
						"type" => "varchar",
						"length" => 50,
					),
					array(
						"name" => "created_date",
						"type" => "timestamp",
						"null" => 1,
					),
					array(
						"name" => "modified_date",
						"type" => "timestamp",
						"null" => 1,
					),
				)
			);
		
			$tables_data[] = array(
				"table_name" => "mmenu_item",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "item_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "group_id",
						"type" => "bigint",
						"unsigned" => 1,
					),
					array(
						"name" => "parent_id",
						"type" => "bigint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "label",
						"type" => "varchar",
						"length" => 255,
					),
					array(
						"name" => "title",
						"type" => "varchar",
						"length" => 255,
					),
					array(
						"name" => "class",
						"type" => "varchar",
						"length" => 255,
					),
					array(
						"name" => "url",
						"type" => "text",
					),
					array(
						"name" => "previous_html",
						"type" => "longblob",
					),
					array(
						"name" => "next_html",
						"type" => "longblob",
					),
					array(
						"name" => "order",
						"type" => "smallint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "created_date",
						"type" => "timestamp",
						"null" => 1,
					),
					array(
						"name" => "modified_date",
						"type" => "timestamp",
						"null" => 1,
					),
				)
			);
			
			$tables_data[] = array(
				"table_name" => "mmenu_object_group",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "group_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "object_type_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "object_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "group",
						"type" => "bigint",
						"unsigned" => 1,
						"null" => 1,
					),
					array(
						"name" => "order",
						"type" => "smallint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "created_date",
						"type" => "timestamp",
						"null" => 1,
					),
					array(
						"name" => "modified_date",
						"type" => "timestamp",
						"null" => 1,
					),
				)
			);
			
			//creating indexes:
			$indexes_to_insert = array();
			$indexes_to_insert[] = array("mmenu_object_group", array("group_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mmenu_object_group", array("object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mmenu_item", array("group_id"));
			$indexes_to_insert[] = array("mmenu_item", array("parent_id"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
