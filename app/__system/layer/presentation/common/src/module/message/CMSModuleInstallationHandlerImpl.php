<?php
namespace CMSModule\message;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$common_module_path = dirname($this->system_presentation_settings_module_path) . "/common";
		if (!is_dir($common_module_path))
			launch_exception(new \Exception("You must install the Common Module first!"));
		
		$user_module_path = dirname($this->system_presentation_settings_module_path) . "/user";
		if (!is_dir($user_module_path))
			launch_exception(new \Exception("You must install the User Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/MessageUtil.php", "MessageUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile("message")) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mmsg_message",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "message_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "from_user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "to_user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "subject",
						"type" => "varchar",
						"length" => 255,
						"null" => 1,
					),
					array(
						"name" => "content",
						"type" => "longblob",
						"null" => 1,
					),
					array(
						"name" => "seen_date",
						"type" => "timestamp",
						"null" => 1,
					),
					array(
						"name" => "from_user_status",
						"type" => "tinyint",
						"unsigned" => 1,
						"default" => 1,
					),
					array(
						"name" => "to_user_status",
						"type" => "tinyint",
						"unsigned" => 1,
						"default" => 1,
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
			$indexes_to_insert[] = array("mmsg_message", array("from_user_id", "to_user_id"));
			$indexes_to_insert[] = array("mmsg_message", array("from_user_status", "to_user_status"));
			$indexes_to_insert[] = array("mmsg_message", array("from_user_id", "to_user_id", "from_user_status"));
			$indexes_to_insert[] = array("mmsg_message", array("from_user_id", "to_user_id", "to_user_status"));
			$indexes_to_insert[] = array("mmsg_message", array("from_user_id", "from_user_status"));
			$indexes_to_insert[] = array("mmsg_message", array("to_user_id", "to_user_status"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
