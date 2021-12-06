<?php 
include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); 
include_once $EVC->getModulePath("user/UserUI", $EVC->getCommonProjectName()); 
?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>../user_environments.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>../user_environments.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings forgot_credentials_settings">
	<div class="show_recover_username_through_email">
		<label>Show Recover Username Through Email:</label>
		<input type="checkbox" class="module_settings_property" name="show_recover_username_through_email" value="1" checked />
		<div class="info">(This implies that all users must have an username and email)</div>
	</div>
	<div class="show_recover_username_through_email_and_security_questions">
		<label>Show Recover Username Through Email and Security Questions:</label>
		<input type="checkbox" class="module_settings_property" name="show_recover_username_through_email_and_security_questions" value="1" checked />
		<div class="info">(This implies that all users must have an username and email)</div>
	</div>
	<div class="show_recover_password_through_email">
		<label>Show Recover Password Through Email:</label>
		<input type="checkbox" class="module_settings_property" name="show_recover_password_through_email" value="1" checked />
		<div class="info">(This implies that all users must have an username which is their email or an email in their user profile)</div>
	</div>
	<div class="show_recover_password_through_security_questions">
		<label>Show Recover Password Through Security Questions:</label>
		<input type="checkbox" class="module_settings_property" name="show_recover_password_through_security_questions" value="1" checked />
		<div class="info">(This implies that the user must know his username)</div>
	</div>
	
	<div class="admin_email">
		<label>Admin Email:</label>
		<input type="text" class="module_settings_property" name="admin_email" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="smtp_host">
		<label>SMTP Host:</label>
		<input type="text" class="module_settings_property" name="smtp_host" value="$GLOBALS['smtp_host']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="smtp_port">
		<label>SMTP Port:</label>
		<input type="text" class="module_settings_property" name="smtp_port" value="$GLOBALS['smtp_port']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="smtp_secure">
		<label>SMTP Secure:</label>
		<input type="text" class="module_settings_property" name="smtp_secure" value="$GLOBALS['smtp_secure']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="smtp_user">
		<label>SMTP Username:</label>
		<input type="text" class="module_settings_property" name="smtp_user" value="$GLOBALS['smtp_user']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="smtp_pass">
		<label>SMTP Password:</label>
		<input type="text" class="module_settings_property" name="smtp_pass" value="$GLOBALS['smtp_pass']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="redirect_page_url">
		<label>Login Redirect Page Url:</label>
		<input type="text" class="module_settings_property" name="redirect_page_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
	</div>
	<div class="username_attribute_label">
		<label>Username Attribute Label:</label>
		<input type="text" class="module_settings_property" name="username_attribute_label" value="Username" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="password_attribute_label">
		<label>Password Attribute Label:</label>
		<input type="text" class="module_settings_property" name="password_attribute_label" value="Password" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="do_not_encrypt_password">
		<label>Do not encrypt the user password:</label>
		<input type="checkbox" class="module_settings_property" name="do_not_encrypt_password" value="1" />
		<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>
	</div>
	
<?php 
	echo UserUI::getUserEnvironmentFieldsHtml();
	echo CommonModuleSettingsUI::getStyleFieldsHtml(); 
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
</div>
