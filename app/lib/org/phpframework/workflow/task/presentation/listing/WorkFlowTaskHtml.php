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
?><div class="listing_task_html page_content_task_html">
	<ul>
		<li class="settings_tab"><a href="#listing_task_html_settings">Settings</a></li>
		<li class="ui_tab"><a href="#listing_task_html_ui">UI</a></li>
		<li class="permissions_tab"><a href="#listing_task_html_permissions">Permissions</a></li>
	</ul>
	
	<div class="settings" id="listing_task_html_settings">
		<div class="listing_type">
			<label>Listing Type:</label>
			<select class="task_property_field" name="listing_type" onChange="ListingTaskPropertyObj.onChangeListingType(this, true)">
				<option value="">Table</option>
				<option value="tree">Tree</option>
				<option value="multi_form">Multi-Form</option>
			</select>
		</div>
		
		<?php include dirname(dirname($file_path)) . "/common/ChooseDBTableHtml.php"; include dirname(dirname($file_path)) . "/common/TableActionHtml.php"; include dirname(dirname($file_path)) . "/common/LinksHtml.php"; ?>
		
		<div class="pagination">
			<label>Pagination:</label>
			
			<ul>
				<li class="pagination_active">
					<label>Is Active:</label>
					<input class="task_property_field" type="checkbox" name="pagination[active]" value="1" />
				</li>
				<li class="pagination_rows_per_page">
					<label>Pagination Rows Per Page:</label>
					<input class="task_property_field" type="number" name="pagination[rows_per_page]" value="" min="1" />
				</li>
			</ul>
		</div>
		
		<?php
 include dirname(dirname($file_path)) . "/common/InnerTaskUIHtml.php"; ?>
	</div>
	
	<div class="ui" id="listing_task_html_ui">
		<?php include dirname(dirname($file_path)) . "/common/TaskUITabContentHtml.php"; ?>
	</div>
	
	<div class="permissions" id="listing_task_html_permissions">
		<?php include dirname(dirname($file_path)) . "/common/TaskPermissionsTabContentHtml.php"; ?>
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
