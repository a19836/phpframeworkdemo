<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class MyCurl { private $v539082ff30; public static function downloadFile($pd404993b, &$v9a84a79e2e = null) { if ($pd404993b) { $v9a84a79e2e = tmpfile(); $pd2e8395a = stream_get_meta_data($v9a84a79e2e); $v390502fcc0 = isset($pd2e8395a['uri']) ? $pd2e8395a['uri'] : null; $v30857f7eca = array( "url" => str_replace(" ","%20", $pd404993b), "settings" => array( "follow_location" => true, "connection_timeout" => 50, "CURLOPT_TIMEOUT" => 50, "CURLOPT_FILE" => $v9a84a79e2e, ) ); $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initSingle($v30857f7eca); $v56a64ecb97->get_contents(); $v539082ff30 = $v56a64ecb97->getData(); $v539082ff30 = isset($v539082ff30[0]) ? $v539082ff30[0] : null; if (!empty($v539082ff30["info"]) && isset($v539082ff30["info"]["http_code"]) && $v539082ff30["info"]["http_code"] == 200) { return array( "name" => basename(parse_url($pd404993b, PHP_URL_PATH)), "type" => !empty($v539082ff30["info"]["content_type"]) ? $v539082ff30["info"]["content_type"] : mime_content_type($v390502fcc0), "tmp_name" => $v390502fcc0, "error" => 0, "size" => !empty($v539082ff30["info"]["size_download"]) ? $v539082ff30["info"]["size_download"] : filesize($v390502fcc0), ); } } return null; } public static function getUrlContents($v6d7009fb42, $pbd9f98de = null) { $v56a64ecb97 = new MyCurl(); $v56a64ecb97->initSingle($v6d7009fb42); $v56a64ecb97->get_contents(); $v539082ff30 = $v56a64ecb97->getData(); $v539082ff30 = isset($v539082ff30[0]) ? $v539082ff30[0] : null; $pb5a67bd8 = isset($v539082ff30["header"]) ? $v539082ff30["header"] : null; $pb4aa6f5a = isset($v539082ff30["content"]) ? $v539082ff30["content"] : null; $v6159e3c80c = isset($v539082ff30["settings"]) ? $v539082ff30["settings"] : null; $pba23d78c = $pbd9f98de == "header" ? $pb5a67bd8 : ( in_array($pbd9f98de, array("content", "content_json", "content_xml", "content_xml_simple", "content_serialized")) ? $pb4aa6f5a : ( $pbd9f98de == "settings" ? $v6159e3c80c : $v539082ff30 ) ); if ($pba23d78c) { if ($pbd9f98de == "content_json") $pba23d78c = json_decode($pba23d78c, true); else if ($pbd9f98de == "content_xml" || $pbd9f98de == "content_xml_simple") { $v6dcd71ad57 = new MyXML($pba23d78c); $pba23d78c = $v6dcd71ad57->toArray(); if ($pbd9f98de == "content_xml_simple") $pba23d78c = MyXML::complexArrayToBasicArray($pba23d78c, array("convert_attributes_to_childs" => true)); } else if ($pbd9f98de == "content_serialized") $pba23d78c = unserialize($pba23d78c); } return $pba23d78c; } public function initSingle($v539082ff30) { if (!isset($v539082ff30["post"])) $v539082ff30["post"] = array(); if (!isset($v539082ff30["get"])) $v539082ff30["get"] = array(); if (!isset($v539082ff30["cookie"])) $v539082ff30["cookie"] = array(); if (!isset($v539082ff30["url"])) $v539082ff30["url"] = false; if (!isset($v539082ff30["files"])) $v539082ff30["files"] = false; if (!isset($v539082ff30["settings"])) $v539082ff30["settings"] = array(); $v539082ff30["post"] = $this->f5f89493a89($v539082ff30["post"]); $v539082ff30["get"] = $this->ma901843720f2($v539082ff30["get"]); $v539082ff30["cookie"] = $this->f0889b9d9fd($v539082ff30["cookie"]); $v539082ff30["settings"] = $this->ma2d0f3075343($v539082ff30["settings"]); if ($v539082ff30["get"]) { $v8a4df75785 = strpos($v539082ff30["url"], "?"); $v539082ff30["url"] .= is_numeric($v8a4df75785) ? $v539082ff30["get"] : "?" . $v539082ff30["get"]; } $this->v539082ff30 = array( array( "url" => $v539082ff30["url"], "post" => $v539082ff30["post"], "get" => $v539082ff30["get"], "cookie" => $v539082ff30["cookie"], "files" => $v539082ff30["files"], "settings" => $v539082ff30["settings"], "content" => false, "error" => false, ) ); } public function initMultiple($v539082ff30) { $pc37695cb = $v539082ff30 ? count($v539082ff30) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if (!isset($v539082ff30[$v43dd7d0051]["post"])) $v539082ff30[$v43dd7d0051]["post"] = array(); if (!isset($v539082ff30[$v43dd7d0051]["get"])) $v539082ff30[$v43dd7d0051]["get"] = array(); if (!isset($v539082ff30[$v43dd7d0051]["cookie"])) $v539082ff30[$v43dd7d0051]["cookie"] = array(); if (!isset($v539082ff30[$v43dd7d0051]["url"])) $v539082ff30[$v43dd7d0051]["url"] = false; if (!isset($v539082ff30[$v43dd7d0051]["files"])) $v539082ff30[$v43dd7d0051]["files"] = false; if (!isset($v539082ff30[$v43dd7d0051]["settings"])) $v539082ff30[$v43dd7d0051]["settings"] = array(); $v539082ff30[$v43dd7d0051]["post"] = $this->f5f89493a89($v539082ff30[$v43dd7d0051]["post"]); $v539082ff30[$v43dd7d0051]["get"] = $this->ma901843720f2($v539082ff30[$v43dd7d0051]["get"]); $v539082ff30[$v43dd7d0051]["cookie"] = $this->f0889b9d9fd($v539082ff30[$v43dd7d0051]["cookie"]); $v539082ff30[$v43dd7d0051]["settings"] = $this->ma2d0f3075343($v539082ff30[$v43dd7d0051]["settings"]); if ($v539082ff30[$v43dd7d0051]["get"]) { $v8a4df75785 = strpos($v539082ff30[$v43dd7d0051]["url"], "?"); $v539082ff30[$v43dd7d0051]["url"] .= is_numeric($v8a4df75785) ? $v539082ff30[$v43dd7d0051]["get"] : "?" . $v539082ff30[$v43dd7d0051]["get"]; } $v539082ff30[$v43dd7d0051]["content"] = false; $v539082ff30[$v43dd7d0051]["error"] = false; } $this->v539082ff30 = $v539082ff30; } public function initSingleGroup($v539082ff30) { if (!isset($v539082ff30["post"])) $v539082ff30["post"] = array(); if (!isset($v539082ff30["get"])) $v539082ff30["get"] = array(); if (!isset($v539082ff30["cookie"])) $v539082ff30["cookie"] = array(); if (!isset($v539082ff30["files"])) $v539082ff30["files"] = false; if (!isset($v539082ff30["settings"])) $v539082ff30["settings"] = array(); $v539082ff30["post"] = $this->f5f89493a89($v539082ff30["post"]); $v539082ff30["get"] = $this->ma901843720f2($v539082ff30["get"]); $v539082ff30["cookie"] = $this->f0889b9d9fd($v539082ff30["cookie"]); $v539082ff30["settings"] = $this->ma2d0f3075343($v539082ff30["settings"]); $this->v539082ff30 = array(); $pc37695cb = !empty($v539082ff30["urls"]) ? count($v539082ff30["urls"]) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if ($v539082ff30["get"]) { $v8a4df75785 = strpos($v539082ff30["urls"][$v43dd7d0051], "?"); $v539082ff30["urls"][$v43dd7d0051] .= is_numeric($v8a4df75785) ? $v539082ff30["get"] : "?" . $v539082ff30["get"]; } $this->v539082ff30[] = array( "url" => $v539082ff30["urls"][$v43dd7d0051], "post" => $v539082ff30["post"], "get" => $v539082ff30["get"], "cookie" => $v539082ff30["cookie"], "files" => $v539082ff30["files"], "settings" => $v539082ff30["settings"], "content" => false, "error" => false, ); } } public function get_contents($pfcc7bae3 = false) { $pf503710f = !defined("PHP_SESSION_NONE") || session_status() != PHP_SESSION_NONE ? session_id() : false; if ($pf503710f) session_write_close(); if ($this->v539082ff30) { if ($pfcc7bae3) $v5c1c342594 = $this->f632382768f($pfcc7bae3); else $v5c1c342594 = $this->f6679986af5(); } if ($pf503710f) session_start(); return $v5c1c342594; } private function f6679986af5() { $v5c1c342594 = true; $pc37695cb = $this->v539082ff30 ? count($this->v539082ff30) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pf3d2eef6 = curl_init(); $this->f583227374b($pf3d2eef6, $this->v539082ff30[$v43dd7d0051]); $pae77d38c = curl_exec($pf3d2eef6); if (!empty($this->v539082ff30[$v43dd7d0051]["settings"]["header"])) { $pa2203b6d = curl_getinfo($pf3d2eef6, CURLINFO_HEADER_SIZE); $v6c438ea8cd = substr($pae77d38c, 0, $pa2203b6d); $pae77d38c = substr($pae77d38c, $pa2203b6d); $this->v539082ff30[$v43dd7d0051]["header"] = self::ma34b6c51eb3b($v6c438ea8cd); } $this->v539082ff30[$v43dd7d0051]["content"] = $pae77d38c; if (curl_errno($pf3d2eef6)) { $this->v539082ff30[$v43dd7d0051]["error"] = curl_error($pf3d2eef6); $v5c1c342594 = false; } else $this->v539082ff30[$v43dd7d0051]["info"] = curl_getinfo($pf3d2eef6); curl_close($pf3d2eef6); } return $v5c1c342594; } private function f632382768f($pfcc7bae3) { $v9c03c5c6a8 = isset($pfcc7bae3["wait"]) ? $pfcc7bae3["wait"] : false; $v67ea20931b = isset($pfcc7bae3["max_chunk_requests"]) && $pfcc7bae3["max_chunk_requests"] > 0 ? $pfcc7bae3["max_chunk_requests"] : false; $v6fbd9af3e8 = isset($pfcc7bae3["max_host_chunk_requests"]) && $pfcc7bae3["max_host_chunk_requests"] > 0 ? $pfcc7bae3["max_host_chunk_requests"] : false; $pf3d2eef6 = array(); $pe01aff98 = curl_multi_init(); if ($v67ea20931b && is_numeric(CURLMOPT_MAX_TOTAL_CONNECTIONS)) curl_multi_setopt($pe01aff98, CURLMOPT_MAX_TOTAL_CONNECTIONS, $v67ea20931b); if ($v6fbd9af3e8 && is_numeric(CURLMOPT_MAX_HOST_CONNECTIONS)) curl_multi_setopt($pe01aff98, CURLMOPT_MAX_HOST_CONNECTIONS, $v6fbd9af3e8); $pc37695cb = $this->v539082ff30 ? count($this->v539082ff30) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pf3d2eef6[$v43dd7d0051]=curl_init(); $this->f583227374b($pf3d2eef6[$v43dd7d0051], $this->v539082ff30[$v43dd7d0051]); if (!$v9c03c5c6a8) { curl_setopt($pf3d2eef6[$v43dd7d0051], CURLOPT_TIMEOUT, 1); curl_setopt($pf3d2eef6[$v43dd7d0051], CURLOPT_NOSIGNAL, 1); } curl_multi_add_handle($pe01aff98, $pf3d2eef6[$v43dd7d0051]); } $pd82569a2 = null; do { $v243b1445b4 = curl_multi_exec($pe01aff98, $pd82569a2); } while ($v243b1445b4 == CURLM_CALL_MULTI_PERFORM); while ($pd82569a2 && $v243b1445b4 == CURLM_OK) { if (!$v9c03c5c6a8) usleep(3); else if (curl_multi_select($pe01aff98) == -1) usleep(1); do { $v243b1445b4 = curl_multi_exec($pe01aff98, $pd82569a2); } while ($v243b1445b4 == CURLM_CALL_MULTI_PERFORM); } $v5c1c342594 = !$pd82569a2 && $v243b1445b4 == CURLM_OK; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { if ($v9c03c5c6a8) { $pae77d38c = curl_multi_getcontent($pf3d2eef6[$v43dd7d0051]); if (!empty($this->v539082ff30[$v43dd7d0051]["settings"]["header"])) { $pa2203b6d = curl_getinfo($pf3d2eef6[$v43dd7d0051], CURLINFO_HEADER_SIZE); $v6c438ea8cd = substr($pae77d38c, 0, $pa2203b6d); $pae77d38c = substr($pae77d38c, $pa2203b6d); $this->v539082ff30[$v43dd7d0051]["header"] = self::ma34b6c51eb3b($v6c438ea8cd); } $this->v539082ff30[$v43dd7d0051]["content"] = $pae77d38c; if (curl_errno($pf3d2eef6[$v43dd7d0051])) { $this->v539082ff30[$v43dd7d0051]["error"] = curl_error($pf3d2eef6[$v43dd7d0051]); $v5c1c342594 = false; } else $this->v539082ff30[$v43dd7d0051]["info"] = curl_getinfo($pf3d2eef6[$v43dd7d0051]); } curl_multi_remove_handle($pe01aff98,$pf3d2eef6[$v43dd7d0051]); curl_close($pf3d2eef6[$v43dd7d0051]); } curl_multi_close($pe01aff98); return $v5c1c342594; } private function f583227374b(&$pf3d2eef6, &$v539082ff30) { $this->f2de37489fe($pf3d2eef6, $v539082ff30["url"]); curl_setopt($pf3d2eef6, CURLOPT_RETURNTRANSFER, 1); if (!empty($v539082ff30["post"])) { if (empty($v539082ff30["settings"]["put"])) curl_setopt($pf3d2eef6, CURLOPT_POST, 1); $this->f4d9c16919e($pf3d2eef6, $v539082ff30); } if (!empty($v539082ff30["cookie"])) curl_setopt($pf3d2eef6, CURLOPT_COOKIE, $v539082ff30["cookie"]); if (!empty($v539082ff30["settings"]["put"])) curl_setopt($pf3d2eef6, CURLOPT_PUT, true); if (isset($v539082ff30["settings"]["header"])) curl_setopt($pf3d2eef6, CURLOPT_HEADER, $v539082ff30["settings"]["header"] ? true : false); if (isset($v539082ff30["settings"]["connection_timeout"])) curl_setopt($pf3d2eef6, CURLOPT_CONNECTTIMEOUT, is_numeric($v539082ff30["settings"]["connection_timeout"]) ? $v539082ff30["settings"]["connection_timeout"] : 60); if (!empty($v539082ff30["settings"]["no_body"])) curl_setopt($pf3d2eef6, CURLOPT_NOBODY, $v539082ff30["settings"]["no_body"] ? true : false); if (!empty($v539082ff30["settings"]["http_header"])) curl_setopt($pf3d2eef6, CURLOPT_HTTPHEADER, $v539082ff30["settings"]["http_header"]); if (!empty($v539082ff30["settings"]["referer"])) curl_setopt($pf3d2eef6, CURLOPT_REFERER , $v539082ff30["settings"]["referer"]); if (!empty($v539082ff30["settings"]["follow_location"])) curl_setopt($pf3d2eef6, CURLOPT_FOLLOWLOCATION, $v539082ff30["settings"]["follow_location"] ? true : false); if (!empty($v539082ff30["settings"]["http_auth"])) curl_setopt($pf3d2eef6, CURLOPT_HTTPAUTH, $v539082ff30["settings"]["http_auth"]); if (!empty($v539082ff30["settings"]["user_pwd"])) curl_setopt($pf3d2eef6, CURLOPT_USERPWD, $v539082ff30["settings"]["user_pwd"]); if (!empty($v539082ff30["settings"]["read_cookies_from_file"])) curl_setopt($pf3d2eef6, CURLOPT_COOKIEFILE, $v539082ff30["settings"]["read_cookies_from_file"]); if (!empty($v539082ff30["settings"]["save_cookies_to_file"])) curl_setopt($pf3d2eef6, CURLOPT_COOKIEJAR, $v539082ff30["settings"]["save_cookies_to_file"]); if (!empty($v539082ff30["settings"])) foreach ($v539082ff30["settings"] as $pe5c5e2fe => $v956913c90f) if (substr($pe5c5e2fe, 0, 8) == "CURLOPT_") { eval("\$v99ec6a5956 = $pe5c5e2fe;"); curl_setopt($pf3d2eef6, $v99ec6a5956, $v956913c90f); } } private function f2de37489fe(&$pf3d2eef6, &$v6f3a2700dd) { $v8a4df75785 = strpos($v6f3a2700dd, "?"); if ($v8a4df75785 !== false) { $pc2defd39 = substr($v6f3a2700dd, $v8a4df75785 + 1); if ($pc2defd39) { parse_str($pc2defd39, $v7bb8636f3b); $v6f3a2700dd = substr($v6f3a2700dd, 0, $v8a4df75785 + 1) . http_build_query($v7bb8636f3b); } } curl_setopt($pf3d2eef6, CURLOPT_URL, $v6f3a2700dd); } private function f4d9c16919e(&$pf3d2eef6, &$v539082ff30) { if (isset($v539082ff30["files"]) && is_array($v539082ff30["files"]) && count($v539082ff30["files"]) > 0) { $v5eece843d1 = ""; for ($v43dd7d0051 = 0; $v43dd7d0051 < 27; $v43dd7d0051++) { $v5eece843d1 .= "-"; } $v5eece843d1 .= rand().rand(); $pe23e0cca = ''; if (isset($v539082ff30["post"]) && is_array($v539082ff30["post"])) foreach ($v539082ff30["post"] as $pbfa01ed1 => $v67db1bd535) { $pe23e0cca .= '--'.$v5eece843d1."\n" . 'Content-Disposition: form-data; name="'.$pbfa01ed1.'"'."\n" . "\n" . urlencode($v67db1bd535)."\n"; } foreach ($v539082ff30["files"] as $pbfa01ed1 => $v67db1bd535) { $peaf74b17 = isset($v67db1bd535["tmp_name"]) ? $v67db1bd535["tmp_name"] : null; $v5e813b295b = isset($v67db1bd535["name"]) ? $v67db1bd535["name"] : null; $v3fb9f41470 = isset($v67db1bd535["type"]) ? $v67db1bd535["type"] : null; $v2f4ddc2b55 = $this->md4db3e8a4f14($peaf74b17); if (substr($v2f4ddc2b55, strlen($v2f4ddc2b55) - 1) == "\n") $v2f4ddc2b55 = substr($v2f4ddc2b55, 0, strlen($v2f4ddc2b55) - 1); $pe23e0cca .= '--'.$v5eece843d1."\n" . 'Content-Disposition: form-data; name="'.$pbfa01ed1.'"; filename="'.$v5e813b295b.'"'."\n" . 'Content-Type: '.$v3fb9f41470."\n" . "\n" . $v2f4ddc2b55."\n" . '--'.$v5eece843d1; } $pe23e0cca .= "--"; $v15493e4c60 = array('Content-type: multipart/form-data; boundary="'.$v5eece843d1.'"', 'Content-length: '.strlen($pe23e0cca) ); $v539082ff30["settings"]["http_header"] = isset($v539082ff30["settings"]["http_header"]) ? $v539082ff30["settings"]["http_header"] : array(); $v539082ff30["settings"]["http_header"] = array_merge($v15493e4c60, $v539082ff30["settings"]["http_header"]); } else if (isset($v539082ff30["post"]) && is_array($v539082ff30["post"])) $pe23e0cca = http_build_query($v539082ff30["post"]); else $pe23e0cca = isset($v539082ff30["post"]) ? $v539082ff30["post"] : null; curl_setopt($pf3d2eef6, CURLOPT_POSTFIELDS, $pe23e0cca); } private function md4db3e8a4f14($pf3dc0762) { $v3646220e38 = ""; $v04ee3b88da = file_exists($pf3dc0762) ? fopen($pf3dc0762, "r") : false; if ($v04ee3b88da) { while (!feof($v04ee3b88da)) $v3646220e38 .= fgets($v04ee3b88da); fclose($v04ee3b88da); } return $v3646220e38; } private function f5f89493a89($v48ee6355ca) { if (is_array($v48ee6355ca)) return $v48ee6355ca; else if ($v48ee6355ca) { $v6af1f205e1 = array(); $v04fae7df44 = explode("?",$v48ee6355ca); $v04fae7df44 = explode("&",$v04fae7df44[count($v04fae7df44) - 1]); $pc37695cb = count($v04fae7df44); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1932a3792d = explode("=",$v04fae7df44[$v43dd7d0051]); if (strlen(trim($v1932a3792d[0])) > 0) $v6af1f205e1[trim($v1932a3792d[0])] = $v1932a3792d[1]; } return $v6af1f205e1; } } private function ma901843720f2($v053b63d4d5) { if (is_array($v053b63d4d5)) { $v49c4a546f0 = ""; foreach ($v053b63d4d5 as $pbfa01ed1 => $v67db1bd535) $v49c4a546f0 .= "&{$pbfa01ed1}={$v67db1bd535}"; return $v49c4a546f0; } else if ($v053b63d4d5) { $v04fae7df44 = explode("?",$v053b63d4d5); $v6bab9ac5a9 = $v04fae7df44[count($v04fae7df44) - 1]; return $v6bab9ac5a9 ? "&".$v6bab9ac5a9 : ""; } } private function f0889b9d9fd($v5638f46235) { if (is_array($v5638f46235)) { $pc1f0fd88 = ""; foreach ($v5638f46235 as $pbfa01ed1 => $v67db1bd535) $pc1f0fd88 .= "{$pbfa01ed1}={$v67db1bd535}; "; return $pc1f0fd88; } return $v5638f46235; } private function ma2d0f3075343($v30857f7eca) { if (is_array($v30857f7eca)) { if (!empty($v30857f7eca["put"]) && !is_bool($v30857f7eca["put"]) && $v30857f7eca["put"] !== 0 && $v30857f7eca["put"] !== 1) unset($v30857f7eca["put"]); if (!empty($v30857f7eca["header"]) && !is_bool($v30857f7eca["header"]) && $v30857f7eca["header"] !== 0 && $v30857f7eca["header"] !== 1) unset($v30857f7eca["header"]); if (!empty($v30857f7eca["connection_timeout"]) && !is_numeric($v30857f7eca["connection_timeout"])) unset($v30857f7eca["connection_timeout"]); if (!empty($v30857f7eca["no_body"]) && !is_bool($v30857f7eca["no_body"]) && $v30857f7eca["no_body"] !== 0 && $v30857f7eca["no_body"] !== 1) unset($v30857f7eca["no_body"]); if (!empty($v30857f7eca["http_header"]) && !is_array($v30857f7eca["http_header"])) $v30857f7eca["http_header"] = explode("\n", str_replace(array("\r\n"), "\n", $v30857f7eca["http_header"])); if (!empty($v30857f7eca["follow_location"]) && !is_bool($v30857f7eca["follow_location"]) && $v30857f7eca["follow_location"] !== 0 && $v30857f7eca["follow_location"] !== 1) unset($v30857f7eca["follow_location"]); if (!empty($v30857f7eca["http_auth"])) { $v10622c4b9c = $v30857f7eca["http_auth"]; switch (strtolower($v10622c4b9c)) { case "basic": $v10622c4b9c = CURLAUTH_BASIC; break; case "digest": $v10622c4b9c = CURLAUTH_DIGEST; break; } $v19c86746db = array(CURLAUTH_BASIC, CURLAUTH_DIGEST, CURLAUTH_GSSNEGOTIATE, CURLAUTH_NTLM, CURLAUTH_ANY, CURLAUTH_ANYSAFE); if (!in_array($v10622c4b9c, $v19c86746db)) unset($v30857f7eca["http_auth"]); else $v30857f7eca["http_auth"] = $v10622c4b9c; } } return $v30857f7eca; } private static function ma34b6c51eb3b($v75fa13877e) { $v15493e4c60 = array(); $v00f73eb9bc = explode("\n", str_replace(array("\r\n"), "\n", $v75fa13877e)); foreach ($v00f73eb9bc as $v43dd7d0051 => $v259d35fa15) { if ($v43dd7d0051 === 0) $v15493e4c60['http_code'] = $v259d35fa15; else if (trim($v259d35fa15)) { $pbd1bc7b0 = strpos($v259d35fa15, ":"); if ($pbd1bc7b0 !== false) { $pbfa01ed1 = trim(substr($v259d35fa15, 0, $pbd1bc7b0)); $v67db1bd535 = trim(substr($v259d35fa15, $pbd1bc7b0 + 1)); } else { $pbfa01ed1 = $v259d35fa15; $v67db1bd535 = null; } if ($pbfa01ed1) $v15493e4c60[$pbfa01ed1] = $v67db1bd535; } } return $v15493e4c60; } private static function f4c71e33805($v847e7d0a83) { return preg_replace('/\\0/', "", $v847e7d0a83); } public function getData() { return $this->v539082ff30; } } ?>
