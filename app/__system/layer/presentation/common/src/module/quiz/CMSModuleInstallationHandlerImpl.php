<?php
namespace CMSModule\quiz;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		$user_module_path = dirname($this->system_presentation_settings_module_path) . "/user";
		if (!is_dir($user_module_path))
			launch_exception(new \Exception("You must install the User Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/QuizUtil.php", "QuizUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile(array("answer", "question", "user_answer"))) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mq_question",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "question_id",
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
				"table_name" => "mq_answer",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "answer_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "question_id",
						"type" => "bigint",
						"unsigned" => 1,
					),
					array(
						"name" => "title",
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
						"name" => "value",
						"type" => "smallint",
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
		
			$tables_data[] = array(
				"table_name" => "mq_user_answer",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "user_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "answer_id",
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
				"table_name" => "mq_object_question",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "question_id",
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
			$indexes_to_insert[] = array("mq_answer", array("question_id"));
			$indexes_to_insert[] = array("mq_object_question", array("question_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mq_object_question", array("object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mq_user_answer", array("answer_id"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
