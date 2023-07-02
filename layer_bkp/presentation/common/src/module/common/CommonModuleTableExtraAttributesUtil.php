<?php
include_once $EVC->getModulePath("common/CommonModuleUI", $EVC->getCommonProjectName());

class CommonModuleTableExtraAttributesUtil {
	private $CMSModuleHandler;
	private $db_driver_name;
	private $settings;
	private $main_attributes_table_name;
	private $extra_attributes_table_name;
	private $extra_attributes_table_alias;
	
	private $EVC;
	private $brokers;
	private $selected_broker;
	private $group_module_id;
	private $extra_attributes_query_name;
	private $extra_attributes_object_name;
	private $extra_attributes;
	private $extra_pks;
	private $files_extra_attributes_name;
	
	private $enabled = false;
	private $xss_sanitize_lib_included = false;
	
	public function __construct(ICMSModuleHandler $CMSModuleHandler, $db_driver_name, $settings, $main_attributes_table_name, $main_attributes_table_alias = false, $extra_attributes_table_name = false, $extra_attributes_table_alias = false) {
		$this->CMSModuleHandler = $CMSModuleHandler;
		$this->db_driver_name = $db_driver_name;
		$this->settings = $settings;
		$this->EVC = $CMSModuleHandler->getEVC();
		$this->brokers = $this->EVC->getPresentationLayer()->getBrokers();
		$this->main_attributes_table_name = $main_attributes_table_name;
		$main_attributes_table_alias = $main_attributes_table_alias ? $main_attributes_table_alias : $main_attributes_table_name;
		$this->extra_attributes_table_name = $extra_attributes_table_name ? $extra_attributes_table_name : $main_attributes_table_name . "_extra";
		$this->extra_attributes_table_alias = $extra_attributes_table_alias ? $extra_attributes_table_alias : $main_attributes_table_alias . "_extra";
		
		$this->group_module_id = $this->getGroupModuleId();
		$this->extra_attributes_query_name = ($this->db_driver_name ? $this->db_driver_name : "default") . "_" . $this->extra_attributes_table_alias;
		$this->extra_attributes_object_name = $this->getObjectName($this->extra_attributes_query_name);
		
		$this->loadExtraAttributes();
		$this->init($settings);
	}
	
	public function insertTableExtra($data, $files = null) {
		if (!$this->enabled)
			return true;
		
		$orig_data = $data;
		
		//saving attachments
		$status = $this->updateTableExtraAttachments($data, $files);
		
		//saving data
		if ($status) {
			$broker = $this->selected_broker;
			
			if (is_a($broker, "IBusinessLogicBrokerClient"))
				$status = $broker->callBusinessLogic("module/" . $this->group_module_id, $this->extra_attributes_object_name . "Service.insert", $data);
			else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
				$d = $this->getPreparedData($data, true);
				
				$status = $broker->callInsert("module/" . $this->group_module_id, "insert_" . $this->extra_attributes_query_name, $d);
				//$status = $status ? $broker->getInsertedId() : null;
			}
			else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
				$d = $this->getPreparedData($data);
				
				$obj = $broker->callObject("module/" . $this->group_module_id, $this->extra_attributes_object_name);
				$status = $obj ? $obj->insert($d) : null;
			}
			else if (is_a($broker, "IDBBrokerClient")) {
				$d = $this->getPreparedData($data);
				
				$attributes = array_filter($d, function($k) {
					return $k && $this->extra_attributes[$k]; 
	    			}, ARRAY_FILTER_USE_KEY);
				
				$status = $broker->insertObject($this->extra_attributes_table_name, $attributes);
				//$status = $status ? $broker->getInsertedId() : null;
			}
		}
		
		//deleting attachments that were not saved but were uploaded
		if (!$status) {
			$del_data = array_diff($orig_data, $data); //only get the new saved attachments
			$this->deleteTableExtraAttachments($del_data); //deleting attachments
		}
		
		return $status;
	}
	
	public function updateTableExtra($data, $files = null) {
		if (!$this->enabled)
			return true;
		
		$orig_data = $data;
		
		//saving attachments
		$status = $this->updateTableExtraAttachments($data, $files);
		
		//saving data
		if ($status) {
			$broker = $this->selected_broker;
			
			if (is_a($broker, "IBusinessLogicBrokerClient"))
				$status = $broker->callBusinessLogic("module/" . $this->group_module_id, $this->extra_attributes_object_name . "Service.update", $data);
			else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
				$d = $this->getPreparedData($data, true);
				
				$status = $broker->callUpdate("module/" . $this->group_module_id, "update_" . $this->extra_attributes_query_name, $d);
			}
			else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
				$d = $this->getPreparedData($data);
				
				$obj = $broker->callObject("module/" . $this->group_module_id, $this->extra_attributes_object_name);
				$status = $obj ? $obj->update($d) : null;
			}
			else if (is_a($broker, "IDBBrokerClient")) {
				$d = $this->getPreparedData($data);
				
				$attributes = array_filter($d, function($k) {
					return $k && $this->extra_attributes[$k]; 
	    			}, ARRAY_FILTER_USE_KEY);
	    			$conditions = array_filter($d, function($k) {
					return $k && $this->extra_pks[$k]; 
	    			}, ARRAY_FILTER_USE_KEY);
				
				$status = $conditions && $broker->updateObject($this->extra_attributes_table_name, $attributes, $conditions);
			}
		}
		
		//deleting attachments that were not saved but were uploaded
		if (!$status) {
			$del_data = array_diff($orig_data, $data); //only get the new saved attachments
			$this->deleteTableExtraAttachments($del_data); //deleting attachments
		}
		
		return $status;
	}
	
	public function insertOrUpdateTableExtra($data, $files = null) {
		if (!$this->enabled)
			return true;
		
		$result = $this->getTableExtra($data, true);
		
		if ($result)
			return $this->updateTableExtra($data, $files);
			
		return $this->insertTableExtra($data, $files);
	}
	
	public function deleteTableExtra($data) {
		if (!$this->enabled)
			return true;
		
		if ($data) {
			//deleting attachments
			$status = $this->deleteTableExtraAttachments($data);
			
			//deleteing data
			if ($status) {
				$broker = $this->selected_broker;
				
				if (is_a($broker, "IBusinessLogicBrokerClient"))
					$status = $broker->callBusinessLogic("module/" . $this->group_module_id, $this->extra_attributes_object_name . "Service.delete", $data);
				else if (is_a($broker, "IIbatisDataAccessBrokerClient"))
					$status = $broker->callDelete("module/" . $this->group_module_id, "delete_" . $this->extra_attributes_query_name, $data);
				else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$obj = $broker->callObject("module/" . $this->group_module_id, $this->extra_attributes_object_name);
					$status = $obj ? $obj->delete($data) : null;
				}
				else if (is_a($broker, "IDBBrokerClient")) {
					$conditions = array_filter($data, function($k) {
						return $k && $this->extra_pks[$k];
		    			}, ARRAY_FILTER_USE_KEY);
		    			
					$status = $conditions && $broker->deleteObject($this->extra_attributes_table_name, $conditions);
				}
			}
			
			return $status;
		}
	}
	
	private function getPreparedData($data, $is_ibatis = false) {
		if ($this->extra_attributes)
			foreach ($this->extra_attributes as $attr_name => $attr_settings) {
				$db_attribute = $attr_settings["db_attribute"];
				
				if ($db_attribute) {
					if (!$db_attribute["primary_key"] && $db_attribute["null"] && (ObjTypeHandler::isDBTypeDate($db_attribute["type"]) || ObjTypeHandler::isDBTypeNumeric($db_attribute["type"]))) {
						$default = $db_attribute["default"];
						
						if (ObjTypeHandler::isDBAttributeValueACurrentTimestamp($default))
							$default = date("Y-m-d H:i:s");
						if (trim(strtolower($default)) == "null")
							$default = null;
						
						if (!isset($data[$attr_name]) || (is_string($data[$attr_name]) && !strlen(trim($data[$attr_name])))) {
							if (is_string($default) && strlen($default))
								$data[$attr_name] = $default;
							if ($is_ibatis)
								$data[$attr_name] = 'null';
							else //is hibernate
								$data[$attr_name] = null;
						}
					}
					
					if (ObjTypeHandler::convertCompositeTypeIntoSimpleType($db_attribute["type"]) != "no_string" && !ObjTypeHandler::isPHPTypeNumeric($db_attribute["type"])) {
						if ($data[$attr_name]) {
							$sanitize_html = empty($attr_settings["allow_javascript"]);
							
							if ($sanitize_html) {
								if (!$this->xss_sanitize_lib_included)
									include_once get_lib("org.phpframework.util.web.html.XssSanitizer"); //leave this here, otherwise it could be over-loading for every request to include without need it...
								
								$this->xss_sanitize_lib_included = true;
								
								$data[$attr_name] = XssSanitizer::sanitizeHtml($data[$attr_name]);
							}
							
							$data[$attr_name] = addcslashes($data[$attr_name], "\\'");
						}
					}
				}
			}
		
		return $data;
	}
	
	private function updateTableExtraAttachments(&$data, $files = null) {
		$status = true;
		
		if ($this->files_extra_attributes_name) {
			$files = isset($files) ? $files : $_FILES;
			$db_data = $this->getTableExtra($data, true);
			
			foreach ($this->files_extra_attributes_name as $attr_name) {
				$delete_attachment = !$data[$attr_name] || $data[$attr_name] != $db_data[$attr_name]; //bc of the default_value that could be set
				
				if ($delete_attachment) {
					if (AttachmentUtil::deleteFile($this->EVC, $db_data[$attr_name], array($this->selected_broker)))
						$data[$attr_name] = null;
				}
				
				if ($files && $files[$attr_name] && $files[$attr_name]["tmp_name"]) {
					//check if file is valid
					$file_type = $this->extra_attributes[$attr_name]["file_type"];
					$s = true;
					
					if ($file_type == "image") {
						$mime_type = $files[$attr_name]["type"] ? $files[$attr_name]["type"] : MimeTypeHandler::getFileMimeType($files[$attr_name]["tmp_name"]);
						
						if (!MimeTypeHandler::isImageMimeType($mime_type))
							$s = false;
					}
					
					if ($s) {
						//insert or update attachment
						$attachment_id = AttachmentUtil::replaceFile($this->EVC, $files[$attr_name], $data[$attr_name], array($this->selected_broker));
						
						if (!$attachment_id)
							$status = false;
						else if ($attachment_id != $data[$attr_name]) //update attachment_id in data if different
							$data[$attr_name] = $attachment_id;
					}
					else
						$status = false;
				}
			}
		}
		
		return $status;
	}
	
	private function deleteTableExtraAttachments($data) {
		$status = true;
		
		if ($this->files_extra_attributes_name)
			foreach ($this->files_extra_attributes_name as $attr_name)
				if ($data[$attr_name] && !AttachmentUtil::deleteFile($this->EVC, $data[$attr_name], array($this->selected_broker)))
					$status = false;
		
		return $status;
	}
	
	public function getTableExtra($data, $no_cache = false) {
		if (!$this->enabled)
			return null;
		
		if ($data) {
			$broker = $this->selected_broker;
			
			if (is_a($broker, "IBusinessLogicBrokerClient"))
				$result = $broker->callBusinessLogic("module/" . $this->group_module_id, $this->extra_attributes_object_name . "Service.get", $data, array("no_cache" => $no_cache));
			else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
				$result = $broker->callSelect("module/" . $this->group_module_id, "get_" . $this->extra_attributes_query_name, $data, array("no_cache" => $no_cache));
				$result = $result[0];
			}
			else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
				$obj = $broker->callObject("module/" . $this->group_module_id, $this->extra_attributes_object_name);
				$result = $obj ? $obj->findById($data, null, array("no_cache" => $no_cache)) : null;
			}
			else if (is_a($broker, "IDBBrokerClient")) {
				$conditions = array_filter($data, function($k) {
					return $k && $this->extra_pks[$k]; 
	    			}, ARRAY_FILTER_USE_KEY);
	    			
				$result = $conditions && $broker->findObjects($this->extra_attributes_table_name, null, $conditions, array("no_cache" => $no_cache));
				$result = $result ? $result[0] : null;
			}
			
			//preparing file fields with attachments
			if ($result && $this->files_extra_attributes_name)
				foreach ($this->files_extra_attributes_name as $attr_name)
					if ($result[$attr_name]) {
						$attachment_data = AttachmentUtil::getAttachmentsByConditions(array($broker), array("attachment_id" => $result[$attr_name]), null, null, $no_cache);
						
						$result[$attr_name . "_path"] = AttachmentUtil::getAttachmentsFolderPath($this->EVC) . $attachment_data[0]["path"];
						$result[$attr_name . "_url"] = AttachmentUtil::getAttachmentsFolderUrl($this->EVC) . $attachment_data[0]["path"];
						$result[$attr_name . "_name"] = $attachment_data[0]["name"];
					}
			
			return $result;
		}
	}
	
	public function getAllTableExtra($conditions, $conditions_join, $options = array(), $no_cache = false) {
		if (!$this->enabled)
			return null;
		
		$options["no_cache"] = isset($options["no_cache"]) ? $options["no_cache"] : $no_cache;
		$broker = $this->selected_broker;
		
		if (is_a($broker, "IBusinessLogicBrokerClient"))
			$result = $broker->callBusinessLogic("module/" . $this->group_module_id, $this->extra_attributes_object_name . "Service.getAll", array("conditions" => $conditions, "conditions_join" => $conditions_join, "options" => $options), $options);
		else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
			$cond = DB::getSQLConditions($conditions, $conditions_join);
			$result = $broker->callSelect("module/" . $this->group_module_id, "get_" . $this->extra_attributes_query_name . "_items", array("conditions" => $cond), $options);
		}
		else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
			$obj = $broker->callObject("module/" . $this->group_module_id, $this->extra_attributes_object_name);
			$result = $obj ? $obj->find(array("conditions" => $conditions, "conditions_join" => $conditions_join), $options) : null;
		}
		else if (is_a($broker, "IDBBrokerClient")) {
			$options["conditions_join"] = $conditions_join;
			$result = $broker->findObjects($this->extra_attributes_table_name, null, $conditions, $options);
		}
			
		$this->prepareTableExtraItemsWithAttachments($result, $no_cache);
		
		return $result;
	}
	
	public function countAllTableExtra($conditions, $conditions_join, $no_cache = false) {
		if (!$this->enabled)
			return null;
			
		$broker = $this->selected_broker;
		
		if (is_a($broker, "IBusinessLogicBrokerClient"))
			return $broker->callBusinessLogic("module/" . $this->group_module_id, $this->extra_attributes_object_name . "Service.countAll", array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache));
		else if (is_a($broker, "IIbatisDataAccessBrokerClient")) {
			$cond = DB::getSQLConditions($conditions, $conditions_join);
			$result = $broker->callSelect("module/" . $this->group_module_id, "count_" . $this->extra_attributes_query_name . "_items", array("conditions" => $cond), array("no_cache" => $no_cache));
			return $result[0]["total"];
		}
		else if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
			$obj = $broker->callObject("module/" . $this->group_module_id, $this->extra_attributes_object_name);
			return $obj ? $obj->count(array("conditions" => $conditions, "conditions_join" => $conditions_join), array("no_cache" => $no_cache)) : null;
		}
		else if (is_a($broker, "IDBBrokerClient")) {
			return $broker->countObjects($this->extra_attributes_table_name, $conditions, array("no_cache" => $no_cache, "conditions_join" => $conditions_join));
		}
	}
	
	public function prepareItemsWithTableExtra(&$items, $pks_name, $options = array(), $no_cache = false) {
		if (!$this->enabled)
			return true;
		
		if ($items) {
			//prepare $pks_name
			if (is_string($pks_name))
				$pks_name = trim($pks_name);
			else if (is_array($pks_name))
				foreach ($pks_name as $idx => $pk_name)
					if (!trim($pk_name))
						unset($pks_name[$idx]);
			
			if (!$pks_name) {
				throw new Exception("CommonModuleTableExtraAttributesUtil::prepareItemsWithTableExtra cannot have the \$pks argument as undefined!");
				return false;
			}
			
			$pks_name = is_array($pks_name) ? $pks_name : array($pks_name);
			
			//prepare buckets
			$unique_pk = count($pks_name) == 1;
			$buckets_limit = $unique_pk ? 100 : (count($pks_name) == 2 ? 20 : 5);
			$bucket_index = 0;
			$buckets = array();
			$items_index_by_id = array();
			
			foreach ($items as $idx => $item) 
				if ($item) {
					if ($unique_pk) {
						$v = $item[ $pks_name[0] ];
						$buckets[$bucket_index][] = $v;
						$pks_key = $v;
					}
					else {
						$data = array();
						$pks_key = "";
						
						foreach ($pks_name as $pk_name) {
							$v = $item[$pk_name];
							$data[$pk_name] = $v;
							$pks_key .= "_" . $v;
						}
						
						$buckets[$bucket_index][] = array("AND" => $data);
					}
					
					$items_index_by_id[$pks_key] = $idx;
					
					if (count($buckets[$bucket_index]) >= $buckets_limit)
						$bucket_index++;
				}
			
			//prepare items
			foreach ($buckets as $bucket) {
				if ($unique_pk)
					$conditions = array($pks_name[0] => array(
						"operator" => "in",
						"value" => $bucket,
					));
				else
					$conditions = array("OR" => $bucket);
				
				$items_extra = $this->getAllTableExtra($conditions, null, $options, $no_cache);
				
				if ($items_extra)
					foreach ($items_extra as $item_extra) 
						if ($item_extra) {
							if ($unique_pk)
								$pks_key = $item_extra[ $pks_name[0] ];
							else {
								$pks_key = "";
								foreach ($pks_name as $pk_name)
									$pks_key .= "_" . $item_extra[$pk_name];
							}
							
							$idx = $items_index_by_id[$pks_key];
							
							if (is_numeric($idx))
								$items[$idx] = array_merge($items[$idx], $item_extra);
						}
			}
		}
		
		return true;
	}
	
	public function prepareTableExtraItemsWithAttachments(&$result, $no_cache = false) {
		//preparing file fields with attachments
		if ($this->enabled && $result && $this->files_extra_attributes_name) {
			$bucket_index = 0;
			$bucket_limit = 100;
			$buckets = array();
			$buckets_start_end = array(
				$bucket_index => array("start" => 0)
			);
			
			foreach ($result as $idx => $item) {
				foreach ($this->files_extra_attributes_name as $attr_name)
					if ($item[$attr_name])
						$buckets[$bucket_index][] = $item[$attr_name];
				
				if ($buckets[$bucket_index] && count($buckets[$bucket_index]) > $bucket_limit) {
					$buckets_start_end[$bucket_index]["end"] = $idx;
					$bucket_index++;
					$buckets_start_end[$bucket_index]["start"] = $idx + 1;
				}
			}
			
			$buckets_start_end[$bucket_index]["end"] = count($result);
			$folder_path = AttachmentUtil::getAttachmentsFolderPath($this->EVC);
			$url = AttachmentUtil::getAttachmentsFolderUrl($this->EVC);
			
			foreach ($buckets as $bucket_index => $attachment_ids) {
				$start = $buckets_start_end[$bucket_index]["start"];
				$end = $buckets_start_end[$bucket_index]["end"];
				
				$attachments = AttachmentUtil::getAttachmentsByIds(array($this->selected_broker), $attachment_ids, $no_cache);
				
				if ($attachments) {
					$attachment_paths_by_id = array();
					foreach ($attachments as $attachment)
						$attachment_paths_by_id[ $attachment["attachment_id"] ] = $attachment["path"];
					
					for ($i = $start; $i < $end; $i++) {
						$item = $result[$i];
						
						if ($item)
							foreach ($this->files_extra_attributes_name as $attr_name)
								if ($item[$attr_name]) {
									$path = $attachment_paths_by_id[ $item[$attr_name] ];
									
									if ($path) {
										$result[$i][$attr_name . "_path"] = $folder_path . $path;
										$result[$i][$attr_name . "_url"] = $url . $path;
										$result[$i][$attr_name . "_name"] = $attachment["name"];
									}
								}
					}
				}
			}
		}
	}
	
	public function checkIfEmptyFields($settings, $data, $files = null) {
		if ($this->enabled && $this->extra_attributes) {
			$fields = array();
			
			foreach ($this->extra_attributes as $attr_name => $attr_settings)
				$fields[$attr_name] = $data[$attr_name];
			
			return CommonModuleUI::checkIfEmptyFields($settings, $fields, $files);
		}
	}
	
	public function prepareFieldsWithNewData($settings, &$data, $old_data, $new_data) {
		if ($this->enabled && $this->extra_attributes)
			foreach ($this->extra_attributes as $attr_name => $attr_settings)
				$data[$attr_name] = $settings["show_$attr_name"] ? $new_data[$attr_name] : $old_data[$attr_name];
	}
	
	//Only check the files fields, bc all the others will be already checked by CommonModuleUI::areFieldsValid method.
	public function areFileFieldsValid($EVC, $settings, &$error_message = false, $files = null) {
		$files = isset($files) ? $files : $_FILES;
		
		if ($this->enabled && $this->files_extra_attributes_name && $files) {
			foreach ($this->files_extra_attributes_name as $attr_name)
				if ($settings["show_$attr_name"]) {
					$input_settings = $settings["fields"][$attr_name]["field"]["input"];
					
					if ($input_settings["type"] == "file" && $files[$attr_name] && $files[$attr_name]["tmp_name"]) {
						$file_type = $this->extra_attributes[$attr_name]["file_type"];
						
						if ($file_type == "image") {
							$mime_type = $files[$attr_name]["type"] ? $files[$attr_name]["type"] : MimeTypeHandler::getFileMimeType($files[$attr_name]["tmp_name"]);
							
							if (!MimeTypeHandler::isImageMimeType($mime_type)) {
								if ($input_settings["validation_message"]) 
									$error_message = translateProjectText($EVC, $input_settings["validation_message"]);
								else 
									$error_message = CommonModuleUI::getFieldValidationMessage($EVC, $settings, $attr_name);
								
								return false;
							}
						}
					}
				}
		}
		
		return true;
	}
	
	public function reloadSavedTableExtra($settings, $pks, &$old_data, &$new_data, &$post_data) {
		if ($this->enabled && $this->files_extra_attributes_name && $pks) {
			$reload = false;
			
			foreach ($this->files_extra_attributes_name as $attr_name) 
				if ($settings["show_$attr_name"]) {
					$reload = true;
					break;
				}
			
			if ($reload) {	
				$db_data = $this->getTableExtra($pks, true);
				
				if ($db_data)
					foreach ($this->files_extra_attributes_name as $attr_name) {
						if ($old_data) {
							$old_data[$attr_name] = $db_data[$attr_name];
							$old_data[$attr_name . "_url"] = $db_data[$attr_name . "_url"];
							$old_data[$attr_name . "_path"] = $db_data[$attr_name . "_path"];
							$old_data[$attr_name . "_name"] = $db_data[$attr_name . "_name"];
						}
						
						if ($new_data) {
							$new_data[$attr_name] = $db_data[$attr_name];
							$new_data[$attr_name . "_url"] = $db_data[$attr_name . "_url"];
							$new_data[$attr_name . "_path"] = $db_data[$attr_name . "_path"];
							$new_data[$attr_name . "_name"] = $db_data[$attr_name . "_name"];
						}
						
						if ($post_data && array_key_exists($attr_name, $post_data))
							$post_data[$attr_name] = $db_data[$attr_name];
					}
			}
		}
	}
	
	public function prepareFileFieldsSettings($EVC, &$settings) {
		if ($this->enabled && $this->files_extra_attributes_name)
			foreach ($this->files_extra_attributes_name as $attr_name) 
				if ($settings["show_$attr_name"]) {
					$input_settings = $settings["fields"][$attr_name]["field"]["input"];
					
					if ($input_settings["type"] == "file") {
						$attr_settings = $this->extra_attributes[$attr_name];
						
						//set input hidden field
						$allow_null = $input_settings["allow_null"];
						$extra_attributes = $input_settings["extra_attributes"];
						$class = $input_settings["class"];
						$title = $input_settings["title"];
						$validation_label = $input_settings["validation_label"];
						$validation_message = $input_settings["validation_message"];
						
						$input_settings["type"] = "text"; //Do not add hidden here, otherwise the parent div will be hidden too
						$input_settings["extra_attributes"] = array( array("name" => "style", "value" => "display:none") );
						$input_settings["validation_type"] = $attr_settings["validation_type"];
						$input_settings["class"] = $input_settings["title"] = $input_settings["allow_null"] = $input_settings["validation_label"] = $input_settings["validation_message"] = $input_settings["validation_regex"] = $input_settings["min_length"] = $input_settings["max_length"] = $input_settings["min_value"] = $input_settings["max_value"] = $input_settings["min_words"] = $input_settings["max_words"] = null;
						
						//set input file field
						if (!$validation_label)
							$validation_label = translateProjectText($EVC, CommonModuleUI::getFieldLabel($settings, $attr_name));
						
						$name = $input_settings["name"] ? $input_settings["name"] : $attr_name;
						$html = '<input type="file" name="' . $name . '" data-validation-label="' . $validation_label . '"';
						
						if ($class)
							$html .= ' class="' . $class . '"';
						
						if ($title)
							$html .= ' title="' . $title . '"';
						
						if ($validation_message)
							$html .= ' data-validation-message="' . $validation_message . '"';
						
						if ($extra_attributes) {
							if(is_array($extra_attributes))
								foreach ($extra_attributes as $f)
									if (is_array($f) && $f["name"])
										$html .= ' ' . $f["name"] . '="' . $f["value"] . '"';
							else
								$html .= ' ' . $extra_attributes;
						}
						
						if ($allow_null != 1)
							$html .= ' data-allow-null-bkp="0"';
						
						$html .= ' />';
						
						//prepare field with new settings
						$input_settings["previous_html"] .= $html;
						$settings["fields"][$attr_name]["field"]["input"] = $input_settings;
					}
				}
		
	}
	
	//This method is used in the module/user/list_and_edit_users_with_user_types/CMSModuleHandlerImpl.php
	public function convertMultipleFilesToFormattedArray($files) {
		$formatted = array();
		
		if ($files)
			foreach ($files as $prop_name => $items)
				foreach ($items as $idx => $item)
					foreach ($item as $attr_name => $value)
						$formatted[$idx][$attr_name][$prop_name] = $value;
		
		return $formatted;
	}
	
	/* PRIVATE METHODS */
	
	private function init($settings) {
		$this->enabled = false;
		
		if ($this->extra_attributes) 
			foreach ($this->extra_attributes as $attr_name => $attr_settings) 
				if ($settings["show_$attr_name"]) {
					$this->enabled = true;
					break;
				}
		
		$this->initSelectedBroker();
	}
	
	//first returns the business_logic broker, then ibatis and then hibernate
	private function initSelectedBroker() {
		if ($this->selected_broker)
			return $this->selected_broker;
		
		foreach ($this->brokers as $broker)
			if (is_a($broker, "IBusinessLogicBrokerClient")) {
				$this->selected_broker = $broker;
				break;
			}
		
		
		if (!$this->selected_broker)
			foreach ($this->brokers as $broker)
				if (is_a($broker, "IIbatisDataAccessBrokerClient"))  {
					$this->selected_broker = $broker;
					break;
				}
		
		if (!$this->selected_broker)
			foreach ($this->brokers as $broker)
				if (is_a($broker, "IHibernateDataAccessBrokerClient")) {
					$this->selected_broker = $broker;
					break;
				}
		
		if (!$this->selected_broker)
			foreach ($this->brokers as $broker)
				if (is_a($broker, "IDBBrokerClient")) {
					$this->selected_broker = $broker;
					break;
				}
		
		if (!$this->selected_broker)
			throw new Exception("Invalid brokers");
	}
	
	private function loadExtraAttributes() {
		$this->extra_attributes = array();
		$this->extra_pks = array();
		$this->files_extra_attributes_name = array();
		
		$fp = $this->getTableExtraAttributesFile();
		
		//get local file
		if (file_exists($fp)) {
			include $fp;
			
			$this->extra_attributes = $table_extra_attributes_settings; //only contains the names of the attributes
			
			if ($this->extra_attributes) {
				$this->extra_pks = array_filter($this->extra_attributes, function($v) {
					return $v && $v["db_attribute"] && $v["db_attribute"]["primary_key"];
				});
				
				$attachment_util_fp = $this->EVC->getModulePath("attachment/AttachmentUtil", $this->EVC->getCommonProjectName());
				
				if (file_exists($attachment_util_fp)) {
					include_once $attachment_util_fp;
					
					foreach ($this->extra_attributes as $attr_name => $attr_settings)
						if ($attr_settings["file_type"])
							$this->files_extra_attributes_name[] = $attr_name;
				}
			}
		}
	}
	
	private function getGroupModuleId() {
		$module_id = $this->CMSModuleHandler->getModuleId();
		$pos = strpos($module_id, "/");
		$group_module_id = $pos > 0 ? substr($module_id, 0, $pos) : $module_id;
		
		return $group_module_id;
	}
	
	private function getTableExtraAttributesFile() {
		return $this->EVC->getModulePath($this->group_module_id . "/" . $this->extra_attributes_query_name . "_attributes_settings", $this->EVC->getCommonProjectName()); //This file created in the module/common/system_settings/admin/CommonModuleAdminTableExtraAttributesUtil.php
	}
	
	private function getObjectName($name) {
		return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($name))));
	}
}
?>
