<?php
namespace CMSModule\object;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$common_module_path = dirname($this->system_presentation_settings_module_path) . "/common";
		if (!is_dir($common_module_path))
			launch_exception(new \Exception("You must install the Common Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/ObjectUtil.php", "ObjectUtil.php")) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mo_object_type",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "object_type_id",
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
			
			//creating indexes:
			$indexes_to_insert = array();
			$indexes_to_insert[] = array("mo_object_type", array("name"));
			
			//insert default object types
			$date = date("Y-m-d H:i:s");
			$insert_options = array("hard_coded_ai_pk" => true); //used in mssql server
			
			$objects_to_insert = array();
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 1, "name" => "page", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 2, "name" => "module", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 3, "name" => "article", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 4, "name" => "objects_group", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 5, "name" => "action", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 6, "name" => "attachment", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 7, "name" => "comment", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 8, "name" => "menu", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 9, "name" => "tag", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 10, "name" => "user", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 11, "name" => "event", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 12, "name" => "quiz", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 13, "name" => "quiz_question", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 14, "name" => "quiz_answer", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 15, "name" => "menu_item", "created_date" => $date, "modified_date" => $date), $insert_options);
			//$objects_to_insert[] = array("mo_object_type", array("object_type_id" => 16, "name" => "", "created_date" => $date, "modified_date" => $date), $insert_options);
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert, 
				"objects_to_insert" => $objects_to_insert,
			));
		}
	}
}
?>
