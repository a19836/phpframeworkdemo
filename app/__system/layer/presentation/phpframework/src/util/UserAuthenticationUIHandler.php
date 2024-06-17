<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class UserAuthenticationUIHandler { public static function getMenu($pdf77ee66, $peb014cfd, $v9431023a8c = null) { return '
		<ul>
			<li class="current_user">Current User: "' . $pdf77ee66->auth["user_data"]["username"] . '"</li>
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
		</ul>'; } } ?>
