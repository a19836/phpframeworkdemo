<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
?><div class="users_perms">
	<table>
		<thead>
			<tr>
				<th class="user_type_id">User</th>
				<th class="activity_id">Permission</th>
				<th class="actions">
					<i class="icon add" onClick="PresentationTaskUtil.addUserPerm(this)"></i>
				</th>
			</tr>
		</thead>
		<tbody index_prefix="users_perms">
			<tr class="no_users"><td colspan="3">There are no configured users...</td></tr>
		</tbody>
	</table>
</div>

<div class="users_management_admin_panel">
	<a href="javascript:void(0)" onClick="PresentationTaskUtil.openUsersManagementAdminPanelPopup(this)">Users Management Admin Panel</a>
	
	<div class="users_management_admin_panel_popup myfancypopup with_iframe_title">
		<iframe></iframe>
	</div>
</div>
