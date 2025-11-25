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
 include_once get_lib("org.phpframework.dispatcher.exception.EVCDispatcherException"); include_once get_lib("org.phpframework.dispatcher.Dispatcher"); class EVCDispatcher extends Dispatcher { private $v6f3a2700dd; private $v9431023a8c; private $v9367d5be85; private $v1651902203; private $v0e418ed793; private $v08d9602741; public function __construct() {} public function setEVC($v08d9602741) { $this->v08d9602741 = $v08d9602741; } public function getEVC() { return $this->v08d9602741; } public function setRouter($v0e418ed793) { $this->v0e418ed793 = $v0e418ed793; } public function getRouter() { return $this->v0e418ed793; } public function dispatch($v6f3a2700dd) { $this->v0e418ed793->load(); $this->v6f3a2700dd = $this->v0e418ed793->parse($v6f3a2700dd); $v04fae7df44 = explode("/", $this->v6f3a2700dd); while (count($v04fae7df44) > 0 && $v04fae7df44[ count($v04fae7df44) - 1] == "") array_pop($v04fae7df44); $this->v9431023a8c = count($v04fae7df44) ? $v04fae7df44[0] : null; if($this->v9431023a8c && $this->v08d9602741->controllerExists($this->v9431023a8c)) { $this->v1651902203 = $this->v08d9602741->getControllerPath($this->v9431023a8c); $this->v9367d5be85 = $v04fae7df44; array_shift($this->v9367d5be85); } else { $v8e5982c702 = $this->v08d9602741->getDefaultController(); if($v8e5982c702 && $this->v08d9602741->controllerExists($v8e5982c702)) { $this->v1651902203 = $this->v08d9602741->getControllerPath($v8e5982c702); $this->v9367d5be85 = $v04fae7df44; } else { launch_exception(new EVCDispatcherException(1, array($this->v9431023a8c, $v8e5982c702))); } } } public function getURL() { return $this->v6f3a2700dd;} public function getPageCode() { return $this->v9431023a8c;} public function getParameters() { return $this->v9367d5be85;} public function getRequestedFilePath() { return $this->v1651902203;} } ?>
