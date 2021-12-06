<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="show_chat_settings">
	<div class="session_id">
		<label>Session Id:</label>
		<input type="text" class="module_settings_property" name="session_id" value="$_COOKIE['session_id']" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="logged_user_id">
		<label>or Logged User Id:</label>
		<input type="text" class="module_settings_property" name="logged_user_id" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	<div class="clear"></div>
	
	<div class="to_user_id">
		<label>To User Id:</label>
		<input class="module_settings_property" type="text" name="to_user_id" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="to_user_label">
		<label>To User Label:</label>
		<input class="module_settings_property" type="text" name="to_user_label" value="#username#" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<span class="info">Please write the user label that will identify the users in the chat. You can only use the user attributes and they should be between ##, this is, something like #username#.</span>
	</div>
	
	<div class="maximum_number_of_loaded_messages">
		<label>Maximum # of loaded messages per request:</label>
		<input class="module_settings_property" type="text" name="maximum_number_of_loaded_messages" value="10" />
	</div>
	
	<div class="load_messages_url">
		<label>Load messages url:</label>
		<input class="module_settings_property" type="text" name="load_messages_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span class="info">This url should be an ajax request which returns an json object with the correspondent messages. This request should receive the post variables: 'message_id' which is a numeric id of the last shown message; and the 'direction' variable which can be positive or negative. If 'direction' is negative, returns the previous messages from message_id, otherwise returns the next messages. Both variables are optional.</span>
	</div>
	
	<div class="on_load_error_message">
		<label>On Load Error message:</label>
		<input class="module_settings_property" type="text" name="on_load_error_message" value="Error trying to load messages. Please try again..." />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
	<div class="send_message_url">
		<label>Send message url:</label>
		<input class="module_settings_property" type="text" name="send_message_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span class="info">This url should be an ajax request which returns 1 in success. This request should receive the post variable: 'content' which is the message content;</span>
	</div>
	
	<div class="on_send_error_message">
		<label>On Send Error message:</label>
		<input class="module_settings_property" type="text" name="on_send_error_message" value="Error trying to send message. Please try again..." />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getStyleFieldsHtml(); 
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
</div>
