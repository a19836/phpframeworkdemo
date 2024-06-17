<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $step = $_GET["step"]; $is_inside_of_iframe = !empty($_GET["iframe"]); switch ($step) { case 1: $page = "/setup/terms_and_conditions"; break; case 2: $page = "/setup/project_name"; break; case 3: $page = "/setup/db"; break; case 3.1: $page = "/setup/layers"; break; case 4: $page = "/setup/end"; break; default: $page = "/setup/terms_and_conditions"; } $entity_path = $EVC->getEntityPath($page); include $entity_path; ?>
