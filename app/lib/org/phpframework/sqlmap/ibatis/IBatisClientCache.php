<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.sqlmap.SQLMapClientCache"); class IBatisClientCache extends SQLMapClientCache { const CACHE_DIR_NAME = "__system/ibatis/"; public function initCacheDirPath($v17be587282) { if(!$this->cache_root_path) { if($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $v17be587282 .= self::CACHE_DIR_NAME; if(CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->cache_root_path = $v17be587282; } } } } } ?>
