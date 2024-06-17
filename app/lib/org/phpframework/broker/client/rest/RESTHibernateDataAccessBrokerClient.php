<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.client.rest.RESTDataAccessBrokerClient"); include_once get_lib("org.phpframework.broker.client.IHibernateDataAccessBrokerClient"); class RESTHibernateDataAccessBrokerClient extends RESTDataAccessBrokerClient implements IHibernateDataAccessBrokerClient { public function callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/$pcd8c70bc/$v20b8676a9f"; return $this->requestResponse($v30857f7eca, array("options" => $v5d3813882f)); } } ?>
