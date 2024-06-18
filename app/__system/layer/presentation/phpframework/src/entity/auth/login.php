<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("DependenciesInstallationHandler"); $popup = isset($_GET["popup"]) ? $_GET["popup"] : null; $username = isset($_GET["username"]) ? $_GET["username"] : ""; $password = isset($_GET["password"]) ? $_GET["password"] : ""; $agreement = !empty($_COOKIE["lla"]) || $popup ? 1 : 0; if ($_POST) { $username = isset($_POST["username"]) ? $_POST["username"] : null; $password = isset($_POST["password"]) ? $_POST["password"] : null; $agreement = isset($_POST["agreement"]) ? $_POST["agreement"] : null; if (empty($username) || empty($password)) $error_message = "Username or Password cannot be undefined. Please try again..."; else if (empty($agreement)) $error_message = "You must accept the terms and conditions in order to proceed. Please try again..."; else if ($UserAuthenticationHandler->isUserBlocked($username)) $error_message = "You attempted to login multiple times.<br/>Your user is now blocked."; else if ($UserAuthenticationHandler->login($username, $password)) { CookieHandler::setSafeCookie("lla", $agreement, 0, "/", CSRFValidator::$COOKIES_EXTRA_FLAGS); if ($popup) { echo "1"; die(); } else { $installed = isset($_COOKIE[DependenciesInstallationHandler::$INSTALL_DEPENDENCIES_VARIABLE_NAME]); if (!$installed) { $dependencies = DependenciesInstallationHandler::getDependencyZipFilesToInstall(); $installed = DependenciesInstallationHandler::isDependencyInstalled($dependencies[ key($dependencies) ]); if (!$installed && !$UserAuthenticationHandler->isPresentationFilePermissionAllowed($EVC->getEntityPath("admin/install_dependencies"), "access")) $installed = true; } if (!$installed) $url_back = $project_url_prefix . "admin/install_dependencies"; else { $url_back = $UserAuthenticationHandler->getUrlBack(); $url_back = $UserAuthenticationHandler->validateUrlBack($url_back) ? $url_back : $project_url_prefix . "admin/"; } header("Location: $url_back"); die("<script>document.location = '$url_back';</script>"); } } else { $UserAuthenticationHandler->insertFailedLoginAttempt($username); $error_message = "Username or Password invalid. Please try again..."; } } $login_data = array( "username" => $username, "password" => $password, "agreement" => $agreement, ); ?>
