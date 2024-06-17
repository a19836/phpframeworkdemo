<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.cache.service.filesystem.FileSystemServiceCacheHandler"); include_once get_lib("org.phpframework.module.ModuleCacheLayer"); include_once get_lib("org.phpframework.module.ModulePathHandler"); include_once get_lib("org.phpframework.xmlfile.XMLFileParser"); include_once get_lib("org.phpframework.dispatcher.Dispatcher"); include_once get_lib("org.phpframework.phpscript.PHPScriptHandler"); class DispatcherCacheHandler extends Dispatcher { public $settings; public $ServiceCacheHandler; private $pad21ece4; public $urls; public $selected_presentation_id; public $modules_path; public function __construct($v30857f7eca, $v3484581594) { $this->settings = array_merge($v30857f7eca, $v3484581594); if (!empty($this->settings["no_cache"])) $this->ServiceCacheHandler = false; else { if (empty($this->settings["dispatchers_cache_path"])) launch_exception(new Exception("'DispatcherCacheHandler->settings[dispatchers_cache_path]' variable cannot be empty!")); $v6e8c485545 = isset($this->settings["dispatchers_module_cache_maximum_size"]) ? $this->settings["dispatchers_module_cache_maximum_size"] : null; $v2bdd3284b4 = isset($this->settings["dispatchers_default_cache_ttl"]) ? $this->settings["dispatchers_default_cache_ttl"] : null; $pdd4ec8be = isset($this->settings["dispatchers_default_cache_type"]) ? $this->settings["dispatchers_default_cache_type"] : null; $this->ServiceCacheHandler = new FileSystemServiceCacheHandler($v6e8c485545); $this->ServiceCacheHandler->setRootPath($this->settings["dispatchers_cache_path"]); $this->ServiceCacheHandler->setDefaultTTL($v2bdd3284b4); $this->ServiceCacheHandler->setDefaultType($pdd4ec8be); } $this->pad21ece4 = new ModuleCacheLayer($this); $this->urls = array(); } public function getModuleCachedLayerDirPath() { return $this->ServiceCacheHandler ? $this->ServiceCacheHandler->getRootPath() : false; } public function setSelectedPresentationId($v00ba50906a) { $this->selected_presentation_id = $v00ba50906a; if($this->ServiceCacheHandler) { if (empty($this->settings["dispatchers_cache_path"])) { launch_exception(new Exception("'DispatcherCacheHandler->settings[dispatchers_cache_path]' variable cannot be empty!")); return false; } $this->ServiceCacheHandler->setRootPath($this->settings["dispatchers_cache_path"] . $this->selected_presentation_id . "/"); } } public function load() { if (empty($this->settings["dispatcher_caches_path"])) launch_exception(new Exception("'DispatcherCacheHandler->settings[dispatcher_caches_path]' variable cannot be empty!")); if (empty($this->settings["dispatchers_cache_file_name"])) launch_exception(new Exception("'DispatcherCacheHandler->settings[dispatchers_cache_file_name]' variable cannot be empty!")); $pf3dc0762 = $this->mf0e88e016c79() . $this->settings["dispatcher_caches_path"] . $this->settings["dispatchers_cache_file_name"]; if(file_exists($pf3dc0762)) { $v487a61b16c = "dispatcher_cache_settings"; $v5932b3e2d4 = "__system/cache_settings/".$this->selected_presentation_id."/"; if($this->ServiceCacheHandler && $this->ServiceCacheHandler->isValid($v5932b3e2d4, $v487a61b16c, false, "php")) { $this->urls = $this->ServiceCacheHandler->get($v5932b3e2d4, $v487a61b16c, "php"); } else { $v538cb1a1f7 = get_lib("org.phpframework.xmlfile.schema.dispatchers", "xsd"); $v50d32a6fc4 = XMLFileParser::parseXMLFileToArray($pf3dc0762, false, $v538cb1a1f7); $pa266c7f5 = is_array($v50d32a6fc4) ? array_keys($v50d32a6fc4) : array(); $pa266c7f5 = isset($pa266c7f5[0]) ? $pa266c7f5[0] : null; $v47e1f4f6b8 = isset($v50d32a6fc4[$pa266c7f5][0]["childs"]["url"]) ? $v50d32a6fc4[$pa266c7f5][0]["childs"]["url"] : null; $pe4342c65 = array(); $pc37695cb = $v47e1f4f6b8 ? count($v47e1f4f6b8) : 0; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v31474cd302 = $v47e1f4f6b8[$v43dd7d0051]; $v6cd9d4006f = XMLFileParser::getAttribute($v31474cd302, "method"); $v492fce9a5d = XMLFileParser::getAttribute($v31474cd302, "ttl"); $pbdbb308f = XMLFileParser::getAttribute($v31474cd302, "suffix_key"); $v15493e4c60 = XMLFileParser::getAttribute($v31474cd302, "headers"); $v67db1bd535 = XMLFileParser::getValue($v31474cd302); $v6cd9d4006f = $v6cd9d4006f ? strtolower($v6cd9d4006f) : "get"; $pe4342c65[ $v6cd9d4006f ][] = array("url" => $v67db1bd535, "ttl" => $v492fce9a5d, "suffix_key" => $pbdbb308f, "headers" => $v15493e4c60); } $this->urls = $pe4342c65; if($this->ServiceCacheHandler) { $this->ServiceCacheHandler->create($v5932b3e2d4, $v487a61b16c, $this->urls, "php"); } } } else { $this->urls = array(); } } public function getCache($v6f3a2700dd) { if($this->getErrorHandler()->ok() && $this->ServiceCacheHandler) { $pf7b3d511 = $this->f3478a69e59($v6f3a2700dd); if($pf7b3d511) { $v067674f4e4 = $this->f2103ce0786($v6f3a2700dd, $pf7b3d511); if($this->ServiceCacheHandler->isValid(false, $v067674f4e4, $pf7b3d511["ttl"])) { return $this->ServiceCacheHandler->get(false, $v067674f4e4); } } } return false; } public function setCache($v6f3a2700dd, $pf8ed4912) { if($this->getErrorHandler()->ok() && $this->ServiceCacheHandler) { $pf7b3d511 = $this->f3478a69e59($v6f3a2700dd); if($pf7b3d511) { $v067674f4e4 = $this->f2103ce0786($v6f3a2700dd, $pf7b3d511); return $this->ServiceCacheHandler->create(false, $v067674f4e4, $pf8ed4912); } } return false; } public function getHeaders($v6f3a2700dd) { $pf7b3d511 = $this->f3478a69e59($v6f3a2700dd); return $pf7b3d511 && isset($pf7b3d511["headers"]) ? str_replace('\n', "\n", $pf7b3d511["headers"]) : false; } public function prepareURL(&$v6f3a2700dd) { $v6f3a2700dd = trim($v6f3a2700dd); while(strpos($v6f3a2700dd, "//") !== false) { $v6f3a2700dd = str_replace("//", "/", $v6f3a2700dd); } } private function f2103ce0786($v6f3a2700dd, $pf7b3d511 = null) { $pbdbb308f = ""; if ($pf7b3d511 && !empty($pf7b3d511["suffix_key"])) { $pf7b3d511["suffix_key"] = str_replace("&lt;?", "<?", str_replace("?&gt;", "?>", $pf7b3d511["suffix_key"])); $pbdbb308f = "_" . PHPScriptHandler::parseContent($pf7b3d511["suffix_key"]); } return "url-" . md5($v6f3a2700dd) . "_get-" . md5(serialize($_GET)) . "_post-" . md5(serialize($_POST)) . $pbdbb308f; } private function f3478a69e59(&$v6f3a2700dd) { $pb3e5a402 = strtolower($_SERVER["REQUEST_METHOD"]); $paa1b457a = $pb3e5a402 == "post" ? (isset($this->urls["post"]) ? $this->urls["post"] : null) : ($pb3e5a402 == "get" ? (isset($this->urls["get"]) ? $this->urls["get"] : null) : array()); $v2e0a199198 = substr($v6f3a2700dd, strlen($v6f3a2700dd) - 1) == "/" ? substr($v6f3a2700dd, 0, strlen($v6f3a2700dd) - 1) : $v6f3a2700dd; $pc37695cb = $paa1b457a ? count($paa1b457a) : 0; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pae397839 = $paa1b457a[$v43dd7d0051]; $pf4ecc3e4 = isset($pae397839["url"]) ? $pae397839["url"] : null; $v5f7147fb39 = $this->med0ef0adfbf7($pf4ecc3e4); if(preg_match($v5f7147fb39, $v2e0a199198)) { $v6f3a2700dd = $v2e0a199198; return $pae397839; } } for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pae397839 = $paa1b457a[$v43dd7d0051]; $pf4ecc3e4 = isset($pae397839["url"]) ? $pae397839["url"] : null; $v5f7147fb39 = $this->med0ef0adfbf7($pf4ecc3e4); if(preg_match($v5f7147fb39, $v6f3a2700dd)) { return $pae397839; } } $v91c08a49b9 = substr($v6f3a2700dd, strlen($v6f3a2700dd) - 1) != "/" ? $v6f3a2700dd."/" : $v6f3a2700dd; for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pae397839 = $paa1b457a[$v43dd7d0051]; $pf4ecc3e4 = isset($pae397839["url"]) ? $pae397839["url"] : null; $v5f7147fb39 = $this->med0ef0adfbf7($pf4ecc3e4); if(preg_match($v5f7147fb39, $v91c08a49b9)) { $v6f3a2700dd = $v91c08a49b9; return $pae397839; } } return false; } private function med0ef0adfbf7($pae397839) { $v5f7147fb39 = "/^" . str_replace("/", "\/", $pae397839) . "$/iu"; return $v5f7147fb39; } private function mf0e88e016c79() { if (empty($this->settings["presentations_modules_file_path"])) launch_exception(new Exception("'DispatcherCacheHandler->settings[presentations_modules_file_path]' variable cannot be empty!")); if (empty($this->settings["presentations_path"])) launch_exception(new Exception("'DispatcherCacheHandler->settings[presentations_path]' variable cannot be empty!")); return ModulePathHandler::getModuleFolderPath($this->selected_presentation_id, $this->settings["presentations_modules_file_path"], $this->settings["presentations_path"], $this->modules_path, $this->settings, $this->pad21ece4); } } ?>
