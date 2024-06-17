<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $popup = $_GET["popup"]; $status = $_GET["status"]; $msg = $_GET["msg"]; if (isset($status)) { if ($status) $status_message = $msg ? $msg : "Feedback sent successfully!"; else $error_message = $msg ? $msg : "Feedback not sent! Please try again..."; } ?>
