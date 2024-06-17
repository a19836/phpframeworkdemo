<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3Bucket"); include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3Folder"); include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3File"); include_once get_lib("org.phpframework.util.io.manager.MyIOManager"); class MyS3Manager extends MyIOManager { private $pc8f43bc2; private $pfb1d6d9d; private $v1da936ab40; private $v4907c60569; private $v3497e41814 = false; public function __construct($v7ed8121f97, $pa6dbebee) { $this->pc8f43bc2 = new MyS3Bucket($v7ed8121f97, $pa6dbebee); $this->pfb1d6d9d = new MyS3Folder($v7ed8121f97, $pa6dbebee); $this->v1da936ab40 = new MyS3File($v7ed8121f97, $pa6dbebee); } public function addBucket($v4907c60569, $v30857f7eca = array()) { $v8b27c73d0e = !empty($v30857f7eca["perm"]) ? $v30857f7eca["perm"] : "p"; $v5c1c342594 = $this->pc8f43bc2->create($v4907c60569, $v8b27c73d0e); if($v5c1c342594) { $v5c1c342594 = self::add(2, $v4907c60569, "/", ".", $v30857f7eca = array("content" => ".")); } return $v5c1c342594; } public function deleteBucket($v4907c60569) { return $this->pc8f43bc2->delete($v4907c60569); } public function getBuckets() { return $this->pc8f43bc2->getList(false); } public function add($v3fb9f41470, $v17be587282, $v5e813b295b, $v30857f7eca = array()) { $v8b27c73d0e = !empty($v30857f7eca["perm"]) ? $v30857f7eca["perm"] : "p"; $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v5e813b295b); if($v3fb9f41470 == 1) { $pbfa01ed1 = self::configureFolderPath($pbfa01ed1); $pae77d38c = "."; } else { if(!$this->checkType($v5e813b295b)) return false; $pae77d38c = isset($v30857f7eca["content"]) && strlen($v30857f7eca["content"]) > 0 ? $v30857f7eca["content"] : " "; } return $this->v1da936ab40->upload($pae77d38c, $this->v4907c60569, $pbfa01ed1, $v8b27c73d0e, array("type" => "string")); } public function edit($v17be587282, $v5e813b295b, $v30857f7eca = array()) { $v8b27c73d0e = !empty($v30857f7eca["perm"]) ? $v30857f7eca["perm"] : "p"; $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v5e813b295b); $pae77d38c = isset($v30857f7eca["content"]) && strlen($v30857f7eca["content"]) > 0 ? $v30857f7eca["content"] : " "; return $this->v1da936ab40->upload($pae77d38c, $this->v4907c60569, $pbfa01ed1, $v8b27c73d0e, array("type" => "string")); } public function delete($v3fb9f41470, $v17be587282, $v5e813b295b) { $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v5e813b295b); if($v3fb9f41470 == 1) { return $this->pfb1d6d9d->delete($this->v4907c60569, $pbfa01ed1); } else { return $this->v1da936ab40->delete($this->v4907c60569, $pbfa01ed1); } } public function copy($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()) { $v8b27c73d0e = !empty($v30857f7eca["perm"]) ? $v30857f7eca["perm"] : "p"; $pb6689abe = !empty($v30857f7eca["dest_bucket"]) ? $v30857f7eca["dest_bucket"] : $this->v4907c60569; $pfd8b0970 = !empty($v30857f7eca["dest_name"]) ? $v30857f7eca["dest_name"] : $v23d7f19208; $pfee4f441 = self::configurePath($this->getRootPath() . $pc941b4ab, $this->v3497e41814) . self::configureName($v23d7f19208); $v62fcc6685d = self::configurePath($this->getRootPath() . $v525288e856, $this->v3497e41814) . self::configureName($pfd8b0970); if($v3fb9f41470 == 1) { $pd16d97cf = self::configureFolderPath($pfee4f441); $paa485ebd = self::configureFolderPath($v62fcc6685d); if($this->v1da936ab40->copy($this->v4907c60569, $pd16d97cf, $pb6689abe, $paa485ebd, $v8b27c73d0e)) { $pfee4f441 .= "/"; $v62fcc6685d .= "/"; return $this->pfb1d6d9d->copy($this->v4907c60569, $pfee4f441, $pb6689abe, $v62fcc6685d, $v8b27c73d0e); } return false; } else { return $this->v1da936ab40->copy($this->v4907c60569, $pfee4f441, $pb6689abe, $v62fcc6685d, $v8b27c73d0e); } } public function move($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()) { if($this->copy($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca)) return $this->delete($v3fb9f41470, $pc941b4ab, $v23d7f19208); return false; } public function rename($v17be587282, $v0c4b06ddf7, $pe6871e84, $v30857f7eca = array()) { $v3fb9f41470 = !empty($v30857f7eca["type"]) ? $v30857f7eca["type"] : 1; if($v3fb9f41470 != 1 && !$this->checkType($pe6871e84)) return false; $v30857f7eca["dest_name"] = self::configureName($pe6871e84); return $this->move($v3fb9f41470, $v17be587282, $v0c4b06ddf7, $v17be587282, $v30857f7eca); } public function getFile($v17be587282, $v5e813b295b, $v902f1c2d84 = false) { $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v5e813b295b); if(!$this->checkType($v5e813b295b)) return false; return $this->v1da936ab40->get($this->v4907c60569, $pbfa01ed1, $v902f1c2d84); } public function getFileInfo($v17be587282, $v5e813b295b, $v3fb9f41470 = 2) { $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v5e813b295b); if($v3fb9f41470 != 1 && !$this->checkType($v5e813b295b)) return false; if($v3fb9f41470 == 1) { $pbfa01ed1 = self::configureFolderPath($pbfa01ed1); } $v872c4849e0 = $this->v1da936ab40->getInfo($this->v4907c60569, $pbfa01ed1); if (isset($v872c4849e0["path"])) $v872c4849e0["path"] = $v3fb9f41470 == 1 ? dirname($v872c4849e0["path"]) : $v872c4849e0["path"]; return $v872c4849e0; } public function getFiles($v17be587282) { $pdcf670f6 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814); $v6ee393d9fb = $this->pfb1d6d9d->getFiles($this->v4907c60569, $pdcf670f6); $v6ee393d9fb["files"] = $this->prepareFiles($v6ee393d9fb["files"]); return $v6ee393d9fb; } public function getFilesCount($v17be587282) { $pdcf670f6 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814); return $this->pfb1d6d9d->getFilesCount($this->v4907c60569, $pdcf670f6); } public function upload($v6eee6903b3, $v17be587282, $pe6871e84, $v30857f7eca = array()) { $v8b27c73d0e = !empty($v30857f7eca["perm"]) ? $v30857f7eca["perm"] : "p"; $v250a1176c9 = trim($pe6871e84) ? $pe6871e84 : (isset($v6eee6903b3["name"]) ? $v6eee6903b3["name"] : null); $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v250a1176c9); if(!$this->checkType($v250a1176c9)) return false; return isset($v6eee6903b3['tmp_name']) ? $this->v1da936ab40->upload($v6eee6903b3['tmp_name'], $this->v4907c60569, $pbfa01ed1, $v8b27c73d0e) : false; } public function exists($v17be587282, $v5e813b295b, $v3fb9f41470 = 2) { $pbfa01ed1 = self::configurePath($this->getRootPath() . $v17be587282, $this->v3497e41814) . self::configureName($v5e813b295b); if($v3fb9f41470 == 1) { $pbfa01ed1 = self::configureFolderPath($pbfa01ed1); } return $this->v1da936ab40->exists($this->v4907c60569, $pbfa01ed1); } public static function configureFolderPath($pa32be502) { $pa32be502 = self::removeDuplicates($pa32be502); if(substr($pa32be502, strlen($pa32be502) - 2) != "/.") $pa32be502 = self::configurePath($pa32be502) . "."; return $pa32be502; } public function setBucket($v4907c60569) { $this->v4907c60569 = $v4907c60569; } public function getBucket() { return $this->v4907c60569; } public function setFree($v3497e41814) { $this->v3497e41814 = $v3497e41814; } public function getMyIOHandler() { return $this->v1da936ab40; } } ?>
