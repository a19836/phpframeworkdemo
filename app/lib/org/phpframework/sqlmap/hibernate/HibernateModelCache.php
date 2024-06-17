<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class HibernateModelCache extends FileSystemXmlSettingsCacheHandler { const CACHE_DIR_NAME = "__system/hibernate/sql/"; private $pd8192d9d; public function __construct() { } public static function getCachedSQLName($pdcf670f6, $v9367d5be85) { $v9994512d98 = is_array($v9367d5be85) ? array_keys($v9367d5be85) : array(); sort($v9994512d98); $v9994512d98 = serialize($v9994512d98); return "{$pdcf670f6}_".hash("md4", $v9994512d98).".sql"; } public function cachedSQLExists($v250a1176c9) { $pf3dc0762 = $this->getCachedSQLPath($v250a1176c9); if($pf3dc0762 && $this->isCacheSQLValid($pf3dc0762)) { return $this->getCachedSQL($v250a1176c9) ? true : false; } return false; } public function getCachedSQL($v250a1176c9) { $pf3dc0762 = $this->getCachedSQLPath($v250a1176c9); if($pf3dc0762 && file_exists($pf3dc0762)) { return file_get_contents($pf3dc0762); } return false; } public function setCachedSQL($v250a1176c9, $v539082ff30) { $pf3dc0762 = $this->getCachedSQLPath($v250a1176c9); if($pf3dc0762) { if(($v7dffdb5a5b = fopen($pf3dc0762, "w"))) { $v5c1c342594 = fputs($v7dffdb5a5b, $v539082ff30); fclose($v7dffdb5a5b); return $v5c1c342594 === false ? false : true; } } return false; } public function initCacheDirPath($v17be587282) { if(!$this->pd8192d9d) { if($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $v17be587282 .= self::CACHE_DIR_NAME; if(CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->pd8192d9d = $v17be587282; } } } } public function getCachedSQLPath($pf3dc0762) { if($this->pd8192d9d && $pf3dc0762) { return $this->pd8192d9d . $pf3dc0762; } return false; } public function isCacheSQLValid($pf3dc0762) { if($pf3dc0762 && file_exists($pf3dc0762)) return filemtime($pf3dc0762) + $this->cache_ttl < time() ? false : true; return false; } } ?>
