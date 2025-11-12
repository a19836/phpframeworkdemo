<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.object.ObjType"); class MyString extends ObjType { public function __construct($v00037ca9db = false) { if($v00037ca9db !== false) $this->setData($v00037ca9db); } public function getData() {return (string)$this->data;} public function setData($v539082ff30) { $this->data = (string)$v539082ff30; return true; } } ?>
