<?php
include_once dirname(__DIR__) . "/common/CommonSettings.php";

class UserSettings extends CommonSettings {
	const DEFAULT_USER_SESSION_EXPIRATION_TTL = 86400;//60 secs => 1 min; 3600 secs => 1 hour; 86400 secs => 1 day.
	const DEFAULT_USER_SESSION_BLOCKED_TTL = 3600;//60 secs => 1 min; 3600 secs => 1 hour; 86400 secs => 1 day.
	const USER_SESSION_ID_VARIABLE_NAME = "session_id";
	const USER_SESSION_CONTROL_VARIABLE_NAME = "session_control";
	const USER_SESSION_CONTROL_EXPIRED_TIME = 1800; //30 * 60 = 1800 secs = 30 minutes
	const USER_SESSION_CONTROL_ENCRYPTION_KEY_HEX = "45983abede350fe7351cf74c0f5d79f3"; //hexadecimal key created through CryptoKeyHandler::getKey()
	const USER_SESSION_CONTROL_METHODS = array("post"); //POST bc it was high probable that was presented a form to the user in the previous request.
	const USER_LOGIN_SHOW_CAPTCHA_VARIABLE_NAME = "show_captcha";
	const HASH_SENSITIVE_DATA = false;
	const MAXIMUM_USERS_RECORDS_IN_COMBO_BOX = 500;
}
?>
