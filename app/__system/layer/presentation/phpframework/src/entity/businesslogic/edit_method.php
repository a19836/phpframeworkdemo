<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $_GET["item_type"] = "businesslogic"; $_GET["class"] = $_GET["service"]; $include_annotations = true; include $EVC->getEntityPath("admin/edit_file_class_method"); if ($obj_data) { include_once $EVC->getUtilPath("WorkFlowBusinessLogicHandler"); $obj_data["is_business_logic_service"] = WorkFlowBusinessLogicHandler::isBusinessLogicService($obj_data); } ?>
