<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.util.io.handler.ftp.MyFTPHandler"); class MyFTPFolder extends MyFTPHandler { public $invalid_files; public function __construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v250a1176c9 = false, $v30857f7eca = array()) { parent::__construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, $v250a1176c9, $v30857f7eca); $this->f7b354b22de(); $this->invalid_files = $this->getInvalidFiles(); if(isset($v30857f7eca["invalid_files"]) && is_array($v30857f7eca["invalid_files"])) $this->invalid_files = array_merge($this->invalid_files, $v30857f7eca["invalid_files"]); } public function create() { } public function getFiles() { } public function getFilesRecursevly() { } public function getFilesCount() { } public function delete() { } public function copy($v3806ce773c) { } private function f7b354b22de() { } private function f085037e150($v250a1176c9) { $v250a1176c9 = basename($v250a1176c9); return array_search($v250a1176c9, $this->invalid_files) === false ? true : false; } public function setFileName($v250a1176c9) { $this->file_name = $v250a1176c9; $this->f7b354b22de(); } } ?>
