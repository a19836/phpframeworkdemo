<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.webservice.layer.LayerWebService"); class DBLayerWebService extends LayerWebService { public function __construct($v2a9b6f4e3b, $v30857f7eca = false) { parent::__construct($v2a9b6f4e3b, $v30857f7eca); $this->web_service_validation_string = "_is_db_webservice"; $this->broker_server_bean_name = DB_BROKER_SERVER_BEAN_NAME; } } ?>
