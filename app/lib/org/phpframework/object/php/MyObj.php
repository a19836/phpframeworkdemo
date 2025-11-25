<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 include_once get_lib("org.phpframework.object.ObjType"); class MyObj extends ObjType { public function __construct() { $this->field = false; } public function setData($v539082ff30) { $v5c1c342594 = parent::setData($v539082ff30); if (is_array($this->data)) { foreach($this->data as $pbfa01ed1 => $v67db1bd535) { $v24b0e52635 = "set" . str_replace(" ", "", ucwords(strtolower( str_replace(array("_", "-"), " ", $pbfa01ed1) ))); if (method_exists($this, $v24b0e52635) && $v24b0e52635 != "setData" && $v24b0e52635 != "setField") eval("\$this->{$v24b0e52635}(\$v67db1bd535);"); else $v5c1c342594 = false; } } return $v5c1c342594; } public function getData() { $v9d05685f42 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "Y", "X", "W", "Z"); $pb2bb35e9 = get_class_methods($this); foreach($pb2bb35e9 as $v603bd47baf) { if (substr($v603bd47baf, 0, 3) == "get" && $v603bd47baf != "getData" && $v603bd47baf != "getField") { $v24b0e52635 = substr($v603bd47baf, 3); $v34f0a629d3 = substr($v24b0e52635, 0, 1); if (in_array($v34f0a629d3, $v9d05685f42)) { $v5e45ec9bb9 = strtolower($v34f0a629d3); for($v43dd7d0051 = 1; $v43dd7d0051 < strlen($v24b0e52635); $v43dd7d0051++) { $pc288256e = $v24b0e52635[$v43dd7d0051]; $v5e45ec9bb9 .= (in_array($pc288256e, $v9d05685f42) ? "_" : "").strtolower($pc288256e); } eval("\$this->data[\"{$v5e45ec9bb9}\"] = \$this->{$v603bd47baf}();"); } } } return parent::getData(); } } ?>
