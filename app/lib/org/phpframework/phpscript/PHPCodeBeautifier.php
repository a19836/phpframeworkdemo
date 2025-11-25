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
 include_once get_lib("lib.vendor.phpcf.phpcf-src.src.init"); class PHPCodeBeautifier { private $v86ffe95514; private $v261c7a42d4; private $v0f9512fda4; private $v5c1c342594; public function __construct() { $this->v86ffe95514 = false; $this->v261c7a42d4 = null; $this->v0f9512fda4 = null; $this->v5c1c342594 = false; } public function wasFormatted() { return $this->v86ffe95514; } public function getIssues() { return $this->v261c7a42d4; } public function getError() { return $this->v0f9512fda4; } public function getStatus() { return $this->v5c1c342594; } public function beautifyCode($v067674f4e4) { $v9b8a410170 = new \Phpcf\Options(); $v9b8a410170->setTabSequence("\t"); $pc2cca5a3 = new \Phpcf\Formatter($v9b8a410170); $v67a0ec3cdc = $pc2cca5a3->format($v067674f4e4); $pf4e3c708 = $v67a0ec3cdc->getContent(); $this->v86ffe95514 = $v67a0ec3cdc->wasFormatted(); $this->v261c7a42d4 = $v67a0ec3cdc->getIssues(); $this->v0f9512fda4 = $v67a0ec3cdc->getError(); $this->v5c1c342594 = $this->v86ffe95514 && empty($this->v261c7a42d4) && empty($this->v0f9512fda4); if ($this->v5c1c342594) { if (substr(trim($v067674f4e4), -2) == "?>" && substr(trim($pf4e3c708), -2) != "?>") $pf4e3c708 = trim($pf4e3c708) . "\n?>"; return $pf4e3c708; } return $v067674f4e4; } } ?>
