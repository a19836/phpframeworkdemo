<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("VideoTutorialHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); include $EVC->getEntityPath("admin/choose_available_project"); include $EVC->getEntityPath("admin/choose_available_tutorial"); $presentation = getPresentation($project_url_prefix); $admin_type = !empty($_COOKIE["admin_type"]) ? $_COOKIE["admin_type"] : "simple"; $tutorials = VideoTutorialHandler::getSimpleTutorials($project_url_prefix, $online_tutorials_url_prefix); $filtered_tutorials = VideoTutorialHandler::filterTutorials($tutorials, $entity, $admin_type); function getPresentation($peb014cfd) { return '<div><img src="' . $peb014cfd . 'img/adminhome/layers_1.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/full_page_request_flow.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_2.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_3.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_4.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_5.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_6.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_7.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/layers_8.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/deployment_1.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/deployment_2.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/deployment_3.png"/></div>
	<div><img src="' . $peb014cfd . 'img/adminhome/deployment_4.png"/></div>'; } ?>
