<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class ExceptionLogHandler { private $v21c0d09111; private $v85bf262ebb; public function __construct($v21c0d09111, $v85bf262ebb = false) { $this->v21c0d09111 = $v21c0d09111; $this->v85bf262ebb = $v85bf262ebb; } public function log(Exception $v4ace7728e6) { if($this->v21c0d09111) { $pffa799aa = $v4ace7728e6->getMessage(); $v9dd1efeb20 = $v4ace7728e6->problem; $v1db8fcc7cd = $pffa799aa != $v9dd1efeb20 ? "$pffa799aa\n$v9dd1efeb20" : $v9dd1efeb20; $this->v21c0d09111->setExceptionLog($v1db8fcc7cd, $v4ace7728e6->getTrace()); } if($this->v85bf262ebb) { echo "<p style=\"margin:10px; font-weight:bold; color:#2C2D34;\">DIE: Program execution ends on the ExceptionHandler class (" . date("Y-m-d H:i:s", time()) . ")</p>"; die(1); } } public function setLogHandler($v21c0d09111) {$this->v21c0d09111 = $v21c0d09111;} public function getLogHandler() {return $this->v21c0d09111;} public function setDieWhenThrowException($v85bf262ebb) {$this->v85bf262ebb = $v85bf262ebb;} public function getDieWhenThrowException() {return $this->v85bf262ebb;} } ?>
