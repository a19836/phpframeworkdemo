<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $filter_by_layout = $_GET["filter_by_layout"]; $filter_by_layout = str_replace("../", "", $filter_by_layout); include $EVC->getUtilPath("admin_uis_permissions"); ?>
