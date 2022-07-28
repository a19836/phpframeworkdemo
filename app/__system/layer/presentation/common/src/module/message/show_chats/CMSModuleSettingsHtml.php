<?php include $EVC->getModulePath("common/init_settings", $EVC->getCommonProjectName()); ?>

<link rel="stylesheet" href="<?= $module["webroot_url"]; ?>settings.css" type="text/css" charset="utf-8" />
<script type="text/javascript" src="<?= $module["webroot_url"]; ?>settings.js"></script>

<div class="show_chats_settings">
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
	
	<div class="default_chat_user_id">
		<label>Default Chat User Id:</label>
		<input type="text" class="module_settings_property" name="default_chat_user_id" value="" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<span class="info">If you wish to open a chat by default, please enter the correspondent user id...</span>
	</div>
	
	<div class="show_chat_url">
		<label>Show chat url:</label>
		<input class="module_settings_property" type="text" name="show_chat_url" value="" url_suffix="?user_id=" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span class="info">This url should be an ajax request of the show_chat module type. To this url, the system will append the user_id of the correspondent chat to be shown, this is, something like this: http://somedomain.com/path/?user_id=&lt;user_id&gt;</span>
	</div>
	
	<div class="load_existent_chat_users_url">
		<label>Load chat users url:</label>
		<input class="module_settings_property" type="text" name="load_existent_chat_users_url" value="" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span class="info">This url should be an ajax request which returns a json list of the users that already have an existent chat with the logged user.</span>
	</div>
	
	<div class="delete_chat_url">
		<label>Delete chat url:</label>
		<input class="module_settings_property" type="text" name="delete_chat_url" value="" url_suffix="?user_id=" />
		<span class="icon search search_page_url" onclick="onIncludePageUrlTaskChooseFile(this)" title="Search Page">Search page</span>
		<span class="info">This url should be an ajax request which returns 1 on success. To this url, the system will append the user_id of the correspondent chat, this is, something like this: http://somedomain.com/path/?user_id=&lt;user_id&gt;</span>
	</div>
	
	<div class="user_label">
		<label>User Label:</label>
		<input class="module_settings_property" type="text" name="user_label" value="#username#" />
		<span class="icon add_variable inline" onclick="ProgrammingTaskUtil.onProgrammingTaskChooseCreatedVariable(this)">Add Variable</span>
		<div class="info">Please write the user label that will identify the users in the chat. You can only use the user attributes and they should be between ##, this is, something like #username#.</div>
	</div>
	
<?php 
	echo CommonModuleSettingsUI::getStyleFieldsHtml(); 
	echo CommonModuleSettingsUI::getCssFieldsHtml();
	echo CommonModuleSettingsUI::getJsFieldsHtml();
?>
</div>
