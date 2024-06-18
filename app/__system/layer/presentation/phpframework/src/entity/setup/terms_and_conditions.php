<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("DependenciesInstallationHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); if (!empty($_POST["acceptance"])) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $continue = true; if (!empty($_POST["dependencies"])) { $zips = DependenciesInstallationHandler::getDependencyZipFilesToInstall(); $continue = DependenciesInstallationHandler::installDependencies($dependencies_repo_url, $zips, $error_message); if (!$continue) $error_message = "Error could not download and install dependencies.<br/>Please confirm if you are connected to the internet." . ($error_message ? "<br/>$error_message" : ""); } if ($continue) { header("location: ?step=2"); echo '<script>window.location = "?step=2"</script>'; die(); } } ?>
