<?php
namespace CMSModule\attachment;

include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationHandler");

class CMSModuleInstallationHandlerImpl extends \CMSModuleInstallationHandler {
	
	public function __construct($layers, $module_id, $system_presentation_settings_module_path, $system_presentation_settings_webroot_module_path, $unzipped_module_path = "", $selected_db_driver = false) {
		parent::__construct($layers, $module_id, $system_presentation_settings_module_path, $system_presentation_settings_webroot_module_path, $unzipped_module_path, $selected_db_driver);
		
		$t = $this->presentation_webroot_module_paths ? count($this->presentation_webroot_module_paths) : 0;
		for ($i = 0; $i < $t; $i++)
			$this->reserved_files[] = $this->presentation_webroot_module_paths[$i] . "/files";
	}
	
	public function install() {
		$object_module_path = dirname($this->system_presentation_settings_module_path) . "/object";
		if (!is_dir($object_module_path))
			launch_exception(new \Exception("You must install the Object Module first!"));
		
		if (parent::install() && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/AttachmentUtil.php", "AttachmentUtil.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/AttachmentUI.php", "AttachmentUI.php") && $this->copyUnzippedFileToPresentationModuleFolder("system_settings/AttachmentSettings.php", "AttachmentSettings.php") && $this->createModuleDBDAOUtilFilesFromHibernateFile()) {
			//Preparing DB Tables
			$tables_data = array();
		
			$tables_data[] = array(
				"table_name" => "mat_attachment",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "attachment_id",
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
						"name" => "type",
						"type" => "varchar",
						"length" => 100,
					),
					array(
						"name" => "size",
						"type" => "bigint",
						"unsigned" => 1,
						"null" => 1,
					),
					array(
						"name" => "path",
						"type" => "varchar",
						"length" => 255,
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
				"table_name" => "mat_object_attachment",
				//"drop" => true,
				"attributes" => array(
					array(
						"name" => "attachment_id",
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
			$indexes_to_insert[] = array("mat_object_attachment", array("attachment_id", "object_type_id", "object_id", "group"));
			$indexes_to_insert[] = array("mat_object_attachment", array("object_type_id", "object_id", "group"));
			
			return $this->installDataToDBs($tables_data, array(
				"indexes_to_insert" => $indexes_to_insert,
			));
		}
	}
}
?>
