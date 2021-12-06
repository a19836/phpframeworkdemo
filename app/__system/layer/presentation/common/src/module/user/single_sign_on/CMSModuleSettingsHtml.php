<?php 
include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); 
include_once $EVC->getModulePath("user/UserUI", $EVC->getCommonProjectName()); 
?>

<link rel="stylesheet" href="<?= $project_common_url_prefix; ?>module/common/validate_module_object/settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $project_common_url_prefix; ?>module/common/validate_module_object/settings.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>../user_environments.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>../user_environments.js"></script>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="edit_settings">
	<ul>
		<li><a href="#auth0_settings">Auth0 Settings</a></li>
		<li><a href="#actions_settings">Actions Settings</a></li>
		<li><a href="#user_settings">User Settings</a></li>
	</ul>
	
	<div id="auth0_settings" class="auth0_settings tab_container">
		<div class="domain">
			<label>Domain:</label>
			<input class="module_settings_property" name="domain" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="client_id">
			<label>Client ID:</label>
			<input class="module_settings_property" name="client_id" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="audience">
			<label>Audience:</label>
			<input class="module_settings_property" name="audience" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="secret">
			<label>Secret:</label>
			<input class="module_settings_property" name="secret" value="" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		<div class="notes">
			<label>Notes:</label>
			<ul>
				<li>Please add all entity/page's urls where this block is related, in the "Allowed Callback URLs" section of the Auth0 admin panel.</li>
				<li>To open your Auth0 admin panel, please click <a href="https://manage.auth0.com/dashboard/" target="auth0_admin_panel">here</a></li>
			</ul>
		</div>
	</div>
	
	<div id="actions_settings" class="actions_settings tab_container validate_object_to_object_settings">
		<div class="actions_style_type">
			<label>Actions Style Type:</label>
			<select class="module_settings_property" name="actions_style_type">
				<option value="">With the Module's Default + Template Style</option>
				<option value="template">With Only the Template Style</option>
			</select>
		</div>
		
		<?php 
		echo CommonModuleSettingsUI::getObjectToObjectValidationActionsHtml();
		?>
	</div>
	
	<div id="user_settings" class="user_settings tab_container">
		<div class="user_settings_type">
			<label>Type:</label>
			<select class="module_settings_property" name="user_settings_type" onChange="onUserSettingsTypeChange(this)">
				<option value="relate_with_specific_user">Related with specific user</option>
				<option value="relate_with_logged_user">Relate with logged user</option>
				<option value="create_new_user_if_none_found">Create new user if none found</option>
				<option value="new_user_creation_not_allowed">New users not allowed</option>
			</select>
		</div>
		
		<div class="user_id">
			<label>User Id:</label>
			<input class="module_settings_property" name="user_id" />
			<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		</div>
		
		<div class="other_user_settings">
			<ul>
				<li><a href="#user_relation_settings">Relate user Settings</a></li>
				<li><a href="#user_creation_settings">Register user Settings</a></li>
			</ul>
			
			<div id="user_relation_settings" class="user_relation_settings tab_container">
				<div class="relates_user_by_email">
					<label>If logged user doesn't exist yet, relates it with existent user with the same email?</label>
					<input type="checkbox" class="module_settings_property" name="relates_user_by_email" value="1" checked />
				</div>
				
				<div class="confirms_user_relation_by_email">
					<label>Confirms found user that should be related?</label>
					<input type="checkbox" class="module_settings_property" name="confirms_user_relation_by_email" value="1" checked />
				</div>
				
				<div class="found_users_list_class">
					<label>Found users list class: </label>
					<input class="module_settings_property" name="found_users_list_class" />
					<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
				</div>
			</div>
			
			<div id="user_creation_settings" class="user_creation_settings tab_container">
				<div class="user_type_id">
					<label>User Type:</label>
					<select class="module_settings_property" name="user_type_id">
					</select>
				</div>
				
				<div class="do_not_encrypt_password">
					<label>Do not encrypt the user password:</label>
					<input type="checkbox" class="module_settings_property" name="do_not_encrypt_password" value="1" />
					<div class="info">(This means that the passwords will not be encrypted in the DB and the Sysadmin can see it by accessing directly the mu_user table in the DB)</div>
				</div>
				
				<?php 
					$fields = array(
						"username", 
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
					 	"css" => false,
					 	"js" => false,
					));
					echo CommonModuleSettingsUI::getObjectToObjectFieldsHtml("Relate this user with the following objects:");
				?>
			</div>
		</div>
		
		<?php
			echo UserUI::getUserEnvironmentFieldsHtml();
		?>
	</div>

	<?php
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
	?>
</div>
