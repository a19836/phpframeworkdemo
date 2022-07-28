<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings send_email_settings">
<?php 
	$fields = array(
		"from" => array("validation_type" => "email"), 
		"to" => array("validation_type" => "email", "show" => 0), 
		"reply_to" => array("validation_type" => "email", "show" => 0, "allow_null" => 1), 
		"name",
		"subject" => array("value" => "#subject#"),
		"message" => array("type" => "textarea", "value" => "#message#"),
	);
	
	echo CommonModuleSettingsUI::getHtmlPTLCode(array(
		"style_type" => true,
	 	"block_class" => true,
		"fields" => $fields,
		"buttons" => array(
	 		"insert" => array("value" => "Send", "button_label" => "Insert/Send Button", "show" => 1, "ok_message" => "Message sent!", "error_message" => "Message not sent! Please try again..."),
	 	),
	 	"css" => true,
	 	"js" => true,
	));
?>
	<div class="reset_after_send">
		<label>Reset form after sending email:</label>
		<input class="module_settings_property" type="checkbox" name="reset_after_send" value="1" />
	</div>
	
	<div class="email_template_settings">
		<label>Email Template: </label>
		
		<div class="subject">
			<label>Subject:</label>
			<input class="module_settings_property" type="text" name="subject" value="#subject#" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="message">
			<label>Message:</label>
			<textarea class="module_settings_property" type="text" name="message">#message#</textarea>
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="smtp_settings">
		<label>SMTP Settings: </label>
		
		<div class="smtp_host">
			<label>SMTP Host:</label>
			<input class="module_settings_property" type="text" name="smtp_host" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="smtp_port">
			<label>SMTP Port:</label>
			<input class="module_settings_property" type="text" name="smtp_port" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="smtp_secure">
			<label>SMTP Secure:</label>
			<select class="module_settings_property" type="text" name="smtp_secure">
				<option value="">-- Non secure --</option>
				<option>ssl</option>
				<option>tls</option>
			</select>
		</div>
		<div class="smtp_user">
			<label>SMTP User:</label>
			<input class="module_settings_property" type="text" name="smtp_user" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="smtp_pass">
			<label>SMTP Password:</label>
			<input class="module_settings_property" type="password" name="smtp_pass" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="clear"></div>
	</div>
</div>
