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
 include_once get_lib("org.phpframework.dispatcher.exception.PresentationDispatcherException"); include_once get_lib("org.phpframework.dispatcher.Dispatcher"); class PresentationDispatcher extends Dispatcher { private $v6f3a2700dd; private $v9431023a8c; private $v9367d5be85; private $v1651902203; private $v0e418ed793; private $pd3623f40; public function __construct() {} public function setPresentationLayer($pd3623f40) { $this->pd3623f40 = $pd3623f40; } public function getPresentationLayer() { return $this->pd3623f40; } public function setRouter($v0e418ed793) { $this->v0e418ed793 = $v0e418ed793; } public function getRouter() { return $this->v0e418ed793; } public function dispatch($v6f3a2700dd) { $this->v0e418ed793->load(); $this->v6f3a2700dd = $this->v0e418ed793->parse($v6f3a2700dd); $v04fae7df44 = explode("/", $this->v6f3a2700dd); while ($v04fae7df44[ count($v04fae7df44) - 1] == "") array_pop($v04fae7df44); $this->v9431023a8c = $v04fae7df44[0]; $this->v9367d5be85 = $v04fae7df44; array_shift($this->v9367d5be85); $this->v1651902203 = $this->pd3623f40->getPagePath($this->v9431023a8c); if(!file_exists($this->v1651902203)) { launch_exception(new PresentationDispatcherException(1, $this->v1651902203)); } } public function getURL() { return $this->v6f3a2700dd;} public function getPageCode() { return $this->v9431023a8c;} public function getParameters() { return $this->v9367d5be85;} public function getRequestedFilePath() { return $this->v1651902203;} } ?>
