<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.object.IObjType"); include_once get_lib("org.phpframework.object.ObjectHandler"); abstract class ObjType implements IObjType { protected $field; protected $data; public function getField() {return $this->field;} public function setField($v5d170b1de6) {$this->field = $v5d170b1de6;} public function getData() {return $this->data;} public function setData($v539082ff30) { $this->data = $v539082ff30; return true; } public function setInstance($v972f1a5c2b) { if(is_object($v972f1a5c2b) && get_class($v972f1a5c2b) && ObjectHandler::checkIfObjType($v972f1a5c2b)) return $this->setData($v972f1a5c2b->getData()); else return $this->setData($v972f1a5c2b); } } ?>
