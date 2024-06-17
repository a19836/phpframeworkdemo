<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once $EVC->getUtilPath("AdminMenuUIHandler"); if (!$is_admin_ui_low_code_allowed) { echo '<script>
		alert("You don\'t have permission to access this Workspace!");
		document.location="' . $project_url_prefix . 'auth/logout";
	</script>'; die(); } $logged_name = $UserAuthenticationHandler->auth["user_data"]["name"] ? $UserAuthenticationHandler->auth["user_data"]["name"] : $UserAuthenticationHandler->auth["user_data"]["username"]; $filter_by_layout_url_query = $filter_by_layout ? "&filter_by_layout=$filter_by_layout&filter_by_layout_permission=$filter_by_layout_permission" : ""; $main_layers_properties = array(); $head = AdminMenuUIHandler::getHeader($project_url_prefix, $project_common_url_prefix); $head .= '
<!-- Add Local JS and CSS files -->
<link rel="stylesheet" href="' . $project_url_prefix . 'css/admin/admin_low_code.css" type="text/css" charset="utf-8" />
<script language="javascript" type="text/javascript" src="' . $project_url_prefix . 'js/admin/admin_low_code.js"></script>

<script>
menu_item_properties = ' . json_encode($menu_item_properties) . ';
</script>'; $main_content = AdminMenuUIHandler::getContextMenus($exists_db_drivers, $get_store_programs_url); if (!$projects) $main_content .= '<script>alert("Error: No projects available! Please contact your sysadmin...");</script>'; $main_content .= '
<div id="selected_menu_properties" class="myfancypopup">
	<div class="title">Properties</div>
	<p class="content"></p>
</div>

<div id="menu_panel">
	<a class="selected_project" href="#" onClick="chooseAvailableProject(\'' . $project_url_prefix . 'admin/choose_available_project?redirect_path=admin&popup=1\');" title="Selected Project: \'' . $project . '\'. Please click here to choose another project.">' . $project . '</a>
	<span class="icon logout" title="Logout" onClick="document.location=this.getAttribute(\'logout_url\')" logout_url="' . $project_url_prefix . 'auth/logout"></span>
	<span class="login_info" title="Logged as \'' . $logged_name . '\' user."><i class="icon user"></i> ' . $logged_name . '</span>
	
	<!--span class="icon go_back" onClick="goBack()" title="Go Back"></span-->
	<span class="icon tools" onClick="chooseAvailableTool(\'' . "{$project_url_prefix}admin/choose_available_tool?filter_by_layout=$filter_by_layout&popup=1" . '\')" title="Choose a Tool"></span>
	<span class="icon refresh" onClick="refreshIframe()" title="Refresh"></span>
	' . ($is_flush_cache_allowed ? '<span class="icon flush_cache" title="Flush Cache" onClick="flushCacheFromAdmin(\'' . $project_url_prefix . 'admin/flush_cache\')"></span>' : '') . '
	<span class="icon home" onClick="goTo(this, \'home_url\', event)" home_url="' . "{$project_url_prefix}admin/admin_home?selected_layout_project=$filter_by_layout" . '" title="Go Home"></span>
</div>

<div id="left_panel">
	<ul class="tabs">'; $available_layers = array("presentation_layers", "business_logic_layers", "data_access_layers", "db_layers"); if ($layers) { foreach ($available_layers as $layer_type_name) if ($layers[$layer_type_name]) { $label = ucwords(str_replace("_", " ", $layer_type_name)); $main_content .= '<li class="tab tab_' . $layer_type_name . '"><a href="#' . $layer_type_name . '" title="' . $label . '"><i class="tab_icon main_node main_node_' . $layer_type_name . '"></i></a></li>'; } $main_content .= '<li class="tab tab_library"><a href="#library" title="Library"><i class="tab_icon main_node main_node_library"></i></a></li>'; } $main_content .= '
	</ul>'; if ($layers) { foreach ($available_layers as $layer_type_name) if ($layers[$layer_type_name]) { $only_one_layer = count($layers[$layer_type_name]) == 1; $class = !$only_one_layer || $layer_type_name == "presentation_layers" ? "with_sub_groups" : ""; $main_content .= '<div id="' . $layer_type_name . '" class="layers ' . ($only_one_layer ? "with_sub_menus" : "without_sub_menus") . '"><div id="' . $layer_type_name . '_tree" class="mytree hidden ' . $class . '"><ul>'; foreach ($layers[$layer_type_name] as $layer_name => $layer) $main_content .= AdminMenuUIHandler::getLayer($layer_name, $layer, $main_layers_properties, $project_url_prefix, $filter_by_layout, $filter_by_layout_permission, $db_driver_broker_name); $main_content .= '</ul></div>'; if ($only_one_layer) { $sub_menu_html = ""; if ($layer_type_name == "presentation_layers") $sub_menu_html = '
						<li class="level">
							<label>Level:</label>
							<select onChange="toggleComplexityLevel(this)">
								<option value="0">Basic</option>
								<option value="1">Advanced</option>
							</select>
						</li>'; $main_content .= getSubMenuHtml($sub_menu_html); } $main_content .= '</div>'; } $main_content .= '<div id="library" class="layers without_sub_menus">
			<div id="library_tree" class="mytree hidden">
				<ul>'; $main_content .= isset($layers["libs"]["lib"]) ? AdminMenuUIHandler::getLayer("lib", $layers["libs"]["lib"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= isset($layers["vendors"]["vendor"]) ? AdminMenuUIHandler::getLayer("vendor", $layers["vendors"]["vendor"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= $layers["others"]["other"] ? AdminMenuUIHandler::getLayer("other", $layers["others"]["other"], $main_layers_properties, $project_url_prefix) : ''; $main_content .= '
				</ul>
			</div>
		</div>'; } $main_content .= '
</div>
<script>
	main_layers_properties = ' . json_encode($main_layers_properties) . ';
</script>
<div id="hide_panel">
	<div class="button minimize" onClick="toggleLeftPanel(this)"></div>
</div>
<div id="right_panel">
	<iframe src="' . "{$project_url_prefix}admin/" . ($filter_by_layout ? "admin_home_project?$filter_by_layout_url_query" : "admin_home?selected_layout_project=$filter_by_layout") . '"></iframe>
	<div class="iframe_overlay">
		<div class="iframe_loading">Loading...</div>
	</div>
</div>'; function getSubMenuHtml($pf8ed4912 = "") { return '<div class="sub_menus">
			<label onClick="toggleSubmenus(this)">
				Sub-menus
				<i class="fas fa-sort-up"></i>
			</label>
			<ul>' . $pf8ed4912 . '</ul>
			<i class="fas fa-sort-down" onClick="toggleSubmenus(this)"></i>
		</div>'; } ?>
