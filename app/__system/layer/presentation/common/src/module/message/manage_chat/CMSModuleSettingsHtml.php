<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="manage_chat_settings">
	<div class="action">
		<label>Action:</label>
		<select class="module_settings_property" name="action" onChange="onChangeAction(this)">
			<option value="new_message">Add new Message</option>
			<option value="load_messages">Load new Messages</option>
			<option value="get_existent_chat_users">Get existent chat users</option>
			<option value="get_last_chats">Get last chats</option>
			<option value="delete_chat">Delete Chat</option>
		</select>
	</div>
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
	
	<div class="maximum_number_of_loaded_messages">
		<label>Maximum # of loaded messages per request:</label>
		<input class="module_settings_property" type="text" name="maximum_number_of_loaded_messages" value="10" />
	</div>
</div>
