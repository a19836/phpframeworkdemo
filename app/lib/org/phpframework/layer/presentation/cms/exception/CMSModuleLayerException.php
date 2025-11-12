<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 class CMSModuleLayerException extends Exception { public $problem; public $file_not_found = false; public function __construct($v6de691233b, $v67db1bd535 = "") { $v9363d877fd = $pd0c2934c = null; if (is_array($v67db1bd535)) { $v9363d877fd = isset($v67db1bd535[0]) ? $v67db1bd535[0] : null; $pd0c2934c = isset($v67db1bd535[1]) ? $v67db1bd535[1] : null; } switch($v6de691233b) { case 1: $this->problem = "Modules Path is undefined or doesn't exist: $v67db1bd535"; break; case 2: $this->problem = "CMSModuleHandlerImpl class is not a subclass of CMSModuleHandler in the file: $v67db1bd535"; break; case 3: $this->problem = "Couldn't create CMSModuleHandler obj for module: $v67db1bd535"; break; case 4: $this->problem = "Module File doesn't exist: $v67db1bd535"; $this->file_not_found = true; break; case 5: $this->problem = "Module '$v9363d877fd' doesn't exist or is disabled. Undefined file path: $pd0c2934c"; break; case 6: $this->problem = "$v67db1bd535 file doesn't exist!"; $this->file_not_found = true; break; case 7: $this->problem = "CMSModuleSimulatorHandlerImpl class is not a subclass of CMSModuleSimulatorHandler in the file: $v67db1bd535"; break; case 8: $this->problem = "Couldn't create CMSModuleSimulatorHandler obj for module: $v67db1bd535"; break; } } } ?>
