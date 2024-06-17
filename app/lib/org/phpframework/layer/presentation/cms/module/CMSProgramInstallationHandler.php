<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.xmlfile.XMLFileParser"); include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSProgramInstallationHandler"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSModuleUtil"); include_once get_lib("org.phpframework.layer.presentation.cms.module.CMSProgramExtraTableInstallationUtil"); include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); include_once get_lib("org.phpframework.compression.ZipHandler"); class CMSProgramInstallationHandler extends CMSProgramExtraTableInstallationUtil implements ICMSProgramInstallationHandler { protected $EVC; protected $user_global_variables_file_path; protected $user_beans_folder_path; protected $workflow_paths_id; protected $layer_beans_settings; protected $layers; protected $db_drivers; protected $layers_brokers_settings; protected $vendors; protected $projects; protected $projects_evcs; protected $program_id; protected $unzipped_program_path; protected $user_settings; protected $UserAuthenticationHandler; protected $program_path; protected $presentation_program_paths; protected $presentation_webroot_program_paths; protected $business_logic_program_paths; protected $ibatis_program_paths; protected $hibernate_program_paths; protected $dao_program_path; protected $presentation_modules_paths; protected $business_logic_modules_paths; protected $ibatis_modules_paths; protected $hibernate_modules_paths; protected $reserved_files; protected $messages; protected $errors; public function __construct($v08d9602741, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v2148c9e38a, $v2635bad135, $v9b98e0e818, $v89f919f2ff, $v3c31477509, $v90b50cf52d, $v2ead4e1315, $v17888baf6a, $v0f11a954cd, $pe8b53bc6, $pdf77ee66 = null) { $this->EVC = $v08d9602741; $this->user_global_variables_file_path = $v3d55458bcd; $this->user_beans_folder_path = $v5039a77f9d; $this->workflow_paths_id = $pdb9e96e6; $this->layer_beans_settings = $v2148c9e38a; $this->layers = $v2635bad135; $this->db_drivers = $v9b98e0e818; $this->layers_brokers_settings = $v89f919f2ff; $this->vendors = $v3c31477509; $this->projects = $v90b50cf52d; $this->projects_evcs = $v2ead4e1315; $this->program_id = $v17888baf6a; $this->unzipped_program_path = $v0f11a954cd; $this->user_settings = $pe8b53bc6; $this->UserAuthenticationHandler = $pdf77ee66; $v6e444a3bf3 = self::getUnzippedProgramInfo($v0f11a954cd); $this->program_path = $v6e444a3bf3 && !empty($v6e444a3bf3["path"]) ? $v6e444a3bf3["path"] : $this->program_id; $this->presentation_program_paths = $this->f02718d7284("PresentationLayer"); $this->presentation_webroot_program_paths = $this->f02718d7284("PresentationLayer", true); $this->business_logic_program_paths = $this->f02718d7284("BusinessLogicLayer"); $this->ibatis_program_paths = $this->f02718d7284("IbatisDataAccessLayer"); $this->hibernate_program_paths = $this->f02718d7284("HibernateDataAccessLayer"); $this->dao_program_path = DAO_PATH . $this->program_path . "/"; $this->presentation_modules_paths = $this->mcfd1ac60d7a9("PresentationLayer"); $this->business_logic_modules_paths = $this->mcfd1ac60d7a9("BusinessLogicLayer"); $this->ibatis_modules_paths = $this->mcfd1ac60d7a9("IbatisDataAccessLayer"); $this->hibernate_modules_paths = $this->mcfd1ac60d7a9("HibernateDataAccessLayer"); $this->reserved_files = array(); $this->messages = array(); $this->errors = array(); } public static function createCMSProgramInstallationHandlerObject($v08d9602741, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v2148c9e38a, $layers, $v9b98e0e818, $v89f919f2ff, $v3c31477509, $projects, $projects_evcs, $v17888baf6a, $v0f11a954cd, $pe8b53bc6, $pdf77ee66 = null) { $v228bf46ac1 = null; try { $pf3dc0762 = ""; if ($v0f11a954cd && file_exists($v0f11a954cd . "/CMSProgramInstallationHandlerImpl.php")) $pf3dc0762 = $v0f11a954cd . "/CMSProgramInstallationHandlerImpl.php"; if ($pf3dc0762) { $v3ae55a9a2e = 'CMSProgram\\' . str_replace("/", "\\", str_replace(" ", "_", trim($v17888baf6a))) . '\CMSProgramInstallationHandlerImpl'; if (!class_exists($v3ae55a9a2e)) include_once $pf3dc0762; eval ('$v228bf46ac1 = new ' . $v3ae55a9a2e . '($v08d9602741, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v2148c9e38a, $layers, $v9b98e0e818, $v89f919f2ff, $v3c31477509, $projects, $projects_evcs, $v17888baf6a, $v0f11a954cd, $pe8b53bc6, $pdf77ee66);'); } else $v228bf46ac1 = new CMSProgramInstallationHandler($v08d9602741, $v3d55458bcd, $v5039a77f9d, $pdb9e96e6, $v2148c9e38a, $layers, $v9b98e0e818, $v89f919f2ff, $v3c31477509, $projects, $projects_evcs, $v17888baf6a, $v0f11a954cd, $pe8b53bc6, $pdf77ee66); } catch (Exception $paec2c009) { launch_exception($paec2c009); } return $v228bf46ac1; } public static function unzipProgramFile($v39d1337f82, $pd3e94e4f = null) { if (!$pd3e94e4f) { $pd3e94e4f = self::getTmpFolderPath(); if (!$pd3e94e4f) return false; } if (ZipHandler::unzip($v39d1337f82, $pd3e94e4f)) return $pd3e94e4f; return null; } public static function getUnzippedProgramInfo($v0f11a954cd) { $v7d7b8cf76b = $v0f11a954cd . "/program.xml"; $v872c4849e0 = null; if (file_exists($v7d7b8cf76b)) { $pfb662071 = XMLFileParser::parseXMLFileToArray($v7d7b8cf76b); $pfb662071 = MyXML::complexArrayToBasicArray($pfb662071, array("lower_case_keys" => true, "trim" => true)); $v872c4849e0 = isset($pfb662071["program"]) ? $pfb662071["program"] : null; if (is_array($v872c4849e0) && array_key_exists("with_db", $v872c4849e0)) $v872c4849e0["with_db"] = empty($v872c4849e0["with_db"]) || in_array(strtolower($v872c4849e0["with_db"]), array("false", "null", "none")) ? false : true; } return $v872c4849e0; } public static function getUnzippedProgramSettingsHtml($v17888baf6a, $v0f11a954cd) { $pc0910244 = $v0f11a954cd . "/CMSProgramInstallationHandlerImpl.php"; $pb5d419dd = ""; if (file_exists($pc0910244)) { include $pc0910244; if (method_exists("CMSProgram\\$v17888baf6a\\CMSProgramInstallationHandlerImpl", "getProgramSettingsHtml")) eval("\$pb5d419dd = CMSProgram\\$v17888baf6a\\CMSProgramInstallationHandlerImpl::getProgramSettingsHtml();"); } return $pb5d419dd; } public static function getProgramSettingsHtml() { return null; } public function getStepHtml($v6602edb5ab, $pd9c013d5 = null, $pd65a9318 = null) { return null; } public function installStep($v6602edb5ab, $pd9c013d5 = null, $pd65a9318 = null) { return true; } public function validate() { return true; } public function install($pe8b53bc6 = false) { $this->messages = array(); $this->errors = array("files" => array()); if ($this->validate()) { $pc4aa460d = isset($pe8b53bc6["overwrite"]) ? $pe8b53bc6["overwrite"] : null; $v02f56e366a = array_diff(scandir($this->unzipped_program_path), array('..', '.')); foreach ($v02f56e366a as $pfd248cca) { if ($pfd248cca == "vendor") { if ($this->vendors) foreach ($this->vendors as $v250a1176c9) { if (is_dir($this->unzipped_program_path . "$pfd248cca/$v250a1176c9")) { $v77cb07b555 = in_array(strtolower($v250a1176c9), array("testunit", "dao")) ? $this->program_path . "/" : ""; $v3b0939dc8a = VENDOR_PATH . "$v250a1176c9/$v77cb07b555"; self::copyProgramFolder($v250a1176c9, $this->unzipped_program_path . "$pfd248cca/$v250a1176c9/", $v3b0939dc8a, $pc4aa460d, $this->errors["files"]); } else self::copyProgramFile($pfd248cca, $this->unzipped_program_path . "$pfd248cca/$v250a1176c9", VENDOR_PATH . $v250a1176c9, $pc4aa460d, $this->errors["files"]); } } else if ($pfd248cca == "presentation") { $pe0545708 = array_diff(scandir($this->unzipped_program_path . $pfd248cca), array('..', '.')); if ($this->presentation_program_paths) foreach ($this->presentation_program_paths as $pa2bba2ac) { foreach ($pe0545708 as $v7131a74f0b) { $v77cb07b555 = in_array(strtolower($v7131a74f0b), array("entity", "view", "block", "util")) ? $this->program_path . "/" : ""; $v3b0939dc8a = $pa2bba2ac . (strtolower($v7131a74f0b) == "webroot" ? "" : "src/") . "$v7131a74f0b/$v77cb07b555"; self::copyProgramFolder($pfd248cca, $this->unzipped_program_path . "$pfd248cca/$v7131a74f0b/", $v3b0939dc8a, $pc4aa460d, $this->errors["files"]); } } } else { $pe0cb6cb3 = array(); switch ($pfd248cca) { case "ibatis": $pe0cb6cb3 = $this->ibatis_program_paths; break; case "hibernate": $pe0cb6cb3 = $this->hibernate_program_paths; break; case "businesslogic": $pe0cb6cb3 = $this->business_logic_program_paths; break; } if ($pe0cb6cb3) foreach ($pe0cb6cb3 as $pa2bba2ac) self::copyProgramFolder($pfd248cca, $this->unzipped_program_path . $pfd248cca . "/", $pa2bba2ac, $pc4aa460d, $this->errors["files"]); } } } if (empty($this->errors["files"])) unset($this->errors["files"]); return empty($this->errors); } public function uninstall() { $this->messages = array(); $this->errors = array("files" => array()); $pae9f0543 = $this->getReservedFiles(); $v02f56e366a = array_diff(scandir($this->unzipped_program_path), array('..', '.')); foreach ($v02f56e366a as $pfd248cca) { if ($pfd248cca == "vendor") { if ($this->vendors) foreach ($this->vendors as $v250a1176c9) { if (is_dir($this->unzipped_program_path . "$pfd248cca/$v250a1176c9")) { if (in_array(strtolower($v250a1176c9), array("testunit", "dao"))) { $v3b0939dc8a = VENDOR_PATH . $v250a1176c9 . "/" . $this->program_path . "/"; if (!CacheHandlerUtil::deleteFolder($v3b0939dc8a, true, $pae9f0543)) $this->errors["files"][] = $v3b0939dc8a; } else self::deleteProgramFile($this->unzipped_program_path . "$pfd248cca/$v250a1176c9/", VENDOR_PATH . $v250a1176c9 . "/", $pae9f0543, $this->errors["files"]); } else self::deleteProgramFile($this->unzipped_program_path . "$pfd248cca/$v250a1176c9", VENDOR_PATH . $v250a1176c9, $pae9f0543, $this->errors["files"]); } } else if ($pfd248cca == "presentation") { $pe0545708 = array_diff(scandir($this->unzipped_program_path . $pfd248cca), array('..', '.')); if ($this->presentation_program_paths) foreach ($this->presentation_program_paths as $pa2bba2ac) { foreach ($pe0545708 as $v7131a74f0b) { if (in_array(strtolower($v7131a74f0b), array("entity", "view", "block", "util"))) { $v3b0939dc8a = $pa2bba2ac . "src/$v7131a74f0b/" . $this->program_path . "/"; if (!CacheHandlerUtil::deleteFolder($v3b0939dc8a, true, $pae9f0543)) $this->errors["files"][] = $v3b0939dc8a; } else { $v3b0939dc8a = $pa2bba2ac . (strtolower($v7131a74f0b) == "webroot" ? "" : "src/") . "$v7131a74f0b/"; if (strtolower($v7131a74f0b) == "config") self::deleteProgramConfigFileVars($this->unzipped_program_path . "$pfd248cca/$v7131a74f0b/", $v3b0939dc8a, $pae9f0543, $this->errors["files"]); else { $pae9f0543[] = $v3b0939dc8a; self::deleteProgramFile($this->unzipped_program_path . "$pfd248cca/$v7131a74f0b/", $v3b0939dc8a, $pae9f0543, $this->errors["files"]); } } } } } else { $pe0cb6cb3 = array(); switch ($pfd248cca) { case "ibatis": $pe0cb6cb3 = $this->ibatis_program_paths; break; case "hibernate": $pe0cb6cb3 = $this->hibernate_program_paths; break; case "businesslogic": $pe0cb6cb3 = $this->business_logic_program_paths; break; } if ($pe0cb6cb3) foreach ($pe0cb6cb3 as $pa2bba2ac) if (!CacheHandlerUtil::deleteFolder($pa2bba2ac, true, $pae9f0543)) $this->errors["files"][] = $pa2bba2ac; } } if (empty($this->errors["files"])) unset($this->errors["files"]); return empty($this->errors); } public function addMessage($pffa799aa) { return $this->messages[] = $pffa799aa; } public function getMessages() { return $this->messages; } public function existsMessage($pffa799aa) { return in_array($pffa799aa, $this->messages); } public function addError($v0f9512fda4) { return $this->errors[] = $v0f9512fda4; } public function getErrors() { return $this->errors; } public static function getTmpRootFolderPath() { return (defined("TMP_PATH") ? TMP_PATH : sys_get_temp_dir()) . "/program/"; } public static function getTmpFolderPath($pc5cbb00b = null) { $v4ab372da3a = self::getTmpRootFolderPath(); $v6f181849e4 = $pc5cbb00b ? $v4ab372da3a . $pc5cbb00b : tempnam($v4ab372da3a, ""); if (file_exists($v6f181849e4)) unlink($v6f181849e4); @mkdir($v6f181849e4, 0755); if (is_dir($v6f181849e4)) return $v6f181849e4 . "/"; } protected function areModulesInstalled($pf161ce74) { $v5c1c342594 = true; if ($pf161ce74 && ($this->presentation_modules_paths || $this->business_logic_modules_paths || $this->ibatis_modules_paths || $this->hibernate_modules_paths)) { $pf161ce74 = is_array($pf161ce74) ? $pf161ce74 : array($pf161ce74); foreach ($pf161ce74 as $pcd8c70bc) if (!$this->isModuleInstalled($pcd8c70bc)) $v5c1c342594 = false; } return $v5c1c342594; } protected function isModuleInstalled($pcd8c70bc) { $v5c1c342594 = null; if ($pcd8c70bc) { $v7690194b14 = array( $this->presentation_modules_paths, $this->business_logic_modules_paths, $this->ibatis_modules_paths, $this->hibernate_modules_paths, ); foreach ($v7690194b14 as $v81c953db41) if ($v81c953db41) { if (!isset($v5c1c342594)) $v5c1c342594 = true; foreach ($v81c953db41 as $v7d0332245c) if (!file_exists($v7d0332245c . $pcd8c70bc)) { $v5c1c342594 = false; break; } if (!$v5c1c342594) break; } } return $v5c1c342594 ? true : false; } protected function arePresentationModulesInstalled($pf161ce74) { $v5c1c342594 = true; if ($pf161ce74 && $this->presentation_modules_paths) { $pf161ce74 = is_array($pf161ce74) ? $pf161ce74 : array($pf161ce74); foreach ($pf161ce74 as $pcd8c70bc) if (!$this->isPresentationModuleInstalled($pcd8c70bc)) $v5c1c342594 = false; } return $v5c1c342594; } protected function isPresentationModuleInstalled($pcd8c70bc) { $v5c1c342594 = null; if ($pcd8c70bc && $this->presentation_modules_paths) { $v5c1c342594 = true; foreach ($this->presentation_modules_paths as $v7d0332245c) if (!file_exists($v7d0332245c . $pcd8c70bc)) { $v5c1c342594 = false; break; } } return $v5c1c342594 ? true : false; } protected function existsDBs() { return count($this->db_drivers); } protected function setDBsData($v3c76382d93, &$v806a006822 = null) { $v5c1c342594 = true; if (!$v806a006822) $v806a006822 = array(); if ($this->db_drivers) { foreach ($this->db_drivers as $v872f5b4dbb) { try { $v182f7d984b = $v872f5b4dbb->setData($v3c76382d93); } catch(Exception $paec2c009) { $v182f7d984b = false; if (!$this->errors["dbs"]) $this->errors["dbs"] = array(); $this->errors["dbs"][] = ($paec2c009->problem ? $paec2c009->problem : "") . $paec2c009->getMessage(); } $v806a006822[] = $v182f7d984b; if (!$v182f7d984b) $v5c1c342594 = false; } } else { $pffa799aa = "This installation needs to run some queries in the DB."; if (!$this->existsMessage($pffa799aa)) $this->addMessage($pffa799aa); } return $v5c1c342594; } protected function getDBsData($v3c76382d93) { $pee4c7870 = array(); if ($this->db_drivers) { foreach ($this->db_drivers as $v872f5b4dbb) { try { $pee4c7870[] = $v872f5b4dbb->getData($v3c76382d93); } catch(Exception $paec2c009) { if (!$this->errors["dbs"]) $this->errors["dbs"] = array(); $this->errors["dbs"][] = ($paec2c009->problem ? $paec2c009->problem : "") . $paec2c009->getMessage(); } } } else { $pffa799aa = "This installation needs to run some queries in the DB."; if (!$this->existsMessage($pffa799aa)) $this->addMessage($pffa799aa); } return $pee4c7870; } protected static function copyProgramFolder($pfd248cca, $v98e7bb1b2a, $v569077ab22, $pc4aa460d, &$v8a29987473) { $padd0d6c7 = array_diff(scandir($v98e7bb1b2a), array('..', '.')); foreach ($padd0d6c7 as $v7dffdb5a5b) { $pf1574b73 = $v98e7bb1b2a . $v7dffdb5a5b; $v7eb8f95833 = $v569077ab22 . $v7dffdb5a5b; if (is_dir($pf1574b73)) { $v7959970a41 = file_exists($v7eb8f95833); if (!$v7959970a41) $v7959970a41 = mkdir($v7eb8f95833, 0755, true); if ($v7959970a41) self::copyProgramFolder($pfd248cca, $pf1574b73 . "/", $v7eb8f95833 . "/", $pc4aa460d, $v8a29987473); else $v8a29987473[$pf1574b73] = $v7eb8f95833; } else self::copyProgramFile($pfd248cca, $pf1574b73, $v7eb8f95833, $pc4aa460d, $v8a29987473); } return empty($v8a29987473); } protected static function copyProgramFile($pfd248cca, $pf1574b73, $v7eb8f95833, $pc4aa460d, &$v8a29987473) { $v7959970a41 = file_exists($v7eb8f95833); if ($pfd248cca == "presentation" && strpos($v7eb8f95833, "/src/config/") !== false && $v7959970a41) { $pe99d1557 = trim(file_get_contents($pf1574b73)); $v313c1738ce = trim(file_get_contents($v7eb8f95833)); if ($pc4aa460d) { $v2c5062f1e7 = trim(str_replace(array("<?php", "<?", "?>"), "", $pe99d1557)); $v313c1738ce = str_replace($v2c5062f1e7, "", $v313c1738ce); $v313c1738ce = preg_replace("/\s*\?>/", "\n?>", $v313c1738ce); } $v6490ea3a15 = $v313c1738ce . $pe99d1557; $v6490ea3a15 = preg_replace("/\?>\s*<\?(php|)/", "", $v6490ea3a15); if (file_put_contents($v7eb8f95833, $v6490ea3a15) === false) $v8a29987473[$pf1574b73] = $v7eb8f95833; } else { if ($pc4aa460d) $v7959970a41 = false; else if ($v7959970a41) { $v6bfcc44e7b = pathinfo($v7eb8f95833, PATHINFO_EXTENSION); $v77cb07b555 = $v6bfcc44e7b ? "." . $v6bfcc44e7b : ""; $v9a84a79e2e = $v6bfcc44e7b ? substr($v7eb8f95833, 0, - strlen($v77cb07b555)) : $v7eb8f95833; $v8a4df75785 = 0; do { $v71178be245 = $v9a84a79e2e . "_" . $v8a4df75785 . $v77cb07b555; $v8a4df75785++; } while(file_exists($v71178be245)); $v6f92ce9fa6 = $pfd248cca == "businesslogic" || $pfd248cca == "testunit" || $pfd248cca == "dao" || ($pfd248cca == "presentation" && strpos($v7eb8f95833, "/src/util/") !== false); $v4c704b9c94 = $v6f92ce9fa6 ? PHPCodePrintingHandler::getClassOfFile($v7eb8f95833) : null; if (rename($v7eb8f95833, $v71178be245)) { $v7959970a41 = false; if ($v6f92ce9fa6 && $v4c704b9c94) { $v1335217393 = isset($v4c704b9c94["name"]) ? $v4c704b9c94["name"] : null; $pab6d2d07 = isset($v4c704b9c94["namespace"]) ? $v4c704b9c94["namespace"] : null; $v4f8cf73e03 = PHPCodePrintingHandler::prepareClassNameWithNameSpace($v1335217393, $pab6d2d07); $v60b931bd87 = PHPCodePrintingHandler::prepareClassNameWithNameSpace(pathinfo($v71178be245, PATHINFO_FILENAME), $pab6d2d07); $v5c1c342594 = PHPCodePrintingHandler::renameClassFromFile($v71178be245, $v4f8cf73e03, $v60b931bd87); } } } if ($v7959970a41) $v8a29987473[$pf1574b73] = $v7eb8f95833; else { $v3dad9c047f = dirname($v7eb8f95833); if (!is_dir($v3dad9c047f) && !mkdir($v3dad9c047f, 0755, true)) $v8a29987473[$pf1574b73] = $v7eb8f95833; else if (!copy($pf1574b73, $v7eb8f95833)) $v8a29987473[$pf1574b73] = $v7eb8f95833; } } return empty($v8a29987473); } protected static function deleteProgramConfigFileVars($pf1574b73, $v7eb8f95833, $pae9f0543, &$v8a29987473) { if (file_exists($pf1574b73) && file_exists($v7eb8f95833) && !in_array($v7eb8f95833, $pae9f0543)) { if (is_dir($v7eb8f95833)) { $pb94e48e0 = array_diff(scandir($pf1574b73), array('..', '.')); $v4f493a2904 = array_diff(scandir($v7eb8f95833), array('..', '.')); foreach ($v4f493a2904 as $pa886ab47) if (in_array($pa886ab47, $pb94e48e0) && !in_array($v7eb8f95833 . "/" . $pa886ab47, $pae9f0543) && !self::deleteProgramConfigFileVars($pf1574b73 . "/" . $pa886ab47, $v7eb8f95833 . "/" . $pa886ab47, $pae9f0543, $v8a29987473)) $v8a29987473[] = $v7eb8f95833 . "/" . $pa886ab47; } else { $pe99d1557 = trim(file_get_contents($pf1574b73)); $v313c1738ce = trim(file_get_contents($v7eb8f95833)); $v2c5062f1e7 = trim(str_replace(array("<?php", "<?", "?>"), "", $pe99d1557)); $v313c1738ce = str_replace($v2c5062f1e7, "", $v313c1738ce); $v313c1738ce = preg_replace("/\s*\?>/", "\n?>", $v313c1738ce); if (file_put_contents($v7eb8f95833, $v313c1738ce) === false) $v8a29987473[] = $v7eb8f95833; } } return empty($v8a29987473); } protected static function deleteProgramFile($pf1574b73, $v7eb8f95833, $pae9f0543, &$v8a29987473) { if (file_exists($pf1574b73) && file_exists($v7eb8f95833)) { if (is_dir($v7eb8f95833)) { $pb94e48e0 = array_diff(scandir($pf1574b73), array('..', '.')); $v4f493a2904 = array_diff(scandir($v7eb8f95833), array('..', '.')); foreach ($v4f493a2904 as $pa886ab47) if (in_array($pa886ab47, $pb94e48e0) && !in_array($v7eb8f95833 . "/" . $pa886ab47, $pae9f0543) && !self::deleteProgramFile($pf1574b73 . "/" . $pa886ab47, $v7eb8f95833 . "/" . $pa886ab47, $pae9f0543, $v8a29987473)) $v8a29987473[] = $v7eb8f95833 . "/" . $pa886ab47; if (!in_array($v7eb8f95833, $pae9f0543)) { $v4f493a2904 = array_diff(scandir($v7eb8f95833), array('..', '.')); if (count($v4f493a2904) == 0 && !rmdir($v7eb8f95833)) $v8a29987473[] = $v7eb8f95833; } } else if (!in_array($v7eb8f95833, $pae9f0543) && !unlink($v7eb8f95833)) $v8a29987473[] = $v7eb8f95833; } return empty($v8a29987473); } protected function getReservedFiles() { $pae9f0543 = array(); if ($this->reserved_files) foreach ($this->reserved_files as $v7dffdb5a5b) $pae9f0543[] = file_exists($v7dffdb5a5b) ? realpath($v7dffdb5a5b) : $v7dffdb5a5b; return $pae9f0543; } protected function includeUserUtilClass() { $pcfc24c15 = false; if (class_exists("UserUtil", false)) $pcfc24c15 = true; else if ($this->presentation_program_paths && $this->projects_evcs) foreach ($this->projects_evcs as $v2b2cf4c0eb => $v90b50cf52d) { foreach ($v90b50cf52d as $v93756c94b3 => $EVC) { if ($EVC) { $pdc75ab34 = $EVC->getModulePath("user/UserUtil", $EVC->getCommonProjectName()); if (file_exists($pdc75ab34)) { include_once $pdc75ab34; $pcfc24c15 = true; break; } } } if ($pcfc24c15) break; } return $pcfc24c15; } protected function includeAttachmentUtilClass() { $pcfc24c15 = false; if (class_exists("AttachmentUtil", false)) $pcfc24c15 = true; else if ($this->presentation_program_paths && $this->projects_evcs) foreach ($this->projects_evcs as $v2b2cf4c0eb => $v90b50cf52d) { foreach ($v90b50cf52d as $v93756c94b3 => $EVC) { if ($EVC) { $pcb4d587d = $EVC->getModulePath("attachment/AttachmentUtil", $EVC->getCommonProjectName()); if (file_exists($pcb4d587d)) { include_once $pcb4d587d; $pcfc24c15 = true; break; } } } if ($pcfc24c15) break; } return $pcfc24c15; } protected function includeUserSessionActivitiesHandlerClass() { $pcfc24c15 = false; if (class_exists("UserSessionActivitiesHandler", false)) $pcfc24c15 = true; else if ($this->presentation_program_paths && $this->projects_evcs) foreach ($this->projects_evcs as $v2b2cf4c0eb => $v90b50cf52d) { foreach ($v90b50cf52d as $v93756c94b3 => $EVC) { if ($EVC) { $v5563fccd95 = $EVC->getModulePath("user/UserSessionActivitiesHandler", $EVC->getCommonProjectName()); if (file_exists($v5563fccd95)) { include_once $v5563fccd95; $pcfc24c15 = true; break; } } } if ($pcfc24c15) break; } return $pcfc24c15; } protected function getAvailableAttachmentsFolders() { $pfba52c6c = array(); if ($this->projects_evcs) { $this->includeAttachmentUtilClass(); if (!class_exists("AttachmentUtil", false)){ $this->addError("AttachmentUtil class does NOT exist!"); return false; } foreach ($this->projects_evcs as $v2b2cf4c0eb => $v90b50cf52d) foreach ($v90b50cf52d as $v93756c94b3 => $v188b4f5fa6) if ($v188b4f5fa6) { $v97417c4539 = $v188b4f5fa6->getConfigPath("pre_init_config"); $pfaf08f23 = new PHPVariablesFileHandler(array($this->user_global_variables_file_path, $v97417c4539)); $pfaf08f23->startUserGlobalVariables(); $pd9c6763b = AttachmentUtil::getAttachmentsFolderPath($v188b4f5fa6); if (file_exists($pd9c6763b) && is_dir($pd9c6763b)) $pfba52c6c[] = $pd9c6763b; $pfaf08f23->endUserGlobalVariables(); } $pfba52c6c = array_unique($pfba52c6c); } return $pfba52c6c; } protected function setUserTypePermissionsToPages($v6bbd1726b0, $v2a62bd1b82, $pe60255bd) { $v5c1c342594 = true; if ($this->presentation_program_paths && $this->existsDBs()) { $this->includeUserUtilClass(); if (!class_exists("UserUtil", false)) { $this->addError("UserUtil class does NOT exist!"); return false; } $v8f602836b9 = date("Y-m-d H:i:s"); $v3c76382d93 = ""; foreach ($this->presentation_program_paths as $pa2bba2ac) { $pdd397f0a = $pa2bba2ac . "src/entity/"; foreach ($pe60255bd as $v1b08a89324) { $v9a84a79e2e = $pdd397f0a . $v1b08a89324; if (file_exists($v9a84a79e2e)) { $v3fab52f440 = UserUtil::getObjectIdFromFilePath($v9a84a79e2e); $v3c76382d93 .= "INSERT IGNORE INTO `mu_user_type_activity_object` (`user_type_id`, `activity_id`, `object_type_id`, `object_id`, `created_date`, `modified_date`) VALUES ($v6bbd1726b0,$v2a62bd1b82,1,$v3fab52f440,'$v8f602836b9','$v8f602836b9');"; } } } if ($v3c76382d93 && !$this->setDBsData($v3c76382d93)) { $this->addError("Could not insert user permissions in mu_user_type_activity_object table!"); $v5c1c342594 = false; } } return $v5c1c342594; } protected function setUserTypePermissionsToProgramPages($v6bbd1726b0, $v2a62bd1b82, $pe60255bd) { foreach ($pe60255bd as $pd69fb7d0 => $v1b08a89324) $pe60255bd[$pd69fb7d0] = $this->program_path . "/" . $v1b08a89324; return $this->setUserTypePermissionsToPages($v6bbd1726b0, $v2a62bd1b82, $pe60255bd); } protected function deleteUserTypePermissionsCachedFolderPaths() { $v5c1c342594 = true; $v8cc573e289 = $this->getUserTypePermissionsCachedFolderPaths(); if ($v8cc573e289) foreach ($v8cc573e289 as $pa32be502) if (file_exists($pa32be502) && !CacheHandlerUtil::deleteFolder($pa32be502)) $v5c1c342594 = false; return $v5c1c342594; } protected function getUserTypePermissionsCachedFolderPaths() { $v8cc573e289 = array(); if ($this->projects_evcs) { $this->includeUserSessionActivitiesHandlerClass(); if (!class_exists("UserSessionActivitiesHandler", false)){ $this->addError("UserSessionActivitiesHandler class does NOT exist!"); return false; } foreach ($this->projects_evcs as $v2b2cf4c0eb => $v90b50cf52d) foreach ($v90b50cf52d as $v93756c94b3 => $v188b4f5fa6) if ($v188b4f5fa6) { $v97417c4539 = $v188b4f5fa6->getConfigPath("pre_init_config"); $pfaf08f23 = new PHPVariablesFileHandler(array($this->user_global_variables_file_path, $v97417c4539)); $pfaf08f23->startUserGlobalVariables(); $pe77f177a = $v188b4f5fa6->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler"); $v8cc573e289[] = $pe77f177a->getRootPath() . UserSessionActivitiesHandler::SESSIONS_CACHE_FOLDER_NAME . "/"; $pfaf08f23->endUserGlobalVariables(); } $v8cc573e289 = array_unique($v8cc573e289); } return $v8cc573e289; } protected function copyFolderAttachments($v7b6b2298fa, $pb1a07165) { $v6ee393d9fb = file_exists($v7b6b2298fa) ? array_diff(scandir($v7b6b2298fa), array('..', '.')) : array(); return $this->copyAttachmentsFiles($v7b6b2298fa, $pb1a07165, $v6ee393d9fb); } protected function copyAttachmentsFiles($v7b6b2298fa, $pb1a07165, $v6ee393d9fb) { $v5c1c342594 = true; if ($v6ee393d9fb && $this->presentation_modules_paths) { $pfba52c6c = $this->getAvailableAttachmentsFolders(); if (!$pfba52c6c) { $this->addError("There no attachments folders to copy this program files. Probably the AttachmentUtil class was not loaded!"); return false; } foreach ($pfba52c6c as $pa32be502) if ($pa32be502) { $pa32be502 = "$pa32be502/$pb1a07165/"; if (AttachmentUtil::createAttachmentFileFolder($pa32be502 . "aux")) { foreach ($v6ee393d9fb as $v7dffdb5a5b) { $v92dcc541a8 = $v7b6b2298fa . $v7dffdb5a5b; $pa5b0817e = $pa32be502 . pathinfo($v7dffdb5a5b, PATHINFO_FILENAME); if (!copy($v92dcc541a8, $pa5b0817e)) { $this->addError("Could not copy image '$v7dffdb5a5b' to '$pa32be502'."); $v5c1c342594 = false; } } } else { $this->addError("Could not create '$pa32be502' folder."); $v5c1c342594 = false; } } } return $v5c1c342594; } protected function updateSettingInBlocks($v9158f5ed68, $v638d4e50bf, $pea464301, $v495e7ac602 = null, $pade4502c = true) { $v5c1c342594 = true; if ($this->presentation_program_paths && $v9158f5ed68 && $v638d4e50bf) { $v71be2abc43 = array(); $v3f1b445225 = $pade4502c ? '"' . $pea464301 . '"' : $pea464301; foreach ($this->presentation_program_paths as $pa2bba2ac) if (substr($pa2bba2ac, 0, strlen($v495e7ac602)) == $v495e7ac602) $v71be2abc43[] = $pa2bba2ac; foreach ($v71be2abc43 as $pa2bba2ac) { foreach ($v71be2abc43 as $pa2bba2ac) { $pdd397f0a = $pa2bba2ac . "src/block/" . $this->program_path . "/"; foreach ($v9158f5ed68 as $v1b08a89324) { $v9a84a79e2e = $pdd397f0a . $v1b08a89324; if (file_exists($v9a84a79e2e)) { $v067674f4e4 = file_get_contents($v9a84a79e2e); $pf4e3c708 = preg_replace('/"' . $v638d4e50bf . '"\s*=>[^,]*,/', '"' . $v638d4e50bf . '" => ' . $v3f1b445225 . ',', $v067674f4e4); if ($v067674f4e4 != $pf4e3c708 && file_put_contents($v9a84a79e2e, $pf4e3c708) === false) { $this->addError("Could not update '$v638d4e50bf' setting in '" . str_replace($pa2bba2ac, "", $v9a84a79e2e) . "' file."); $v5c1c342594 = false; } } } } } } return $v5c1c342594; } protected function updateSettingWithSpecificValueInBlocks($v9158f5ed68, $v638d4e50bf, $v951071d591, $v3f1b445225, $v495e7ac602 = null, $pade4502c = true) { $v5c1c342594 = true; if ($this->presentation_program_paths && $v9158f5ed68 && $v638d4e50bf) { $v71be2abc43 = array(); $v3f1b445225 = $pade4502c ? '"' . $v3f1b445225 . '"' : $v3f1b445225; foreach ($this->presentation_program_paths as $pa2bba2ac) if (substr($pa2bba2ac, 0, strlen($v495e7ac602)) == $v495e7ac602) $v71be2abc43[] = $pa2bba2ac; foreach ($v71be2abc43 as $pa2bba2ac) { foreach ($v71be2abc43 as $pa2bba2ac) { $pdd397f0a = $pa2bba2ac . "src/block/" . $this->program_path . "/"; foreach ($v9158f5ed68 as $v1b08a89324) { $v9a84a79e2e = $pdd397f0a . $v1b08a89324; if (file_exists($v9a84a79e2e)) { $v067674f4e4 = file_get_contents($v9a84a79e2e); $v86f9a5564f = $pade4502c ? '("|\')' : ''; $pf4e3c708 = preg_replace('/"' . $v638d4e50bf . '"\s*=>\s*' . $v86f9a5564f . preg_quote($v951071d591, '/') . $v86f9a5564f . ',/', '"' . $v638d4e50bf . '" => ' . $v3f1b445225 . ',', $v067674f4e4); if ($v067674f4e4 != $pf4e3c708 && file_put_contents($v9a84a79e2e, $pf4e3c708) === false) { $this->addError("Could not update '$v638d4e50bf' setting in '" . str_replace($pa2bba2ac, "", $v9a84a79e2e) . "' file."); $v5c1c342594 = false; } } } } } } return $v5c1c342594; } protected function updateBllAndDalAndDBBrokerInBlocks() { $v5c1c342594 = true; $pa32be502 = $this->unzipped_program_path . "/presentation/block/"; if ($this->presentation_program_paths && file_exists($pa32be502)) { $v9158f5ed68 = $this->getFolderPagesList($pa32be502); if ($v9158f5ed68 && is_array($this->layers)) foreach ($this->layers as $v2b2cf4c0eb => $v847a7225e0) if (is_a($v847a7225e0, "PresentationLayer")) { $v7a0994a134 = isset($this->layers_brokers_settings[$v2b2cf4c0eb]) ? $this->layers_brokers_settings[$v2b2cf4c0eb] : null; if (!$v7a0994a134) $v5c1c342594 = false; $pa2bba2ac = $v847a7225e0->getLayerPathSetting(); $v5483bfa973 = isset($v7a0994a134["db_brokers"]) ? $v7a0994a134["db_brokers"] : null; $pe6312535 = array(); if ($v5483bfa973) foreach ($v5483bfa973 as $v664b293eb3) $pe6312535[] = isset($v664b293eb3[0]) ? $v664b293eb3[0] : null; if (!in_array("dbdata", $pe6312535) && !$this->updateSettingWithSpecificValueInBlocks($v9158f5ed68, "db_broker", "dbdata", isset($pe6312535[0]) ? $pe6312535[0] : null, $pa2bba2ac)) $v5c1c342594 = false; $pf864769c = isset($v7a0994a134["ibatis_brokers"]) ? $v7a0994a134["ibatis_brokers"] : null; $v72589e8e58 = array(); if ($pf864769c) foreach ($pf864769c as $v646bcf0490) $v72589e8e58[] = isset($v646bcf0490[0]) ? $v646bcf0490[0] : null; if (!in_array("iorm", $v72589e8e58) && !$this->updateSettingWithSpecificValueInBlocks($v9158f5ed68, "dal_broker", "iorm", isset($v72589e8e58[0]) ? $v72589e8e58[0] : null, $pa2bba2ac)) $v5c1c342594 = false; $paf75a67c = isset($v7a0994a134["hibernate_brokers"]) ? $v7a0994a134["hibernate_brokers"] : null; $pb301aef7 = array(); if ($paf75a67c) foreach ($paf75a67c as $pfde176a8) $pb301aef7[] = isset($pfde176a8[0]) ? $pfde176a8[0] : null; if (!in_array("horm", $pb301aef7) && !$this->updateSettingWithSpecificValueInBlocks($v9158f5ed68, "dal_broker", "horm", isset($pb301aef7[0]) ? $pb301aef7[0] : null, $pa2bba2ac)) $v5c1c342594 = false; $v6e9af47944 = isset($v7a0994a134["business_logic_brokers"]) ? $v7a0994a134["business_logic_brokers"] : null; $pf378c2c2 = array(); if ($v6e9af47944) foreach ($v6e9af47944 as $v316845d7f1) $pf378c2c2[] = isset($v316845d7f1[0]) ? $v316845d7f1[0] : null; if (!in_array("soa", $pf378c2c2) && !$this->updateSettingWithSpecificValueInBlocks($v9158f5ed68, "method_obj", '$EVC->getBroker("soa")', '$EVC->getBroker("' . (isset($pf378c2c2[0]) ? $pf378c2c2[0] : null) . '")', $pa2bba2ac, false)) $v5c1c342594 = false; } } return $v5c1c342594; } protected function getFolderPagesList($pd9c6763b, $v06e5ec04f3 = "") { $pe60255bd = array(); $v6ee393d9fb = array_diff(scandir($pd9c6763b), array('..', '.')); foreach ($v6ee393d9fb as $v1b08a89324) { $v9a84a79e2e = $pd9c6763b . $v1b08a89324; if (is_dir($v9a84a79e2e)) $pe60255bd = array_merge($pe60255bd, $this->getFolderPagesList($pd9c6763b . $v1b08a89324 . "/", $v06e5ec04f3 . $v1b08a89324 . "/")); else $pe60255bd[] = $v06e5ec04f3 . $v1b08a89324; } return $pe60255bd; } protected function copyFilesTaskDiagram($v43554be15d, $pc4aa460d = false) { $v5c1c342594 = true; if ($this->presentation_program_paths && $v43554be15d) { foreach ($this->layers as $v2b2cf4c0eb => $v847a7225e0) if (is_a($v847a7225e0, "PresentationLayer")) { $v4c3f85237e = $this->layer_beans_settings[$v2b2cf4c0eb]; $v14b72a9b5d = $v4c3f85237e && isset($v4c3f85237e[2]) ? $v4c3f85237e[2] : null; if ($v14b72a9b5d && $this->projects && !empty($this->projects[$v2b2cf4c0eb])) if (empty($v847a7225e0->settings["presentation_entities_path"])) launch_exception(new Exception("\$Layer->settings[presentation_entities_path] cannot be empty!")); foreach ($this->projects[$v2b2cf4c0eb] as $v93756c94b3) { $pa32be502 = $v93756c94b3 . "/"; foreach ($v43554be15d as $pdd397f0a => $pf74c1694) { $v5f222f7178 = $this->unzipped_program_path . $pf74c1694; if (file_exists($v5f222f7178)) { $pb0258e5c = $pa32be502 . $v847a7225e0->settings["presentation_entities_path"] . $this->program_path . "/" . $pdd397f0a; $v9a84a79e2e = $v847a7225e0->getLayerPathSetting() . $pb0258e5c; if (is_dir($v9a84a79e2e)) { $pbe261fe2 = $this->f31fd2fb43d($v14b72a9b5d, $pb0258e5c); if ($pbe261fe2 && (!file_exists($pbe261fe2) || $pc4aa460d)) { $v5c2535819f = dirname($pbe261fe2); if (is_dir($v5c2535819f) || mkdir($v5c2535819f, 0775, true)) { if (!copy($v5f222f7178, $pbe261fe2)) $v5c1c342594 = false; } } } } } } } } return $v5c1c342594; } private function f31fd2fb43d($pbf61d117, $v6c7faf569b) { if (!empty($this->workflow_paths_id["presentation_ui"])) { $v6c7faf569b .= substr($v6c7faf569b, -1) == "/" ? "" : "/"; $v31199c28eb = "_{$pbf61d117}_" . md5($v6c7faf569b); $v14b888c9ab = pathinfo($this->workflow_paths_id["presentation_ui"]); $pa32be502 = $v14b888c9ab['dirname'] . "/" . $v14b888c9ab['filename'] . $v31199c28eb . (!empty($v14b888c9ab['extension']) ? "." . $v14b888c9ab['extension'] : ""); return $pa32be502; } return null; } private function f02718d7284($pfd248cca, $pe02267cf = false) { $v57a9807e67 = array(); $v6e444a3bf3 = -1; if (is_array($this->layers)) foreach ($this->layers as $v2b2cf4c0eb => $v847a7225e0) if (is_a($v847a7225e0, $pfd248cca)) { if ($pfd248cca == "PresentationLayer") { if ($this->projects && !empty($this->projects[$v2b2cf4c0eb])) foreach ($this->projects[$v2b2cf4c0eb] as $v93756c94b3) { if ($pe02267cf) $v57a9807e67[] = $v847a7225e0->getLayerPathSetting() . $v93756c94b3 . "/" . $v847a7225e0->settings["presentation_webroot_path"]; else $v57a9807e67[] = $v847a7225e0->getLayerPathSetting() . $v93756c94b3 . "/"; } } else $v57a9807e67[] = $v847a7225e0->getLayerPathSetting() . "program/" . $this->program_path . "/"; } return array_unique($v57a9807e67); } private function mcfd1ac60d7a9($pfd248cca) { $v57a9807e67 = array(); if (is_array($this->layers)) foreach ($this->layers as $v847a7225e0) if (is_a($v847a7225e0, $pfd248cca)) { if ($pfd248cca == "PresentationLayer") { if (empty($v847a7225e0->settings["presentation_modules_path"])) launch_exception(new Exception("\$Layer->settings[presentation_modules_path] cannot be empty!")); $v11506aed93 = $v847a7225e0->getLayerPathSetting() . $v847a7225e0->getCommonProjectName() . "/" . $v847a7225e0->settings["presentation_modules_path"]; } else $v11506aed93 = $v847a7225e0->getLayerPathSetting() . "module/"; if ($v11506aed93) $v57a9807e67[] = $v11506aed93; } return array_unique($v57a9807e67); } } ?>
