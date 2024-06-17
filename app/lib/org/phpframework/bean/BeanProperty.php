<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include get_lib("org.phpframework.bean.exception.BeanPropertyException"); class BeanProperty { const AK = "2wIDAQAB"; public $name; public $value = false; public $reference = false; public function __construct($v5e813b295b, $v67db1bd535 = false, $v6da63250f5 = false) { $this->name = trim($v5e813b295b); $this->value = $v67db1bd535; $this->reference = $v6da63250f5; $this->f085037e150(); } private function f085037e150() { if(empty($this->name)) { launch_exception(new BeanPropertyException(1, $this->name)); return false; } elseif($this->value && $this->reference) { launch_exception(new BeanPropertyException(2, array($this->value, $this->reference))); return false; } return true; } } ?>
