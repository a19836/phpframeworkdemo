<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.compression.IFileCompressionHandler"); class Bzip2FileCompressionHandler implements IFileCompressionHandler { protected $file_pointer = null; public function __construct() { if (!function_exists("bzopen")) throw new Exception("Bzip2 lib is not installed or bzopen function does NOT exists!"); } public function open($pf3dc0762) { $this->file_pointer = bzopen($pf3dc0762, "w"); if ($this->file_pointer === false) throw new Exception("Could not open file! Please check if the '" . basename($pf3dc0762) . "' file is writeable..."); return true; } public function write($v327f72fb62) { $v8d6672117e = bzwrite($this->file_pointer, $v327f72fb62); if ($v8d6672117e === false) throw new Exception("Could not write to file! Please check if you have enough free space..."); return $v8d6672117e; } public function close() { return bzclose($this->file_pointer); } } ?>
