<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); include_once get_lib("org.phpframework.cache.xmlsettings.IXmlSettingsCacheHandler"); abstract class XmlSettingsCacheHandler implements IXmlSettingsCacheHandler { protected $cache_ttl = 30758400; public function setCacheTTL($v61de9a39ed) {$this->cache_ttl = $v61de9a39ed;} public function getCacheTTL() {return $this->cache_ttl;} protected function prepareFilePath(&$pf3dc0762) { $pf3dc0762 = CacheHandlerUtil::getCacheFilePath($pf3dc0762); } } ?>
