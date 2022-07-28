<?php
namespace CMSModule\event;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		$tag_module_path = dirname($this->system_presentation_settings_module_path) . "/tag";
		if (!is_dir($tag_module_path))
			launch_exception(new \Exception("You must install the Tag Module first!"));
		
		$attachment_module_path = dirname($this->system_presentation_settings_module_path) . "/attachment";
		if (!is_dir($attachment_module_path))
			launch_exception(new \Exception("You must install the Attachment Module first!"));
		
		$comment_module_path = dirname($this->system_presentation_settings_module_path) . "/comment";
		if (!is_dir($comment_module_path)) 
			launch_exception(new \Exception("You must install the Comment Module first!"));
		
		$action_module_path = dirname($this->system_presentation_settings_module_path) . "/action";
		if (!is_dir($action_module_path))
			launch_exception(new \Exception("You must install the Action Module first!"));
		
		$user_module_path = dirname($this->system_presentation_settings_module_path) . "/user";
		if (!is_dir($user_module_path))
			launch_exception(new \Exception("You must install the User Module first!"));
		
		$zip_module_path = dirname($this->system_presentation_settings_module_path) . "/zip";
		if (!is_dir($zip_module_path))
			launch_exception(new \Exception("You must install the Zip Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/EventUtil.php", "EventUtil.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/EventSettings.php", "EventSettings.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile()) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "me_event",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "event_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "title",
						"type" => "varchar",
						"length" => 1000,
						"null" => 1,
					),
					array(
						"name" => "sub_title",
						"type" => "varchar",
						"length" => 1000,
						"null" => 1,
					),
					array(
						"name" => "description",
						"type" => "longblob",
						"null" => 1,
					),
					array(
						"name" => "published",
						"type" => "tinyint",
						"unsigned" => 1,
						"length" => 1,
						"null" => 0,
						"default" => 0,
					),
					array(
						"name" => "photo_id",
						"type" => "bigint",
						"unsigned" => 1,
						"null" => 1,
					),
					array(
						"name" => "allow_comments",
						"type" => "tinyint",
						"unsigned" => 1,
						"length" => 1,
						"null" => 0,
						"default" => 0,
					),
					array(
						"name" => "address",
						"type" => "varchar",
						"length" => 100,
						"null" => 1,
					),
					array(
						"name" => "zip_id",
						"type" => "varchar",
						"length" => 15,
						"null" => 1,
					),
					array(
						"name" => "locality",
						"type" => "varchar",
						"length" => 50,
						"null" => 1,
					),
					array(
						"name" => "country_id",
						"type" => "bigint",
						"unsigned" => 1,
						"null" => 1,
					),
					array(
						"name" => "latitude",
						"type" => "coordinate",
						"null" => 1,
					),
					array(
						"name" => "longitude",
						"type" => "coordinate",
						"null" => 1,
					),
					array(
						"name" => "begin_date",
						"type" => "timestamp",
						"null" => 1,
					),
					array(
						"name" => "end_date",
						"type" => "timestamp",
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
			
			$tables_data[] = array(
				"table_name" => "me_object_event",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "event_id",
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
			$indexes_to_insert[] = array("me_object_event", array("event_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("me_object_event", array("object_type_id", "object_id", "group"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
