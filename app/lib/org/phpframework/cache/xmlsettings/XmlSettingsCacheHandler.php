<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); include_once get_lib("org.phpframework.cache.xmlsettings.IXmlSettingsCacheHandler"); abstract class XmlSettingsCacheHandler implements IXmlSettingsCacheHandler { protected $cache_ttl = 30758400; public function setCacheTTL($v61de9a39ed) {$this->cache_ttl = $v61de9a39ed;} public function getCacheTTL() {return $this->cache_ttl;} protected function prepareFilePath(&$pf3dc0762) { $pf3dc0762 = CacheHandlerUtil::getCacheFilePath($pf3dc0762); } } ?>
