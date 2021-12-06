<?php
namespace Module\User;

include_once get_lib("org.phpframework.util.text.TextShuffler");

class UserServiceUtil {
	
	public static function getEncryptedPassword($password) {
		return password_hash(md5($password), PASSWORD_BCRYPT);
	}
	
	public static function encodeSensitiveUserData(&$data, $extra_attrs = array()) {
		if ($data["username"] && !is_array($data["username"])) //it could be a condition array
			$data["username"] = \TextShuffler::autoShuffle($data["username"]);
		
		if ($data["name"] && !is_array($data["name"])) //it could be a condition array
			$data["name"] = \TextShuffler::autoShuffle($data["name"]);
		
		if ($data["email"] && !is_array($data["email"])) //it could be a condition array
			$data["email"] = \TextShuffler::autoShuffle($data["email"]);
				
		if ($extra_attrs) {
			$extra_attrs = !is_array($extra_attrs) ? array($extra_attrs) : $extra_attrs;
			
			foreach ($extra_attrs as $attr)
				if ($attr && isset($data[$attr]) && !is_array($data[$attr])) //it could be a condition array
					$data[$attr] = \TextShuffler::autoShuffle($data[$attr]);
		}
	}
	
	public static function decodeSensitiveUserData(&$data, $extra_attrs = array()) {
		if ($data["username"] && !is_array($data["username"])) //it could be a condition array
			$data["username"] = \TextShuffler::autoUnshuffle($data["username"]);
		
		if ($data["name"] && !is_array($data["name"])) //it could be a condition array
			$data["name"] = \TextShuffler::autoUnshuffle($data["name"]);
		
		if ($data["email"] && !is_array($data["email"])) //it could be a condition array
			$data["email"] = \TextShuffler::autoUnshuffle($data["email"]);
		
		if ($extra_attrs) {
			$extra_attrs = !is_array($extra_attrs) ? array($extra_attrs) : $extra_attrs;
			
			foreach ($extra_attrs as $attr)
				if ($attr && isset($data[$attr]) && !is_array($data[$attr])) //it could be a condition array
					$data[$attr] = \TextShuffler::autoShuffle($data[$attr]);
		}
	}
	
	public static function decodeSensitiveUsersData(&$users, $extra_attrs = array()) {
		if ($users)
			foreach ($users as &$user)
				self::decodeSensitiveUserData($user, $extra_attrs);
	}
	
	/* 
	 * When call a business-logic service we can pass some default args like searching and conditions to be used in the sql queries. The values for these searching fields must be encode too, otherwise the search won't work.
	 */
	public static function encodeSearchSensitiveUserData(&$data, $extra_attrs = array()) {
		$sensitive_attrs = array("username", "name", "email");
		$extra_attrs = $extra_attrs && !is_array($extra_attrs) ? array($extra_attrs) : $extra_attrs;
		
		if (is_array($extra_attrs))
			$sensitive_attrs = array_merge($sensitive_attrs, $extra_attrs);
		
		//Based in CommonService::prepareSearch
		if ($data["searching"] && is_array($data["searching"]) && is_array($data["searching"]["fields"]))
			foreach ($data["searching"]["fields"] as $i => $field) {
				$f = strtolower($field);
				
				$pos = strpos($f, ".");
				if ($pos > 0 && !in_array($f, $sensitive_attrs))
					$f = substr($f, $pos + 1);
				
				if (in_array($f, $sensitive_attrs))
					$data["searching"]["values"][$i] = \TextShuffler::autoShuffle($data["searching"]["values"][$i]);
			}
		
		//Based in CommonService::prepareInputData and \DB::getSQLConditions
		self::encodeSearchConditionsSensitiveUserData($data["conditions"], $extra_attrs);
	}
	
	//Based in CommonService::prepareInputData and \DB::getSQLConditions
	public static function encodeSearchConditionsSensitiveUserData(&$conditions, $extra_attrs = array()) {
		if ($conditions) {
			$sensitive_attrs = array("username", "name", "email");
			$extra_attrs = $extra_attrs && !is_array($extra_attrs) ? array($extra_attrs) : $extra_attrs;
			
			if (is_array($extra_attrs))
				$sensitive_attrs = array_merge($sensitive_attrs, $extra_attrs);
			
			foreach ($conditions as $key => $value) {
				$lkey = strtolower($key);
				
				if ($lkey == "AND" || $lkey == "OR" || (is_numeric($key) && is_array($value))) {
					if (is_array($value))
						self::encodeSearchConditionsSensitiveUserData($conditions[$key], $extra_attrs);
				}
				else {
					$pos = strpos($lkey, ".");
					if ($pos > 0 && !in_array($lkey, $sensitive_attrs))
						$lkey = substr($lkey, $pos + 1);
					
					if (in_array($lkey, $sensitive_attrs)) {
						if (is_array($value)) {
							$is_assoc = array_keys($value) !== range(0, count($value) - 1);
							
							if ($is_assoc)
								$value = array($value);
							
							foreach ($value as $i => $v) {
								if (is_array($v)) {
									foreach ($v as $k => $a)
										if (strtolower($k) == "value")
											$conditions[$key][$i][$k] = \TextShuffler::autoShuffle($a);
								}
								else
									$conditions[$key][$i] = \TextShuffler::autoShuffle($v);
							}
						}
						else
							$conditions[$key] = \TextShuffler::autoShuffle($value);
					}
				}
			}
		}
	}
}
?>
