<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
if (!function_exists("normalize_windows_path_to_linux")) { function normalize_windows_path_to_linux($pa32be502) { return DIRECTORY_SEPARATOR != "/" ? str_replace(DIRECTORY_SEPARATOR, "/", $pa32be502) : $pa32be502; } } if (!function_exists("get_lib")) { function get_lib($pa32be502) { $pa32be502 = strpos($pa32be502, "lib.") === 0 ? substr($pa32be502, strlen("lib.")) : $pa32be502; return dirname(dirname(dirname(dirname(normalize_windows_path_to_linux(__DIR__))))) . "/" . str_replace(".", "/", $pa32be502) . ".php"; } } include_once get_lib("org.phpframework.cms.wordpress.WordPressUrlsParser"); class WordPressRequestHandler { public $parse_output; private $v0e0f1bdb43; private $pa02dc6aa; private $pfe7577f3 = false; public function __construct($v0e0f1bdb43, $pa02dc6aa) { $this->v0e0f1bdb43 = $v0e0f1bdb43; $this->pa02dc6aa = $pa02dc6aa ? $pa02dc6aa : $v0e0f1bdb43; $this->parse_output = $this->isPHPFrameworkRequestToParse(); } public function isPHPFrameworkRequestToParse() { global $phpframework_options, $current_phpframework_result_key; if (isset($phpframework_options) || isset($current_phpframework_result_key)) return false; if ($_SERVER["HTTP_REFERER"] && !empty($_COOKIE[$this->pa02dc6aa . "_phpframework_url"])) { $v0c2387431b = explode("#", $_SERVER["HTTP_REFERER"]); $v0c2387431b = explode("?", $v0c2387431b[0]); $v0c2387431b = $v0c2387431b[0]; $v5841083cb4 = explode("#", $_COOKIE[$this->pa02dc6aa . "_phpframework_url"]); $v5841083cb4 = explode("?", $v5841083cb4[0]); $v5841083cb4 = $v5841083cb4[0]; return $v5841083cb4 == $v0c2387431b; } return false; } public function startCatchingOutput() { if ($this->parse_output) { register_shutdown_function(array($this, "endCatchingOutput")); $this->pfe7577f3 = true; ob_start(null, 0); } } public function endCatchingOutput() { if ($this->pfe7577f3) { $this->pfe7577f3 = false; $pf8ed4912 = ob_get_contents(); ob_end_clean(); $v1312342d80 = site_url() . "/"; $pf41879cb = isset($_COOKIE[$this->pa02dc6aa . "_phpframework_url"]) ? $_COOKIE[$this->pa02dc6aa . "_phpframework_url"] : null; $v8a1ab58da7 = isset($_COOKIE[$this->pa02dc6aa . "_allowed_wordpress_urls"]) ? $_COOKIE[$this->pa02dc6aa . "_allowed_wordpress_urls"] : null; $v0376534591 = isset($_COOKIE[$this->pa02dc6aa . "_parse_wordpress_urls"]) ? $_COOKIE[$this->pa02dc6aa . "_parse_wordpress_urls"] : null; $v37dc825b04 = isset($_COOKIE[$this->pa02dc6aa . "_parse_wordpress_relative_urls"]) ? $_COOKIE[$this->pa02dc6aa . "_parse_wordpress_relative_urls"] : null; $pae77d38c = array( "wordpress_site_url" => $v1312342d80, "current_page_url" => $pf41879cb ); $v5d3813882f = array( "allowed_wordpress_urls" => unserialize($v8a1ab58da7), "parse_wordpress_urls" => $v0376534591, "parse_wordpress_relative_urls" => $v37dc825b04, ); WordPressUrlsParser::parseWordPressHeaders($pae77d38c["wordpress_site_url"], $pae77d38c["current_page_url"], $v5d3813882f); if ($pf8ed4912) { $pb55d576b = substr($pf8ed4912, 0, 1) == "{" && substr($pf8ed4912, -1) == "}" ? json_decode($pf8ed4912, true) : null; if ($pb55d576b) { $pb55d576b = WordPressUrlsParser::prepareArrayWithWordPressUrls($pb55d576b, $v5d3813882f, $pae77d38c); $pf8ed4912 = json_encode($pb55d576b); } else $pf8ed4912 = WordPressUrlsParser::parseWordPressHtml($pf8ed4912, $pae77d38c["wordpress_site_url"], $pae77d38c["current_page_url"], $v5d3813882f); } echo $pf8ed4912; } } } ?>
