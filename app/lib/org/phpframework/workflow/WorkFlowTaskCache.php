<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); class WorkFlowTaskCache extends FileSystemXmlSettingsCacheHandler { const CACHE_DIR_NAME = "workflow/__system/"; const LOADED_TASKS_FILE_NAME = "loaded_tasks"; const LOADED_TASKS_INCLUDES_FILE_NAME = "loaded_tasks_includes"; const LOADED_TASKS_SETTINGS_FILE_NAME = "loaded_tasks_settings"; const LOADED_TASKS_CONTAINERS_FILE_NAME = "loaded_tasks_containers"; protected $cache_root_path; protected $is_active = false; public function cachedLoadedTasksExists($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_FILE_NAME . "_" . $pcee76419); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $this->prepareFilePath($pf3dc0762); return file_exists($pf3dc0762) && file_get_contents($pf3dc0762); } return false; } public function getCachedLoadedTasks($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_FILE_NAME . "_" . $pcee76419); return $this->getCache($pf3dc0762); } public function setCachedLoadedTasks($pcee76419, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_FILE_NAME . "_" . $pcee76419); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function cachedLoadedTasksIncludesExists($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_INCLUDES_FILE_NAME . "_" . $pcee76419); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $this->prepareFilePath($pf3dc0762); return file_exists($pf3dc0762) && file_get_contents($pf3dc0762); } return false; } public function getCachedLoadedTasksIncludes($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_INCLUDES_FILE_NAME . "_" . $pcee76419); return $this->getCache($pf3dc0762); } public function setCachedLoadedTasksIncludes($pcee76419, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_INCLUDES_FILE_NAME . "_" . $pcee76419); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function cachedLoadedTasksSettingsExists($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_SETTINGS_FILE_NAME . "_" . $pcee76419); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $this->prepareFilePath($pf3dc0762); return file_exists($pf3dc0762) && file_get_contents($pf3dc0762); } return false; } public function getCachedLoadedTasksSettings($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_SETTINGS_FILE_NAME . "_" . $pcee76419); return $this->getCache($pf3dc0762); } public function setCachedLoadedTasksSettings($pcee76419, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_SETTINGS_FILE_NAME . "_" . $pcee76419); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function cachedTasksContainersExists($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_CONTAINERS_FILE_NAME . "_" . $pcee76419); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $this->prepareFilePath($pf3dc0762); return file_exists($pf3dc0762) && file_get_contents($pf3dc0762); } return false; } public function getCachedTasksContainers($pcee76419) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_CONTAINERS_FILE_NAME . "_" . $pcee76419); return $this->getCache($pf3dc0762); } public function setCachedTasksContainers($pcee76419, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath(self::LOADED_TASKS_CONTAINERS_FILE_NAME . "_" . $pcee76419); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function initCacheDirPath($v17be587282) { if(!$this->cache_root_path) { if($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $v17be587282 .= self::CACHE_DIR_NAME; if(CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->cache_root_path = $v17be587282; $this->is_active = true; } } } } public function isActive() { return $this->is_active; } public function getCachedFilePath($pf3dc0762) { if($this->cache_root_path && $pf3dc0762) { return $this->cache_root_path . $pf3dc0762; } return false; } public function getCachedId($v103484e461) { return md5(serialize($v103484e461)); } public function flushCache() { return CacheHandlerUtil::deleteFolder($this->cache_root_path); } } ?>
