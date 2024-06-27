<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include $EVC->getViewPath("admin/choose_available_project"); $projects_head = $head; $projects_main_content = $main_content; include $EVC->getViewPath("admin/choose_available_tutorial"); $tutorials_head = $head; $tutorials_main_content = $main_content; $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $videos_main_content = VideoTutorialHandler::getFeaturedTutorialsHtml($filtered_tutorials); if ($videos_main_content) $videos_main_content = '<div class="featured_tutorials">
										<div class="featured_header">
											<div class="featured_header_tip">Start here</div>
											<div class="featured_header_title">Build your app with confidence</div>
											<div class="featured_header_sub_title">Unlock your potential with these essential tools and guides for beginners.</div>
										</div>
										' . $videos_main_content . '
										<div class="featured_buttons">
											<button onClick="openWindow(this, \'url\', \'videos\')" url="' . $online_tutorials_url_prefix . 'video/simple"><span class="icon video"></span> Click here to watch more videos</button>
											<button onClick="openWindow(this, \'url\', \'documentation\')" url="' . $online_tutorials_url_prefix . '"><span class="icon tutorials"></span> Click here to read our documentation</button>
										</div>
									</div>'; $head = $projects_head . $tutorials_head . '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/featured_tutorials.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_home.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_home.js"></script>

<script>
var is_popup = 1; //must be 1 so when we choose the project, it refresh the main panel.
</script>
'; $main_content = '
<div class="admin_panel">
	<div class="title">Welcome to Bloxtor, ' . $logged_name . '</div>
	
	<ul>
		<li><a href="#projs">All Projects</a></li>
		<li><a href="#tutorials">Video Tutorials</a></li>
		<li><a onClick="openOnlineTutorialsPopup(\'' . $online_tutorials_url_prefix . '\')">How it works?</a></li>
	</ul>
	
	<div id="projs" class="projs">
		' . $projects_main_content . '
		' . $videos_main_content . '
	</div>
	
	<div id="tutorials" class="tutorials">
		' . $tutorials_main_content . '
	</div>
</div>'; ?>
