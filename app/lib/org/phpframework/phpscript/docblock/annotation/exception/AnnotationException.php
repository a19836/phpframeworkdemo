<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class AnnotationException extends Exception { public $problem; public function __construct($v6de691233b, $paec2c009, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Error in annotation $v9363d877fd, when executing php function: $pd0c2934c"; break; } if (!empty($paec2c009)) { if (is_string($paec2c009)) parent::__construct($paec2c009, $v6de691233b, null); else parent::__construct(!empty($paec2c009->problem) ? $paec2c009->problem : $paec2c009->getMessage(), $v6de691233b, $paec2c009); } } } ?>
