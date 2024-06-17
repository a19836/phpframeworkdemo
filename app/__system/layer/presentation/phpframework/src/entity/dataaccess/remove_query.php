<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($_GET["query_id"] && $_GET["query_type"]) { $file_type = "save_query"; $_POST["object"] = array(); $_POST["overwrite"] = 1; $queries_ids = array( $_GET["query_type"] => array( $_GET["query_id"] => 0 ) ); include $EVC->getEntityPath("dataaccess/save"); } die(); ?>
