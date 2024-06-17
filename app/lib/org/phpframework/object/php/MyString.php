<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.object.ObjType"); class MyString extends ObjType { public function __construct($v00037ca9db = false) { if($v00037ca9db !== false) $this->setData($v00037ca9db); } public function getData() {return (string)$this->data;} public function setData($v539082ff30) { $this->data = (string)$v539082ff30; return true; } } ?>
