<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.layer.cache.CacheLayer"); class BusinessLogicCacheLayer extends CacheLayer { public function initBeanObjs($pcd8c70bc) { $this->Layer->initBeanObjs($pcd8c70bc); $this->bean_objs = $this->Layer->getBeanObjs(); $this->bean_objs["vars"] = isset($this->bean_objs["vars"]) && is_array($this->bean_objs["vars"]) ? $this->bean_objs["vars"] : array(); $this->bean_objs["vars"] = array_merge($this->bean_objs["vars"], $this->settings); } public function getModulePath($pcd8c70bc) { return $this->Layer->getModulePath($pcd8c70bc); } public function initModuleCache($pcd8c70bc) { if(isset($this->modules_cache[$pcd8c70bc])) return true; $this->Layer->initModuleServices($pcd8c70bc); if($this->Layer->getErrorHandler()->ok()) { $v11506aed93 = $this->getModulePath($pcd8c70bc); if (empty($this->settings["business_logic_cache_file_name"])) launch_exception(new CacheLayerException(4, "BusinessLogicCacheLayer->settings[business_logic_cache_file_name]")); $v0ff71d0593 = $v11506aed93 . $this->settings["business_logic_cache_file_name"]; if($v0ff71d0593 && file_exists($v0ff71d0593)) { $this->initBeanObjs($pcd8c70bc); if($this->Layer->getModuleCacheLayer()->cachedModuleSettingsExists($pcd8c70bc)) { $pa3e341cf = $this->Layer->getModuleCacheLayer()->getCachedModuleSettings($pcd8c70bc); $this->modules_cache[$pcd8c70bc] = isset($pa3e341cf["modules_cache"]) ? $pa3e341cf["modules_cache"] : null; $this->keys[$pcd8c70bc] = isset($pa3e341cf["keys"]) ? $pa3e341cf["keys"] : null; $this->service_related_keys_to_delete[$pcd8c70bc] = isset($pa3e341cf["service_related_keys_to_delete"]) ? $pa3e341cf["service_related_keys_to_delete"] : null; } else { $this->modules_cache[$pcd8c70bc] = $this->parseCacheFile($pcd8c70bc, $v0ff71d0593); $this->prepareModulesCache($pcd8c70bc); $pa3e341cf = array(); $pa3e341cf["modules_cache"] = isset($this->modules_cache[$pcd8c70bc]) ? $this->modules_cache[$pcd8c70bc] : null; $pa3e341cf["keys"] = isset($this->keys[$pcd8c70bc]) ? $this->keys[$pcd8c70bc] : null; $pa3e341cf["service_related_keys_to_delete"] = isset($this->service_related_keys_to_delete[$pcd8c70bc]) ? $this->service_related_keys_to_delete[$pcd8c70bc] : null; $this->Layer->getModuleCacheLayer()->setCachedModuleSettings($pcd8c70bc, $pa3e341cf); } return true; } } return false; } public function getModuleCacheObj($pcd8c70bc, $v20b8676a9f, $v539082ff30) { $pc8b88eb4 = isset($this->modules_cache[$pcd8c70bc]) ? $this->modules_cache[$pcd8c70bc] : null; $v1ce69f3b9f = isset($pc8b88eb4["services"]) ? $pc8b88eb4["services"] : null; if (isset($v1ce69f3b9f[$v20b8676a9f])) { $v95eeadc9e9 = isset($v1ce69f3b9f[$v20b8676a9f]) ? $v1ce69f3b9f[$v20b8676a9f] : null; $pcee3c9fd = isset($v95eeadc9e9["cache_handler"]) ? $v95eeadc9e9["cache_handler"] : null; if ($pcee3c9fd) { $v972f1a5c2b = false; if (!empty($pc8b88eb4["objects"][$pcee3c9fd])) $v972f1a5c2b = $pc8b88eb4["objects"][$pcee3c9fd]; else { if (!empty($this->modules_cache[$pcd8c70bc]["bean_factory"])) $pddfc29cd = $this->modules_cache[$pcd8c70bc]["bean_factory"]; else { $this->initBeanObjs($pcd8c70bc); $pddfc29cd = new BeanFactory(); $pddfc29cd->addObjects($this->bean_objs); $pddfc29cd->init(array( "settings" => isset($pc8b88eb4["beans"]) ? $pc8b88eb4["beans"] : null )); $this->modules_cache[$pcd8c70bc]["bean_factory"] = $pddfc29cd; } $pddfc29cd->setCacheRootPath($this->getCachedDirPath()); $v972f1a5c2b = $pddfc29cd->getObject($pcee3c9fd); if (!$v972f1a5c2b) { $pddfc29cd->initObject($pcee3c9fd, false); $this->modules_cache[$pcd8c70bc]["bean_factory"] = $pddfc29cd; $v972f1a5c2b = $pddfc29cd->getObject($pcee3c9fd); if (!$v972f1a5c2b) $v972f1a5c2b = $this->Layer->getModuleConstructorObj($pcd8c70bc, $pcee3c9fd, null, $v539082ff30); } $this->modules_cache[$pcd8c70bc]["objects"][$pcee3c9fd] = $v972f1a5c2b; } if($v972f1a5c2b) return $v972f1a5c2b; else launch_exception(new CacheLayerException(2, $pcd8c70bc . "::" . $v20b8676a9f . "::" . $pcee3c9fd)); } else launch_exception(new CacheLayerException(1, $pcd8c70bc . "::" . $v20b8676a9f)); } return false; } public function getCachedDirPath() { if (empty($this->settings["business_logic_cache_path"])) launch_exception(new CacheLayerException(4, "BusinessLogicCacheLayer->settings[business_logic_cache_path]")); return $this->settings["business_logic_cache_path"]; } } ?>
