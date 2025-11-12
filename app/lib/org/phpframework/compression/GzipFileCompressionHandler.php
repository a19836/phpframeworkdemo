<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.compression.IFileCompressionHandler"); class GzipFileCompressionHandler implements IFileCompressionHandler { protected $file_pointer = null; public function __construct(){ if (!function_exists("gzopen")) throw new Exception("Gzip lib is not installed or gzopen function does NOT exists!"); } public function open($pf3dc0762) { $this->file_pointer = gzopen($pf3dc0762, "wb"); if ($this->file_pointer === false) throw new Exception("Could not open file! Please check if the '" . basename($pf3dc0762) . "' file is writeable..."); return true; } public function write($v327f72fb62) { $v8d6672117e = gzwrite($this->file_pointer, $v327f72fb62); if ($v8d6672117e === false) throw new Exception("Could not write to file! Please check if you have enough free space..."); return $v8d6672117e; } public function close() { return gzclose($this->file_pointer); } } ?>
