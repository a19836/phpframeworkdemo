<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 */
 include_once get_lib("org.phpframework.broker.client.rest.RESTDataAccessBrokerClient"); include_once get_lib("org.phpframework.broker.client.IHibernateDataAccessBrokerClient"); class RESTHibernateDataAccessBrokerClient extends RESTDataAccessBrokerClient implements IHibernateDataAccessBrokerClient { public function callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { $v30857f7eca = $this->settings; $v30857f7eca["url"] .= "/$pcd8c70bc/$v20b8676a9f"; return $this->requestResponse($v30857f7eca, array("options" => $v5d3813882f)); } } ?>
