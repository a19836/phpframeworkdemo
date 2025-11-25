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
 class SQLMapResultException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "ERROR: ResultMap item doesn't have column name defined!"; break; case 2: $this->problem = "ERROR: ResultMap item doesn't have property name defined!"; break; case 3: $this->problem = "ERROR: ResultMap doesn't have any items!"; break; case 4: $this->problem = "ERROR: ResultMap doesn't exists!"; break; case 5: $this->problem = "ERROR: ResultMap column name doesn't exist! Column '$v9363d877fd' doesn't exist in [".implode(", ",$pd0c2934c)."]"; break; case 6: $this->problem = "ERROR: ResultMap column '".$v67db1bd535."' doesn't exist in the DB result! Please check your result map xml."; break; } } } ?>
