<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.layer.dataaccess.DataAccessLayer"); include_once get_lib("org.phpframework.sqlmap.SQLMapIncludesHandler"); class IbatisDataAccessLayer extends DataAccessLayer { public function callQuerySQL($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85 = false, $v5d3813882f = false) { debug_log_function("IbatisDataAccessLayer->callQuerySQL", array($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85)); $v5d3813882f["call_query_sql"] = true; $this->initModuleServices($pcd8c70bc); if ($this->getErrorHandler()->ok()) return $this->md8cd08bf5303($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85, $v5d3813882f); return false; } public function callQuery($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85 = false, $v5d3813882f = false) { debug_log_function("IbatisDataAccessLayer->callQuery(", array($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85, $v5d3813882f)); $v18521bca9a = $this->isCacheActive(); $v5d3813882f = $v5d3813882f ? $v5d3813882f : array(); $pe7197351 = array_merge($v5d3813882f, array("key_suffix" => "_includes")); if ($v18521bca9a && empty($v5d3813882f["no_cache"]) && $this->getCacheLayer()->isValid($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f)) { $pc06f1034 = $this->getCacheLayer()->get($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $pe7197351); SQLMapIncludesHandler::includeLibsOfResultClassAndMap($pc06f1034); return $this->getCacheLayer()->get($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f); } $this->initModuleServices($pcd8c70bc); if ($this->getErrorHandler()->ok()) { $v9ad1385268 = $this->md8cd08bf5303($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85, $v5d3813882f, $pc06f1034); if($this->getErrorHandler()->ok()) { if($v18521bca9a) { $this->getCacheLayer()->check($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $pc06f1034, $pe7197351); $this->getCacheLayer()->check($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v9ad1385268, $v5d3813882f); } return $v9ad1385268; } } return false; } public function getQueryProps($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85 = false, $v5d3813882f = false) { $v9073377656 = array(); $this->initModuleServices($pcd8c70bc); if($this->getErrorHandler()->ok()) { $pc8b88eb4 = $this->modules[$pcd8c70bc]; $v11506aed93 = $this->modules_path[$pcd8c70bc]; $v9073377656["module"] = $pc8b88eb4; $v9073377656["module_path"] = $v11506aed93; if(isset($pc8b88eb4[$v20b8676a9f])) { $v95eeadc9e9 = $pc8b88eb4[$v20b8676a9f]; $v250a1176c9 = isset($v95eeadc9e9[0]) ? $v95eeadc9e9[0] : null; $pa530fa8f = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; $v982d6fe381 = isset($v95eeadc9e9[2]) ? $v95eeadc9e9[2] : null; $v269e64f6b5 = $v11506aed93 . ($v982d6fe381 != "file" ? "/" . $v250a1176c9 : ""); $v71571534b0 = $pa530fa8f; $v9073377656["service"] = $v95eeadc9e9; $v9073377656["query_path"] = $v269e64f6b5; $v9073377656["query_id"] = $v71571534b0; } } return $v9073377656; } public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuerySQL($pc8b88eb4, "select", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuery($pc8b88eb4, "select", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuerySQL($pc8b88eb4, "insert", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuery($pc8b88eb4, "insert", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuerySQL($pc8b88eb4, "update", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuery($pc8b88eb4, "update", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuerySQL($pc8b88eb4, "delete", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuery($pc8b88eb4, "delete", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuerySQL($pc8b88eb4, "procedure", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->callQuery($pc8b88eb4, "procedure", $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } private function md8cd08bf5303($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85, $v5d3813882f, &$pc06f1034 = false) { $pc8b88eb4 = $this->modules[$pcd8c70bc]; $v11506aed93 = $this->modules_path[$pcd8c70bc]; if(isset($pc8b88eb4[$v20b8676a9f])) { $v95eeadc9e9 = $pc8b88eb4[$v20b8676a9f]; $v250a1176c9 = isset($v95eeadc9e9[0]) ? $v95eeadc9e9[0] : null; $pa530fa8f = isset($v95eeadc9e9[1]) ? $v95eeadc9e9[1] : null; $v982d6fe381 = isset($v95eeadc9e9[2]) ? $v95eeadc9e9[2] : null; $v269e64f6b5 = $v11506aed93 . ($v982d6fe381 != "file" ? "/" . $v250a1176c9 : ""); $v71571534b0 = $pa530fa8f; if($v269e64f6b5 && file_exists($v269e64f6b5)) { $v43972b7818 = $this->getSQLClient($v5d3813882f); if($this->isCacheActive()) { $v43972b7818->setCacheRootPath( $this->getCacheLayer()->getCachedDirPath() ); } else { $v43972b7818->setCacheRootPath(false); } $v43972b7818->loadXML($v269e64f6b5); $v9d1744e29c = $v43972b7818->getQuery($pf9445ab0, $v71571534b0); if($v9d1744e29c) { if($v5d3813882f["call_query_sql"]) { return $v43972b7818->getQuerySQL($v9d1744e29c, $v9367d5be85, $v5d3813882f); } else { $pc06f1034 = $v43972b7818->getLibsOfResultClassAndMap($v9d1744e29c); return $v43972b7818->execQuery($v9d1744e29c, $v9367d5be85, $v5d3813882f); } } return false; } launch_exception(new DataAccessLayerException(1, $v269e64f6b5)); return false; } launch_exception(new DataAccessLayerException(2, $pcd8c70bc . "::" . $pf9445ab0 . "::" . $v20b8676a9f)); return false; } protected function getRegexToGrepDataAccessFilesAndGetNodeIds() { return "/<(insert|update|delete|select|procedure)([^>]*)([ ]+)id=([\"]?)([\w\-\+&#;\s\.]+)([\"]?)/iu"; } } ?>
