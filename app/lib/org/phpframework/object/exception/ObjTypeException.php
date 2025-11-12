<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class ObjTypeException extends Exception { public $problem; public function __construct($v1335217393, $v67db1bd535) { $v67db1bd535 = is_object($v67db1bd535) ? get_class($v67db1bd535) : json_encode($v67db1bd535); $this->problem = "Wrong {$v1335217393} value: {$v67db1bd535} "; } } ?>
