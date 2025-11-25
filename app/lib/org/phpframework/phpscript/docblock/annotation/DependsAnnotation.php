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
 namespace DocBlockParser\Annotation; class DependsAnnotation extends Annotation { public function __construct() { $this->vectors = array("path", "desc"); } public function parseArgs($v6da2e4df28, $v86066462c3) { $this->args = $v86066462c3; } public function checkMethodAnnotations(&$v5730eacfdc, $pcc2d93a5) { return true; } } ?>
