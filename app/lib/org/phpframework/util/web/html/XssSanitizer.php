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
 if (file_exists( get_lib("lib.vendor.xsssanitizer.src.Sanitizer") )) { include_once get_lib("lib.vendor.xsssanitizer.src.FilterRunnerTrait"); include_once get_lib("lib.vendor.xsssanitizer.src.FilterInterface"); include_once get_lib("lib.vendor.xsssanitizer.src.AttributeFinder"); include_once get_lib("lib.vendor.xsssanitizer.src.TagFinderInterface"); include_once get_lib("lib.vendor.xsssanitizer.src.TagFinder.ByAttribute"); include_once get_lib("lib.vendor.xsssanitizer.src.TagFinder.ByTag"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeCleaner"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContentCleaner"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.EscapeTags"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.FilterRunner"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.MetaRefresh"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.RemoveAttributes"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.RemoveBlocks"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContent.CompactExplodedWords"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContent.DecodeEntities"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContent.DecodeUtf8"); include_once get_lib("lib.vendor.xsssanitizer.src.Sanitizer"); } class XssSanitizer { public static function sanitizeHtml($pf8ed4912) { if ($pf8ed4912 && class_exists("Phlib\XssSanitizer\Sanitizer")) { $v00c2ba641b = new Phlib\XssSanitizer\Sanitizer(); return $v00c2ba641b->sanitize($pf8ed4912); } return $pf8ed4912; } public static function sanitizeVariable($v847e7d0a83) { if ($v847e7d0a83) { if (is_array($v847e7d0a83) || is_object($v847e7d0a83)) { foreach ($v847e7d0a83 as $pe5c5e2fe => $v956913c90f) $v847e7d0a83[$pe5c5e2fe] = self::sanitizeVariable($v956913c90f); } else $v847e7d0a83 = self::sanitizeHtml($v847e7d0a83); } return $v847e7d0a83; } } ?>
