<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
class FileCompressionFactory { public static function create($v74128956c0) { $v0ff021f094 = self::isValid($v74128956c0); if (!$v0ff021f094) throw new Exception("Compression method ($v74128956c0) is not allowed!"); $v1335217393 = "{$v74128956c0}FileCompressionHandler"; return new $v1335217393(); } public static function isValid($v74128956c0) { $v1335217393 = "{$v74128956c0}FileCompressionHandler"; $pf3dc0762 = get_lib("org.phpframework.compression.{$v1335217393}"); if (file_exists($pf3dc0762)) { include_once $pf3dc0762; return is_a($v1335217393, "IFileCompressionHandler", true); } return false; } } ?>
