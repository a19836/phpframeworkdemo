<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.broker.client.local.LocalDataAccessBrokerClient"); include_once get_lib("org.phpframework.broker.client.IHibernateDataAccessBrokerClient"); class LocalHibernateDataAccessBrokerClient extends LocalDataAccessBrokerClient implements IHibernateDataAccessBrokerClient { public function callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { return $this->getBrokerServer()->callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f); } } ?>
