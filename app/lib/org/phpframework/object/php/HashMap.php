<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); class HashMap extends ObjType { public function __construct($pd093e676 = false) { if($pd093e676 !== false) $this->setData($pd093e676); } public function getData() {return (array)$this->data;} public function setData($v539082ff30) { if(is_array($v539082ff30)) { $this->data = (array)$v539082ff30; return true; } launch_exception(new ObjTypeException(get_class($this), $v539082ff30)); return false; } public function getValue($pbfa01ed1 = 0) { return isset($this->data[$pbfa01ed1]) ? $this->data[$pbfa01ed1] : null; } public function setValue($pbfa01ed1, $v67db1bd535) { $this->data[$pbfa01ed1] = $v67db1bd535; } public function getAllValues() { return $this->getData(); } } ?>
