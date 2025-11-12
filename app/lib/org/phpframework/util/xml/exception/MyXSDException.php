<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class MyXSDException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { $v9363d877fd = $pd0c2934c = $v62e526cceb = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; $v62e526cceb = isset($v67db1bd535[2]) ? $v67db1bd535[2] : null; } switch($v6de691233b) { case 1: $this->problem = "DOMDocument::schemaValidate('".$pd0c2934c."') generated errors, trying to validate  file '".$v62e526cceb."'!<br/>Errors:<ul>"; $v8a29987473 = $v9363d877fd; if ($v8a29987473) foreach ($v8a29987473 as $v0f9512fda4) $this->problem .= "<li>".$v0f9512fda4."</li>"; $this->problem .= "</ul>"; break; case 2: $this->problem = "ERROR: The xml schema file '{$v67db1bd535}' does not exist."; break; } } } ?>
