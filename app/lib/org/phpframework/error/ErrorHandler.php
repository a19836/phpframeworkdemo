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
 class ErrorHandler { private $v0f9512fda4; public function __construct() { $this->start(); } public function stop() { $this->v0f9512fda4 = true; } public function start() { $this->v0f9512fda4 = false; } public function ok() { return !$this->v0f9512fda4; } } ?>
