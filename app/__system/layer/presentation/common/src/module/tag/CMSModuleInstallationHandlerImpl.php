<?php
namespace CMSModule\tag;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/TagUtil.php", "TagUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile("tag")) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mt_tag",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "tag_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "tag",
						"type" => "varchar",
						"length" => 200,
						"null" => 0,
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
				"table_name" => "mt_object_tag",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "tag_id",
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
			
			$sqls = array();
			
			//creating indexes:
			$indexes_to_insert = array();
			$indexes_to_insert[] = array("mt_tag", array("tag_id", "tag"));
			$indexes_to_insert[] = array("mt_tag", array("tag"));
			$indexes_to_insert[] = array("mt_object_tag", array("tag_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mt_object_tag", array("object_type_id", "object_id", "group"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
