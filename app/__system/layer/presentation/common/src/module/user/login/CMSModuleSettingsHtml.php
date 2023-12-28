<?php 
include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); 
include_once $EVC->getModulePath("user/UserUI", $EVC->getCommonProjectName()); 
?>
<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>../user_environments.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>../user_environments.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<script>
	onUpdatePTLFromFieldsSettings = onUserLoginUpdatePTLFromFieldsSettings;
</script>

<div class="edit_settings login_settings">
	<div class="maximum_login_attempts_to_block_user">
		<label>Maximum number of login attempts until the system blocks user:</label>
		<input type="text" class="module_settings_property" name="maximum_login_attempts_to_block_user" value="5" maxlength="2" />
	</div>
	<div class="show_captcha">
		<label>Show Captcha:</label>
		<input type="checkbox" class="module_settings_property" name="show_captcha" value="1" checked onClick="togglePanelFromCheckbox(this, 'maximum_login_attempts_to_show_captcha')" />
	</div>
	<div class="maximum_login_attempts_to_show_captcha">
		<label>Maximum number of login attempts until the system shows captcha:</label>
		<input type="text" class="module_settings_property" name="maximum_login_attempts_to_show_captcha" value="3" maxlength="2" />
	</div>
	
	<div class="redirect_page_url">
		<label>Login Redirect Page Url:</label>
		<input type="text" class="module_settings_property" name="redirect_page_url" value="{$project_url_prefix}/" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
	</div>
	<div class="welcoming_message">
		<label>Logged-in Welcome Message:</label>
		<input type="text" class="module_settings_property" name="welcoming_message" value="Welcome user: '#username#'!" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="register_page_url">
		<label>Register Page Url:</label>
		<input type="text" class="module_settings_property" name="register_page_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
	</div>
	<div class="forgot_credentials_page_url">
		<label>Forgot Credentials Page Url:</label>
		<input type="text" class="module_settings_property" name="forgot_credentials_page_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
	</div>
	<div class="single_sign_on_page_url">
		<label>Single Sign On Page Url:</label>
		<input type="text" class="module_settings_property" name="single_sign_on_page_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
	</div>
	
	<?php 
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => array(
			"username" => array("validation_message" => "Username cannot be undefined."), 
			"password" => array("type" => "password", "validation_message" => "Password cannot be undefined."),
		),
		"buttons" => array(
	 		"insert" => array(
	 			"show" => true,
	 			"button_show_option_hidden" => true, 
	 			"button_label" => "Login Button", 
	 			"value" => "Login",
	 		),
	 	),
	 	"css" => true,
	 	"js" => true,
	));
	?>
	
	<div class="register_attribute_label">
		<label>Register Attribute Label:</label>
		<input type="text" class="module_settings_property" name="register_attribute_label" value="Register?" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="forgot_credentials_attribute_label">
		<label>Forgot Credentials Attribute Label:</label>
		<input type="text" class="module_settings_property" name="forgot_credentials_attribute_label" value="Forgot Credentials?" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="single_sign_on_attribute_label">
		<label>Single Sign On Attribute Label:</label>
		<input type="text" class="module_settings_property" name="single_sign_on_attribute_label" value="Single Sign On?" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="do_not_encrypt_password">
		<label>Do not encrypt the user password:</label>
		<input type="checkbox" class="module_settings_property" name="do_not_encrypt_password" value="1" />
		<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>
	</div>
	
	<?php echo UserUI::getUserEnvironmentFieldsHtml(); ?>
</div>
