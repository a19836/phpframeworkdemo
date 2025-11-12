<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class SQLMapClientCache extends FileSystemXmlSettingsCacheHandler { protected $cache_root_path; public function cachedXMLElmExists($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedXMLElm($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); return $this->getCache($pf3dc0762); } public function setCachedXMLElm($pf3dc0762, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function deleteCachedXMLElm($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762) { return $this->deleteCache($pf3dc0762); } return true; } public function getCachedFilePath($pf3dc0762) { if($this->cache_root_path && $pf3dc0762) { return $this->cache_root_path . hash("md4", $pf3dc0762); } return false; } } ?>
