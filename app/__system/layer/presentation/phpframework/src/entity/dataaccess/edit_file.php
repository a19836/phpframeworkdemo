<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); if ($_POST) $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $do_not_die_on_save = true; include $EVC->getEntityPath("admin/edit_raw_file"); if ($_POST && $ret) { if ($obj && is_a($obj, "DataAccessLayer") && $ret["status"]) { $cache_path = $obj->getCacheLayer()->getCachedDirPath() . "/" . (is_a($obj, "IbatisDataAccessLayer") ? IBatisClientCache::CACHE_DIR_NAME : HibernateClientCache::CACHE_DIR_NAME); CacheHandlerUtil::deleteFolder($cache_path, false); } echo json_encode($ret); die(); } ?>
