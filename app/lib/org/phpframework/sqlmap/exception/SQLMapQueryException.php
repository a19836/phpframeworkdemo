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
 class SQLMapQueryException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "ERROR: ParameterMap item doesn't have column name defined!"; break; case 2: $this->problem = "ERROR: ParameterMap item doesn't have property name defined!"; break; case 3: $this->problem = "ERROR: ParameterMap doesn't have any items!"; break; case 4: $this->problem = "ERROR: ParameterMap doesn't exists!"; break; case 6: $this->problem = "ERROR: ParameterMap class obj '".get_class($v9363d877fd)."' doesn't contain the '$pd0c2934c' method!"; break; case 7: $this->problem = "ERROR: Query can only have ParameterMap if the input value is an array!"; break; case 8: $this->problem = "ERROR: ParameterMap column '".$v67db1bd535."' doesn't exist in the input data! Please check your parameter map xml."; break; } } } ?>
