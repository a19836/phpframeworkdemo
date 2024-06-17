<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */ include_once get_lib("org.phpframework.util.io.handler.ftp.MyFTPHandler"); class MyFTPFile extends MyFTPHandler { public function __construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v250a1176c9 = false, $v30857f7eca = array()) { parent::__construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, $v250a1176c9, $v30857f7eca); } public function upload($v6eee6903b3, $v7cf76881d7, $pe6871e84 = false) { } public function create($v57b4b0200b) { } public function edit($v57b4b0200b) { } public function get() { } public function delete() { } public function copy($v3806ce773c) { } public function setFileName($v250a1176c9) { $this->file_name = $v250a1176c9; } } ?>
