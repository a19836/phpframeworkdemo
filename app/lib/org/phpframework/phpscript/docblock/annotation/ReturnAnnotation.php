<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace DocBlockParser\Annotation; class ReturnAnnotation extends Annotation { public function __construct() { $this->is_output = true; $this->vectors = array("type", "desc"); } public function parseArgs($v6da2e4df28, $v86066462c3) { $this->args = self::getConfiguredArgs($v86066462c3); } public function checkMethodAnnotations(&$v5730eacfdc, $pcc2d93a5) { return $this->checkValueAnnotations($v5730eacfdc); } } ?>
