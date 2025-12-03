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
 class FileCompressionFactory { public static function create($v74128956c0) { $v0ff021f094 = self::isValid($v74128956c0); if (!$v0ff021f094) throw new Exception("Compression method ($v74128956c0) is not allowed!"); $v1335217393 = "{$v74128956c0}FileCompressionHandler"; return new $v1335217393(); } public static function isValid($v74128956c0) { $v1335217393 = "{$v74128956c0}FileCompressionHandler"; $pf3dc0762 = get_lib("org.phpframework.compression.{$v1335217393}"); if (file_exists($pf3dc0762)) { include_once $pf3dc0762; return is_a($v1335217393, "IFileCompressionHandler", true); } return false; } public static function getClassPrefixByType($v3fb9f41470) { switch (strtolower($v3fb9f41470)) { case "bzip2": return "Bzip2"; case "gzip": return "Gzip"; case "gzipstream": return "Gzipstream"; case "zip": return "Zip"; } return null; } public static function getClassPrefixByExtension($v6bfcc44e7b) { switch (strtolower($v6bfcc44e7b)) { case "bz2": return "Bzip2"; case "gz": return "Gzip"; case "zip": return "Zip"; } return null; } public static function getExtension($v74128956c0) { switch ($v74128956c0) { case "Bzip2": return "bz2"; case "Gzip": return "gz"; case "Gzipstream": return "gz"; case "Zip": return "zip"; } return null; } } ?>
