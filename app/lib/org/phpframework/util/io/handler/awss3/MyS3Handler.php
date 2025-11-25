<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 include_once get_lib("org.phpframework.util.io.handler.MyIOHandler"); include_once get_lib("lib.vendor.awss3.S3"); class MyS3Handler extends MyIOHandler { public $S3; public function __construct($v614e1f4104, $v253839514f) { $this->S3 = new S3($v614e1f4104, $v253839514f); } public function getType($pf3dc0762) { $v4159504aa3 = $this->getFileTypes(); $v250a1176c9 = basename($pf3dc0762); if ($v250a1176c9 == ".") return isset($v4159504aa3["folder"]) ? $v4159504aa3["folder"] : null; $v3fb9f41470 = self::getFileType($v250a1176c9); return isset($v4159504aa3[$v3fb9f41470]) ? $v4159504aa3[$v3fb9f41470] : null; } public function getACL($v8b27c73d0e) { switch(strtolower($v8b27c73d0e)) { case "p": $pf9163b61 = S3::ACL_PRIVATE; break; case "r": $pf9163b61 = S3::ACL_PUBLIC_READ; break; case "w": $pf9163b61 = S3::ACL_PUBLIC_READ_WRITE; break; default: $pf9163b61 = S3::ACL_PRIVATE; } return $pf9163b61; } public function exists($v4907c60569, $pe6469026) { return $this->S3->getObjectInfo($v4907c60569, $pe6469026, false); } public function getInfo($v4907c60569, $pe6469026) { if($this->exists($v4907c60569, $pe6469026)) { $v3fb9f41470 = $this->getType($pe6469026); $v872c4849e0 = array(); $v872c4849e0["type"] = isset($v3fb9f41470["id"]) ? $v3fb9f41470["id"] : null; $v872c4849e0["type_desc"] = isset($v3fb9f41470["desc"]) ? $v3fb9f41470["desc"] : null; if($v872c4849e0["type"] == 1) { $v872c4849e0["path"] = $pe6469026; $v872c4849e0["name"] = basename(dirname($pe6469026)); } else { $v872c4849e0["path"] = $pe6469026; $v872c4849e0["name"] = basename($pe6469026); $v872c4849e0["extension"] = $this->getFileExtension($pe6469026); $v872c4849e0["mime_type"] = $this->getFileMimeTypeByExtension($v872c4849e0["extension"]); } return $v872c4849e0; } else return array(); } } ?>
