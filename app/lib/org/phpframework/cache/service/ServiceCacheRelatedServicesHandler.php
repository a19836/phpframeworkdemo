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
 include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); abstract class ServiceCacheRelatedServicesHandler { const MAXIMUM_ITEMS_PER_FILE = 10000; const RELATED_SERVICES_FOLDER_NAME = "__related"; protected $CacheHandler; abstract public function addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false); abstract public function delete($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $v91d4d88b89); public function getServiceRuleToDeletePath($pdcf670f6, $v3fb9f41470, $v9a6acca23e, $pd9e207f2) { return $this->getServiceRuleToDeleteDirPath($pdcf670f6, $v3fb9f41470) . $this->getServiceRuleToDeleteRelativePath($v9a6acca23e, $pd9e207f2); } protected function getServiceRuleToDeleteDirPath($pdcf670f6, $v3fb9f41470) { return $this->CacheHandler->getServiceDirPath($pdcf670f6, $v3fb9f41470) . self::RELATED_SERVICES_FOLDER_NAME . "/"; } protected function getServiceRuleToDeleteRelativePath($v9a6acca23e, $pd9e207f2) { $v9a6acca23e = CacheHandlerUtil::getCorrectKeyType($v9a6acca23e); return strtolower($v9a6acca23e) . "/" . md5($pd9e207f2) . "/"; } public function getCacheHandler() {return $this->CacheHandler;} } ?>
