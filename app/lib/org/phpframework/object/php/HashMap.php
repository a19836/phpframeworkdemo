<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); class HashMap extends ObjType { public function __construct($pd093e676 = false) { if($pd093e676 !== false) $this->setData($pd093e676); } public function getData() {return (array)$this->data;} public function setData($v539082ff30) { if(is_array($v539082ff30)) { $this->data = (array)$v539082ff30; return true; } launch_exception(new ObjTypeException(get_class($this), $v539082ff30)); return false; } public function getValue($pbfa01ed1 = 0) { return isset($this->data[$pbfa01ed1]) ? $this->data[$pbfa01ed1] : null; } public function setValue($pbfa01ed1, $v67db1bd535) { $this->data[$pbfa01ed1] = $v67db1bd535; } public function getAllValues() { return $this->getData(); } } ?>
