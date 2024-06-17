<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.BrokerServer"); abstract class LocalBrokerServer extends BrokerServer { public function callWebService() { launch_exception( new Exception("You cannot call the callWebService in the LocalBrokerServer, because is not a web service!\n The LocalBrokerServer should be called internally by other files, through the 'include' php function!") ); return null; } } ?>
