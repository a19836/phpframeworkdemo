<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 interface ICMSProgramInstallationHandler { public static function getProgramSettingsHtml(); public function getStepHtml($v6602edb5ab, $pd9c013d5 = null, $pd65a9318 = null); public function installStep($v6602edb5ab, $pd9c013d5 = null, $pd65a9318 = null); public function validate(); public function install($pe8b53bc6 = false); public function uninstall(); } ?>
