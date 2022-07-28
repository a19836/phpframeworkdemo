<?php
class UserUI {
	
	public static function getUserEnvironmentFieldsHtml($title = "Link this user with the following environments:<br/>This means that if a user has an environment defined, it's username will only correspond to these environments, meaning that it's possible to have multiple users with the same username for different environments.<br/>If no environment defined, the users will be global to all DB and repeat usernames won't be possible.<br/>User environments:", $var_name = "user_environments") {
		$user_environment_html = '
			<div class="user_environment">
				<div class="environment_id">
					<label>Environment ID:</label>
					<input type="text" class="module_settings_property" name="' . $var_name . '[]" />
				</div>
				<span class="icon delete" onClick="$(this).parent().remove()">Remove</span>
				<div class="clear"></div>
			</div>';
		
		return '
		<script>
			var ' . $var_name . '_html = \'' . addcslashes(str_replace("\n", "", $user_environment_html), "\\'") . '\';
		</script>
		<div class="clear"></div>
		<div class="user_environments_settings ' . $var_name . '">
			<label class="user_environments_main_label">' . $title . '</label>
			<span class="icon add" onClick="addUserEnvironmentItem(this, ' . $var_name . '_html)">Add</span>
			
			' . str_replace('<span class="icon delete" onClick="$(this).parent().remove()">Remove</span>', '', $user_environment_html) . '
		</div>
		<div class="clear"></div>';
	}
}
?>
