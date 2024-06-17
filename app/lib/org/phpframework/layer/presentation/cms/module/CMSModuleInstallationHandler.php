<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSModuleInstallationHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleUtil"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationBLNamespaceHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleInstallationDBDAOHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSModuleSettingsCacheHandler"); include_once get_lib("org.phpframework.compression.ZipHandler"); class CMSModuleInstallationHandler implements ICMSModuleInstallationHandler { protected $layers; protected $module_id; protected $system_presentation_settings_module_path; protected $system_presentation_settings_webroot_module_path; protected $unzipped_module_path; protected $UserAuthenticationHandler; protected $presentation_module_paths; protected $presentation_webroot_module_paths; protected $business_logic_module_paths; protected $ibatis_module_paths; protected $hibernate_module_paths; protected $dao_module_path; protected $db_drivers; protected $reserved_files; protected $used_db_drivers; protected $messages; public function __construct($v2635bad135, $pcd8c70bc, $v01f92f852f, $v7390898d7f, $v195e6fae4f = "", $pc66a0204 = false, $pdf77ee66 = null) { $this->layers = $v2635bad135; $this->module_id = $pcd8c70bc; $this->system_presentation_settings_module_path = $v01f92f852f; $this->system_presentation_settings_webroot_module_path = $v7390898d7f; $this->unzipped_module_path = $v195e6fae4f; $this->UserAuthenticationHandler = $pdf77ee66; $this->presentation_module_paths = $this->mf73501658ca2($pcd8c70bc, "PresentationLayer"); $this->presentation_webroot_module_paths = $this->mf73501658ca2($pcd8c70bc, "PresentationLayer", true); $this->business_logic_module_paths = $this->mf73501658ca2($pcd8c70bc, "BusinessLogicLayer"); $this->ibatis_module_paths = $this->mf73501658ca2($pcd8c70bc, "IbatisDataAccessLayer"); $this->hibernate_module_paths = $this->mf73501658ca2($pcd8c70bc, "HibernateDataAccessLayer"); $this->dao_module_path = DAO_PATH . "module/$pcd8c70bc/"; $this->db_drivers = $this->me7a8551e073c($pc66a0204); $this->used_db_drivers = array(); $this->reserved_files = array(); $this->messages = array(); } public static function createCMSModuleInstallationHandlerObject($v2635bad135, $pcd8c70bc, $v01f92f852f, $v7390898d7f, $v195e6fae4f = "", $pc66a0204 = false, $pdf77ee66 = null) { $pb0e1c0c6 = null; try { $pf3dc0762 = ""; if ($v195e6fae4f) { if (file_exists($v195e6fae4f . "/CMSModuleInstallationHandlerImpl.php")) $pf3dc0762 = $v195e6fae4f . "/CMSModuleInstallationHandlerImpl.php"; } else if (file_exists($v01f92f852f . "/CMSModuleInstallationHandlerImpl.php")) $pf3dc0762 = $v01f92f852f . "/CMSModuleInstallationHandlerImpl.php"; if ($pf3dc0762) { $v3ae55a9a2e = 'CMSModule\\' . str_replace("/", "\\", str_replace(" ", "_", trim($pcd8c70bc))) . '\CMSModuleInstallationHandlerImpl'; if (!class_exists($v3ae55a9a2e)) include_once $pf3dc0762; eval ('$pb0e1c0c6 = new ' . $v3ae55a9a2e . '($v2635bad135, $pcd8c70bc, $v01f92f852f, $v7390898d7f, $v195e6fae4f, $pc66a0204, $pdf77ee66);'); } else $pb0e1c0c6 = new CMSModuleInstallationHandler($v2635bad135, $pcd8c70bc, $v01f92f852f, $v7390898d7f, $v195e6fae4f, $pc66a0204, $pdf77ee66); } catch (Exception $paec2c009) { launch_exception($paec2c009); } return $pb0e1c0c6; } public static function unzipModuleFile($v39d1337f82, $pd3e94e4f = null) { if (!$pd3e94e4f) { $pd3e94e4f = self::getTmpFolderPath(); if (!$pd3e94e4f) return false; } if (ZipHandler::unzip($v39d1337f82, $pd3e94e4f)) return $pd3e94e4f; return null; } public static function getUnzippedModuleSettings($v195e6fae4f) { $pa68661d5 = $v195e6fae4f . "/settings.xml"; $v872c4849e0 = null; if (file_exists($pa68661d5)) { $pfb662071 = XMLFileParser::parseXMLFileToArray($pa68661d5); $pfb662071 = MyXML::complexArrayToBasicArray($pfb662071, array("lower_case_keys" => true, "trim" => true)); $v872c4849e0 = isset($pfb662071["module"]) ? $pfb662071["module"] : null; } return $v872c4849e0; } public function install() { $this->messages = array(); if ($this->unzipped_module_path && is_dir($this->unzipped_module_path)) { $v5c1c342594 = true; if (is_dir($this->unzipped_module_path . "/system_settings")) { if ($this->system_presentation_settings_module_path && !CMSModuleUtil::copyFolder($this->unzipped_module_path . "/system_settings", $this->system_presentation_settings_module_path)) $v5c1c342594 = false; if (is_dir($this->unzipped_module_path . "/system_settings/webroot")) { if (!$this->deleteFileFromSystemPresentationSettingsModuleFolder("webroot")) $v5c1c342594 = false; if ($this->system_presentation_settings_webroot_module_path && !CMSModuleUtil::copyFolder($this->unzipped_module_path . "/system_settings/webroot", $this->system_presentation_settings_webroot_module_path)) $v5c1c342594 = false; } if ($v5c1c342594 && file_exists($this->unzipped_module_path . "/CMSModuleInstallationHandlerImpl.php") && !$this->copyUnzippedFileToSystemPresentationSettingsModuleFolder("CMSModuleInstallationHandlerImpl.php")) $v5c1c342594 = false; } if (is_dir($this->unzipped_module_path . "/presentation")) { if ($this->presentation_module_paths && !CMSModuleUtil::copyFileToLayers("presentation", "", $this->unzipped_module_path, $this->presentation_module_paths)) $v5c1c342594 = false; if ($v5c1c342594 && is_dir($this->unzipped_module_path . "/presentation/webroot")) { if (!$this->deleteFileFromPresentationModuleFolder("webroot")) $v5c1c342594 = false; if ($v5c1c342594 && $this->presentation_webroot_module_paths && !CMSModuleUtil::copyFileToLayers("presentation/webroot", "", $this->unzipped_module_path, $this->presentation_webroot_module_paths)) $v5c1c342594 = false; } } if (is_dir($this->unzipped_module_path . "/businesslogic") && $this->business_logic_module_paths) { if (CMSModuleUtil::copyFileToLayers("businesslogic", "", $this->unzipped_module_path, $this->business_logic_module_paths)) $v5c1c342594 = CMSModuleInstallationBLNamespaceHandler::updateExtendedCommonServiceCodeInBusinessLogicPHPFiles($this->layers, $this->business_logic_module_paths); else $v5c1c342594 = false; } if (is_dir($this->unzipped_module_path . "/ibatis") && $this->ibatis_module_paths && !CMSModuleUtil::copyFileToLayers("ibatis", "", $this->unzipped_module_path, $this->ibatis_module_paths)) $v5c1c342594 = false; if (is_dir($this->unzipped_module_path . "/hibernate") && $this->hibernate_module_paths && !CMSModuleUtil::copyFileToLayers("hibernate", "", $this->unzipped_module_path, $this->hibernate_module_paths)) $v5c1c342594 = false; if (is_dir($this->unzipped_module_path . "/dao") && $this->dao_module_path && !CMSModuleUtil::copyFolder($this->unzipped_module_path . "/dao", $this->dao_module_path)) $v5c1c342594 = false; return $v5c1c342594; } } public function uninstall($pa4eaf6db = false) { $v5c1c342594 = true; $this->messages = array(); $pae9f0543 = $this->getReservedFiles(); $v7690194b14 = array( $this->presentation_module_paths, $this->presentation_webroot_module_paths, $this->business_logic_module_paths, $this->ibatis_module_paths, $this->hibernate_module_paths, ); foreach ($v7690194b14 as $v81c953db41) if ($v81c953db41) foreach ($v81c953db41 as $v7d0332245c) if (!CMSModuleUtil::deleteFolder($v7d0332245c, $pae9f0543)) $v5c1c342594 = false; if ($v5c1c342594 && $pa4eaf6db) return CMSModuleUtil::deleteFolder($this->dao_module_path, $pae9f0543) && CMSModuleUtil::deleteFolder($this->system_presentation_settings_module_path, $pae9f0543) && CMSModuleUtil::deleteFolder($this->system_presentation_settings_webroot_module_path, $pae9f0543); return $v5c1c342594; } public function isModuleInstalled($v6781d0b8a6 = false) { $v5c1c342594 = null; $v7690194b14 = array( $this->presentation_module_paths, $this->presentation_webroot_module_paths, $this->business_logic_module_paths, $this->ibatis_module_paths, $this->hibernate_module_paths, ); foreach ($v7690194b14 as $v81c953db41) if ($v81c953db41) { if (!isset($v5c1c342594)) $v5c1c342594 = true; foreach ($v81c953db41 as $v7d0332245c) if (!file_exists($v7d0332245c)) { $v5c1c342594 = false; break; } if (!$v5c1c342594) break; } if ($v5c1c342594 && $v6781d0b8a6) $v5c1c342594 = file_exists($this->system_presentation_settings_module_path) && file_exists($this->system_presentation_settings_webroot_module_path); return $v5c1c342594 ? true : false; } public function createModuleDBDAOUtilFilesFromHibernateFile($pc6bfc1d1 = null) { if ($pc6bfc1d1) { $pa58b0566 = array(); $pc6bfc1d1 = is_array($pc6bfc1d1) ? $pc6bfc1d1 : array($pc6bfc1d1); foreach ($pc6bfc1d1 as $v250a1176c9) $pa58b0566[] = $this->unzipped_module_path . "/hibernate/$v250a1176c9.xml"; } else $pa58b0566 = array( $this->unzipped_module_path . "/hibernate/" . $this->module_id . ".xml", $this->unzipped_module_path . "/hibernate/object_" . $this->module_id . ".xml" ); $v9ff9df9b4e = array(); $v5c1c342594 = CMSModuleInstallationDBDAOHandler::createModuleDBDAOUtilFilesFromHibernateFile($pa58b0566, array( "businesslogic" => $this->business_logic_module_paths, "presentation" => $this->presentation_module_paths, "system_settings" => array($this->system_presentation_settings_module_path), ), $this->module_id, $v9ff9df9b4e ); if ($v9ff9df9b4e) foreach ($v9ff9df9b4e as $pffa799aa) $this->addMessage($pffa799aa); return $v5c1c342594; } public function freeModuleCache() { $v5c1c342594 = true; if (is_array($this->layers)) { foreach ($this->layers as $v847a7225e0) { if (is_a($v847a7225e0, "PresentationLayer")) { if ($v847a7225e0->isCacheActive()) { $pd8192d9d = $v847a7225e0->getModuleCachedLayerDirPath(); if ($pd8192d9d) { $pd8192d9d = $pd8192d9d . CMSModuleSettingsCacheHandler::CACHE_DIR_NAME; if (!CMSModuleUtil::deleteFolder($pd8192d9d)) $v5c1c342594 = false; } } } } } return $v5c1c342594; } public function getUsedDBDrivers() { return $this->used_db_drivers; } public function setUsedDBDrivers($v7245a54d44) { return $this->used_db_drivers = $v7245a54d44; } public function areAllDBDriversUsed() { $pbd4de115 = true; if ($this->db_drivers) foreach ($this->db_drivers as $v872f5b4dbb) { $pebb3f429 = $v872f5b4dbb->getOptions(); $v5d01417f00 = md5(serialize($pebb3f429)); if (!$this->isUsedDBDriver($v5d01417f00)) { $pbd4de115 = false; break; } } return $pbd4de115; } public function detectedLayerByClass($v12274018b4) { if (is_array($this->layers)) foreach ($this->layers as $v847a7225e0) if (is_a($v847a7225e0, $v12274018b4)) return true; return false; } public function addMessage($pffa799aa) { return $this->messages[] = $pffa799aa; } public function getMessages() { return $this->messages; } public static function getTmpRootFolderPath() { return (defined("TMP_PATH") ? TMP_PATH : sys_get_temp_dir()) . "/module/"; } public static function getTmpFolderPath($pc5cbb00b = null) { $v4ab372da3a = self::getTmpRootFolderPath(); $v6f181849e4 = $pc5cbb00b ? $v4ab372da3a . $pc5cbb00b : tempnam($v4ab372da3a, ""); if (file_exists($v6f181849e4)) unlink($v6f181849e4); @mkdir($v6f181849e4, 0755); if (is_dir($v6f181849e4)) return $v6f181849e4 . "/"; } protected function addUsedDBDriver($v872f5b4dbb) { $this->used_db_drivers[] = $v872f5b4dbb; } protected function isUsedDBDriver($v872f5b4dbb) { return in_array($v872f5b4dbb, $this->used_db_drivers); } protected function getReservedFiles() { $pae9f0543 = array(); if ($this->reserved_files) foreach ($this->reserved_files as $v7dffdb5a5b) $pae9f0543[] = file_exists($v7dffdb5a5b) ? realpath($v7dffdb5a5b) : $v7dffdb5a5b; return $pae9f0543; } protected function copyUnzippedFileToSystemPresentationSettingsModuleFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFile($this->unzipped_module_path . "/$v92dcc541a8", $this->system_presentation_settings_module_path . "/$pa5b0817e"); } protected function deleteFileFromSystemPresentationSettingsModuleFolder($v92dcc541a8) { return CMSModuleUtil::deleteFiles(array($this->system_presentation_settings_module_path . "/$v92dcc541a8"), $this->getReservedFiles()); } protected function copyUnzippedFileToSystemPresentationSettingsWebrootModuleFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFile($this->unzipped_module_path . "/$v92dcc541a8", $this->system_presentation_settings_webroot_module_path . "/$pa5b0817e"); } protected function deleteFileFromSystemPresentationSettingsWebrootModuleFolder($v92dcc541a8) { return CMSModuleUtil::deleteFiles(array($this->system_presentation_settings_webroot_module_path . "/$v92dcc541a8"), $this->getReservedFiles()); } protected function copyUnzippedFileToPresentationModuleFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFileToLayers($v92dcc541a8, $pa5b0817e, $this->unzipped_module_path, $this->presentation_module_paths); } protected function deleteFileFromPresentationModuleFolder($v92dcc541a8) { return CMSModuleUtil::deleteFileFromLayers($v92dcc541a8, $this->presentation_module_paths, $this->getReservedFiles()); } protected function copyUnzippedFileToPresentationWebrootFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFileToLayers($v92dcc541a8, $pa5b0817e, $this->unzipped_module_path, $this->presentation_webroot_module_paths); } protected function deleteFileFromPresentationWebrootFolder($v92dcc541a8) { return CMSModuleUtil::deleteFileFromLayers($v92dcc541a8, $this->presentation_webroot_module_paths, $this->getReservedFiles()); } protected function copyUnzippedFileToBusinessLogicModuleFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFileToLayers($v92dcc541a8, $pa5b0817e, $this->unzipped_module_path, $this->business_logic_module_paths); } protected function deleteFileFromBusinessLogicModuleFolder($v92dcc541a8) { return CMSModuleUtil::deleteFileFromLayers($v92dcc541a8, $this->business_logic_module_paths, $this->getReservedFiles()); } protected function copyUnzippedFileToIbatisModuleFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFileToLayers($v92dcc541a8, $pa5b0817e, $this->unzipped_module_path, $this->ibatis_module_paths); } protected function deleteFileFromIbatisModuleFolder($v92dcc541a8) { return CMSModuleUtil::deleteFileFromLayers($v92dcc541a8, $this->ibatis_module_paths, $this->getReservedFiles()); } protected function copyUnzippedFileToHibernateModuleFolder($v92dcc541a8, $pa5b0817e = false) { $pa5b0817e = $pa5b0817e ? $pa5b0817e : $v92dcc541a8; return CMSModuleUtil::copyFileToLayers($v92dcc541a8, $pa5b0817e, $this->unzipped_module_path, $this->hibernate_module_paths); } protected function deleteFileFromHibernateModuleFolder($v92dcc541a8) { return CMSModuleUtil::deleteFileFromLayers($v92dcc541a8, $this->hibernate_module_paths, $this->getReservedFiles()); } protected function setDBsData($v3c76382d93) { $v806a006822 = array(); foreach ($this->db_drivers as $v872f5b4dbb) { $v806a006822[] = $v872f5b4dbb->setData($v3c76382d93); } return $v806a006822; } protected function getDBsData($v3c76382d93) { $pee4c7870 = array(); foreach ($this->db_drivers as $v872f5b4dbb) { $pee4c7870[] = $v872f5b4dbb->detData($v3c76382d93); } return $pee4c7870; } protected function installDataToDBs($pd7dcf6a3, $v5d3813882f = false) { $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $v8695370806 = isset($v5d3813882f["indexes_to_insert"]) ? $v5d3813882f["indexes_to_insert"] : null; $pf734e1bd = isset($v5d3813882f["objects_to_insert"]) ? $v5d3813882f["objects_to_insert"] : null; $pd56f9321 = isset($v5d3813882f["sqls"]) ? $v5d3813882f["sqls"] : null; if (!$pd7dcf6a3 && !$v8695370806 && !$pf734e1bd && !$pd56f9321) return true; if (empty($this->db_drivers)) { launch_exception(new Exception("Error: There is no DB defined!")); return false; } $v5c1c342594 = true; $pe7cab44b = null; foreach ($this->db_drivers as $v872f5b4dbb) { $pebb3f429 = $v872f5b4dbb->getOptions(); $v5d01417f00 = md5(serialize($pebb3f429)); if (!$this->isUsedDBDriver($v5d01417f00)) { $this->addUsedDBDriver($v5d01417f00); $v182f7d984b = true; if ($this->UserAuthenticationHandler) foreach ($pd7dcf6a3 as $v87a92bb1ad) if (!empty($v87a92bb1ad["table_name"])) $this->UserAuthenticationHandler->insertReservedDBTableNameIfNotExistsYet(array("name" => $v87a92bb1ad["table_name"])); try { if ($pd7dcf6a3) foreach ($pd7dcf6a3 as $v87a92bb1ad) { if (!empty($v87a92bb1ad["drop"]) && !empty($v87a92bb1ad["table_name"])) { $v3c76382d93 = $v872f5b4dbb->getDropTableStatement($v87a92bb1ad["table_name"]); $v872f5b4dbb->setData($v3c76382d93); } $v3c76382d93 = $v872f5b4dbb->getCreateTableStatement($v87a92bb1ad); if (!$v872f5b4dbb->setData($v3c76382d93)) $v182f7d984b = false; } if ($v182f7d984b && $v8695370806) foreach ($v8695370806 as $v9431798abe) { $v19a136ef7c = isset($v9431798abe[0]) ? $v9431798abe[0] : null; $pb0e3a96b = isset($v9431798abe[1]) ? $v9431798abe[1] : null; $v3d20d4394d = isset($v9431798abe[2]) ? $v9431798abe[2] : null; $v3c76382d93 = $v872f5b4dbb->getAddTableIndexStatement($v19a136ef7c, $pb0e3a96b, $v3d20d4394d); if ($v3c76382d93 && !$v872f5b4dbb->setData($v3c76382d93, $v3d20d4394d)) $v182f7d984b = false; } if ($v182f7d984b && $pf734e1bd) foreach ($pf734e1bd as $pb545807d) { $v448837bb84 = isset($pb545807d[0]) ? $pb545807d[0] : null; $pdc802f90 = isset($pb545807d[1]) ? $pb545807d[1] : null; $pa951c84f = isset($pb545807d[2]) ? $pb545807d[2] : null; if (!$v872f5b4dbb->insertObject($v448837bb84, $pdc802f90, $pa951c84f)) $v182f7d984b = false; } if ($v182f7d984b && $pd56f9321) foreach ($pd56f9321 as $v3c76382d93) if (!$v872f5b4dbb->setData($v3c76382d93)) $v182f7d984b = false; } catch (Exception $paec2c009) { $v182f7d984b = false; $pe7cab44b .= "\n\nDB DRIVER: " . ($pebb3f429["db_name"] ? $pebb3f429["db_name"] : null) . "\n" . $paec2c009->problem . $paec2c009->getMessage(); } if (!$v182f7d984b) $v5c1c342594 = false; } } if ($pe7cab44b) launch_exception(new Exception($pe7cab44b)); return $v5c1c342594; } private function mf73501658ca2($pcd8c70bc, $pfd248cca, $pe02267cf = false) { $v57a9807e67 = array(); if (is_array($this->layers)) foreach ($this->layers as $v847a7225e0) if (is_a($v847a7225e0, $pfd248cca)) { if ($pfd248cca == "PresentationLayer") { if ($pe02267cf) { if (empty($v847a7225e0->settings["presentation_webroot_path"])) launch_exception(new Exception("\$Layer->settings[presentation_webroot_path] cannot be empty!")); $v11506aed93 = $v847a7225e0->getLayerPathSetting() . $v847a7225e0->getCommonProjectName() . "/" . $v847a7225e0->settings["presentation_webroot_path"] . "module/$pcd8c70bc"; } else { if (empty($v847a7225e0->settings["presentation_modules_path"])) launch_exception(new Exception("\$Layer->settings[presentation_modules_path] cannot be empty!")); $v11506aed93 = $v847a7225e0->getLayerPathSetting() . $v847a7225e0->getCommonProjectName() . "/" . $v847a7225e0->settings["presentation_modules_path"] . $pcd8c70bc; } } else $v11506aed93 = $v847a7225e0->getLayerPathSetting() . "module/$pcd8c70bc"; if ($v11506aed93) $v57a9807e67[] = $v11506aed93; } return array_unique($v57a9807e67); } private function me7a8551e073c($pc66a0204 = false) { $v9b98e0e818 = array(); if (is_array($this->layers)) foreach ($this->layers as $v847a7225e0) if (is_a($v847a7225e0, "DBLayer")) { $pc189ad81 = $v847a7225e0->getBrokers(); foreach ($pc189ad81 as $v2b2cf4c0eb => $v4de58f13f0) if ((!$pc66a0204 || $v2b2cf4c0eb == $pc66a0204) && is_a($v4de58f13f0, "DB") && !in_array($v4de58f13f0, $v9b98e0e818)) $v9b98e0e818[] = $v4de58f13f0; } return $v9b98e0e818; } } ?>
