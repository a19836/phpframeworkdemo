<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class SQLMapClientException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Undefined sql mapping node '".$v67db1bd535."' in the queries xml file."; break; case 2: $this->problem = "There is a query with out id in the '".$v67db1bd535."' queries."; break; case 3: $this->problem = "Duplicate node entries '$pd0c2934c' in the '$v9363d877fd' nodes."; break; } } } ?>
