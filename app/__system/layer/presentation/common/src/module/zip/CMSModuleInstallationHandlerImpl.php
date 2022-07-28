<?php
namespace CMSModule\zip;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function install() {
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/ZipUtil.php", "ZipUtil.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile(array("zip", "zone", "city", "state"))) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mz_zip",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "zip_id",
						"type" => "varchar",
						"length" => 15,
						"primary_key" => 1,
					),
					array(
						"name" => "country_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
					),
					array(
						"name" => "zone_id",
						"type" => "bigint",
						"unsigned" => 1,
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
				"table_name" => "mz_zone",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "zone_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "city_id",
						"type" => "bigint",
						"unsigned" => 1,
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
				"table_name" => "mz_city",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "city_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "state_id",
						"type" => "bigint",
						"unsigned" => 1,
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
				"table_name" => "mz_state",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "state_id",
						"type" => "bigint",
						"unsigned" => 1,
						"primary_key" => 1,
						"auto_increment" => 1,
					),
					array(
						"name" => "country_id",
						"type" => "bigint",
						"unsigned" => 1,
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
				"table_name" => "mz_country",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "country_id",
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
			$indexes_to_insert[] = array("mz_zip", array("country_id"));
			$indexes_to_insert[] = array("mz_zip", array("zone_id"));
			$indexes_to_insert[] = array("mz_zip", array("country_id", "zone_id"));
			
			$indexes_to_insert[] = array("mz_zone", array("city_id"));
			$indexes_to_insert[] = array("mz_zone", array("name"));
			
			$indexes_to_insert[] = array("mz_city", array("state_id"));
			$indexes_to_insert[] = array("mz_city", array("name"));
			
			$indexes_to_insert[] = array("mz_state", array("country_id"));
			$indexes_to_insert[] = array("mz_state", array("name"));
			
			$indexes_to_insert[] = array("mz_country", array("name"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
