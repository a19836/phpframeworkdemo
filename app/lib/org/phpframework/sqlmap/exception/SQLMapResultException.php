<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class SQLMapResultException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "ERROR: ResultMap item doesn't have column name defined!"; break; case 2: $this->problem = "ERROR: ResultMap item doesn't have property name defined!"; break; case 3: $this->problem = "ERROR: ResultMap doesn't have any items!"; break; case 4: $this->problem = "ERROR: ResultMap doesn't exists!"; break; case 5: $this->problem = "ERROR: ResultMap column name doesn't exist! Column '$v9363d877fd' doesn't exist in [".implode(", ",$pd0c2934c)."]"; break; case 6: $this->problem = "ERROR: ResultMap column '".$v67db1bd535."' doesn't exist in the DB result! Please check your result map xml."; break; } } } ?>
