<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); if ($_GET["action"] == "remove") $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); else { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); if ($_GET["action"] == "paste_and_remove") $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); } include $EVC->getEntityPath("admin/manage_file"); ?>
