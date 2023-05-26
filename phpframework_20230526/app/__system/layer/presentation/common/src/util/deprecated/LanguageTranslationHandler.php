<?php
class LanguageTranslationHandler {
	
	public static $default_lan = DEFAULT_LAN;
	
	public static $language_translations = array(
		"en" => array(
			//No need, bc the strings are already in English. Only add here if you want to change some string.
		),
		"pt" => array(
			//TODO
		),
	);
	
	public static function getLanguageTranslation($id) {
		$lan = isset($_COOKIE["lan"]) && $_COOKIE["lan"] ? $_COOKIE["lan"] : self::$default_lan;
		
		$translations = self::$language_translations[ strtolower($lan) ];
		
		if (isset($translations) && isset($translations[$id])) {
			return $translations[$id];
		}
		
		return $id;
	}
}
?>
