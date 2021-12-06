<?php
include_once get_lib("org.phpframework.util.text.TextTranslator");
include __DIR__ . "/TranslatorSettings.php";

class TranslatorUtil extends TranslatorSettings {
	
	private static $TextTranslator;
	
	public static function getTextTranslatorRootFolderPath($EVC) {
		$folder_path = trim(self::getConstantVariable("TEXT_TRANSLATOR_ROOT_FOLDER_PATH"));
		if (empty($folder_path)) {
			$folder_path = $EVC->getModulesPath( $EVC->getCommonProjectName() ) . "translator/translations";
		}
		
		return substr($folder_path, -1) != "/" ? "$folder_path/" : $folder_path;
	}
	
	public static function getTextTranslatorDefaultLanguage() {
		$lang = trim(self::getConstantVariable("TEXT_TRANSLATOR_DEFAULT_LANGUAGE"));
		return $lang ? $lang : "en";
	}
	
	public static function getTextTranslatorObject($EVC) {
		if (!self::$TextTranslator)
			self::$TextTranslator = new TextTranslator(self::getTextTranslatorRootFolderPath($EVC), self::getTextTranslatorDefaultLanguage());
		
		return self::$TextTranslator;
	}
	
	/* CATEGORY METHODS */
	
	public static function insertCategory($EVC, $data) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->insertCategory($data["category"]);
	}
	
	public static function updateCategory($EVC, $data) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->updateCategory($data["old_category"], $data["new_category"]);
	}
	
	public static function deleteCategory($EVC, $category) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->removeCategory($category);
	}
	
	public static function categoryExists($EVC, $category) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->categoryExists($category) ? array("category" => $category) : null;
	}
	
	public static function getCategories($EVC, $path = null) {
		$categories = array();
		
		$root_path = self::getTextTranslatorRootFolderPath($EVC);
		$path = $path ? "$path/" : "";
		$files = array_diff(scandir($root_path . $path), array('.', '..'));
	
		foreach ($files as $file)
			if (is_dir($root_path . $path . $file)) {
				$sub_files = self::getCategories($EVC, $path . $file);
				$categories[ $path . $file ] = $sub_files;
			}
		
		return $categories;
	}
	
	/* LANGUAGES METHODS */
	
	public static function insertLanguage($EVC, $data) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->insertLanguage($data["language"], $data["category"]);
	}
	
	public static function updateLanguage($EVC, $data) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->updateLanguage($data["old_language"], $data["new_language"], $data["category"]);
	}
	
	public static function deleteLanguage($EVC, $lang, $category) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->removeLanguage($lang, $category);
	}
	
	public static function languageExists($EVC, $lang, $category) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->languageExists($lang, $category) ? array("language" => $lang, "category" => $category) : null;
	}
	
	public static function getLanguages($EVC, $category) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->getCategoryLanguages($category);
	}
	
	/* TRANSLATION METHODS */
	
	public static function setTranslations($EVC, $translations, $category, $lang) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->setTranslationsFile($translations, $category, $lang);
	}
	
	public static function getTranslations($EVC, $category, $lang) {
		$TextTranslator = self::getTextTranslatorObject($EVC);
		return $TextTranslator->getTranslations($category, $lang);
	}
}
?>
