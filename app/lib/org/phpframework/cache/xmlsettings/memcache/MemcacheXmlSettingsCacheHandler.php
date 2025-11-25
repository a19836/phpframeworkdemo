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
 include_once get_lib("org.phpframework.cache.xmlsettings.XmlSettingsCacheHandler"); include_once get_lib("org.phpframework.memcache.IMemcacheHandler"); class MemcacheXmlSettingsCacheHandler extends XmlSettingsCacheHandler { private $v6fa83921b8; public function setMemcacheHandler(IMemcacheHandler $v6fa83921b8) {$this->v6fa83921b8 = $v6fa83921b8;} public function getMemcacheHandler() {return $this->v6fa83921b8;} public function getCache($pf3dc0762) { if (!empty($this->v6fa83921b8)) { $pbfa01ed1 = CacheHandlerUtil::getFilePathKey($pf3dc0762); $v57b4b0200b = $this->v6fa83921b8->get($pbfa01ed1); if (!empty($v57b4b0200b)) { $pfb662071 = CacheHandlerUtil::unserializeContent($v57b4b0200b); return is_array($pfb662071) ? $pfb662071 : false; } } return false; } public function setCache($pf3dc0762, $v539082ff30) { if (!empty($this->v6fa83921b8) && is_array($v539082ff30)) { $pbfa01ed1 = CacheHandlerUtil::getFilePathKey($pf3dc0762); $v587d93a3a7 = $this->getCache($pf3dc0762); $v65a396e40d = is_array($v587d93a3a7) ? array_merge($v587d93a3a7, $v539082ff30) : $v539082ff30; $v57b4b0200b = CacheHandlerUtil::serializeContent($v65a396e40d); $v492fce9a5d = $this->cache_ttl ? $this->cache_ttl + time() : 0; return $this->v6fa83921b8->set($pbfa01ed1, $v57b4b0200b, $v492fce9a5d); } return false; } public function isCacheValid($pf3dc0762) { if (!empty($this->v6fa83921b8)) { $pbfa01ed1 = CacheHandlerUtil::getFilePathKey($pf3dc0762); return $this->v6fa83921b8->get($pbfa01ed1) !== false; } return false; } public function deleteCache($pf3dc0762) { if (!empty($this->v6fa83921b8)) { $pbfa01ed1 = CacheHandlerUtil::getFilePathKey($pf3dc0762); return $this->v6fa83921b8->delete($pbfa01ed1) !== false; } return false; } } ?>
