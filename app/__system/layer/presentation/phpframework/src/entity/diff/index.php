<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); include_once $EVC->getEntityPath("admin/admin_advanced"); unset($layers["db_layers"]); if ($layers["presentation_layers"]) foreach ($layers["presentation_layers"] as $layer_name => $layer) foreach ($layer as $fn => $f) if ($fn != "properties" && $fn != "aliases") unset($layers["presentation_layers"][$layer_name][$fn]); ?>
