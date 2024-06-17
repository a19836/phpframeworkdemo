<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.compression.IFileCompressionHandler"); class GzipstreamFileCompressionHandler implements IFileCompressionHandler { protected $file_pointer = null; protected $deflate_context; public function __construct() { if (!function_exists("deflate_init")) throw new Exception("Gzipstream lib is not installed or deflate_init function does NOT exists!"); } public function open($pf3dc0762) { $this->file_pointer = fopen($pf3dc0762, "wb"); if ($this->file_pointer === false) throw new Exception("Could not open file! Please check if the '" . basename($pf3dc0762) . "' file is writeable..."); $this->deflate_context = deflate_init(ZLIB_ENCODING_GZIP, array('level' => 9)); return true; } public function write($v327f72fb62) { $v8d6672117e = fwrite($this->file_pointer, deflate_add($this->deflate_context, $v327f72fb62, ZLIB_NO_FLUSH)); if ($v8d6672117e === false) throw new Exception("Could not write to file! Please check if you have enough free space..."); return $v8d6672117e; } public function close() { fwrite($this->file_pointer, deflate_add($this->deflate_context, '', ZLIB_FINISH)); return fclose($this->file_pointer); } } ?>
