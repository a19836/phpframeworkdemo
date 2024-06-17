<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
?><div class="form_task_html page_content_task_html">
	<ul>
		<li class="settings_tab"><a href="#form_task_html_settings">Settings</a></li>
		<li class="ui_tab"><a href="#form_task_html_ui">UI</a></li>
		<li class="permissions_tab"><a href="#form_task_html_permissions">Permissions</a></li>
	</ul>
	
	<div class="settings" id="form_task_html_settings">
		<?php include dirname(dirname($file_path)) . "/common/ChooseDBTableHtml.php"; include dirname(dirname($file_path)) . "/common/FormActionHtml.php"; include dirname(dirname($file_path)) . "/common/LinksHtml.php"; include dirname(dirname($file_path)) . "/common/InnerTaskUIHtml.php"; ?>
	</div>
	
	<div class="ui" id="form_task_html_ui">
		<?php include dirname(dirname($file_path)) . "/common/TaskUITabContentHtml.php"; ?>
	</div>
	
	<div class="permissions" id="form_task_html_permissions">
		<?php include dirname(dirname($file_path)) . "/common/TaskPermissionsTabContentHtml.php"; ?>
	</div>
	
	<div class="task_property_exit" exit_id="default_exit" exit_color="#426efa"></div>
</div>
