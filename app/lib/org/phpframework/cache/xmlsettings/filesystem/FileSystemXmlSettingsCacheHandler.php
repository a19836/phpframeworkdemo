<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 include_once get_lib("org.phpframework.cache.xmlsettings.XmlSettingsCacheHandler"); class FileSystemXmlSettingsCacheHandler extends XmlSettingsCacheHandler { public function getCache($pf3dc0762) { $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists($pf3dc0762)) { $v57b4b0200b = @file_get_contents($pf3dc0762); if (!empty($v57b4b0200b)) { $pfb662071 = CacheHandlerUtil::unserializeContent($v57b4b0200b); return is_array($pfb662071) ? $pfb662071 : false; } } return false; } public function setCache($pf3dc0762, $v539082ff30, $pf25d75af = false) { $pdc9a8b3f = $pf3dc0762; $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists(dirname($pf3dc0762))) { if(is_array($v539082ff30)) { if (!$pf25d75af) { $v587d93a3a7 = $this->getCache($pdc9a8b3f); $v65a396e40d = is_array($v587d93a3a7) ? array_merge($v587d93a3a7, $v539082ff30) : $v539082ff30; } else $v65a396e40d = $v539082ff30; if(($v7dffdb5a5b = fopen($pf3dc0762, "w"))) { $v57b4b0200b = CacheHandlerUtil::serializeContent($v65a396e40d); $v5c1c342594 = fputs($v7dffdb5a5b, $v57b4b0200b); fclose($v7dffdb5a5b); return $v5c1c342594 === false ? false : true; } } } return false; } public function isCacheValid($pf3dc0762) { $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists($pf3dc0762)) return filemtime($pf3dc0762) + $this->cache_ttl < time() ? false : true; return false; } public function deleteCache($pf3dc0762) { $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists($pf3dc0762)) return unlink($pf3dc0762); return false; } } ?>
