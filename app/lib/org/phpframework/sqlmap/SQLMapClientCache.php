<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class SQLMapClientCache extends FileSystemXmlSettingsCacheHandler { protected $cache_root_path; public function cachedXMLElmExists($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedXMLElm($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); return $this->getCache($pf3dc0762); } public function setCachedXMLElm($pf3dc0762, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function deleteCachedXMLElm($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762) { return $this->deleteCache($pf3dc0762); } return true; } public function getCachedFilePath($pf3dc0762) { if($this->cache_root_path && $pf3dc0762) { return $this->cache_root_path . hash("md4", $pf3dc0762); } return false; } } ?>
