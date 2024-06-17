<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3Handler"); class MyS3Bucket extends MyS3Handler { public function __construct($v614e1f4104, $v253839514f) { parent::__construct($v614e1f4104, $v253839514f); } public function create($v4907c60569, $v8b27c73d0e = "p", $pae397839 = false) { $pf9163b61 = $this->getACL($v8b27c73d0e); return $this->S3->putBucket($v4907c60569, $pf9163b61, $pae397839); } public function delete($v4907c60569) { $v5c1c342594 = true; $v6ee393d9fb = $this->getBucketFiles($v4907c60569); foreach($v6ee393d9fb as $pbfa01ed1 => $v67db1bd535) { if(!$this->S3->deleteObject($v4907c60569, $pbfa01ed1)) $v5c1c342594 = false; } return $v5c1c342594 ? $this->S3->deleteBucket($v4907c60569) : false; } public function getBucketFiles($v4907c60569) { return $this->S3->getBucket($v4907c60569); } public function getLocation($v4907c60569) { return $this->S3->getBucketLocation($v4907c60569); } public function getList($v2d06ae5c1d = true) { return $this->S3->listBuckets($v2d06ae5c1d); } } ?>
