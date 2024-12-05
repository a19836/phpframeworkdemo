<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if (!empty($_GET["function"])) { $_GET["item_type"] = "businesslogic"; $do_not_die_on_save = true; include $EVC->getEntityPath("admin/remove_file_function"); if (!empty($obj) && is_a($obj, "BusinessLogicLayer") && !empty($_POST) && !empty($status)) CacheHandlerUtil::deleteFolder($obj->getCacheLayer()->getCachedDirPath(), false); echo isset($status) ? $status : null; die(); } die(); ?>
