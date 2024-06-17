<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
interface IMyIOManager { public function add($v3fb9f41470, $v17be587282, $v5e813b295b, $v30857f7eca = array()); public function edit($v17be587282, $v5e813b295b, $v30857f7eca = array()); public function delete($v3fb9f41470, $v17be587282, $v5e813b295b); public function copy($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()); public function move($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()); public function rename($v17be587282, $v0c4b06ddf7, $pe6871e84, $v30857f7eca = array()); public function getFile($v17be587282, $v5e813b295b); public function getFileInfo($v17be587282, $v5e813b295b); public function getFileNameExtension($v5e813b295b); public function getFiles($v17be587282); public function getFilesCount($v17be587282); public function upload($v6eee6903b3, $v17be587282, $pe6871e84, $v30857f7eca = array()); public function exists($v17be587282, $v5e813b295b); public function setOptions($v5d3813882f); public function setOption($pe238ca78, $v67db1bd535); } ?>
