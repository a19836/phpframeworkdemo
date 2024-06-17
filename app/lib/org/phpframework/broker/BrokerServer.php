<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.Broker"); include_once get_lib("org.phpframework.layer.Layer"); abstract class BrokerServer extends Broker { protected $Layer; public function __construct(Layer $v847a7225e0) { $this->Layer = $v847a7225e0; parent::__construct(); } public function getBrokerLayer() { return $this->Layer; } } ?>
