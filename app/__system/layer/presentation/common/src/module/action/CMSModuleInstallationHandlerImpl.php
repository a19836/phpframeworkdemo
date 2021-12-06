<?php
namespace CMSModule\action;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		$user_module_path = dirname($this->system_presentation_settings_module_path) . "/user";
		if (!is_dir($user_module_path)) 
			launch_exception(new \Exception("You must install the User Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/ActionUtil.php", "ActionUtil.php")) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mact_action",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "action_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "name",
						"type" => "varchar",
						"length" => 100,
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
				"table_name" => "mact_user_action",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "action_id",
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
						"name" => "time",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "value",
						"type" => "varchar",
						"length" => 100,
						"null" => 1,
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
			$indexes_to_insert[] = array("mact_user_action", array("action_id"));
			$indexes_to_insert[] = array("mact_user_action", array("object_type_id", "object_id"));
			$indexes_to_insert[] = array("mact_user_action", array("action_id", "object_type_id", "object_id"));
			
			//insert default actions
			$date = date("Y-m-d H:i:s");
			$insert_options = array("hard_coded_ai_pk" => true); //used in mssql server
			
			$objects_to_insert = array();
			$objects_to_insert[] = array("mact_action", array("action_id" => 1, "name" => "like", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mact_action", array("action_id" => 2, "name" => "unlike", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mact_action", array("action_id" => 3, "name" => "rating", "created_date" => $date, "modified_date" => $date), $insert_options);
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert, 
				"objects_to_insert" => $objects_to_insert, 
			));
		}
	}
}
?>
