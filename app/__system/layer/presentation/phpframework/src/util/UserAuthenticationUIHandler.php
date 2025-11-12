<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class UserAuthenticationUIHandler { public static function getMenu($pdf77ee66, $peb014cfd, $v9431023a8c = null) { $pd97bc935 = isset($pdf77ee66->auth["user_data"]["username"]) ? $pdf77ee66->auth["user_data"]["username"] : null; return '
		<ul>
			<li class="current_user">Current User: "' . $pd97bc935 . '"</li>
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_users" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_users">Manage Users</a></li>
			<!--li' . ($v9431023a8c == "user/edit_user" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_user">Add User</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_user_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_user_types">Manage User Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_user_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_user_type">Add User Type</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_object_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_object_types">Manage Object Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_object_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_object_type">Add Object Type</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_user_user_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_user_user_types">Manage User User Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_user_user_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_user_user_type">Add User User Type</a></li-->
			
			<li class="manage_menu_item' . ($v9431023a8c == "user/manage_permissions" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_permissions">Manage Permissions</a></li>
			<!--li' . ($v9431023a8c == "user/edit_permission" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_permission">Add Permission</a></li-->
			
			<li class="manage_menu_item manage_user_type_permissions' . ($v9431023a8c == "user/manage_user_type_permissions" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_user_type_permissions">Manage User Type Permissions</a></li>
			
			<li class="manage_menu_item manage_layout_types' . ($v9431023a8c == "user/manage_layout_types" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_layout_types">Manage Layout Types</a></li>
			<!--li' . ($v9431023a8c == "user/edit_layout_type" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_layout_type">Add Layout Type</a></li-->
			<li class="manage_menu_item manage_layout_type_permissions' . ($v9431023a8c == "user/manage_layout_type_permissions" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_layout_type_permissions">Manage Layout Type Permissions</a></li>
			
			<li class="manage_menu_item manage_reserved_db_table_names' . ($v9431023a8c == "user/manage_reserved_db_table_names" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_reserved_db_table_names">Manage Reserved DB Table Name</a></li>
			<!--li' . ($v9431023a8c == "user/edit_reserved_db_table_name" ? ' class="active"' : '') . '><a href="' . $peb014cfd . 'user/edit_reserved_db_table_name">Add Reserved DB Table Name</a></li-->
			
			<li class="manage_menu_item manage_login_controls' . ($v9431023a8c == "user/manage_login_controls" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/manage_login_controls">Manage Login Controls</a></li>
			
			' . ($pdf77ee66->isLocalDB() ? '<li class="manage_menu_item change_db_keys' . ($v9431023a8c == "user/change_db_keys" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/change_db_keys">Change DB Keys</a></li>' : '') . '
			
			<li class="manage_menu_item change_auth_settings' . ($v9431023a8c == "user/change_auth_settings" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/change_auth_settings">Change Auth Settings</a></li>
			<li class="manage_menu_item change_other_settings' . ($v9431023a8c == "user/change_other_settings" ? ' active' : '') . '"><a href="' . $peb014cfd . 'user/change_other_settings">Change Other Settings</a></li>
		</ul>'; } } ?>
