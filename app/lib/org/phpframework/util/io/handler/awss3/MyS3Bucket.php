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
 include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3Handler"); class MyS3Bucket extends MyS3Handler { public function __construct($v614e1f4104, $v253839514f) { parent::__construct($v614e1f4104, $v253839514f); } public function create($v4907c60569, $v8b27c73d0e = "p", $pae397839 = false) { $pf9163b61 = $this->getACL($v8b27c73d0e); return $this->S3->putBucket($v4907c60569, $pf9163b61, $pae397839); } public function delete($v4907c60569) { $v5c1c342594 = true; $v6ee393d9fb = $this->getBucketFiles($v4907c60569); foreach($v6ee393d9fb as $pbfa01ed1 => $v67db1bd535) { if(!$this->S3->deleteObject($v4907c60569, $pbfa01ed1)) $v5c1c342594 = false; } return $v5c1c342594 ? $this->S3->deleteBucket($v4907c60569) : false; } public function getBucketFiles($v4907c60569) { return $this->S3->getBucket($v4907c60569); } public function getLocation($v4907c60569) { return $this->S3->getBucketLocation($v4907c60569); } public function getList($v2d06ae5c1d = true) { return $this->S3->listBuckets($v2d06ae5c1d); } } ?>
