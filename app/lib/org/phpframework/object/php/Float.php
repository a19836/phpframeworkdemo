<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); class Float extends ObjType { public function __construct($v5c1105bd54 = false) { if ($v5c1105bd54 !== false) $this->setData($v5c1105bd54); } public function getData() {return (int)$this->data;} public function setData($v539082ff30) { if (preg_match("/^(([\-]?)([0-9]*)([\.]?)([0-9]{0,7}))$/i", $v539082ff30)) { $this->data = $v539082ff30; return true; } launch_exception(new ObjTypeException(get_class($this), $v539082ff30)); return false; } } ?>
