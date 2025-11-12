<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
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
