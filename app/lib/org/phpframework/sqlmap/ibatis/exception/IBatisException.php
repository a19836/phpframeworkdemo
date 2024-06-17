<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class IBatisException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Invalid query type '$v9363d877fd'. You must select one of the following types: [".strtolower(implode(", ", $pd0c2934c))."]"; break; case 2: $this->problem = ucfirst(strtolower($v9363d877fd))." query '$pd0c2934c' does not exist."; break; case 3: $this->problem = "Query '".$v67db1bd535."' can only have one parameter map or parameter class. You cannot have multiple parameter types."; break; case 4: $this->problem = "Query '".$v67db1bd535."' can only have one result map or result class. You cannot have multiple result types."; break; } } } ?>
