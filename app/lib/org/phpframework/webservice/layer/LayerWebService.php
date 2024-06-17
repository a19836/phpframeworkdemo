<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
abstract class LayerWebService { protected $PHPFrameWork; protected $settings; protected $url; protected $web_service_validation_string; protected $broker_server_bean_name; public function __construct($v2a9b6f4e3b, $v30857f7eca = false) { $this->PHPFrameWork = $v2a9b6f4e3b; $this->settings = $v30857f7eca; $this->mcdfff12f30fd(); } private function mcdfff12f30fd() { $v6f3a2700dd = $this->settings && isset($this->settings["url"]) ? $this->settings["url"] : false; $v6f3a2700dd = empty($v6f3a2700dd) && isset($_GET["url"]) ? $_GET["url"] : $v6f3a2700dd; $v02a69d4e0f = strstr($v6f3a2700dd, "?", true); $this->url = $v02a69d4e0f ? $v02a69d4e0f : $v6f3a2700dd; $this->url = $this->url && substr($this->url, -1, 1) == "/" ? substr($this->url, 0, -1) : $this->url; $this->md955eb1fc479(); $this->f361757c993(); } private function md955eb1fc479() { unset($_GET["url"]); if (isset($_SERVER["QUERY_STRING"])) $_SERVER["QUERY_STRING"] = preg_replace("/url=([^&]*)([&]?)/u", "", $_SERVER["QUERY_STRING"]); if (isset($_SERVER["REDIRECT_QUERY_STRING"])) $_SERVER["REDIRECT_QUERY_STRING"] = preg_replace("/url=([^&]*)([&]?)/u", "", $_SERVER["REDIRECT_QUERY_STRING"]); if (isset($_SERVER["argv"]) && $_SERVER["argv"]) $_SERVER["argv"][0] = preg_replace("/url=([^&]*)([&]?)/u", "", $_SERVER["argv"][0]); } private function f361757c993() { $pd7a36e35 = isset($this->settings["global_variables"]) ? $this->settings["global_variables"] : null; $pfffd7fa4 = isset($this->settings["request_encryption_key"]) ? $this->settings["request_encryption_key"] : null; if ($pfffd7fa4 && $pd7a36e35) { include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); $pbfa01ed1 = CryptoKeyHandler::hexToBin($pfffd7fa4); $v46db43a407 = CryptoKeyHandler::hexToBin($pd7a36e35); $pd7a36e35 = CryptoKeyHandler::decryptSerializedObject($v46db43a407, $pbfa01ed1); } if ($pd7a36e35) foreach ($pd7a36e35 as $v1cfba8c105 => $pa6209df1) if ($v1cfba8c105) $GLOBALS[$v1cfba8c105] = $pa6209df1; } public function callWebService() { if ($this->web_service_validation_string && $this->url == $this->web_service_validation_string) { echo 1; die(); } $this->PHPFrameWork->loadBeansFile(BEANS_FILE_PATH); set_log_handler_settings(); $pe95619c9 = $this->PHPFrameWork->getObject($this->broker_server_bean_name); return $pe95619c9->callWebService($this->url); } } ?>
