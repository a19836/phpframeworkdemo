<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.BrokerClient"); include_once get_lib("org.phpframework.util.web.MyCurl"); include_once get_lib("org.phpframework.util.xml.XMLSerializer"); include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); abstract class RESTBrokerClient extends BrokerClient { protected $settings; protected $global_variables_name; public function __construct($v30857f7eca = null, $pba2c5f6a = null) { parent::__construct(); $this->settings = $v30857f7eca ? $v30857f7eca : array(); $this->global_variables_name = $pba2c5f6a; } protected function requestResponse($v30857f7eca, $v539082ff30 = null) { $v6f3a2700dd = isset($v30857f7eca["url"]) ? $v30857f7eca["url"] : $this->settings["url"]; if ($v6f3a2700dd) { $v02ec69eab9 = isset($v30857f7eca["response_type"]) ? $v30857f7eca["response_type"] : $this->settings["response_type"]; $pfffd7fa4 = isset($this->settings["request_encryption_key"]) ? $this->settings["request_encryption_key"] : null; $pcc7198a3 = isset($this->settings["response_encryption_key"]) ? $this->settings["response_encryption_key"] : null; $pba66cbf9 = isset($this->settings["rest_auth_user"]) ? $this->settings["rest_auth_user"] : null; $v1d6484c1f2 = isset($this->settings["rest_auth_pass"]) ? $this->settings["rest_auth_pass"] : null; unset($v30857f7eca["url"]); unset($v30857f7eca["response_type"]); unset($v30857f7eca["rest_auth_user"]); unset($v30857f7eca["rest_auth_pass"]); unset($v30857f7eca["request_encryption_key"]); unset($v30857f7eca["response_encryption_key"]); if (!isset($v30857f7eca["follow_location"])) $v30857f7eca["follow_location"] = 1; if (!isset($v30857f7eca["referer"])) $v30857f7eca["referer"] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null; $v30857f7eca["header"] = true; $pd65a9318 = array( "response_type" => $v02ec69eab9, ); if ($v539082ff30) { if ($pfffd7fa4) { $pbfa01ed1 = CryptoKeyHandler::hexToBin($pfffd7fa4); $v46db43a407 = CryptoKeyHandler::encryptSerializedObject($v539082ff30, $pbfa01ed1); $v539082ff30 = CryptoKeyHandler::binToHex($v46db43a407); } $pd65a9318["data"] = $v539082ff30; } if ($pba66cbf9 && $v1d6484c1f2) { $pd65a9318["rest_auth_user"] = password_hash($pba66cbf9, PASSWORD_DEFAULT); $pd65a9318["rest_auth_pass"] = password_hash($v1d6484c1f2, PASSWORD_DEFAULT); } if ($this->global_variables_name) { $pd7a36e35 = array(); foreach ($this->global_variables_name as $v1cfba8c105) $pd7a36e35[$v1cfba8c105] = $GLOBALS[$v1cfba8c105]; if ($pfffd7fa4 && $pd7a36e35) { $pbfa01ed1 = $pbfa01ed1 ? $pbfa01ed1 : CryptoKeyHandler::hexToBin($pfffd7fa4); $v46db43a407 = CryptoKeyHandler::encryptSerializedObject($pd7a36e35, $pbfa01ed1); $pd7a36e35 = CryptoKeyHandler::binToHex($v46db43a407); } $pd65a9318["gv"] = $pd7a36e35; } $v1fc19b96e1 = parse_url($v6f3a2700dd, PHP_URL_HOST); $v7c0d95d431 = explode(":", $_SERVER["HTTP_HOST"]); $v7c0d95d431 = $v7c0d95d431[0]; $v5638f46235 = $v7c0d95d431 == $v1fc19b96e1 ? $_COOKIE : null; debug_log_function(get_class($this) . "->requestResponse", array($v6f3a2700dd, $v30857f7eca)); $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initSingle( array( "url" => $v6f3a2700dd, "cookie" => $v5638f46235, "settings" => $v30857f7eca, "post" => $pd65a9318, ) ); $v56a64ecb97->get_contents(false); $v040fc148b8 = $v56a64ecb97->getData(); $pae77d38c = isset($v040fc148b8[0]["content"]) ? $v040fc148b8[0]["content"] : null; $v6c438ea8cd = isset($v040fc148b8[0]["header"]) ? $v040fc148b8[0]["header"] : null; if ($pcc7198a3 && $pae77d38c) { $pbfa01ed1 = CryptoKeyHandler::hexToBin($pcc7198a3); $v46db43a407 = CryptoKeyHandler::hexToBin($pae77d38c); $pae77d38c = CryptoKeyHandler::decryptText($v46db43a407, $pbfa01ed1); } if ($v02ec69eab9 == "xml") $v7bd5d88a74 = XMLSerializer::convertValidXmlToVar($pae77d38c, "response", "row"); else if ($v02ec69eab9 == "json") $v7bd5d88a74 = json_decode($pae77d38c, true); else { if ($v6c438ea8cd && !empty($v6c438ea8cd["Response-Object-Lib"])) { $pf3dc0762 = get_lib($v6c438ea8cd["Response-Object-Lib"]); if (file_exists($pf3dc0762)) include_once $pf3dc0762; } $v7bd5d88a74 = unserialize($pae77d38c); } if ($v7bd5d88a74 && isset($v7bd5d88a74["method"])) return $v7bd5d88a74["result"]; launch_exception(new Exception("Error connecting to REST broker with url: $v6f3a2700dd")); return null; } launch_exception(new Exception("Empty REST broker url!")); return null; } } ?>
