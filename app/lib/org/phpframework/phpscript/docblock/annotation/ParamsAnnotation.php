<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 namespace DocBlockParser\Annotation; class ParamsAnnotation extends Annotation { public function __construct() { $this->is_input = true; } public function parseArgs($v6da2e4df28, $v86066462c3) { $v020036c951 = "/**\n" . implode("\n", $v86066462c3) . "\n*/"; $pee257eb2 = new \DocBlockParser(); $pee257eb2->ofComment($v020036c951); $v52b4591032 = $pee257eb2->getObjects(); $v6da2e4df28->setIncludedTag("param"); $this->args = !empty($v52b4591032["param"]) ? $v52b4591032["param"] : null; } public function checkMethodAnnotations(&$v5730eacfdc, $pcc2d93a5) { $v5c1c342594 = true; if ($this->args) { $pc37695cb = count($this->args); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v972f1a5c2b = $this->args[$v43dd7d0051]; if (!$v972f1a5c2b->checkMethodAnnotations($v5730eacfdc, $v43dd7d0051)) $v5c1c342594 = false; } } return $v5c1c342594; } } ?>
