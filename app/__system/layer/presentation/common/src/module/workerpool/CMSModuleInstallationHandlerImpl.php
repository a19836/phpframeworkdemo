<?php
namespace CMSModule\workerpool;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/WorkerPoolUtil.php", "WorkerPoolUtil.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/WorkerPoolSettings.php", "WorkerPoolSettings.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile("worker")) {
			//Preparing DB Tables
			$tables_data = array();
			
			$tables_data[] = array(
				"table_name" => "mwp_worker",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "worker_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "class",
						"type" => "varchar",
						"length" => 2048,
						"null" => 1,
					),
					array(
						"name" => "args",
						"type" => "longblob",
						"null" => 1,
					),
					array(
						"name" => "status",
						"type" => "tinyint",
						"unsigned" => 1,
						"default" => "0",
						"comment" => "0: to parse; 1: being parsed; 2: parsed/closed; 3: failed",
					),
					array(
						"name" => "thread_id",
						"type" => "varchar",
						"length" => 100,
						"null" => 1,
					),
					array(
						"name" => "begin_time",
						"type" => "bigint",
						"null" => 1,
					),
					array(
						"name" => "end_time",
						"type" => "bigint",
						"null" => 1,
					),
					array(
						"name" => "failed_attempts",
						"type" => "tinyint",
						"unsigned" => 1,
						"default" => "0",
					),
					array(
						"name" => "description",
						"type" => "longblob",
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
			$indexes_to_insert[] = array("mwp_worker", array("status", "begin_time", "failed_attempts"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
