<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
namespace WorkFlowTask\programming\restconnector; include_once dirname(__DIR__) . "/geturlcontents/WorkFlowTaskImpl.php"; class WorkFlowTaskImpl extends \WorkFlowTask\programming\geturlcontents\WorkFlowTaskImpl { public function __construct() { $this->method_obj = "RestConnector"; $this->method_name = "connect"; } } ?>
