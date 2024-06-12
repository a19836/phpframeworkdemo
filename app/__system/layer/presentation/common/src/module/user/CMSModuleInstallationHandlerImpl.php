<?php
namespace CMSModule\user;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		$attachment_module_path = dirname($this->system_presentation_settings_module_path) . "/attachment";
		if (!is_dir($attachment_module_path)) 
			launch_exception(new \Exception("You must install the Attachment Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/UserUtil.php", "UserUtil.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/UserSettings.php", "UserSettings.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/ManageUserTypeActivityObjectsUtil.php", "ManageUserTypeActivityObjectsUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile(array("user", "user_type_activity_object"))) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mu_user",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "username",
						"type" => "varchar",
						"length" => 50,
					),
					array(
						"name" => "password",
						"type" => "varchar",
						"length" => 255,
					),
					array(
						"name" => "email",
						"type" => "varchar",
						"length" => 100,
						"null" => 1,
					),
					array(
						"name" => "name",
						"type" => "varchar",
						"length" => 50,
						"null" => 1,
					),
					array(
						"name" => "active",
						"type" => "tinyint",
						"length" => 1,
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "security_question_1",
						"type" => "varchar",
						"length" => "255",
						"null" => 1,
					),
					array(
						"name" => "security_answer_1",
						"type" => "varchar",
						"length" => "255",
						"null" => 1,
					),
					array(
						"name" => "security_question_2",
						"type" => "varchar",
						"length" => "255",
						"null" => 1,
					),
					array(
						"name" => "security_answer_2",
						"type" => "varchar",
						"length" => "255",
						"null" => 1,
					),
					array(
						"name" => "security_question_3",
						"type" => "varchar",
						"length" => "255",
						"null" => 1,
					),
					array(
						"name" => "security_answer_3",
						"type" => "varchar",
						"length" => "255",
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
				"table_name" => "mu_user_type",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_type_id",
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
				"table_name" => "mu_user_user_type",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "user_type_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
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
				"table_name" => "mu_activity",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "activity_id",
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
				"table_name" => "mu_user_type_activity_object",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_type_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "activity_id",
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
				"table_name" => "mu_user_activity_object",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "thread_id",
						"type" => "varchar",
						"length" => 20,
						"primary_key" => 1,
					),
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "activity_id",
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
						"name" => "extra",
						"type" => "longblob",
						"null" => 1,
						"comment" => "This is to write some other info like the correspondent parameters, etc...",
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
				"table_name" => "mu_user_session",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "username",
						"type" => "varchar",
						"length" => 50,
						"primary_key" => 1,
					),
					array(
						"name" => "environment_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "session_id",
						"type" => "varchar",
						"length" => 200,
						"unique" => 1,
					),
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
					),
					array(
						"name" => "logged_status",
						"type" => "tinyint",
						"length" => 1,
						"default" => "0",
					),
					array(
						"name" => "login_time",
						"type" => "bigint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "login_ip",
						"type" => "varchar",
						"length" => 100,
					),
					array(
						"name" => "logout_time",
						"type" => "bigint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "logout_ip",
						"type" => "varchar",
						"length" => 100,
						"default" => "",
					),
					array(
						"name" => "failed_login_attempts",
						"type" => "tinyint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "failed_login_time",
						"type" => "bigint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "failed_login_ip",
						"type" => "varchar",
						"length" => 100,
					),
					array(
						"name" => "captcha",
						"type" => "varchar",
						"length" => 50,
						"default" => "",
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
				"table_name" => "mu_user_environment",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "environment_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
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
				"table_name" => "mu_object_user",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_id",
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
			
			$tables_data[] = array(
				"table_name" => "mu_external_user",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "external_user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"null" => 1,
					),
					array(
						"name" => "external_type_id",
						"type" => "tinyint",
						"unsigned" => 1,
						"default" => "0",
						"comment" => "0: auth0"
					),
					array(
						"name" => "social_network_type",
						"type" => "varchar",
						"length" => 255,
						"default" => "",
					),
					array(
						"name" => "social_network_user_id",
						"type" => "varchar",
						"length" => 255,
						"default" => "",
					),
					array(
						"name" => "token_1",
						"type" => "longblob",
						"null" => 1,
						"comment" => "auth0 code"
					),
					array(
						"name" => "token_2",
						"type" => "longblob",
						"null" => 1,
						"comment" => "auth0 state"
					),
					array(
						"name" => "token_3",
						"type" => "longblob",
						"null" => 1,
						"comment" => "some other token"
					),
					array(
						"name" => "data",
						"type" => "longblob",
						"null" => 1,
						"comment" => "returned json user data"
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
			$indexes_to_insert[] = array("mu_activity", array("name"));
			$indexes_to_insert[] = array("mu_user_type", array("name"));
			$indexes_to_insert[] = array("mu_object_user", array("user_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mu_object_user", array("object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mu_user_environment", array("environment_id"));
			$indexes_to_insert[] = array("mu_user_user_type", array("user_type_id"));
			$indexes_to_insert[] = array("mu_user_activity_object", array("user_id"));
			$indexes_to_insert[] = array("mu_user_activity_object", array("activity_id"));
			$indexes_to_insert[] = array("mu_user_activity_object", array("object_type_id", "object_id"));
			$indexes_to_insert[] = array("mu_user_activity_object", array("user_id", "activity_id", "object_type_id", "object_id"));
			$indexes_to_insert[] = array("mu_user_activity_object", array("activity_id", "object_type_id", "object_id"));
			$indexes_to_insert[] = array("mu_user_type_activity_object", array("user_type_id"));
			$indexes_to_insert[] = array("mu_user_type_activity_object", array("activity_id"));
			$indexes_to_insert[] = array("mu_user_type_activity_object", array("object_type_id", "object_id"));
			$indexes_to_insert[] = array("mu_user_type_activity_object", array("activity_id", "object_type_id", "object_id"));
			$indexes_to_insert[] = array("mu_user_session", array("environment_id"));
			$indexes_to_insert[] = array("mu_user_session", array("user_id"));
			$indexes_to_insert[] = array("mu_user", array("username", "password"));
			
			//insert default user types
			$date = date("Y-m-d H:i:s");
			$insert_options = array("hard_coded_ai_pk" => true); //used in mssql server
			$objects_to_insert = array();
			
			$objects_to_insert[] = array("mu_user_type", array("user_type_id" => 1, "name" => "public", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mu_user_type", array("user_type_id" => 2, "name" => "admin", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mu_user_type", array("user_type_id" => 3, "name" => "regular", "created_date" => $date, "modified_date" => $date), $insert_options);
			
			//insert default activity
			$objects_to_insert[] = array("mu_activity", array("activity_id" => 1, "name" => "access", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mu_activity", array("activity_id" => 2, "name" => "write", "created_date" => $date, "modified_date" => $date), $insert_options);
			$objects_to_insert[] = array("mu_activity", array("activity_id" => 3, "name" => "delete", "created_date" => $date, "modified_date" => $date), $insert_options);
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert, 
				"objects_to_insert" => $objects_to_insert,
			));
		}
	}
}
?>
