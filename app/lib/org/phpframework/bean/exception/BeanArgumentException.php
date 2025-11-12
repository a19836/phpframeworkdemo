<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class BeanArgumentException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Bean argument should have a numeric index: '{$v67db1bd535}'!"; break; case 2: $this->problem = "Bean argument should have a numeric index equal or bigger than 1: '{$v67db1bd535}'!"; break; case 3: $this->problem = "Bean argument cannot have value and reference at the same time: value: '". $v9363d877fd ."', reference: '". $pd0c2934c ."'!"; break; } } } ?>
