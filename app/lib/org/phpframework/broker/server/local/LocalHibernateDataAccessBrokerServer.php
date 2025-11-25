<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 include_once get_lib("org.phpframework.broker.server.local.LocalDataAccessBrokerServer"); include_once get_lib("org.phpframework.broker.server.IHibernateDataAccessBrokerServer"); class LocalHibernateDataAccessBrokerServer extends LocalDataAccessBrokerServer implements IHibernateDataAccessBrokerServer { public function callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f = false) { return $this->Layer->callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f); } } ?>
