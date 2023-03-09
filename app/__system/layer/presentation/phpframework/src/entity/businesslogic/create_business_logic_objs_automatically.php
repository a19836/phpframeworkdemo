<?php
/*
 * Copyright (c) 2007 PHPMyFrameWork - Joao Pinto
 * AUTHOR: Joao Paulo Lopes Pinto -- http://jplpinto.com
 * 
 * The use of this code must be allowed first by the creator Joao Pinto, since this is a private and proprietary code.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS 
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY 
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER 
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT 
 * OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE. IN NO EVENT SHALL 
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN 
 * AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("WorkFlowDataAccessHandler"); include_once $EVC->getUtilPath("WorkFlowBusinessLogicHandler"); include_once $EVC->getUtilPath("FlushCacheHandler"); include_once $EVC->getUtilPath("LayoutTypeProjectHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $filter_by_layout = $_GET["filter_by_layout"]; $path = str_replace("../", "", $path);$filter_by_layout = str_replace("../", "", $filter_by_layout); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "BusinessLogicLayer")) { $layer_path = $obj->getLayerPathSetting(); $folder_path = $layer_path . $path; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($folder_path, "layer", "access"); $LayoutTypeProjectHandler = new LayoutTypeProjectHandler($UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $bean_file_name, $bean_name); $brokers = $obj->getBrokers(); $layer_brokers_settings = WorkFlowBeansFileHandler::getLayerBrokersSettings($user_global_variables_file_path, $user_beans_folder_path, $brokers); $data_access_brokers = $layer_brokers_settings["data_access_brokers"] ? $layer_brokers_settings["data_access_brokers"] : array(); $db_brokers = $layer_brokers_settings["db_brokers"] ? $layer_brokers_settings["db_brokers"] : array(); $related_brokers = array_merge($db_brokers, $data_access_brokers); if ($_POST["step_1"]) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); $files = $_POST["files"]; $aliases = $_POST["aliases"]; $db_driver = $_POST["db_driver"]; $include_db_driver = $_POST["include_db_driver"]; $db_type = $_POST["type"]; $overwrite = $_POST["overwrite"]; $namespace = trim($_POST["namespace"]); $json = $_POST["json"]; $reserved_sql_keywords = array(); $common_namespace = null; $common_service_file_path = $obj->settings["business_logic_modules_service_common_file_path"]; if ($common_service_file_path && file_exists($common_service_file_path)) { include_once $common_service_file_path; $common_namespace = PHPCodePrintingHandler::getNamespacesFromFile($common_service_file_path); $common_namespace = $common_namespace[0]; $common_namespace = substr($common_namespace, 0, 1) == "\\" ? substr($common_namespace, 1) : $common_namespace; $common_namespace = substr($common_namespace, -1) == "\\" ? substr($common_namespace, 0, -1) : $common_namespace; eval("\$reserved_sql_keywords = \\$common_namespace\\CommonService::getReservedSQLKeywords();"); } $statuses = array(); if (is_array($files)) { $UserAuthenticationHandler->incrementUsedActionsTotal(); $t = count($related_brokers); foreach ($files as $file => $items) { if (isset($items["all"])) $items = array("all" => $items["all"]); if (is_array($items)) { foreach ($items as $node_id => $broker_name) { $bfn = $bn = null; for ($i = 0; $i < $t; $i++) { $b = $related_brokers[$i]; if ($b[0] == $broker_name) { $bfn = $b[1]; $bn = $b[2]; } } if ($broker_name && $bfn && $bn) { $WBFH = new WorkFlowBeansFileHandler($user_beans_folder_path . $bfn, $user_global_variables_file_path); $layer_obj = $WBFH->getBeanObject($bn); $tasks_file_path = $db_type == "diagram" ? WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $db_driver) : null; $alias = trim($aliases[$file][$node_id]); $broker_code_prefix = '$this->getBusinessLogicLayer()->getBroker("' . $broker_name . '")'; if (!$layer_obj) $statuses[] = array($file, null, false); else if (is_a($layer_obj, "DataAccessLayer")) { $data_access_layer_path = $layer_obj->getLayerPathSetting(); $data_access_file_path = $data_access_layer_path . $file; if (file_exists($data_access_file_path)) { $layer_obj->getSQLClient()->loadXML($data_access_file_path); $xml_data = $layer_obj->getSQLClient()->getNodesData(); $module_id = getModuleId($file); $dst_file_path = $folder_path . ($path ? basename($file) : $file); $LayoutTypeProjectHandler->createLayoutTypePermissionsForFilePathAndLayoutTypeName($filter_by_layout, dirname($dst_file_path)); $db_broker = WorkFlowBeansFileHandler::getLayerLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, $layer_obj, $db_driver); if ($layer_obj->getType() == "hibernate") { $xml_data = $node_id != "all" ? array($node_id => $xml_data["class"][$node_id]) : $xml_data["class"]; if (is_array($xml_data)) { foreach ($xml_data as $obj_id => $obj_data) { $d = createHibernateBusinessLogicFile($layer_obj, $db_broker, $db_driver, $include_db_driver, $tasks_file_path, $dst_file_path, $obj_id, $module_id, $obj_data, $broker_code_prefix, $reserved_sql_keywords, $overwrite, $common_namespace, $namespace, $alias); $d[0] = substr($d[0], strlen($layer_path)); $statuses[] = $d; } } } else { $d = createIbatisBusinessLogicFile($layer_obj, $db_broker, $db_driver, $include_db_driver, $tasks_file_path, $dst_file_path, $module_id, $xml_data, $broker_code_prefix, $reserved_sql_keywords, $overwrite, $common_namespace, $namespace, $alias); $d[0] = substr($d[0], strlen($layer_path)); $statuses[] = $d; } } } else if (is_a($layer_obj, "DBLayer")) { $WorkFlowDataAccessHandler = new WorkFlowDataAccessHandler(); if ($tasks_file_path) { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $db_driver); $WorkFlowDataAccessHandler->setTasksFilePath($tasks_file_path); } else { $tables = $layer_obj->getFunction("listTables", null, array("db_driver" => $db_driver)); $tables_data = array(); $t = count($tables); for ($i = 0; $i < $t; $i++) { $table = $tables[$i]; if (!empty($table)) { $attrs = $layer_obj->getFunction("listTableFields", $table["name"], array("db_driver" => $db_driver)); $fks = $layer_obj->getFunction("listForeignKeys", $table["name"], array("db_driver" => $db_driver)); $tables_data[ $table["name"] ] = array($attrs, $fks, $table); } } $tasks = WorkFlowDBHandler::getUpdateTaskDBDiagramFromTablesData($tables_data); $WorkFlowDataAccessHandler->setTasks($tasks); } $nodes = $WorkFlowDataAccessHandler->getQueryObjectsArrayFromDBTaskFlow($file); $xml_data = SQLMapClient::getDataAccessNodesConfigured($nodes["queries"][0]["childs"]); $d = createTableBusinessLogicFile($layer_obj, $db_broker, $db_driver, $include_db_driver, $tasks_file_path, $folder_path, $file, $xml_data, $broker_code_prefix, $reserved_sql_keywords, $overwrite, $common_namespace, $namespace, $alias); $d[0] = substr($d[0], strlen($layer_path)); $statuses[] = $d; $LayoutTypeProjectHandler->createLayoutTypePermissionsForFilePathAndLayoutTypeName($filter_by_layout, $folder_path); } } } } } FlushCacheHandler::flushCache($EVC, $webroot_cache_folder_path, $webroot_cache_folder_url, $workflow_paths_id, $user_global_variables_file_path, $user_beans_folder_path, $css_and_js_optimizer_webroot_cache_folder_path, $deployments_temp_folder_path, $programs_temp_folder_path); } if ($json) { echo json_encode($statuses); die(); } } else { $brokers_db_drivers_name = array(); foreach ($brokers as $broker_name => $broker) if (is_a($broker, "IDataAccessBrokerClient") || is_a($broker, "IDBBrokerClient")) { $brokers_db_drivers_name[$broker_name] = WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, array($broker_name => $broker), true); $LayoutTypeProjectHandler->filterLayerBrokersDBDriversPropsFromLayoutName($brokers_db_drivers_name[$broker_name], $filter_by_layout); } $default_broker_name = $related_brokers[0][0]; $db_drivers = $brokers_db_drivers_name[$default_broker_name]; $default_db_driver = key($db_drivers); $WBFH = new WorkFlowBeansFileHandler($user_beans_folder_path . $related_brokers[0][1], $user_global_variables_file_path); $layer_obj = $WBFH->getBeanObject($related_brokers[0][2]); $is_db_layer = is_a($layer_obj, "DBLayer"); if ($is_db_layer) { $db_driver_tables = $layer_obj->getFunction("listTables", null, array("db_driver" => $default_db_driver)); $default_db_driver_table = $db_driver_tables[0]["name"]; } $db_brokers_bean_file_by_bean_name = array(); foreach ($db_brokers as $b) $db_brokers_bean_file_by_bean_name[ $b[2] ] = $b[1]; } } $PHPVariablesFileHandler->endUserGlobalVariables(); function createHibernateBusinessLogicFile($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $pf3dc0762, $v9ff79a4a24, $pcd8c70bc, $pf232dd5a, $v96235e0cbf, $pa3585c80, $pc4aa460d, $v3a2d613bf9, $v1efaf06c58 = false, $v7c3c74d27f = false) { $v13eedf3e61 = dirname($pf3dc0762); if (file_exists($v13eedf3e61) || mkdir($v13eedf3e61, 0755, true)) { if ($v7c3c74d27f) { $v1335217393 = $v7c3c74d27f; $pf3dc0762 = "$v13eedf3e61/{$v7c3c74d27f}.php"; } else { $v1335217393 = str_replace(" ", "", ucwords(str_replace(array("_", "-"), " ", $v9ff79a4a24))); $pf3dc0762 = "$v13eedf3e61/{$v1335217393}Service.php"; } while (!$pc4aa460d && file_exists($pf3dc0762)) { $v93ff269092 = rand(0, 100); $v1335217393 .= $v93ff269092; $pf3dc0762 = "$v13eedf3e61/{$v1335217393}" . ($v7c3c74d27f ? "" : "Service") . ".php"; } if ($pf232dd5a["childs"]["parameter_map"][0]) { $v3df2d026a1 = $pf232dd5a["childs"]["parameter_map"][0]["attrib"]["id"]; if (!$v3df2d026a1) { $v3df2d026a1 = "MainParameterMap"; $pf232dd5a["childs"]["parameter_map"][0]["attrib"]["id"] = $v3df2d026a1; } $pf232dd5a["childs"]["relationships"]["parameter_map"][$v3df2d026a1] = $pf232dd5a["childs"]["parameter_map"][0]; } $v987a981e39 = $pf232dd5a["childs"]["relationships"]; $v1612a5ddce = $pf232dd5a["childs"]["queries"]; $pcaaa70b9 = WorkFlowDataAccessHandler::getHbnObjParameters($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $pf232dd5a); $v000d848b94 = $pcaaa70b9; if (ObjTypeHandler::$attribute_names_as_created_date) foreach (ObjTypeHandler::$attribute_names_as_created_date as $v5e45ec9bb9) unset($v000d848b94[$v5e45ec9bb9]); $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v887e85c917 = checkTypeOfExistentPrimaryKeys($pcaaa70b9, $v806c8712d7); $v441298e472 = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($pcaaa70b9, false); $pf9488a65 = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($pcaaa70b9, false, true); $v067674f4e4 = '<?php'; if ($v1efaf06c58) $v067674f4e4 .= '
namespace ' . $v1efaf06c58 . ';
'; $v067674f4e4 .= '
include_once $vars["business_logic_modules_service_common_file_path"];
	
class ' . $v1335217393 . ($v7c3c74d27f ? "" : "Service") . ' extends ' . ($v3a2d613bf9 && $v1efaf06c58 != $v3a2d613bf9 ? "\\$v3a2d613bf9\\" : '') . 'CommonService {
	
	private function getDataAccessBroker() {
		$broker = ' . $v96235e0cbf . ';
		
		return $broker;
	}

	public function callHbnObject($options = null) {
		$obj = $this->getDataAccessBroker()->callObject("' . $pcd8c70bc . '", "' . $v9ff79a4a24 . '", $options);
		return $obj;
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pcaaa70b9, $v887e85c917, true, true, true, true, false, false) . '
	public function insert($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		' . $pe7720ba3 . $v441298e472 . '
		$obj = $this->callHbnObject($options);
		$status = null;
		
		if ($obj)
			$status = $obj->insert($data, $ids, $options);
		
		' . ($v806c8712d7 ? '$id = $status ? $ids["' . $v806c8712d7 . '"] : false; //hibernate->insert method already returns the getInsertedId in: $ids[xxx]. This code supposes that there is only 1 auto increment pk.' : '$id = $status;') . '
		
		return $id;
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v000d848b94, true, true, true, true, true, true, false) . '
	public function update($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
	
		' . $pe7720ba3 . $pf9488a65 . '
		$obj = $this->callHbnObject($options);
		$status = null;
		
		if ($obj)
			$status = $obj->update($data, $options);
	
		return $status;
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v000d848b94, false, true, true, true, true, true, false) . '	
	public function updateAll($data) {
		if ($data && ($data["conditions"] || $data["all"])) {
			$options = $data["options"];
			$this->mergeOptionsWithBusinessLogicLayer($options);
			unset($data["options"]);
			
			' . $pe7720ba3 . $pf9488a65 . '
			$obj = $this->callHbnObject($options);
			$status = null;
			
			if ($obj)
				$status = $obj->updateByConditions($data, $options);
		
			return $status;
		}
	}
	'; $v6238264823 = array(); if (is_array($pcaaa70b9)) { foreach ($pcaaa70b9 as $v5e813b295b => $pc5faab2f) { if ($pc5faab2f["primary_key"]) { $v485abd759c = $pc5faab2f["name"]; $pc5faab2f["name"] = $v485abd759c ? "old_" . $v485abd759c : null; $v6238264823["old_" . $v5e813b295b] = $pc5faab2f; $pc5faab2f["name"] = $v485abd759c ? "new_" . $v485abd759c : null; $v6238264823["new_" . $v5e813b295b] = $pc5faab2f; } } } $v067674f4e4 .= '
' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v6238264823, true, false, true, false, true, true, false) . '
	public function updatePrimaryKeys($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		' . $pe7720ba3 . '
		$obj = $this->callHbnObject($options);
		$status = null;
		
		if ($obj)
			$status = $obj->updatePrimaryKeys($data, $options);
		
		return $status;
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pcaaa70b9, true, false, true, false, true, false, false) . '
	public function delete($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		' . $pe7720ba3 . '
		$obj = $this->callHbnObject($options);
		$status = null;
		
		if ($obj)
			$status = $obj->delete($data, $options);
	
		return $status;
	}
	
	public function deleteAll($data) {
		if ($data && ($data["conditions"] || $data["all"])) {
			$options = $data["options"];
			$this->mergeOptionsWithBusinessLogicLayer($options);
			unset($data["options"]);
			
			' . $pe7720ba3 . '
			$obj = $this->callHbnObject($options);
			$status = null;
			
			if ($obj)
				$status = $obj->deleteByConditions($data, $options);
			
			return $status;
		}
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pcaaa70b9, true, false, true, false, true, false, false) . '
	public function get($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		' . $pe7720ba3 . '
		$obj = $this->callHbnObject($options);
		$res = null;
		
		if ($obj)
			$res = $obj->findById($data, $options);
		
		return $res;
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pcaaa70b9, true, true, false, false, true, false, true) . '
	public function getAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		' . $pe7720ba3 . '
		$obj = $this->callHbnObject($options);
		$res = null;
		
		if ($obj)
			$res = $obj->find($data, $options);
		
		return $res;
	}

' . WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pcaaa70b9, true, true, false, false, true, false, true) . '
	public function countAll($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		unset($data["options"]);
		
		' . $pe7720ba3 . '
		$obj = $this->callHbnObject($options);
		$res = null;
		
		if ($obj)
			$res = $obj->count($data, $options);
		
		return $res;
	}
	' . prepareDataAccessNodes($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $v987a981e39, $v9ff79a4a24, $pcd8c70bc, $v96235e0cbf, $pa3585c80, true, $pf232dd5a, $pcaaa70b9) . '
	' . prepareDataAccessNodes($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $v1612a5ddce, $v9ff79a4a24, $pcd8c70bc, $v96235e0cbf, $pa3585c80, true) . '
}
?>'; return array($pf3dc0762, $v9ff79a4a24, file_put_contents($pf3dc0762, $v067674f4e4) > 0); } return false; } function createIbatisBusinessLogicFile($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $pf3dc0762, $pcd8c70bc, $v1612a5ddce, $v96235e0cbf, $pa3585c80, $pc4aa460d, $v3a2d613bf9, $v1efaf06c58 = false, $v7c3c74d27f = false) { $v13eedf3e61 = dirname($pf3dc0762); if (file_exists($v13eedf3e61) || mkdir($v13eedf3e61, 0755, true)) { $v5442ff2fbd = pathinfo($pf3dc0762); $v9ff79a4a24 = $v5442ff2fbd["filename"]; if ($v7c3c74d27f) { $v1335217393 = $v7c3c74d27f; $pf3dc0762 = "$v13eedf3e61/{$v7c3c74d27f}.php"; } else { $v1335217393 = WorkFlowDataAccessHandler::getClassName($v9ff79a4a24); $pf3dc0762 = "$v13eedf3e61/{$v1335217393}Service.php"; } while (!$pc4aa460d && file_exists($pf3dc0762)) { $v93ff269092 = rand(0, 100); $v1335217393 .= $v93ff269092; $pf3dc0762 = "$v13eedf3e61/{$v1335217393}" . ($v7c3c74d27f ? "" : "Service") . ".php"; } $v067674f4e4 = '<?php'; if ($v1efaf06c58) $v067674f4e4 .= '
namespace ' . $v1efaf06c58 . ';
'; $v067674f4e4 .= '
include_once $vars["business_logic_modules_service_common_file_path"];

class ' . $v1335217393 . ($v7c3c74d27f ? "" : "Service") . ' extends ' . ($v3a2d613bf9 && $v1efaf06c58 != $v3a2d613bf9 ? "\\$v3a2d613bf9\\" : '') . 'CommonService {
	
	private function getDataAccessBroker() {
		$broker = ' . $v96235e0cbf . ';
		
		return $broker;
	}
	' . prepareDataAccessNodes($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $v1612a5ddce, $v9ff79a4a24, $pcd8c70bc, $v96235e0cbf, $pa3585c80) . '
}
?>'; return array($pf3dc0762, $v9ff79a4a24, file_put_contents($pf3dc0762, $v067674f4e4) > 0); } return false; } function createTableBusinessLogicFile($pfa9a25ae, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $pdd397f0a, $v8c5df8072b, $v1612a5ddce, $v96235e0cbf, $pa3585c80, $pc4aa460d, $v3a2d613bf9, $v1efaf06c58 = false, $v7c3c74d27f = false) { if (file_exists($pdd397f0a) || mkdir($pdd397f0a, 0755, true)) { if ($v7c3c74d27f) { $v1335217393 = $v7c3c74d27f; $pf3dc0762 = "$pdd397f0a/{$v7c3c74d27f}.php"; } else { $v1335217393 = WorkFlowDataAccessHandler::getClassName($v8c5df8072b); $pf3dc0762 = "$pdd397f0a/{$v1335217393}Service.php"; } while (!$pc4aa460d && file_exists($pf3dc0762)) { $v93ff269092 = rand(0, 100); $v1335217393 .= $v93ff269092; $pf3dc0762 = "$pdd397f0a/{$v1335217393}" . ($v7c3c74d27f ? "" : "Service") . ".php"; } $ped0a6251 = $pfa9a25ae->getFunction("listTableFields", $v8c5df8072b, array("db_driver" => $v872f5b4dbb)); $pfdbbc383 = $pe2f18119 = array(); $pa04a2fa5 = array_keys( getTableAutoIncrementedPrimaryKeys($ped0a6251) ); if ($ped0a6251) foreach ($ped0a6251 as $v1b0cfa478b) { $pfdbbc383[] = $v1b0cfa478b["name"]; if ($v1b0cfa478b["primary_key"]) $pe2f18119[] = $v1b0cfa478b["name"]; } $v887e85c917 = count($pe2f18119) > 0 ? (count($pa04a2fa5) > 0 ? 2 : 1) : false; $v067674f4e4 = '<?php'; if ($v1efaf06c58) $v067674f4e4 .= '
namespace ' . $v1efaf06c58 . ';
'; $v067674f4e4 .= '
include_once $vars["business_logic_modules_service_common_file_path"];

class ' . $v1335217393 . ($v7c3c74d27f ? "" : "Service") . ' extends ' . ($v3a2d613bf9 && $v1efaf06c58 != $v3a2d613bf9 ? "\\$v3a2d613bf9\\" : '') . 'CommonService {
	
	private function getDBBroker() {
		$broker = ' . $v96235e0cbf . ';
		
		return $broker;
	}
	
	private function getTableName() {
		return "' . $v8c5df8072b . '";
	}
	
	private function getTableAttributes() {
		return array("' . implode('", "', $pfdbbc383) . '");
	}
	
	private function getTablePrimaryKeys() {
		return array("' . implode('", "', $pe2f18119) . '");
	}
	
	private function getTableAutoIncrementPrimaryKeys() {
		return array("' . implode('", "', $pa04a2fa5) . '");
	}
	
	private function filterDataByTableAttributes($data, $do_not_include_pks = true) {
		if ($data) {
			$attributes = $this->getTableAttributes();
			$pks = $do_not_include_pks ? self::getTablePrimaryKeys() : array();
			
			foreach ($data as $k => $v)
				if (!in_array($k, $attributes) || in_array($k, $pks))
					unset($data[$k]);
		}
		
		return $data;
	}
	
	private function filterDataByTablePrimaryKeys($data) {
		if ($data) {
			$pks = $this->getTablePrimaryKeys();
			
			foreach ($data as $k => $v)
				if (!in_array($k, $pks))
					unset($data[$k]);
		}
		
		return $data;
	}
	
	private function filterDataExcludingTableAutoIncrementPrimaryKeys($data) {
		if ($data) {
			$pks = $this->getTableAutoIncrementPrimaryKeys();
			
			foreach ($data as $k => $v)
				if (in_array($k, $pks))
					unset($data[$k]);
		}
		
		return $data;
	}
	' . prepareTableNodes($pfa9a25ae, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $v8c5df8072b, $v1612a5ddce, $v96235e0cbf, $pa3585c80, $v887e85c917) . '
}
?>'; return array($pf3dc0762, $v8c5df8072b, file_put_contents($pf3dc0762, $v067674f4e4) > 0); } return false; } function prepareTableNodes($pfa9a25ae, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $v8c5df8072b, $v987a981e39, $v96235e0cbf, $pa3585c80, $v887e85c917) { $v067674f4e4 = ""; $v1335217393 = WorkFlowDataAccessHandler::getClassName($v8c5df8072b); $v0674ea4a10 = array(); $pa8d2aaca = "\$this->getDBBroker()"; if (is_array($v987a981e39)) { foreach ($v987a981e39 as $pa1c701b0 => $v16eb00c1d7) { foreach ($v16eb00c1d7 as $v6aad4fb598 => $v50819961ef) { $v5e813b295b = getFunctionName($v6aad4fb598); $v1dc11964f1 = $v68745269c7 = ""; switch($pa1c701b0) { case "insert": if (substr($v6aad4fb598, - strlen("_with_ai_pk")) == "_with_ai_pk") { if ($v16eb00c1d7[ substr($v6aad4fb598, 0, - strlen("_with_ai_pk")) ]) continue 2; } else if ($v16eb00c1d7[$v6aad4fb598 . "_with_ai_pk"]) { $v5e813b295b = getFunctionName($v6aad4fb598); $v6aad4fb598 = $v6aad4fb598 . "_with_ai_pk"; $v50819961ef = $v16eb00c1d7[$v6aad4fb598]; } $v5e813b295b = stripos($v5e813b295b, "insert") !== false || stripos($v5e813b295b, "add") !== false ? $v5e813b295b : "insert" . ucfirst($v5e813b295b); $v5e813b295b = $v5e813b295b == "insert$v1335217393" ? "insert" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $pfa9a25ae, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $v441298e472 = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($v9367d5be85, false); $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, $v887e85c917, true, true, true, true, false, false); if ($v441298e472) $v68745269c7 .= $v441298e472 . "
		"; $v68745269c7 .= "\$attributes = \$this->filterDataByTableAttributes(\$data, false);
		\$ai_pks = \$this->getTableAutoIncrementPrimaryKeys();
		\$set_ai_pk = null;
		
		//This code supposes that there is only 1 auto increment pk
		foreach (\$ai_pks as \$pk_name) 
			if (\$data[\$pk_name]) {
				\$set_ai_pk = \$pk_name;
				break;
			}
		
		if (\$set_ai_pk) {
			\$options[\"hard_coded_ai_pk\"] = true;
			\$result = {$pa8d2aaca}->insertObject(\$this->getTableName(), \$attributes, \$options);
			\$result = \$result ? \$data[\$set_ai_pk] : false;
		}
		else {
			\$attributes = \$this->filterDataExcludingTableAutoIncrementPrimaryKeys(\$attributes);
			\$result = {$pa8d2aaca}->insertObject(\$this->getTableName(), \$attributes, \$options);
			\$result = \$result ? {$pa8d2aaca}->getInsertedId(\$options) : false;
		}"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "update": $v5e813b295b = stripos($v5e813b295b, "update") !== false || stripos($v5e813b295b, "edit") !== false ? $v5e813b295b : "update" . ucfirst($v5e813b295b); $v5e813b295b = $v5e813b295b == "update$v1335217393" ? "update" : $v5e813b295b; $v5e813b295b = $v5e813b295b == "update{$v1335217393}PrimaryKeys" || $v5e813b295b == "update{$v1335217393}Pks" ? "updatePrimaryKeys" : $v5e813b295b; $v5e813b295b = $v5e813b295b == "updateAll{$v1335217393}Items" ? "updateAll" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $pfa9a25ae, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $v441298e472 = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($v9367d5be85, false, true); $pf81e7d81 = $v5e813b295b != "updateAll" ? ( $v5e813b295b == "updatePrimaryKeys" ? WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, false, true, false, true, true, false) : WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, true, true, true, true, true, false) ) : WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, false, true, true, true, true, true, false); if ($v441298e472) $v68745269c7 .= $v441298e472 . "
		"; if ($v5e813b295b == "updateAll") $v68745269c7 .= "\$attributes = \$this->filterDataByTableAttributes(\$data);
		\$conditions = \$data[\"conditions\"];
		\$options[\"all\"] = \$data[\"all\"];
		\$result = {$pa8d2aaca}->updateObject(\$this->getTableName(), \$attributes, \$conditions, \$options);"; else if ($v5e813b295b == "updatePrimaryKeys") $v68745269c7 .= "\$attributes = \$conditions = array();
		\$pks = self::getTablePrimaryKeys();
		
		foreach (\$pks as \$pk) {
			\$attributes[\$pk] = \$data[\"new_\" . \$pk];
			\$conditions[\$pk] = \$data[\"old_\" . \$pk];
		}
		
		\$result = {$pa8d2aaca}->updateObject(\$this->getTableName(), \$attributes, \$conditions, \$options);"; else $v68745269c7 .= "\$attributes = \$this->filterDataByTableAttributes(\$data);
		\$conditions = \$this->filterDataByTablePrimaryKeys(\$data);
		\$result = {$pa8d2aaca}->updateObject(\$this->getTableName(), \$attributes, \$conditions, \$options);"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "delete": $v5e813b295b = stripos($v5e813b295b, "delete") !== false || stripos($v5e813b295b, "remove") !== false ? $v5e813b295b : "delete" . ucfirst($v5e813b295b); $v5e813b295b = $v5e813b295b == "delete$v1335217393" ? "delete" : $v5e813b295b; $v5e813b295b = $v5e813b295b == "deleteAll{$v1335217393}Items" ? "deleteAll" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $pfa9a25ae, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $pf81e7d81 = $v5e813b295b != "deleteAll" ? WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, false, true, false, true, false, false) : null; if ($v5e813b295b == "deleteAll") $v68745269c7 .= "\$conditions = \$data[\"conditions\"];
		\$options[\"all\"] = \$data[\"all\"];
		\$result = {$pa8d2aaca}->deleteObject(\$this->getTableName(), \$conditions, \$options);"; else $v68745269c7 .= "\$conditions = \$this->filterDataByTablePrimaryKeys(\$data);
		\$result = {$pa8d2aaca}->deleteObject(\$this->getTableName(), \$conditions, \$options);"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "select": $v04c7684275 = isCountFunctionName($v5e813b295b); $pa05557b6 = !$v04c7684275 && (stripos($v5e813b295b, "get") !== false || stripos($v5e813b295b, "select") !== false); $v5e813b295b = $v04c7684275 || $pa05557b6 ? $v5e813b295b : "get" . ucfirst($v5e813b295b); $v5e813b295b = $v5e813b295b == "get$v1335217393" ? "get" : $v5e813b295b; $v5e813b295b = $v5e813b295b == "count$v1335217393" ? "count" : $v5e813b295b; $v5e813b295b = $v5e813b295b == "get{$v1335217393}Items" ? "getAll" : $v5e813b295b; $v5e813b295b = $v5e813b295b == "count{$v1335217393}Items" ? "countAll" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $pfa9a25ae, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); if ($v5e813b295b != "getAll" && $v5e813b295b != "countAll") $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, true, true, false, true, false, false); else { $pa02649b9 = $v9367d5be85; prepareSelectAllSQLParameters($v50819961ef, $v987a981e39, $pfa9a25ae, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $pa02649b9, $v8c5df8072b); $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pa02649b9, true, true, false, false, true, false, true); } $v68745269c7 .= "self::prepareInputData(\$data);
		
		if (\$data[\"searching_condition\"])
			\$options[\"sql_conditions\"] = \"1=1\" . \$data[\"searching_condition\"];
		
		"; if ($v5e813b295b == "get") $v68745269c7 .= "\$conditions = \$this->filterDataByTablePrimaryKeys(\$data);
		\$result = {$pa8d2aaca}->findObjects(\$this->getTableName(), null, \$conditions, \$options);
		\$result = \$result ? \$result[0] : null;"; else if ($v5e813b295b == "countAll") $v68745269c7 .= "\$result = {$pa8d2aaca}->countObjects(\$this->getTableName(), null, \$options);"; else if ($v5e813b295b == "getAll") $v68745269c7 .= "\$result = {$pa8d2aaca}->findObjects(\$this->getTableName(), null, null, \$options);"; else { $v3c76382d93 = $v50819961ef["value"]; $pa8db77e3 = $pfa9a25ae->getFunction("convertDefaultSQLToObject", $v3c76382d93, array("db_driver" => $v872f5b4dbb)); $v9994512d98 = array(); if ($pa8db77e3["keys"]) foreach ($pa8db77e3["keys"] as $pbfa01ed1) $v9994512d98[] = $pbfa01ed1["ptable"] == $v8c5df8072b ? $pbfa01ed1 : array( "ptable" => $pbfa01ed1["ftable"], "pcolumn" => $pbfa01ed1["fcolumn"], "ftable" => $pbfa01ed1["ptable"], "fcolumn" => $pbfa01ed1["pcolumn"], "value" => $pbfa01ed1["value"], "join" => $pbfa01ed1["join"], "operator" => $pbfa01ed1["operator"], ); $pd6263008 = str_replace("'$v8c5df8072b'", '$this->getTableName()', str_replace("\n", "\n\t\t", var_export($v9994512d98, 1))); $v68745269c7 .= "\$keys = " . $pd6263008 . ";
		
		\$rel_elm = array(\"keys\" => \$keys);
		\$parent_conditions = \$this->filterDataByTablePrimaryKeys(\$data);
		"; if ($v04c7684275) $v68745269c7 .= "\$result = {$pa8d2aaca}->countRelationshipObjects(\$this->getTableName(), \$rel_elm, \$parent_conditions, \$options);"; else $v68745269c7 .= "\$result = {$pa8d2aaca}->findRelationshipObjects(\$this->getTableName(), \$rel_elm, \$parent_conditions, \$options);"; } $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; } } } } return $v067674f4e4; } function prepareDataAccessNodes($v51d1745dbd, $pab752e34, $v872f5b4dbb, $v8b13fa2358, $v5e053dece2, $v987a981e39, $v9ff79a4a24, $pcd8c70bc, $v96235e0cbf, $pa3585c80, $v00e5d13da1 = false, $v1eda8d978f = false, $pcaaa70b9 = false) { $v067674f4e4 = ""; $v1335217393 = WorkFlowDataAccessHandler::getClassName($v9ff79a4a24); $v0674ea4a10 = array(); $v92406677bd = $v00e5d13da1 ? "\$obj = \$this->callHbnObject(\$options);" : ""; $pf80bd68a = $v00e5d13da1 ? "\$obj" : "\$this->getDataAccessBroker()"; $v6f1902ee66 = $v00e5d13da1 ? "" : "'$pcd8c70bc', "; if (is_array($v987a981e39)) { foreach ($v987a981e39 as $pa1c701b0 => $v16eb00c1d7) { foreach ($v16eb00c1d7 as $v6aad4fb598 => $v50819961ef) { $v5e813b295b = getFunctionName($v6aad4fb598); $v1dc11964f1 = $v68745269c7 = ""; switch($pa1c701b0) { case "insert": $pd51ed019 = false; if (substr($v6aad4fb598, - strlen("_with_ai_pk")) == "_with_ai_pk") { if ($v16eb00c1d7[ substr($v6aad4fb598, 0, - strlen("_with_ai_pk")) ]) continue 2; } else if ($v16eb00c1d7[$v6aad4fb598 . "_with_ai_pk"]) { $v50819961ef = $v16eb00c1d7[$v6aad4fb598 . "_with_ai_pk"]; $pd51ed019 = true; } $v5e813b295b = stripos($v5e813b295b, "insert") !== false || stripos($v5e813b295b, "add") !== false ? $v5e813b295b : "insert" . ucfirst($v5e813b295b); $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "insert$v1335217393" ? "insert" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $v887e85c917 = checkTypeOfExistentPrimaryKeys($v9367d5be85, $v806c8712d7); $v441298e472 .= WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($v9367d5be85, true); $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, $v887e85c917, true, true, true, true, false, false); if ($v92406677bd) $v68745269c7 .= $v92406677bd . "
		"; if ($v441298e472) $v68745269c7 .= $v441298e472 . "
		"; if ($v806c8712d7 && $pd51ed019) { $pa9b33815 = $v6aad4fb598 . "_with_ai_pk"; $v68745269c7 .= "//This code supposes that there is only 1 auto increment pk and that '$pa9b33815' is a sql with the auto increment pk, and that '$v6aad4fb598' is a sql without auto increment pks.
		if (\$data[\"$v806c8712d7\"]) {
			\$options[\"hard_coded_ai_pk\"] = true;
			\$result = {$pf80bd68a}->callInsert({$v6f1902ee66}'$pa9b33815', \$data, \$options);
			\$result = \$result ? \$data[\"$v806c8712d7\"] : false;
		}
		else {
			\$result = {$pf80bd68a}->callInsert({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);
			\$result = \$result ? {$pf80bd68a}->getInsertedId(\$options) : false;
		}
		"; } else { $v8c5df8072b = WorkFlowDataAccessHandler::getSQLStatementTable($v50819961ef, $v51d1745dbd, $pab752e34, $v872f5b4dbb); if ($v806c8712d7) $v68745269c7 .= "//This code supposes that there is only 1 auto increment pk
		\$options[\"hard_coded_ai_pk\"] = true;
		
		if (!\$data[\"$v806c8712d7\"])
			\$data[\"$v806c8712d7\"] = {$pf80bd68a}->findObjectsColumnMax(\"$v8c5df8072b\", \"$v806c8712d7\") + 1; //DO NOT SET IT TO 'DEFAULT' BC IT WON'T WORK IN MS-SQL-SERVER. 'DEFAULT' ONLY WORKS IN MYSQL AND POSTGRES!
		"; $v68745269c7 .= "\$result = {$pf80bd68a}->callInsert({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);
		"; if ($v806c8712d7) $v68745269c7 .= "\$result = \$result ? \$data[\"$v806c8712d7\"] : false;
		"; else { $pbec62cc6 = WorkFlowDBHandler::getTableFromTables($v0674ea4a10, $v8c5df8072b); if ($v8c5df8072b && getTableAutoIncrementedPrimaryKeys($pbec62cc6)) $v68745269c7 .= "\$result = \$result ? {$pf80bd68a}->getInsertedId(\$options) : false;"; else $v68745269c7 .= "\$result = \$result ? true : false;"; } } $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "update": $v5e813b295b = stripos($v5e813b295b, "update") !== false || stripos($v5e813b295b, "edit") !== false ? $v5e813b295b : "update" . ucfirst($v5e813b295b); $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "update$v1335217393" ? "update" : $v5e813b295b; $v5e813b295b = !$v00e5d13da1 && ($v5e813b295b == "update{$v1335217393}PrimaryKeys" || $v5e813b295b == "update{$v1335217393}Pks") ? "updatePrimaryKeys" : $v5e813b295b; $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "updateAll{$v1335217393}Items" ? "updateAll" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $v441298e472 = WorkFlowBusinessLogicHandler::prepareAttributesDefaultValueCode($v9367d5be85, true, true); $pf81e7d81 = $v5e813b295b != "updateAll" ? ( $v5e813b295b == "updatePrimaryKeys" ? WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, false, true, false, true, true, false) : WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, true, true, true, true, true, false) ) : WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, false, true, true, true, true, true, false); if ($v441298e472) $v68745269c7 .= $v441298e472 . "
		"; if ($v92406677bd) $v68745269c7 .= $v92406677bd . "
		"; if ($v5e813b295b == "updateAll") $v68745269c7 .= "if (\$data[\"conditions\"] || \$data[\"all\"])
			\$result = {$pf80bd68a}->callUpdate({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);"; else $v68745269c7 .= "\$result = {$pf80bd68a}->callUpdate({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "delete": $v5e813b295b = stripos($v5e813b295b, "delete") !== false || stripos($v5e813b295b, "remove") !== false ? $v5e813b295b : "delete" . ucfirst($v5e813b295b); $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "delete$v1335217393" ? "delete" : $v5e813b295b; $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "deleteAll{$v1335217393}Items" ? "deleteAll" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $pf81e7d81 = $v5e813b295b != "deleteAll" ? WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, false, true, false, true, false, false) : null; if ($v92406677bd) $v68745269c7 .= $v92406677bd . "
		"; if ($v5e813b295b == "deleteAll") $v68745269c7 .= "if (\$data[\"conditions\"] || \$data[\"all\"])
			\$result = {$pf80bd68a}->callDelete({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);"; else $v68745269c7 .= "\$result = {$pf80bd68a}->callDelete({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "select": $v04c7684275 = isCountFunctionName($v5e813b295b); $pa05557b6 = !$v04c7684275 && (stripos($v5e813b295b, "get") !== false || stripos($v5e813b295b, "select") !== false); $v5e813b295b = $v04c7684275 || $pa05557b6 ? $v5e813b295b : "get" . ucfirst($v5e813b295b); $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "get$v1335217393" ? "get" : $v5e813b295b; $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "count$v1335217393" ? "count" : $v5e813b295b; $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "get{$v1335217393}Items" ? "getAll" : $v5e813b295b; $v5e813b295b = !$v00e5d13da1 && $v5e813b295b == "count{$v1335217393}Items" ? "countAll" : $v5e813b295b; $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); if ($v5e813b295b != "getAll" && $v5e813b295b != "countAll") $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, true, true, false, true, false, false); else { $pa02649b9 = $v9367d5be85; $v8c5df8072b = WorkFlowDataAccessHandler::getSQLStatementTable($v50819961ef, $v51d1745dbd, $pab752e34, $v872f5b4dbb); prepareSelectAllSQLParameters($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $pa02649b9, $v8c5df8072b); $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($pa02649b9, true, true, false, false, true, false, true); } if ($v92406677bd) $v68745269c7 .= $v92406677bd . "
		"; $v68745269c7 .= "self::prepareInputData(\$data);
		\$result = {$pf80bd68a}->callSelect({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);"; if ($v5e813b295b == "get") $v68745269c7 .= "
		\$result = \$result ? \$result[0] : null;"; else if ($v04c7684275) $v68745269c7 .= "
		\$result = \$result && \$result[0] ? \$result[0][\"total\"] : null;"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "procedure": $v5e813b295b = stripos($v5e813b295b, "call") !== false ? $v5e813b295b : "call" . ucfirst($v5e813b295b); $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareSQLStatementCode($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $pe12d2a7d = WorkFlowBusinessLogicHandler::prepareAddcslashesCode($v9367d5be85); $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, true, false, true, true, false, false); if ($v92406677bd) $v68745269c7 .= $v92406677bd . "
		"; $v68745269c7 .= "\$result = {$pf80bd68a}->callProcedure({$v6f1902ee66}'$v6aad4fb598', \$data, \$options);"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; case "one_to_one": case "many_to_one": case "one_to_many": case "many_to_many": $v5e813b295b = stripos($v5e813b295b, "findrelationship") !== false || stripos($v5e813b295b, "get") !== false || stripos($v5e813b295b, "select") !== false ? $v5e813b295b : "get" . ucfirst($v5e813b295b); $pe7720ba3 = getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358); $v1dc11964f1 = prepareRelationshipCode($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $v1eda8d978f, $v9367d5be85); if (empty($v50819961ef["@"]["parameter_class"])) WorkFlowDataAccessHandler::addPrimaryKeysToParameters($pcaaa70b9, $v9367d5be85); $pf81e7d81 = WorkFlowBusinessLogicHandler::getAnnotationsFromParameters($v9367d5be85, true, false, true, false, true, false, false); $v68745269c7 = "unset(\$data[\"options\"]);
		
		\$obj = \$this->callHbnObject(\$options);
		\$result = \$obj ? \$obj->findRelationship('$v6aad4fb598', \$data, \$options) : null;"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, "", $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); if (stripos($v5e813b295b, "findrelationship") !== false) $v5e813b295b = "countRelationship" . ucfirst(str_ireplace("findrelationship", "", $v5e813b295b)); else $v5e813b295b = "count" . ucfirst(str_ireplace(array("numberof", "number", "total", "get", "select"), "", $v5e813b295b)); $v68745269c7 = "\$obj = \$this->callHbnObject(\$options);
		\$result = \$obj ? \$obj->countRelationship('$v6aad4fb598', \$data, \$options) : null;"; $v067674f4e4 .= getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, "", $v1dc11964f1, $v68745269c7, $pf81e7d81, $pe7720ba3); break; } } } } return $v067674f4e4; } function getBusinessLogicServiceFunctionCode($v5e813b295b, $v9367d5be85, $pe12d2a7d, $v1dc11964f1, $v68745269c7, $pf81e7d81 = null, $pe7720ba3 = null) { $v067674f4e4 = ""; if ($v5e813b295b) { $v067674f4e4 .= '
' . $pf81e7d81 . '
	public function ' . $v5e813b295b . '($data) {
		$options = $data["options"];
		$this->mergeOptionsWithBusinessLogicLayer($options);
		'; if ($pe7720ba3) $v067674f4e4 .= $pe7720ba3 . '
		'; if (!$pf81e7d81 && $v1dc11964f1) { if ($pe12d2a7d) { $v067674f4e4 .= $pe12d2a7d . '
		'; } $v067674f4e4 .= '
		' . $v1dc11964f1 . '
		
		$result = null;
		if ($status) {
			' . str_replace("\n", "\n\t", $v68745269c7) . '
		}'; } else { $v067674f4e4 .= '
		' . $v68745269c7; } $v067674f4e4 .= '
		
		return $result;
	}
	'; } return $v067674f4e4; } function prepareRelationshipCode(&$v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, &$v0674ea4a10, $v1eda8d978f, &$v9367d5be85) { WorkFlowDataAccessHandler::prepareRelationshipParameters($v50819961ef, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $v1eda8d978f, $v9367d5be85); $v067674f4e4 = prepareParametersCode($v50819961ef, $v987a981e39, $v9367d5be85); return $v067674f4e4; } function prepareSQLStatementCode($pf232dd5a, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, &$v0674ea4a10, $pa3585c80, &$v9367d5be85) { WorkFlowDataAccessHandler::prepareSQLStatementParameters($pf232dd5a, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); $v067674f4e4 = prepareParametersCode($pf232dd5a, $v987a981e39, $v9367d5be85); return $v067674f4e4; } function prepareSelectAllSQLParameters($pf232dd5a, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, &$v0674ea4a10, $pa3585c80, &$v9367d5be85, $v8c5df8072b) { if ($v8c5df8072b) { if ($v987a981e39["insert"]["insert_" . $v8c5df8072b]) { prepareSQLStatementCode($v987a981e39["insert"]["insert_" . $v8c5df8072b], $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); } else { $ped0a6251 = $v0674ea4a10[$v8c5df8072b]; if (!$ped0a6251) $ped0a6251 = $v51d1745dbd->getFunction("listTableFields", $v8c5df8072b, array("db_broker" => $pab752e34, "db_driver" => $v872f5b4dbb)); if ($ped0a6251) { $v3c76382d93 = "select * from $v8c5df8072b where 1=1"; foreach ($ped0a6251 as $v5e45ec9bb9 => $v1b0cfa478b) if (ObjTypeHandler::isDBTypeNumeric($v1b0cfa478b["type"])) $v3c76382d93 .= " and $v5e45ec9bb9=#$v5e45ec9bb9#"; else $v3c76382d93 .= " and $v5e45ec9bb9='#$v5e45ec9bb9#'"; $pf232dd5a["value"] = $v3c76382d93; prepareSQLStatementCode($pf232dd5a, $v987a981e39, $v51d1745dbd, $pab752e34, $v872f5b4dbb, $v5e053dece2, $v0674ea4a10, $pa3585c80, $v9367d5be85); } } } } function removeMapEntriesFromParameters($pf232dd5a, $v987a981e39, &$v9367d5be85) { if (empty($pf232dd5a["@"]["parameter_class"])) { $v2967293505 = $pf232dd5a["@"]["parameter_map"]; $v7d1068ab72 = $v2967293505 ? $v987a981e39["parameter_map"][$v2967293505]["parameter"] : null; if ($v7d1068ab72) { $pc37695cb = count($v7d1068ab72); $v98e58142ff = array(); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v98e58142ff[] = $v7d1068ab72[$v43dd7d0051]["input_name"]; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pdca845fa = $v7d1068ab72[$v43dd7d0051]; $v49bdd49c66 = $pdca845fa["output_name"]; if ($v49bdd49c66 && isset($v9367d5be85[$v49bdd49c66]) && !in_array($v49bdd49c66, $v98e58142ff) && ($pdca845fa["input_type"] || $pdca845fa["output_type"])) unset($v9367d5be85[$v49bdd49c66]); } } } } function prepareParametersCode($pf232dd5a, $v987a981e39, $v9367d5be85) { removeMapEntriesFromParameters($pf232dd5a, $v987a981e39, $v9367d5be85); if ($v9367d5be85) { $v067674f4e4 = '$status = true;'; $v4159504aa3 = array(); foreach ($v9367d5be85 as $v5e45ec9bb9 => $v1b0cfa478b) { $v5e813b295b = $v1b0cfa478b["name"] ? $v1b0cfa478b["name"] : $v5e45ec9bb9; $v3fb9f41470 = $v1b0cfa478b["type"]; $v18d8ec0406 = $v1b0cfa478b["mandatory"]; if ($v3fb9f41470) { if ($v4159504aa3[$v3fb9f41470]) { $v93ff269092 = $v4159504aa3[$v3fb9f41470]; } else { $v93ff269092 = rand(0, 1000); $v4159504aa3[$v3fb9f41470] = $v93ff269092; $pc37695cb = ObjTypeHandler::convertSimpleTypeIntoCompositeType($v3fb9f41470); $v067674f4e4 .= '
		
		$obj_' . $v93ff269092 . ' = $status ? ObjectHandler::createInstance("' . $pc37695cb . '") : null;
		$status = $status && ObjectHandler::checkIfObjType($obj_' . $v93ff269092 . ');'; } $v067674f4e4 .= '
		$status = $status && ( '; $v067674f4e4 .= $v18d8ec0406 ? 'isset($data["' . $v5e813b295b . '"]) && ' : 'empty($data["' . $v5e813b295b . '"]) || '; $v067674f4e4 .= '$obj_' . $v93ff269092 . '->setInstance($data["' . $v5e813b295b . '"]) );'; } else if ($v18d8ec0406) { $v067674f4e4 .= '
		$status = $status && isset($data["' . $v5e813b295b . '"]);'; } } return $v067674f4e4; } return ""; } function getFunctionName($v6aad4fb598) { return lcfirst( WorkFlowDataAccessHandler::getClassName($v6aad4fb598) ); } function getModuleId($v7dffdb5a5b) { $v7dffdb5a5b = str_replace("//", "/", $v7dffdb5a5b); $v7dffdb5a5b = substr($v7dffdb5a5b, 0, 1) == "/" ? substr($v7dffdb5a5b, 1) : $v7dffdb5a5b; $pbd1bc7b0 = strrpos($v7dffdb5a5b, "/"); $pbd1bc7b0 = $pbd1bc7b0 !== false ? $pbd1bc7b0 : 0; $pcd8c70bc = substr($v7dffdb5a5b, 0, $pbd1bc7b0); return $pcd8c70bc; } function getTableAutoIncrementedPrimaryKeys($pc3502754) { $pe2f18119 = array(); if ($pc3502754) foreach ($pc3502754 as $v59b2a92509 => $pf932fb91) if ($pf932fb91["primary_key"] && WorkFlowDataAccessHandler::isAutoIncrementedAttribute($pf932fb91)) $pe2f18119[$v59b2a92509] = $pf932fb91; return $pe2f18119; } function checkTypeOfExistentPrimaryKeys($v9367d5be85, &$v806c8712d7) { $v28bcd49a84 = false; $v806c8712d7 = null; if (is_array($v9367d5be85)) foreach ($v9367d5be85 as $v5e813b295b => $pc5faab2f) if ($pc5faab2f["primary_key"]) { if (WorkFlowDataAccessHandler::isAutoIncrementedAttribute($pc5faab2f)) { $v28bcd49a84 = 2; $v806c8712d7 = $pc5faab2f["name"] ? $pc5faab2f["name"] : $v5e813b295b; } else $v28bcd49a84 = 1; } return $v28bcd49a84; } function isCountFunctionName($v5e813b295b) { $v974b39e108 = array("count", "total"); $pc37695cb = count($v974b39e108); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v86b750c0a7 = $v974b39e108[$v43dd7d0051]; $pbd1bc7b0 = stripos($v5e813b295b, "count"); if ($pbd1bc7b0 !== false) { $pbdb99a55 = substr($v5e813b295b, $pbd1bc7b0 + strlen($v86b750c0a7), 1); if (empty($pbdb99a55) || $pbdb99a55 == strtoupper($pbdb99a55)) return true; } } return false; } function getDBDriverOptionsCode($v872f5b4dbb, $v8b13fa2358) { $v067674f4e4 = ''; if ($v872f5b4dbb && $v8b13fa2358) $v067674f4e4 = '$options["db_driver"] = "' . $v872f5b4dbb . '";'; return $v067674f4e4; } ?>
