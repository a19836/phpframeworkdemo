<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.mongodb.exception.MongoDBException"); include_once get_lib("org.phpframework.mongodb.IMongoDBHandler"); class MongoDBHandler implements IMongoDBHandler { private $pf3d2eef6; private $v2068a4d581; private $v30db5ee601; private db_name; public function connect($v244067a7fe = "", $pb67a2609 = "", $pd97bc935 = "", $v8a9d082c74 = "", $v7e782022ec = "", $v5d3813882f = null) { try { $pe19888c0 = is_numeric($v7e782022ec) ? $v244067a7fe . ":" . $v7e782022ec : $v244067a7fe; $this->v2068a4d581 = false; $this->v30db5ee601 = !empty($pd97bc935); if (!empty($pd97bc935)) { if (empty($v5d3813882f)) $v5d3813882f = array(); $v5d3813882f["username"] = $pd97bc935; $v5d3813882f["password"] = $v8a9d082c74; } $this->pf3d2eef6 = new MongoDB\Driver\Manager("mongodb://$pe19888c0", $v5d3813882f); $this->db_name = $pb67a2609; if ($this->pf3d2eef6) { $this->v2068a4d581 = true; return $this->pf3d2eef6; } else launch_exception(new MongoDBException(1, null, array($v244067a7fe, $pb67a2609, $pd97bc935, "***", $v7e782022ec))); } catch(Exception $paec2c009) { launch_exception(new MongoDBException(1, $paec2c009, array($v244067a7fe, $pb67a2609, $pd97bc935, "***", $v7e782022ec))); } } public function close() { if ($this->v2068a4d581) { $pd8481879 = new MongoDB\Driver\Command( array("logout" => 1) ); $this->pf3d2eef6->executeCommand($this->db_name, $pd8481879); $this->v2068a4d581 = false; } } public function ok() { return $this->v2068a4d581; } public function getConn() { return $this->v2068a4d581 ? $this->pf3d2eef6 : null; } public function get($pbc6196f8, $pbfa01ed1) { if ($this->v2068a4d581 && $pbc6196f8 && $pbfa01ed1) { $v7959970a41 = $this->existsCollection($pbc6196f8); if ($v7959970a41) { $v1cbfbb49c5 = new MongoDB\BSON\ObjectId($pbfa01ed1); $v28ad2c02f1 = $this->findCollectionDocuments($pbc6196f8, array('_id' => $v1cbfbb49c5), array("limit" => 1)); $v28ad2c02f1 = isset($v28ad2c02f1[0]) ? $v28ad2c02f1[0] : null; if (is_array($v28ad2c02f1) && isset($v28ad2c02f1["content"])) return $v28ad2c02f1["content"]; } } return false; } public function getByRegex($pbc6196f8, $v5f7147fb39) { $pf12f7921 = array(); if ($this->v2068a4d581 && $pbc6196f8 && $v5f7147fb39) { $v7959970a41 = $this->existsCollection($pbc6196f8); if ($v7959970a41) { $v5430c73cb8 = new MongoDB\BSON\Regex($v5f7147fb39); return $this->findCollectionDocuments($pbc6196f8, array('raw_id' => $v5430c73cb8)); } } return $pf12f7921; } public function set($pbc6196f8, $pbfa01ed1, $v57b4b0200b) { if ($this->v2068a4d581 && $pbc6196f8 && $pbfa01ed1) { $v7959970a41 = $this->existsCollection($pbc6196f8); if (!$v7959970a41) { $v7959970a41 = $this->createCollection($pbc6196f8); if ($v7959970a41) $v1478ac8d84->ensureIndex( array( "raw_id" => 1 ) ); } if ($v7959970a41) { $v1cbfbb49c5 = new MongoDB\BSON\ObjectId($pbfa01ed1); $v539082ff30 = array( '_id' => $v1cbfbb49c5, 'raw_id' => $pbfa01ed1, "content" => $v57b4b0200b, ); return $this->insertCollectionDocument($pbc6196f8, $v539082ff30); } } return false; } public function delete($pbc6196f8, $pbfa01ed1) { if ($this->v2068a4d581 && $pbc6196f8 && $pbfa01ed1) { $v7959970a41 = $this->existsCollection($pbc6196f8); if ($v7959970a41) { $v1cbfbb49c5 = new MongoDB\BSON\ObjectId($pbfa01ed1); return $this->deleteCollectionDocuments($pbc6196f8, array('_id' => $v1cbfbb49c5)); } } return false; } public function deleteByRegex($pbc6196f8, $v5f7147fb39) { if ($this->v2068a4d581 && $pbc6196f8 && $v5f7147fb39) { $v7959970a41 = $this->existsCollection($pbc6196f8); if ($v7959970a41) { $v5430c73cb8 = new MongoDB\BSON\Regex($v5f7147fb39); return $this->deleteCollectionDocuments($pbc6196f8, array('raw_id' => $v5430c73cb8)); } } return false; } public function executeCommand($pa5f787e0) { try { if ($this->v2068a4d581 && $pa5f787e0) { $pd8481879 = new MongoDB\Driver\Command($pa5f787e0); $v7eb3ade8cb = $this->pf3d2eef6->executeCommand($this->db_name, $pd8481879); $v7bd5d88a74 = null; if ($v7eb3ade8cb) { $pfb662071 = $v7eb3ade8cb->toArray(); $v7bd5d88a74 = isset($pfb662071[0]) ? $pfb662071[0] : null; } return $v7bd5d88a74 && !empty($v7bd5d88a74["ok"]); } } catch(Exception $paec2c009) { launch_exception(new MongoDBException(3, $paec2c009, $pa5f787e0)); } return false; } public function executeQuery($pbc6196f8, $v35facb36d1 = array(), $v5d3813882f = array()) { try { if ($this->v2068a4d581 && $pbc6196f8) { $v9d1744e29c = new MongoDB\Driver\Query($v35facb36d1, $v5d3813882f); $v3dd67d635b = $this->pf3d2eef6->executeQuery($this->db_name . "." . $pbc6196f8, $v9d1744e29c); $v539082ff30 = array(); foreach ($v3dd67d635b as $pff59654a) $v539082ff30[] = $pff59654a; return $v539082ff30; } } catch(Exception $paec2c009) { launch_exception(new MongoDBException(7, $paec2c009, array("collection_name" => $pbc6196f8, "filter" => $v35facb36d1, "options" => $v5d3813882f))); } return false; } public function deleteCollection($pbc6196f8) { return $this->executeCommand( array("drop" => $pbc6196f8) ); } public function createCollection($pbc6196f8) { return $this->executeCommand( array("create" => $pbc6196f8) ); } public function existsCollection($pbc6196f8) { return $this->executeCommand( array("collstats" => $pbc6196f8) ); } public function insertCollectionDocument($pbc6196f8, $v539082ff30) { try { if ($this->v2068a4d581 && $pbc6196f8) { $v765850bdf2 = new MongoDB\Driver\BulkWrite; $v765850bdf2->insert($v539082ff30); $v328483cee7 = $this->pf3d2eef6->executeBulkWrite($this->db_name, $v765850bdf2); return $v328483cee7 && !empty($v328483cee7["ok"]); } } catch(Exception $paec2c009) { launch_exception(new MongoDBException(4, $paec2c009, array("collection_name" => $pbc6196f8, "data" => $v539082ff30))); } return false; } public function updateCollectionDocument($pbc6196f8, $v35facb36d1, $v539082ff30) { try { if ($this->v2068a4d581 && $pbc6196f8 && $v35facb36d1) { $v765850bdf2 = new MongoDB\Driver\BulkWrite; $v765850bdf2->update($v35facb36d1, array('$set' => $v539082ff30), array('multi' => false)); $v328483cee7 = $this->pf3d2eef6->executeBulkWrite($this->db_name, $v765850bdf2); return $v328483cee7 && !empty($v328483cee7["ok"]); } } catch(Exception $paec2c009) { launch_exception(new MongoDBException(5, $paec2c009, array("collection_name" => $pbc6196f8, "filter" => $v35facb36d1, "data" => $v539082ff30))); } return false; } public function deleteCollectionDocuments($pbc6196f8, $v35facb36d1) { try { if ($this->v2068a4d581 && $pbc6196f8 && $v35facb36d1) { $v765850bdf2 = new MongoDB\Driver\BulkWrite; $v765850bdf2->delete($v35facb36d1); $v328483cee7 = $this->pf3d2eef6->executeBulkWrite($this->db_name, $v765850bdf2); return $v328483cee7 && !empty($v328483cee7["ok"]); } } catch(Exception $paec2c009) { launch_exception(new MongoDBException(6, $paec2c009, array("collection_name" => $pbc6196f8, "filter" => $v35facb36d1))); } return false; } public function findCollectionDocuments($pbc6196f8, $v35facb36d1 = array(), $v5d3813882f = array()) { return $this->executeQuery($pbc6196f8, $v35facb36d1, $v5d3813882f); } } ?>
