<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class ErrorHandler { private $v0f9512fda4; public function __construct() { $this->start(); } public function stop() { $this->v0f9512fda4 = true; } public function start() { $this->v0f9512fda4 = false; } public function ok() { return !$this->v0f9512fda4; } } ?>
