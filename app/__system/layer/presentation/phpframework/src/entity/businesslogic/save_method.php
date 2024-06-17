<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("WorkFlowBusinessLogicHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); if ($_POST["object"]) WorkFlowBusinessLogicHandler::prepareObjectIfIsBusinessLogicService($_POST["object"]); $do_not_die_on_save = true; include $EVC->getEntityPath("admin/save_file_class_method"); if ($obj && is_a($obj, "BusinessLogicLayer") && $_POST && $status) CacheHandlerUtil::deleteFolder($obj->getCacheLayer()->getCachedDirPath(), false); die($status); ?>
