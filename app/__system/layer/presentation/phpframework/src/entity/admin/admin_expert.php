<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); include $EVC->getUtilPath("admin_uis_layers_and_permissions"); ?>
