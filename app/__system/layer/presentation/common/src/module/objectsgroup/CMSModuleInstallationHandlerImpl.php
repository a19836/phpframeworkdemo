<?php
namespace CMSModule\objectsgroup;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$tag_module_path = dirname($this->system_presentation_settings_module_path) . "/tag";
		if (!is_dir($tag_module_path)) 
			launch_exception(new \Exception("You must install the Tag Module first!"));
		
		$attachment_module_path = dirname($this->system_presentation_settings_module_path) . "/attachment";
		if (!is_dir($attachment_module_path))
			launch_exception(new \Exception("You must install the Attachment Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/ObjectsGroupUtil.php", "ObjectsGroupUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile(array("objects_group", "object_objects_group"))) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mog_objects_group",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "objects_group_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "object",
						"type" => "longblob",
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
				"table_name" => "mog_object_objects_group",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "objects_group_id",
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
			$indexes_to_insert[] = array("mog_object_objects_group", array("objects_group_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mog_object_objects_group", array("object_type_id", "object_id", "group"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
