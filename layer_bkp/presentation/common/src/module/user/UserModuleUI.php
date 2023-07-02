<?php
namespace CMSModule\user;

class UserModuleUI {
	
	public static function addPasswordGeneratorToPasswordField(&$settings) {
		$settings["fields"]["password"]["field"]["input"]["next_html"] = '<a class="password_generator" href="javascript:void(0)" onClick="generateRandomPassword(this)">Generate Password</a>';
				
		$settings["js"] .= '
if (typeof generateRandomPassword != "function")
	function generateRandomPassword(elm) {
		var input = elm.parentNode.closest(".password").querySelector("input");
		
		if (input) {
			var new_password = "";
			var length = 8;
			var lower_charset = "abcdefghijklmnopqrstuvwxyz";
			var upper_charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			var numeric_charset = "0123456789";
			var charset = lower_charset + upper_charset + numeric_charset;
			
			new_password += lower_charset.charAt(Math.floor(Math.random() * 10));
			new_password += upper_charset.charAt(Math.floor(Math.random() * 10));
			new_password += numeric_charset.charAt(Math.floor(Math.random() * 10));
			
			for (var i = 0, n = charset.length; i < length; ++i) 
				new_password += charset.charAt(Math.floor(Math.random() * n));
			
			input.value = new_password;
			input.type = "text";
		}
	}';
	}
}
?>
