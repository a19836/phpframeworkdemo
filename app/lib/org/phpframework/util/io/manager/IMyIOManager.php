<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 interface IMyIOManager { public function add($v3fb9f41470, $v17be587282, $v5e813b295b, $v30857f7eca = array()); public function edit($v17be587282, $v5e813b295b, $v30857f7eca = array()); public function delete($v3fb9f41470, $v17be587282, $v5e813b295b); public function copy($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()); public function move($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()); public function rename($v17be587282, $v0c4b06ddf7, $pe6871e84, $v30857f7eca = array()); public function getFile($v17be587282, $v5e813b295b); public function getFileInfo($v17be587282, $v5e813b295b); public function getFileNameExtension($v5e813b295b); public function getFiles($v17be587282); public function getFilesCount($v17be587282); public function upload($v6eee6903b3, $v17be587282, $pe6871e84, $v30857f7eca = array()); public function exists($v17be587282, $v5e813b295b); public function setOptions($v5d3813882f); public function setOption($pe238ca78, $v67db1bd535); } ?>
