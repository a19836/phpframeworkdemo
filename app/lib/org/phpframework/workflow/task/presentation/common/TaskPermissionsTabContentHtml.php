<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
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
