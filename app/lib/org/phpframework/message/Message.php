<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class Message { private $v1cbfbb49c5; private $pcd8c70bc; private $pffa799aa; private $pfdbbc383 = array(); public function __construct() { } public function setId($v1cbfbb49c5) {$this->v1cbfbb49c5 = $v1cbfbb49c5;} public function getId() {return $this->v1cbfbb49c5;} public function setModule($pcd8c70bc) {$this->pcd8c70bc = $pcd8c70bc;} public function getModule() {return $this->pcd8c70bc;} public function setMessage($pffa799aa) {$this->pffa799aa = $pffa799aa;} public function getMessage() {return $this->pffa799aa;} public function setAttributes($pfdbbc383) {$this->pfdbbc383 = $pfdbbc383;} public function getAttributes() {return $this->pfdbbc383;} public function getAttribute($v5e813b295b) {return isset($this->pfdbbc383[$v5e813b295b]) ? $this->pfdbbc383[$v5e813b295b] : null;} public function checkAttributes($pfdbbc383) { if(is_array($pfdbbc383)) { foreach($pfdbbc383 as $v5e813b295b => $v67db1bd535) { $v956913c90f = isset($this->pfdbbc383[$v5e813b295b]) ? $this->pfdbbc383[$v5e813b295b] : null; if( (!isset($v956913c90f) && strlen($v67db1bd535) > 0) || $v956913c90f != $v67db1bd535) return false; } } return true; } } ?>
