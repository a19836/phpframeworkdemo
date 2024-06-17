<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class SQLMapQueryException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "ERROR: ParameterMap item doesn't have column name defined!"; break; case 2: $this->problem = "ERROR: ParameterMap item doesn't have property name defined!"; break; case 3: $this->problem = "ERROR: ParameterMap doesn't have any items!"; break; case 4: $this->problem = "ERROR: ParameterMap doesn't exists!"; break; case 6: $this->problem = "ERROR: ParameterMap class obj '".get_class($v9363d877fd)."' doesn't contain the '$pd0c2934c' method!"; break; case 7: $this->problem = "ERROR: Query can only have ParameterMap if the input value is an array!"; break; case 8: $this->problem = "ERROR: ParameterMap column '".$v67db1bd535."' doesn't exist in the input data! Please check your parameter map xml."; break; } } } ?>
