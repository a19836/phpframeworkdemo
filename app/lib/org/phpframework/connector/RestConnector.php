<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.web.MyCurl"); include_once get_lib("org.phpframework.util.xml.MyXML"); class RestConnector { public static function connect($v539082ff30, $pbd9f98de = null) { return MyCurl::getUrlContents($v539082ff30, $pbd9f98de); } } ?>
