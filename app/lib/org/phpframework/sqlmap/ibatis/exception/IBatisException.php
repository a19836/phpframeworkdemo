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
 class IBatisException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Invalid query type '$v9363d877fd'. You must select one of the following types: [".strtolower(implode(", ", $pd0c2934c))."]"; break; case 2: $this->problem = ucfirst(strtolower($v9363d877fd))." query '$pd0c2934c' does not exist."; break; case 3: $this->problem = "Query '".$v67db1bd535."' can only have one parameter map or parameter class. You cannot have multiple parameter types."; break; case 4: $this->problem = "Query '".$v67db1bd535."' can only have one result map or result class. You cannot have multiple result types."; break; } } } ?>
