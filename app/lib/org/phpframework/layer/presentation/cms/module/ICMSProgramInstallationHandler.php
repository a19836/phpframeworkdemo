<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
interface ICMSProgramInstallationHandler { public static function getProgramSettingsHtml(); public function getStepHtml($v6602edb5ab, $pd9c013d5 = null, $pd65a9318 = null); public function installStep($v6602edb5ab, $pd9c013d5 = null, $pd65a9318 = null); public function validate(); public function install($pe8b53bc6 = false); public function uninstall(); } ?>
