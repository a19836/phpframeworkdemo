<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); include_once get_lib("org.phpframework.cache.user.IUserCacheHandler"); abstract class UserCacheHandler implements IUserCacheHandler { protected $root_path; protected $ttl; protected $serialize; const DEFAULT_TTL = 30758400; public function config($v492fce9a5d = false, $pc0eabaff = true) { $this->ttl = $v492fce9a5d ? $v492fce9a5d : self::DEFAULT_TTL; $this->serialize = $pc0eabaff; } public function setRootPath($v4ab372da3a) { CacheHandlerUtil::configureFolderPath($v4ab372da3a); $this->root_path = $v4ab372da3a; } public function getRootPath() {return $this->root_path;} public function serializeContent($pae77d38c) { return $this->serialize ? serialize($pae77d38c) : $pae77d38c; } public function unserializeContent($pae77d38c) { return $this->serialize ? unserialize($pae77d38c) : $pae77d38c; } protected function prepareFilePath(&$pf3dc0762) { $pf3dc0762 = CacheHandlerUtil::getCacheFilePath($pf3dc0762); } } ?>
