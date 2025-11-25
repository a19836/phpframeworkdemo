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
 class AnnotationException extends Exception { public $problem; public function __construct($v6de691233b, $paec2c009, $v67db1bd535 = array()) { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Error in annotation $v9363d877fd, when executing php function: $pd0c2934c"; break; } if (!empty($paec2c009)) { if (is_string($paec2c009)) parent::__construct($paec2c009, $v6de691233b, null); else parent::__construct(!empty($paec2c009->problem) ? $paec2c009->problem : $paec2c009->getMessage(), $v6de691233b, $paec2c009); } } } ?>
