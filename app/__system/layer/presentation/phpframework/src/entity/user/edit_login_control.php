<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $username = $_GET["username"]; if ($username) { $login_control_data = $UserAuthenticationHandler->getLoginControl($username); } if ($_POST) { if ($username && $login_control_data) { if ($_POST["delete"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "delete"); if ($username && $UserAuthenticationHandler->deleteLoginControl($username)) { die("<script>alert('Username data deleted successfully'); document.location = '$project_url_prefix/user/manage_login_controls';</script>"); } else { $error_message = "There was an error trying to delete this Username data. Please try again..."; } } else if ($_POST["reset_failed_login_attempts"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); if ($UserAuthenticationHandler->resetFailedLoginAttempts($username)) { die("<script>alert('Username reset successfully'); document.location = '$project_url_prefix/user/manage_login_controls';</script>"); } else { $error_message = "There was an error trying to reset this username. Please try again..."; } } } else { $error_message = "No username data to reset."; } } ?>
