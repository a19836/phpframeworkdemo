<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($_GET["method"] && $_GET["class"]) { $file_type = "remove_file_class_method"; $_POST["r"] = true; include $EVC->getEntityPath("admin/save_php_file_props"); } else die(); ?>
