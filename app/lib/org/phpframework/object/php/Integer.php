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
 include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); class Integer extends ObjType { public function __construct($v5c1105bd54 = false) { if($v5c1105bd54 !== false) $this->setData($v5c1105bd54); } public function getData() {return (int)$this->data;} public function setData($v539082ff30) { if(is_numeric($v539082ff30)) { $this->data = (int)$v539082ff30; return true; } launch_exception(new ObjTypeException(get_class($this), $v539082ff30)); return false; } } ?>
