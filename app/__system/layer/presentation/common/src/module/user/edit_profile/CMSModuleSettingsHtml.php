<?php 
include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); 
include_once $EVC->getModulePath("user/UserUI", $EVC->getCommonProjectName()); 
?>

<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/edit_settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>../user_environments.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>../user_environments.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings">
	<div class="do_not_encrypt_password">
		<label>Do not encrypt the user password:</label>
		<input type="checkbox" class="module_settings_property" name="do_not_encrypt_password" value="1" />
		<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>
	</div>
	
<?php 
	$fields = array(
		"username", 
		"current_password" => array("type" => "password"),
		"password" => array("type" => "password"),
		"name",
		"email" => array("validation_type" => "email", "validation_message" => "Invalid Email format."),
		"security_question_1" => array("type" => "select"),
		"security_answer_1",
		"security_question_2" => array("type" => "select"),
		"security_answer_2",
		"security_question_3" => array("type" => "select"),
		"security_answer_3",
		"user_attachments" => array("show" => 0),
	);
	$CommonModuleTableExtraAttributesSettingsUtil->prepareNewFieldsSettings("user", $fields, null, array("is_edit" => true));
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
		"buttons" => false,
	 	"css" => true,
	 	"js" => true,
	));
	echo UserUI::getUserEnvironmentFieldsHtml();
	echo CommonModuleSettingsUI::getObjectToObjectFieldsHtml("Relate this user with the following objects:");
?>
</div>
