<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class CMSProgramExtraTableInstallationUtil { const EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX = "extra"; public function renameProjectsTableExtraAttributesCallsInPresentationFilesBasedInDefaultDBDriver($v566db8f5a9) { $v5c1c342594 = true; if ($v566db8f5a9 && $this->projects_evcs) { $v6b817be097 = $this->unzipped_program_path . "/presentation/entity/"; $v5ec1fba56e = $this->getFolderPagesList($v6b817be097); $v4876e08d4b = $this->unzipped_program_path . "/presentation/block/"; $v7c4347e590 = $this->getFolderPagesList($v4876e08d4b); $v5de0f30e4f = $this->unzipped_program_path . "/presentation/util/"; $v8184829389 = $this->getFolderPagesList($v5de0f30e4f); foreach ($this->projects_evcs as $v2b2cf4c0eb => $v90b50cf52d) foreach ($v90b50cf52d as $v93756c94b3 => $v188b4f5fa6) if ($v188b4f5fa6) { $v97417c4539 = $v188b4f5fa6->getConfigPath("pre_init_config"); $pfaf08f23 = new PHPVariablesFileHandler(array($this->user_global_variables_file_path, $v97417c4539)); $pfaf08f23->startUserGlobalVariables(); $v5ba36af525 = !empty($GLOBALS["default_db_driver"]) ? $GLOBALS["default_db_driver"] : "default"; $pfaf08f23->endUserGlobalVariables(); $v74b9a5d485 = $v188b4f5fa6->getEntitiesPath() . $this->program_id . "/"; $v2acde44729 = $v188b4f5fa6->getBlocksPath() . $this->program_id . "/"; $pd9b88de2 = $v188b4f5fa6->getUtilsPath() . $this->program_id . "/"; if (!$this->renameProjectTableExtraAttributesCallsInPresentationFiles($v566db8f5a9, $v5ba36af525, $v74b9a5d485, $v5ec1fba56e) || !$this->renameProjectTableExtraAttributesCallsInPresentationFiles($v566db8f5a9, $v5ba36af525, $v2acde44729, $v7c4347e590) || !$this->renameProjectTableExtraAttributesCallsInPresentationFiles($v566db8f5a9, $v5ba36af525, $pd9b88de2, $v8184829389)) $v5c1c342594 = false; } } return $v5c1c342594; } public function renameProjectTableExtraAttributesCallsInPresentationFiles($v566db8f5a9, $v5ba36af525, $v4400db82aa, $pec266fee) { $v5c1c342594 = true; if ($v566db8f5a9 && $v5ba36af525 && $pec266fee) { $pf5bf6141 = $v566db8f5a9 . "_" . self::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX; foreach ($pec266fee as $v1b08a89324) { $v9a84a79e2e = $v4400db82aa . $v1b08a89324; if (pathinfo($v1b08a89324, PATHINFO_EXTENSION) == "php" && file_exists($v9a84a79e2e)) if (!self::updateOldExtraAttributesTableCode($v9a84a79e2e, $pf5bf6141, $v5ba36af525 . "_" . $pf5bf6141, true)) $v5c1c342594 = false; } } return $v5c1c342594; } public function copyTableExtraAttributesSettings($pcd8c70bc, $pc661dc6b, $v566db8f5a9, $v4d7b07c237) { $v5c1c342594 = true; $pc20b779e = $pc661dc6b . "_" . self::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX; $pf5bf6141 = $v566db8f5a9 . "_" . self::EXTRA_ATTRIBUTES_TABLE_NAME_SUFFIX; if (!empty($this->user_settings["db_drivers"])) { if ($this->presentation_modules_paths && !empty($v4d7b07c237["attributes_settings"])) { $pdb3b4b1e = $v4d7b07c237["attributes_settings"]; $v6ada726660 = "{$pf5bf6141}_attributes_settings.php"; foreach ($this->presentation_modules_paths as $pa32be502) { $v11506aed93 = $pa32be502 . $pcd8c70bc . "/"; $v4c9cc78358 = str_replace(LAYER_PATH, "", $v11506aed93); if (!is_dir($v11506aed93)) { $this->addError(ucfirst($pcd8c70bc) . " module is not installed in '$v4c9cc78358' layer!"); $v5c1c342594 = false; } else { foreach ($this->user_settings["db_drivers"] as $v5ba36af525) { $pf68e0f83 = $v11506aed93 . $v5ba36af525 . "_$v6ada726660"; if (file_exists($pf68e0f83)) { if (!self::f62e0ecbcf5($pf68e0f83, $pdb3b4b1e)) { $this->addError("Could not merge {$pf5bf6141}_attributes_settings.php in '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } } else if (!copy($pdb3b4b1e, $pf68e0f83)) { $this->addError("Could not copy {$pf5bf6141}_attributes_settings.php to '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } } } } } if ($this->business_logic_modules_paths && !empty($v4d7b07c237["generic_business_logic_service"])) { $pdb3b4b1e = $v4d7b07c237["generic_business_logic_service"]; $pf5dc5cd4 = pathinfo($pdb3b4b1e, PATHINFO_FILENAME); $v6ada726660 = self::f4f4f9f34a5($pf5bf6141) . "Service.php"; foreach ($this->business_logic_modules_paths as $pa32be502) { $v11506aed93 = $pa32be502 . $pcd8c70bc . "/"; $v4c9cc78358 = str_replace(LAYER_PATH, "", $v11506aed93); if (!is_dir($v11506aed93)) { $this->addError(ucfirst($pcd8c70bc) . " module is not installed in '$v4c9cc78358' layer!"); $v5c1c342594 = false; } else { $pf68e0f83 = $v11506aed93 . $v6ada726660; $v89b96cda4a = pathinfo($v6ada726660, PATHINFO_FILENAME); $v7959970a41 = file_exists($pf68e0f83); if ($v7959970a41 && !self::me32868209821($pf68e0f83, $pdb3b4b1e, null, null, $v105c74e20f)) { $this->addError("Could not merge $v6ada726660 in '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 && !copy($pdb3b4b1e, $pf68e0f83)) { $this->addError("Could not copy $v6ada726660 to '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 || $v105c74e20f) { if (!self::updateBusinessLogicServiceClassNameInFile($pf68e0f83, $pf5dc5cd4, $v89b96cda4a)) { $this->addError("Could not rename business logic class name in '" . $v4c9cc78358 . $v6ada726660 . "'!"); $v5c1c342594 = false; } } } } } if ($this->business_logic_modules_paths && !empty($v4d7b07c237["business_logic_service"])) { $pdb3b4b1e = $v4d7b07c237["business_logic_service"]; $pf5dc5cd4 = self::f4f4f9f34a5($pf5bf6141) . "Service"; foreach ($this->user_settings["db_drivers"] as $v5ba36af525) { $v6ada726660 = self::f4f4f9f34a5($v5ba36af525 . "_" . $pf5bf6141) . "Service.php"; foreach ($this->business_logic_modules_paths as $pa32be502) { $v11506aed93 = $pa32be502 . $pcd8c70bc . "/"; $v4c9cc78358 = str_replace(LAYER_PATH, "", $v11506aed93); if (!is_dir($v11506aed93)) { $this->addError(ucfirst($pcd8c70bc) . " module is not installed in '$v4c9cc78358' layer!"); $v5c1c342594 = false; } else { $pf68e0f83 = $v11506aed93 . $v6ada726660; $v89b96cda4a = pathinfo($v6ada726660, PATHINFO_FILENAME); $v7959970a41 = file_exists($pf68e0f83); if ($v7959970a41 && !self::me32868209821($pf68e0f83, $pdb3b4b1e, $pf5bf6141, $v5ba36af525, $v105c74e20f)) { $this->addError("Could not merge $v6ada726660 in '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 && !copy($pdb3b4b1e, $pf68e0f83)) { $this->addError("Could not copy $v6ada726660 to '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 || $v105c74e20f) { if ($v105c74e20f) { $v92837824e2 = str_replace(LAYER_PATH, "", $v105c74e20f); $this->addMessage("Note that this program added new attributes to the Article module and replace the previous Business Logic Service in '{$v4c9cc78358}$v6ada726660'. If you wish to have the '$v6ada726660' with your old attributes too, please merge this file with the '$v92837824e2' file."); } if (!self::updateOldExtraAttributesTableCode($pf68e0f83, $pf5bf6141, $v5ba36af525 . "_" . $pf5bf6141)) { $this->addError("Could not update code with db driver info in '" . $v4c9cc78358 . $v6ada726660 . "'!"); $v5c1c342594 = false; } else if (!self::updateBusinessLogicServiceClassNameInFile($pf68e0f83, $pf5dc5cd4, $v89b96cda4a)) { $this->addError("Could not rename business logic class name in '" . $v4c9cc78358 . $v6ada726660 . "'!"); $v5c1c342594 = false; } } } } } } if ($this->ibatis_modules_paths && !empty($v4d7b07c237["ibatis"])) { $pdb3b4b1e = $v4d7b07c237["ibatis"]; foreach ($this->user_settings["db_drivers"] as $v5ba36af525) { $v6ada726660 = $v5ba36af525 . "_" . $pf5bf6141 . ".xml"; foreach ($this->ibatis_modules_paths as $pa32be502) { $v11506aed93 = $pa32be502 . $pcd8c70bc . "/"; $v4c9cc78358 = str_replace(LAYER_PATH, "", $v11506aed93); if (!is_dir($v11506aed93)) { $this->addError(ucfirst($pcd8c70bc) . " module is not installed in '$v4c9cc78358' layer!"); $v5c1c342594 = false; } else { $pf68e0f83 = $v11506aed93 . $v6ada726660; $v7959970a41 = file_exists($pf68e0f83); if ($v7959970a41 && !self::mecf28d1254b3($pf68e0f83, $pdb3b4b1e, $pf5bf6141, $v5ba36af525, $v105c74e20f)) { $this->addError("Could not merge $v6ada726660 in '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 && !copy($pdb3b4b1e, $pf68e0f83)) { $this->addError("Could not copy $v6ada726660 to '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 || $v105c74e20f) { if ($v105c74e20f) { $v92837824e2 = str_replace(LAYER_PATH, "", $v105c74e20f); $this->addMessage("Note that this program added new attributes to the Article module and replace the previous Ibatis Rules in '{$v4c9cc78358}$v6ada726660'. If you wish to have the '$v6ada726660' with your old attributes too, please merge this file with the '$v92837824e2' file."); } if (!self::updateOldExtraAttributesTableCode($pf68e0f83, $pf5bf6141, $v5ba36af525 . "_" . $pf5bf6141)) { $this->addError("Could not update code with db driver info in '" . $v4c9cc78358 . $v6ada726660 . "'!"); $v5c1c342594 = false; } } } } } } if ($this->hibernate_modules_paths && !empty($v4d7b07c237["hibernate"])) { $pdb3b4b1e = $v4d7b07c237["hibernate"]; foreach ($this->user_settings["db_drivers"] as $v5ba36af525) { $v6ada726660 = $v5ba36af525 . "_" . $pf5bf6141 . ".xml"; foreach ($this->hibernate_modules_paths as $pa32be502) { $v11506aed93 = $pa32be502 . $pcd8c70bc . "/"; $v4c9cc78358 = str_replace(LAYER_PATH, "", $v11506aed93); if (!is_dir($v11506aed93)) { $this->addError(ucfirst($pcd8c70bc) . " module is not installed in '$v4c9cc78358' layer!"); $v5c1c342594 = false; } else { $pf68e0f83 = $v11506aed93 . $v6ada726660; $v7959970a41 = file_exists($pf68e0f83); if ($v7959970a41 && !self::f1b5866f087($pf68e0f83, $pdb3b4b1e, $pf5bf6141, $v5ba36af525, $v105c74e20f)) { $this->addError("Could not merge $v6ada726660 in '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 && !copy($pdb3b4b1e, $pf68e0f83)) { $this->addError("Could not copy $v6ada726660 to '" . $v4c9cc78358 . "'!"); $v5c1c342594 = false; } else if (!$v7959970a41 || $v105c74e20f) { if ($v105c74e20f) { $v92837824e2 = str_replace(LAYER_PATH, "", $v105c74e20f); $this->addMessage("Note that this program added new attributes to the Article module and replace the previous Hibernate Rules in '{$v4c9cc78358}$v6ada726660'. If you wish to have the '$v6ada726660' with your old attributes too, please merge this file with the '$v92837824e2' file."); } if (!self::updateOldExtraAttributesTableCode($pf68e0f83, $pf5bf6141, $v5ba36af525 . "_" . $pf5bf6141, false, true)) { $this->addError("Could not update code with db driver info in '" . $v4c9cc78358 . $v6ada726660 . "'!"); $v5c1c342594 = false; } } } } } } } if ($this->existsDBs() && !empty($v4d7b07c237["attributes_settings"])) { include $v4d7b07c237["attributes_settings"]; if ($table_extra_attributes_settings) { $v50bf2c9568 = true; if ($this->UserAuthenticationHandler) $this->UserAuthenticationHandler->insertReservedDBTableNameIfNotExistsYet(array("name" => $pc20b779e)); foreach ($this->db_drivers as $v872f5b4dbb) { $v08ab593fba = $v872f5b4dbb->getOptions(); $pac4bc40a = $v872f5b4dbb->listTables(); $v7959970a41 = $v872f5b4dbb->isTableInNamesList($pac4bc40a, $pc20b779e); if ($v7959970a41) { $pc3502754 = $v872f5b4dbb->listTableFields($pc20b779e); foreach ($table_extra_attributes_settings as $v5e45ec9bb9 => $pd152184b) { if (empty($pc3502754[$v5e45ec9bb9]) && !empty($pd152184b["db_attribute"])) { $v3c76382d93 = $v872f5b4dbb->getAddTableAttributeStatement($pc20b779e, $pd152184b["db_attribute"], $v08ab593fba); if (!$v872f5b4dbb->setData($v3c76382d93)) $v50bf2c9568 = false; } } } else { $pc3502754 = $v872f5b4dbb->listTableFields($pc661dc6b); $pfdbbc383 = array(); if ($pc3502754) foreach ($pc3502754 as $v5e45ec9bb9 => $pd152184b) if (!empty($pd152184b["primary_key"])) $pfdbbc383[] = $pd152184b; if (!$pfdbbc383) { $this->addError("No primary keys for table '$pc661dc6b' in one of the DB Drivers!"); $v50bf2c9568 = false; } else { foreach ($table_extra_attributes_settings as $v5e45ec9bb9 => $pd152184b) if (!empty($pd152184b["db_attribute"])) $pfdbbc383[] = $pd152184b["db_attribute"]; $v87a92bb1ad = array( "table_name" => $pc20b779e, "attributes" => $pfdbbc383, ); $v3c76382d93 = $v872f5b4dbb->getCreateTableStatement($v87a92bb1ad, $v08ab593fba); if (!$v872f5b4dbb->setData($v3c76382d93)) $v50bf2c9568 = false; } } } if (!$v50bf2c9568) { $this->addError("Could not add the '$pc20b779e' table with the correspondent attributes in all DB Drivers!"); $v5c1c342594 = false; } } } return $v5c1c342594; } private static function f62e0ecbcf5($v88ee21e014, $pe7588214) { $v5c1c342594 = true; $v145de4646a = file_get_contents($v88ee21e014); $pf4e3c708 = file_get_contents($pe7588214); if (trim($v145de4646a) != trim($pf4e3c708)) { include $pe7588214; if ($table_extra_attributes_settings) { $v34e214ca87 = $table_extra_attributes_settings; include $v88ee21e014; $table_extra_attributes_settings = $table_extra_attributes_settings ? array_merge($v34e214ca87, $table_extra_attributes_settings) : $v34e214ca87; $v067674f4e4 = "<?php\n\$table_extra_attributes_settings = " . var_export($table_extra_attributes_settings, true) . ";\n?>"; if (file_put_contents($v88ee21e014, $v067674f4e4) === false) $v5c1c342594 = false; } } return $v5c1c342594; } public static function updateBusinessLogicServiceClassNameInFile($v7dffdb5a5b, $pd48aae7b, $v56440b0dac) { $v5c1c342594 = true; $v067674f4e4 = file_get_contents($v7dffdb5a5b); $pf4e3c708 = self::mf8e06444c951($v067674f4e4, $pd48aae7b, $v56440b0dac); if ($v067674f4e4 != $pf4e3c708 && file_put_contents($v7dffdb5a5b, $pf4e3c708) === false) $v5c1c342594 = false; return $v5c1c342594; } private static function mf8e06444c951($v067674f4e4, $pd48aae7b, $v56440b0dac) { return preg_replace("/class\s+" . $pd48aae7b . "(\s+|\{)/", "class " . $v56440b0dac . '$1', $v067674f4e4); } private static function me32868209821($v88ee21e014, $pe7588214, $pf5bf6141 = null, $v5ba36af525 = null, &$v56a30961a5 = null) { $v5c1c342594 = true; $v145de4646a = file_get_contents($v88ee21e014); $pf4e3c708 = file_get_contents($pe7588214); if ($pf5bf6141 && $v5ba36af525) { $pf4e3c708 = self::md8f75e9f5cc4($pf4e3c708, $pf5bf6141, $v5ba36af525 . "_" . $pf5bf6141); $pf4e3c708 = self::mf8e06444c951($pf4e3c708, pathinfo($pe7588214, PATHINFO_FILENAME), pathinfo($v88ee21e014, PATHINFO_FILENAME)); } if (trim($v145de4646a) != trim($pf4e3c708)) { $v93ff269092 = rand(0, 1000000); $pfbd491a9 = pathinfo($v88ee21e014); $v60799700a0 = substr($pfbd491a9["filename"], 0, - strlen("Service")); $v56a30961a5 = dirname($v88ee21e014) . "/" . $v60799700a0 . $v93ff269092 . "Service." . (!empty($pfbd491a9["extension"]) ? $pfbd491a9["extension"] : "php"); $v5c1c342594 = rename($v88ee21e014, $v56a30961a5); if ($v5c1c342594) { if (!self::updateBusinessLogicServiceClassNameInFile($v56a30961a5, $v60799700a0 . "Service", $v60799700a0 . $v93ff269092 . "Service")) $v5c1c342594 = false; if (!copy($pe7588214, $v88ee21e014)) $v5c1c342594 = false; } } return $v5c1c342594; } private static function mecf28d1254b3($v88ee21e014, $pe7588214, $pf5bf6141, $v5ba36af525, &$v56a30961a5) { $v5c1c342594 = true; $v145de4646a = file_get_contents($v88ee21e014); $pf4e3c708 = file_get_contents($pe7588214); if ($pf5bf6141 && $v5ba36af525) $pf4e3c708 = self::md8f75e9f5cc4($pf4e3c708, $pf5bf6141, $v5ba36af525 . "_" . $pf5bf6141, false, true); if (trim($v145de4646a) != trim($pf4e3c708)) { $v93ff269092 = rand(0, 1000000); $pfbd491a9 = pathinfo($v88ee21e014); $v56a30961a5 = dirname($v88ee21e014) . "/" . $pfbd491a9["filename"] . "_" . $v93ff269092 . "." . (!empty($pfbd491a9["extension"]) ? $pfbd491a9["extension"] : "xml"); if (!rename($v88ee21e014, $v56a30961a5) || !copy($pe7588214, $v88ee21e014)) $v5c1c342594 = false; } return $v5c1c342594; } private static function f1b5866f087($v88ee21e014, $pe7588214, $pf5bf6141, $v5ba36af525, &$v56a30961a5) { return self::mecf28d1254b3($v88ee21e014, $pe7588214, $pf5bf6141, $v5ba36af525, $v56a30961a5); } public static function updateOldExtraAttributesTableCode($v7dffdb5a5b, $v93e7cbd679, $pf8ebc057, $v2c822f3395 = false, $pe31075b3 = false) { $v067674f4e4 = self::getUpdatedOldExtraAttributesTableFileCode($v7dffdb5a5b, $v93e7cbd679, $pf8ebc057, $v2c822f3395, $pe31075b3); return file_put_contents($v7dffdb5a5b, $v067674f4e4) !== false; } public static function getUpdatedOldExtraAttributesTableFileCode($v7dffdb5a5b, $v93e7cbd679, $pf8ebc057, $v2c822f3395 = false, $pe31075b3 = false) { $v067674f4e4 = file_get_contents($v7dffdb5a5b); return self::md8f75e9f5cc4($v067674f4e4, $v93e7cbd679, $pf8ebc057, $v2c822f3395, $pe31075b3); } private static function md8f75e9f5cc4($v067674f4e4, $v93e7cbd679, $pf8ebc057, $v2c822f3395 = false, $pe31075b3 = false) { $pe6d37fc5 = self::f4f4f9f34a5($v93e7cbd679); $v046c9efa9e = self::f4f4f9f34a5($pf8ebc057); $pbf4e8fd2 = $pe6d37fc5 . "Service"; $v5c46898002 = $v046c9efa9e . "Service"; if ($pe31075b3) { preg_match('/table=("|\'|)(\w+)("|\'|)/u', $v067674f4e4, $pbae7526c, PREG_OFFSET_CAPTURE); $v8c5df8072b = isset($pbae7526c[2][0]) ? $pbae7526c[2][0] : null; } if ($v2c822f3395) { $v067674f4e4 = preg_replace('/("|\')(' . preg_quote($pbf4e8fd2) . ')(\.|"|\')/', '$1' . $v5c46898002 . '$3', $v067674f4e4); $v067674f4e4 = preg_replace('/("|\')(' . preg_quote($pe6d37fc5) . ')("|\')/', '$1' . $v046c9efa9e . '$3', $v067674f4e4); } else $v067674f4e4 = str_replace($pe6d37fc5, $v046c9efa9e, $v067674f4e4); $v067674f4e4 = preg_replace('/("|\')(\w*)(' . preg_quote($v93e7cbd679) . ')(\w*)("|\')/', '$1$2' . $pf8ebc057 . '$4$5', $v067674f4e4); if ($pe31075b3 && $v8c5df8072b) $v067674f4e4 = preg_replace('/table=("|\'|)(\w+)("|\'|)/u', 'table=$1' . $v8c5df8072b . '$3', $v067674f4e4); return $v067674f4e4; } private static function f4f4f9f34a5($v5e813b295b) { return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($v5e813b295b)))); } } ?>
