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
 include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); class ArrayList extends ObjType { public function __construct($pd093e676 = false) { if ($pd093e676 !== false) $this->setData($pd093e676); } public function getData() {return (array)$this->data;} public function setData($v539082ff30) { if (is_array($v539082ff30)) { $this->data = (array)$v539082ff30; $this->reset(); return true; } launch_exception(new ObjTypeException(get_class($this), $v539082ff30)); return false; } public function getValue($v8a4df75785 = 0) { return isset($this->data[$v8a4df75785]) ? $this->data[$v8a4df75785] : null; } public function setValue($v67db1bd535) { $this->data[] = $v67db1bd535; } public function each() { if (version_compare(PHP_VERSION, '7', '>')) { $pbfa01ed1 = key($this->data); $v67db1bd535 = current($this->data); next($this->data); } else list($pbfa01ed1, $v67db1bd535) = each($this->data); return $v67db1bd535; } public function reset() { reset($this->data); } public function getAllValues() { return $this->getData(); } } ?>
