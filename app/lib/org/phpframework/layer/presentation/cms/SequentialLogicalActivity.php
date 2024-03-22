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
class SequentialLogicalActivity { private $v37e6ee1556 = false; private $v08d9602741; public function setEVC($v08d9602741) { $this->v08d9602741 = $v08d9602741; } public function getEVC() { return $this->v08d9602741; } public function execute($v55bd236ac1, &$pee4c7870 = null) { $EVC = $this->v08d9602741; $pa47fac06 = $EVC->getCommonProjectName(); include_once get_lib("org.phpframework.util.web.html.HtmlFormHandler"); include_once $EVC->getModulePath("common/CommonModuleUI", $pa47fac06); include_once $EVC->getModulePath("object/ObjectUtil", $pa47fac06); include_once $EVC->getModulePath("user/UserUtil", $pa47fac06); $v2aed819666 = array( "EVC" => $EVC, "_GET" => $_GET, "_POST" => $_POST, "_REQUEST" => $_REQUEST, "_FILES" => $_FILES, "_COOKIE" => $_COOKIE, "_ENV" => $_ENV, "_SERVER" => $_SERVER, "_SESSION" => $_SESSION, "GLOBALS" => $GLOBALS, ); $pee4c7870 = is_array($pee4c7870) ? array_merge($v2aed819666, $pee4c7870) : $v2aed819666; $pf8ed4912 = $this->f67598304bc($v55bd236ac1, $pee4c7870); unset($pee4c7870["EVC"]); unset($pee4c7870["_GET"]); unset($pee4c7870["_POST"]); unset($pee4c7870["_REQUEST"]); unset($pee4c7870["_FILES"]); unset($pee4c7870["_COOKIE"]); unset($pee4c7870["_ENV"]); unset($pee4c7870["_SERVER"]); unset($pee4c7870["_SESSION"]); unset($pee4c7870["GLOBALS"]); return $pf8ed4912; } private function f67598304bc($v55bd236ac1, &$pee4c7870, &$pa2507f6c = false, &$v61cbe50bc9 = false) { $pf8ed4912 = ''; if (is_array($v55bd236ac1)) foreach ($v55bd236ac1 as $pd69fb7d0 => $v1285a575c1) if (!$pa2507f6c) { $pbb2163ef = isset($v1285a575c1["condition_type"]) ? $v1285a575c1["condition_type"] : null; $v84e76dfbbc = isset($v1285a575c1["condition_value"]) ? $v1285a575c1["condition_value"] : null; $v5c1c342594 = $this->ma578de667073($pbb2163ef, $v84e76dfbbc, $pee4c7870); if ($v5c1c342594) { $v256e3a39a7 = isset($v1285a575c1["action_type"]) ? strtolower($v1285a575c1["action_type"]) : ""; $pcf8113cc = isset($v1285a575c1["action_value"]) ? $v1285a575c1["action_value"] : null; $v9ad1385268 = $this->f1d5d9e8ab5($v256e3a39a7, $pcf8113cc, $pee4c7870, $pa2507f6c, $v61cbe50bc9); $v0d39e09d41 = isset($v1285a575c1["result_var_name"]) ? trim($v1285a575c1["result_var_name"]) : ""; $v0d39e09d41 = $v0d39e09d41 && substr($v0d39e09d41, 0, 1) == '$' ? substr($v0d39e09d41, 1) : $v0d39e09d41; if ($v0d39e09d41) { if (substr($v0d39e09d41, -2) == "[]") { $v0d39e09d41 = substr($v0d39e09d41, 0, -2); if (!isset($pee4c7870[ $v0d39e09d41 ]) || !is_array($pee4c7870[ $v0d39e09d41 ])) $pee4c7870[ $v0d39e09d41 ] = array(); $pee4c7870[ $v0d39e09d41 ][] = $v9ad1385268; } else $pee4c7870[ $v0d39e09d41 ] = $v9ad1385268; } else $pf8ed4912 .= $v9ad1385268; if ($v61cbe50bc9) { echo $pf8ed4912; die(); } else if ($pa2507f6c) break; } } return $pf8ed4912; } private function ma578de667073($pbb2163ef, $v84e76dfbbc, &$pee4c7870) { $v5c1c342594 = true; if ($pbb2163ef) { $pbb2163ef = strtolower($pbb2163ef); switch($pbb2163ef) { case "execute_if_var": case "execute_if_not_var": $v5c1c342594 = false; $v847e7d0a83 = trim($v84e76dfbbc); if (!empty($v847e7d0a83)) { $v847e7d0a83 = substr($v847e7d0a83, 0, 1) == '$' || substr($v847e7d0a83, 0, 2) == '\\$' ? $v847e7d0a83 : '$' . $v847e7d0a83; $v067674f4e4 = '<?= ' . $v847e7d0a83 . ' ? 1 : 0 ?>'; $v9ad1385268 = \PHPScriptHandler::parseContent($v067674f4e4, $pee4c7870); $v5c1c342594 = !empty($v9ad1385268); } if (strpos($pbb2163ef, "_not_") !== false) $v5c1c342594 = !$v5c1c342594; break; case "execute_if_post_button": $v3ad42a02b0 = trim($v84e76dfbbc); $v3ad42a02b0 = substr($v3ad42a02b0, 0, 1) == '$' ? substr($v3ad42a02b0, 1) : $v3ad42a02b0; $v5c1c342594 = $v3ad42a02b0 ? !empty($_POST[$v3ad42a02b0]) : false; break; case "execute_if_not_post_button": $v3ad42a02b0 = trim($v84e76dfbbc); $v3ad42a02b0 = substr($v3ad42a02b0, 0, 1) == '$' ? substr($v3ad42a02b0, 1) : $v3ad42a02b0; $v5c1c342594 = $v3ad42a02b0 ? empty($_POST[$v3ad42a02b0]) : true; break; case "execute_if_get_button": $v3ad42a02b0 = trim($v84e76dfbbc); $v3ad42a02b0 = substr($v3ad42a02b0, 0, 1) == '$' ? substr($v3ad42a02b0, 1) : $v3ad42a02b0; $v5c1c342594 = $v3ad42a02b0 ? !empty($_GET[$v3ad42a02b0]) : false; break; case "execute_if_not_get_button": $v3ad42a02b0 = trim($v84e76dfbbc); $v3ad42a02b0 = substr($v3ad42a02b0, 0, 1) == '$' ? substr($v3ad42a02b0, 1) : $v3ad42a02b0; $v5c1c342594 = $v3ad42a02b0 ? empty($_GET[$v3ad42a02b0]) : true; break; case "execute_if_post_resource": $v69013e2fd6 = trim($v84e76dfbbc); $v69013e2fd6 = substr($v69013e2fd6, 0, 1) == '$' ? substr($v69013e2fd6, 1) : $v69013e2fd6; $v5c1c342594 = $_POST["resource"] == $v69013e2fd6; break; case "execute_if_not_post_resource": $v69013e2fd6 = trim($v84e76dfbbc); $v69013e2fd6 = substr($v69013e2fd6, 0, 1) == '$' ? substr($v69013e2fd6, 1) : $v69013e2fd6; $v5c1c342594 = $_POST["resource"] != $v69013e2fd6; break; case "execute_if_get_resource": $v69013e2fd6 = trim($v84e76dfbbc); $v69013e2fd6 = substr($v69013e2fd6, 0, 1) == '$' ? substr($v69013e2fd6, 1) : $v69013e2fd6; $v5c1c342594 = $_GET["resource"] == $v69013e2fd6; break; case "execute_if_not_get_resource": $v69013e2fd6 = trim($v84e76dfbbc); $v69013e2fd6 = substr($v69013e2fd6, 0, 1) == '$' ? substr($v69013e2fd6, 1) : $v69013e2fd6; $v5c1c342594 = $_GET["resource"] != $v69013e2fd6; break; case "execute_if_previous_action": $v5c1c342594 = $pee4c7870 ? !empty($pee4c7870[count($pee4c7870) - 1]) : false; break; case "execute_if_not_previous_action": $v5c1c342594 = $pee4c7870 ? empty($pee4c7870[count($pee4c7870) - 1]) : true; break; case "execute_if_condition": case "execute_if_not_condition": case "execute_if_code": case "execute_if_not_code": $v5c1c342594 = false; if (is_numeric($v84e76dfbbc)) $v5c1c342594 = !empty($v84e76dfbbc); else if ($v84e76dfbbc === true) $v5c1c342594 = true; else if ((is_array($v84e76dfbbc) || is_object($v84e76dfbbc)) && !empty($v84e76dfbbc)) $v5c1c342594 = true; else if (!empty($v84e76dfbbc)) { $v067674f4e4 = '<?= ' . $v84e76dfbbc . ' ?>'; $v9ad1385268 = \PHPScriptHandler::parseContent($v067674f4e4, $pee4c7870); $v5c1c342594 = !empty($v9ad1385268); } if (strpos($pbb2163ef, "_not_") !== false) $v5c1c342594 = !$v5c1c342594; break; } } return $v5c1c342594; } private function f1d5d9e8ab5($v256e3a39a7, $pcf8113cc, &$pee4c7870, &$pa2507f6c = false, &$v61cbe50bc9 = false) { $v9ad1385268 = null; if ($v256e3a39a7) { $v256e3a39a7 = strtolower($v256e3a39a7); $EVC = $this->getEVC(); switch ($v256e3a39a7) { case "html": if (is_array($pcf8113cc)) { translateProjectFormSettings($EVC, $pcf8113cc); $pcf8113cc["CacheHandler"] = $EVC->getPresentationLayer()->getPHPFrameWork()->getObject("UserCacheHandler"); if (isset($pcf8113cc["ptl"]["code"])) { if (isset($pcf8113cc["ptl"]["external_vars"]) && is_array($pcf8113cc["ptl"]["external_vars"])) $pcf8113cc["ptl"]["external_vars"] = array_merge($pee4c7870, $pcf8113cc["ptl"]["external_vars"]); else $pcf8113cc["ptl"]["external_vars"] = $pee4c7870; } $v9ad1385268 = \HtmlFormHandler::createHtmlForm($pcf8113cc, $pee4c7870); } else $v9ad1385268 = $pcf8113cc; break; case "callbusinesslogic": $pb537c4d4 = isset($pcf8113cc["method_obj"]) ? $pcf8113cc["method_obj"] : null; $pb537c4d4 = $this->f46854f4feb($pb537c4d4, $pee4c7870); if ($pb537c4d4 && method_exists($pb537c4d4, "callBusinessLogic")) { $pcd8c70bc = isset($pcf8113cc["module_id"]) ? $pcf8113cc["module_id"] : null; $v20b8676a9f = isset($pcf8113cc["service_id"]) ? $pcf8113cc["service_id"] : null; $v9367d5be85 = isset($pcf8113cc["parameters"]) ? $pcf8113cc["parameters"] : null; $v5d3813882f = isset($pcf8113cc["options"]) ? $pcf8113cc["options"] : null; $pcd8c70bc = $this->f46854f4feb($pcd8c70bc, $pee4c7870); $v20b8676a9f = $this->f46854f4feb($v20b8676a9f, $pee4c7870); $v9367d5be85 = $this->f46854f4feb($v9367d5be85, $pee4c7870); $v5d3813882f = $this->f46854f4feb($v5d3813882f, $pee4c7870); $v9ad1385268 = $pb537c4d4->callBusinessLogic($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f); } else launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain callBusinessLogic method!')); break; case "callibatisquery": $v1fd26838fd = false; $pf9445ab0 = isset($pcf8113cc["service_type"]) ? $pcf8113cc["service_type"] : null; switch($pf9445ab0) { case "insert": $v603bd47baf = "callInsert"; $v1fd26838fd = true; break; case "update": $v603bd47baf = "callUpdate"; $v1fd26838fd = true; break; case "delete": $v603bd47baf = "callDelete"; $v1fd26838fd = true; break; case "select": $v603bd47baf = "callSelect"; $v1fd26838fd = true; break; case "procedure": $v603bd47baf = "callProcedure"; $v1fd26838fd = true; break; default: $v603bd47baf = "callQuery"; } $pb537c4d4 = isset($pcf8113cc["method_obj"]) ? $pcf8113cc["method_obj"] : null; $pb537c4d4 = $this->f46854f4feb($pb537c4d4, $pee4c7870); if ($pb537c4d4 && method_exists($pb537c4d4, $v603bd47baf)) { $pcd8c70bc = isset($pcf8113cc["module_id"]) ? $pcf8113cc["module_id"] : null; $v20b8676a9f = isset($pcf8113cc["service_id"]) ? $pcf8113cc["service_id"] : null; $pf9445ab0 = isset($pcf8113cc["service_type"]) ? $pcf8113cc["service_type"] : null; $v9367d5be85 = isset($pcf8113cc["parameters"]) ? $pcf8113cc["parameters"] : null; $v5d3813882f = isset($pcf8113cc["options"]) ? $pcf8113cc["options"] : null; $pcd8c70bc = $this->f46854f4feb($pcd8c70bc, $pee4c7870); $v20b8676a9f = $this->f46854f4feb($v20b8676a9f, $pee4c7870); $pf9445ab0 = $this->f46854f4feb($pf9445ab0, $pee4c7870); $v9367d5be85 = $this->f46854f4feb($v9367d5be85, $pee4c7870); $v5d3813882f = $this->f46854f4feb($v5d3813882f, $pee4c7870); if ($v1fd26838fd) $v9ad1385268 = $pb537c4d4->$v603bd47baf($pcd8c70bc, $v20b8676a9f, $v9367d5be85, $v5d3813882f); else $v9ad1385268 = $pb537c4d4->$v603bd47baf($pcd8c70bc, $pf9445ab0, $v20b8676a9f, $v9367d5be85, $v5d3813882f); } else launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain ' . $v603bd47baf . ' method!')); break; case "callhibernatemethod": $pb537c4d4 = isset($pcf8113cc["method_obj"]) ? $pcf8113cc["method_obj"] : null; $pb537c4d4 = $this->f46854f4feb($pb537c4d4, $pee4c7870); if ($pb537c4d4) { if ($pcf8113cc["broker_method_obj_type"] != "exists_hbn_var") { if (method_exists($pb537c4d4, "callObject")) { $pcd8c70bc = isset($pcf8113cc["module_id"]) ? $pcf8113cc["module_id"] : null; $v20b8676a9f = isset($pcf8113cc["service_id"]) ? $pcf8113cc["service_id"] : null; $v5d3813882f = isset($pcf8113cc["options"]) ? $pcf8113cc["options"] : null; $pcd8c70bc = $this->f46854f4feb($pcd8c70bc, $pee4c7870); $v20b8676a9f = $this->f46854f4feb($v20b8676a9f, $pee4c7870); $v5d3813882f = $this->f46854f4feb($v5d3813882f, $pee4c7870); $pb537c4d4 = $pb537c4d4->callObject($pcd8c70bc, $v20b8676a9f, $v5d3813882f); } else launch_exception(new \Exception('$action_value["method_obj"] must contain callObject method that returns a Hibernate Object!')); } $v9c29bc9d47 = isset($pcf8113cc["service_method"]) ? $pcf8113cc["service_method"] : null; if (method_exists($pb537c4d4, $v9c29bc9d47)) { $v0d6749c7fd = array( "insert" => array("data", "ids", "options"), "insertAll" => array("data", "statuses", "ids", "options"), "update" => array("data", "options"), "updateAll" => array("data", "statuses", "options"), "insertOrUpdate" => array("data", "ids", "options"), "insertOrUpdateAll" => array("data", "statuses", "ids", "options"), "delete" => array("data", "options"), "deleteAll" => array("data", "statuses", "options"), "updatePrimaryKeys" => array("data", "options"), "findById" => array("data", "data", "options"), "find" => array("data", "options"), "count" => array("data", "options"), "findRelationships" => array("parent_ids", "options"), "findRelationship" => array("rel_name", "parent_ids", "options"), "countRelationships" => array("parent_ids", "options"), "countRelationship" => array("rel_name", "parent_ids", "options"), "callQuerySQL" => array("query_type", "query_id", "data", "options"), "callQuery" => array("query_type", "query_id", "data", "options"), "callInsertSQL" => array("query_id", "data", "options"), "callInsert" => array("query_id", "data", "options"), "callUpdateSQL" => array("query_id", "data", "options"), "callUpdate" => array("query_id", "data", "options"), "callDeleteSQL" => array("query_id", "data", "options"), "callDelete" => array("query_id", "data", "options"), "callSelectSQL" => array("query_id", "data", "options"), "callSelect" => array("query_id", "data", "options"), "callProcedureSQL" => array("query_id", "data", "options"), "callProcedure" => array("query_id", "data", "options"), "getFunction" => array("function_name", "data", "options"), "getData" => array("sql", "options"), "setData" => array("sql", "options"), "getInsertedId" => array("options"), ); $pf677bde0 = isset($v0d6749c7fd[$v9c29bc9d47]) ? $v0d6749c7fd[$v9c29bc9d47] : null; $v86066462c3 = array(); $v32f28291a1 = null; $v5f16e827c3 = null; if ($pf677bde0) foreach ($pf677bde0 as $pea70e132) { $v956913c90f = isset($pcf8113cc["sma_" . $pea70e132]) ? $pcf8113cc["sma_" . $pea70e132] : null; $v956913c90f = $this->f46854f4feb($v956913c90f, $pee4c7870); if ($pea70e132 == "ids") { $v5f16e827c3 = $v956913c90f; $v86066462c3[] = &$v32f28291a1; } else $v86066462c3[] = $v956913c90f; } $v9ad1385268 = call_user_func_array(array($pb537c4d4, $v9c29bc9d47), $v86066462c3); if ($v5f16e827c3) $pee4c7870[$v5f16e827c3] = $v32f28291a1; } else launch_exception(new \Exception('$action_value["method_obj"] must contain ' . $v9c29bc9d47 . ' method!')); } else launch_exception(new \Exception('$action_value["method_obj"] cannot be null!')); break; case "getquerydata": case "setquerydata": $pb537c4d4 = isset($pcf8113cc["method_obj"]) ? $pcf8113cc["method_obj"] : null; $pb537c4d4 = $this->f46854f4feb($pb537c4d4, $pee4c7870); if ($pb537c4d4 && method_exists($pb537c4d4, "getData")) { $v3c76382d93 = isset($pcf8113cc["sql"]) ? $pcf8113cc["sql"] : null; $v5d3813882f = isset($pcf8113cc["options"]) ? $pcf8113cc["options"] : null; $v3c76382d93 = $this->f46854f4feb($v3c76382d93, $pee4c7870); $v5d3813882f = $this->f46854f4feb($v5d3813882f, $pee4c7870); if ($v256e3a39a7 == "getquerydata") $v9ad1385268 = $pb537c4d4->getData($v3c76382d93, $v5d3813882f); else $v9ad1385268 = $pb537c4d4->setData($v3c76382d93, $v5d3813882f); } else launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain getData method!')); break; case "callfunction": $this->mcdc2cbc34b3c($pcf8113cc, $pee4c7870); $v24b0e52635 = isset($pcf8113cc["func_name"]) ? $pcf8113cc["func_name"] : null; $v24b0e52635 = $this->f46854f4feb($v24b0e52635, $pee4c7870); $pc0481df4 = !empty($pcf8113cc["func_args"]) ? $this->f46854f4feb($pcf8113cc["func_args"], $pee4c7870) : array(); if ($v24b0e52635 && function_exists($v24b0e52635)) $v9ad1385268 = call_user_func_array($v24b0e52635, $pc0481df4); else launch_exception(new \Exception('$func_name "' . $v24b0e52635 . '" is not a function!')); break; case "callobjectmethod": $this->mcdc2cbc34b3c($pcf8113cc, $pee4c7870); $pb52f9d64 = isset($pcf8113cc["method_static"]) ? $pcf8113cc["method_static"] : null; $pb537c4d4 = isset($pcf8113cc["method_obj"]) ? $pcf8113cc["method_obj"] : null; $v603bd47baf = isset($pcf8113cc["method_name"]) ? $pcf8113cc["method_name"] : null; $pb52f9d64 = $this->f46854f4feb($pb52f9d64, $pee4c7870); $pb537c4d4 = $this->f46854f4feb($pb537c4d4, $pee4c7870); $v603bd47baf = $this->f46854f4feb($v603bd47baf, $pee4c7870); $pf677bde0 = !empty($pcf8113cc["method_args"]) ? $this->f46854f4feb($pcf8113cc["method_args"], $pee4c7870) : array(); if ($pb52f9d64) { if (method_exists("\\" . $pb537c4d4, $v603bd47baf)) $v9ad1385268 = call_user_func_array("\\$pb537c4d4::$v603bd47baf", $pf677bde0); else launch_exception(new \Exception("\\" . $pb537c4d4 . ' class must contain ' . $v603bd47baf . ' static method!')); } else if ($pb537c4d4 && method_exists($pb537c4d4, $v603bd47baf)) $v9ad1385268 = call_user_func_array(array($pb537c4d4, $v603bd47baf), $pf677bde0); else launch_exception(new \Exception('$action_value["method_obj"] cannot be null and must contain ' . $v603bd47baf . ' method!')); break; case "restconnector": if (is_array($pcf8113cc)) { include_once get_lib("org.phpframework.connector.RestConnector"); $v539082ff30 = isset($pcf8113cc["data"]) ? $pcf8113cc["data"] : null; $pbd9f98de = isset($pcf8113cc["result_type"]) ? $pcf8113cc["result_type"] : null; $v9ad1385268 = \RestConnector::connect($v539082ff30, $pbd9f98de); } else launch_exception(new \Exception('$action_value is not an array with the RestConnector::connect\'s arguments')); break; case "soapconnector": if (is_array($pcf8113cc)) { include_once get_lib("org.phpframework.connector.SoapConnector"); $v539082ff30 = isset($pcf8113cc["data"]) ? $pcf8113cc["data"] : null; $pbd9f98de = isset($pcf8113cc["result_type"]) ? $pcf8113cc["result_type"] : null; $v9ad1385268 = \SoapConnector::connect($v539082ff30, $pbd9f98de); } else launch_exception(new \Exception('$action_value is not an array with the SoapConnector::connect\'s arguments')); break; case "insert": case "update": case "delete": case "select": case "count": case "procedure": case "getinsertedid": $v4c8e54c3e3 = isset($pcf8113cc["dal_broker"]) ? $pcf8113cc["dal_broker"] : null; $v4c8e54c3e3 = $this->f46854f4feb($v4c8e54c3e3, $pee4c7870); $v872f5b4dbb = isset($pcf8113cc["db_driver"]) ? $pcf8113cc["db_driver"] : null; $v872f5b4dbb = $this->f46854f4feb($v872f5b4dbb, $pee4c7870); if (!$v4c8e54c3e3) launch_exception(new \Exception("DAL Broker not selected!")); $pd922c2f7 = $EVC->getBroker($v4c8e54c3e3); if (!$pd922c2f7) launch_exception(new \Exception("Broker '" . $v4c8e54c3e3 . "' does NOT exist!")); $v5d3813882f = isset($pcf8113cc["options"]) ? $pcf8113cc["options"] : null; $v5d3813882f = $this->f46854f4feb($v5d3813882f, $pee4c7870); if ($v872f5b4dbb) { $v5d3813882f = is_array($v5d3813882f) ? $v5d3813882f : ($v5d3813882f ? array($v5d3813882f) : array()); $v5d3813882f["db_driver"] = $v872f5b4dbb; } if ($v256e3a39a7 == "getinsertedid") $v9ad1385268 = $pd922c2f7->getInsertedId($v5d3813882f); else { $pc661dc6b = isset($pcf8113cc["table"]) ? $pcf8113cc["table"] : null; $pc661dc6b = $this->f46854f4feb($pc661dc6b, $pee4c7870); $v3c76382d93 = isset($pcf8113cc["sql"]) ? $pcf8113cc["sql"] : null; if ($pc661dc6b && $v256e3a39a7 != "procedure") { $pfdbbc383 = isset($pcf8113cc["attributes"]) ? $pcf8113cc["attributes"] : null; $pfdbbc383 = $this->f46854f4feb($pfdbbc383, $pee4c7870); $paf1bc6f6 = isset($pcf8113cc["conditions"]) ? $pcf8113cc["conditions"] : null; $paf1bc6f6 = $this->f46854f4feb($paf1bc6f6, $pee4c7870); $ped0a6251 = array(); $pa96c9a0d = array(); if (is_array($pfdbbc383)) foreach ($pfdbbc383 as $v1b0cfa478b) if (!empty($v1b0cfa478b["column"])) { $v5e45ec9bb9 = isset($v1b0cfa478b["name"]) ? $v1b0cfa478b["name"] : null; $pd6321fb0 = isset($v1b0cfa478b["value"]) ? $v1b0cfa478b["value"] : null; $ped0a6251[ $v1b0cfa478b["column"] ] = $v256e3a39a7 == "select" ? $v5e45ec9bb9 : $pd6321fb0; } if (is_array($paf1bc6f6)) foreach ($paf1bc6f6 as $v32dd06ab9b) if (!empty($v32dd06ab9b["column"])) $pa96c9a0d[ $v32dd06ab9b["column"] ] = isset($v32dd06ab9b["value"]) ? $v32dd06ab9b["value"] : null; switch ($v256e3a39a7) { case "insert": $v9ad1385268 = $pd922c2f7->insertObject($pc661dc6b, $ped0a6251, $v5d3813882f); break; case "update": $v9ad1385268 = $pd922c2f7->updateObject($pc661dc6b, $ped0a6251, $pa96c9a0d, $v5d3813882f); break; case "delete": $v9ad1385268 = $pd922c2f7->deleteObject($pc661dc6b, $pa96c9a0d, $v5d3813882f); break; case "select": $v9ad1385268 = $pd922c2f7->findObjects($pc661dc6b, $ped0a6251, $pa96c9a0d, $v5d3813882f); break; case "count": $v9ad1385268 = $pd922c2f7->countObjects($pc661dc6b, $pa96c9a0d, $v5d3813882f); break; } } else if (!$v3c76382d93) launch_exception(new \Exception('Sql cannot be empty for "' . $v256e3a39a7 . '" action!')); else { $v3c76382d93 = $this->f46854f4feb($v3c76382d93, $pee4c7870); if ($v256e3a39a7 == "select" || $v256e3a39a7 == "count" || $v256e3a39a7 == "procedure") { if (is_array($v5d3813882f)) unset($v5d3813882f["return_type"]); $v9ad1385268 = $pd922c2f7->getData($v3c76382d93, $v5d3813882f); $v9ad1385268 = isset($v9ad1385268["result"]) ? $v9ad1385268["result"] : null; if ($v256e3a39a7 == "count") { if ($v9ad1385268 && isset($v9ad1385268[0]) && is_array($v9ad1385268[0])) $v9ad1385268 = array_shift(array_values($v9ad1385268[0])); else $v9ad1385268 = null; } } else $v9ad1385268 = $pd922c2f7->setData($v3c76382d93, $v5d3813882f); } } break; case "show_ok_msg": case "show_ok_msg_and_stop": case "show_ok_msg_and_die": case "show_ok_msg_and_redirect": case "show_error_msg": case "show_error_msg_and_stop": case "show_error_msg_and_die": case "show_error_msg_and_redirect": $pffa799aa = isset($pcf8113cc["message"]) ? $pcf8113cc["message"] : null; $pffa799aa = $this->f46854f4feb($pffa799aa, $pee4c7870); $v23dcb71e04 = strpos($v256e3a39a7, "_ok_") ? $pffa799aa : null; $pef612b9d = strpos($v256e3a39a7, "_error_") ? $pffa799aa : null; $v959a588da6 = strpos($v256e3a39a7, "_redirect") && isset($pcf8113cc["redirect_url"]) ? $this->f46854f4feb($pcf8113cc["redirect_url"], $pee4c7870) : null; $v9ad1385268 = \CommonModuleUI::getModuleMessagesHtml($EVC, $v23dcb71e04, $pef612b9d, $v959a588da6); if (strpos($v256e3a39a7, "_die")) $v61cbe50bc9 = true; else if (strpos($v256e3a39a7, "_stop")) $pa2507f6c = true; break; case "alert_msg": case "alert_msg_and_stop": case "alert_msg_and_redirect": $pffa799aa = isset($pcf8113cc["message"]) ? $pcf8113cc["message"] : null; $pffa799aa = $this->f46854f4feb($pffa799aa, $pee4c7870); $v959a588da6 = strpos($v256e3a39a7, "_redirect") && isset($pcf8113cc["redirect_url"]) ? $this->f46854f4feb($pcf8113cc["redirect_url"], $pee4c7870) : null; $v9ad1385268 = '<script>
						' . ($pffa799aa ? 'alert("' . addcslashes($pffa799aa, '"') . '");' : '') . '
						' . ($v959a588da6 ? 'document.location="' . addcslashes($v959a588da6, '"') . '";' : '') . '
					</script>'; if (strpos($v256e3a39a7, "_stop")) $pa2507f6c = true; break; case "redirect": $v959a588da6 = $this->f46854f4feb($pcf8113cc, $pee4c7870); if ($v959a588da6) $v9ad1385268 = '<script>document.location="' . addcslashes($v959a588da6, '"') . '";</script>'; break; case "refresh": $v9ad1385268 = '<script>var url = document.location; document.location=url;</script>'; break; case "return_previous_record": case "return_next_record": case "return_specific_record": $pe27a6344 = isset($pcf8113cc["records_variable_name"]) ? $pcf8113cc["records_variable_name"] : null; $pe27a6344 = $this->f46854f4feb($pe27a6344, $pee4c7870); $v62e186f8df = !is_array($pe27a6344) ? $pee4c7870[$pe27a6344] : $pe27a6344; if (is_array($v62e186f8df)) { $pe75dcf6f = isset($pcf8113cc["index_variable_name"]) ? $pcf8113cc["index_variable_name"] : null; $pe75dcf6f = $this->f46854f4feb($pe75dcf6f, $pee4c7870); $pd4a3d431 = is_numeric($pe75dcf6f) ? $pe75dcf6f : $_GET[$pe75dcf6f]; $pd4a3d431 = is_numeric($pd4a3d431) ? $pd4a3d431 : 0; if ($v256e3a39a7 == "return_previous_record") $pd4a3d431--; else if ($v256e3a39a7 == "return_next_record") $pd4a3d431++; $v9ad1385268 = $v62e186f8df[$pd4a3d431]; } break; case "check_logged_user_permissions": $pe201cb4b = isset($pcf8113cc["all_permissions_checked"]) ? $pcf8113cc["all_permissions_checked"] : null; $pe201cb4b = $this->f46854f4feb($pcf8113cc["all_permissions_checked"], $pee4c7870); $pa929f3e8 = isset($pcf8113cc["users_perms"]) ? $pcf8113cc["users_perms"] : null; $v93dda00907 = isset($pcf8113cc["entity_path"]) ? $pcf8113cc["entity_path"] : null; $pe569ae33 = isset($pcf8113cc["logged_user_id"]) ? $pcf8113cc["logged_user_id"] : null; $v9ad1385268 = $this->me7b206ef8f17($v93dda00907, $pe569ae33, $pa929f3e8, $pe201cb4b); break; case "code": $v368e4322b2 = array(); $pcf8113cc = $this->f46854f4feb($pcf8113cc, $pee4c7870, false); $v9ad1385268 = \PHPScriptHandler::parseContent($pcf8113cc, $pee4c7870, $v368e4322b2); if (isset($v368e4322b2[0]) && $v368e4322b2[0] !== false) $v9ad1385268 = $v368e4322b2[0]; break; case "sanitize_variable": if (!$this->v37e6ee1556) include_once get_lib("org.phpframework.util.web.html.XssSanitizer"); $this->v37e6ee1556 = true; $v9ad1385268 = $this->f46854f4feb($pcf8113cc, $pee4c7870); $v9ad1385268 = \XssSanitizer::sanitizeVariable($v9ad1385268); break; case "list_report": $v3fb9f41470 = isset($pcf8113cc["type"]) ? $pcf8113cc["type"] : null; $v28c1cd997a = isset($pcf8113cc["continue"]) ? $pcf8113cc["continue"] : null; $pe86cbe92 = isset($pcf8113cc["doc_name"]) ? $pcf8113cc["doc_name"] : null; $v847e7d0a83 = isset($pcf8113cc["variable"]) ? $pcf8113cc["variable"] : null; $pc2cdfd1b = $this->f46854f4feb($v847e7d0a83, $pee4c7870); $v1e71ed36f0 = $v3fb9f41470 == "xls" ? "application/vnd.ms-excel" : ($v3fb9f41470 == "csv" ? "text/csv" : "text/plain"); header("Content-Type: $v1e71ed36f0"); header('Content-Disposition: attachment; filename="' . $pe86cbe92 . '.' . $v3fb9f41470 . '"'); $v327f72fb62 = ""; if ($pc2cdfd1b && is_array($pc2cdfd1b) && count($pc2cdfd1b)) { $v8170d6ac3b = $pc2cdfd1b[ array_keys($pc2cdfd1b)[0] ]; if (is_array($v8170d6ac3b)) { $v223002cb02 = array_keys($v8170d6ac3b); $v7c3bb5ff4b = count($v223002cb02); $paf010fc8 = "\n"; $v86e289a4f9 = "\t"; $v7dd61acb7f = ""; if ($v3fb9f41470 == "csv") { $v86e289a4f9 = ","; $v7dd61acb7f = '"'; $v327f72fb62 .= "sep=$v86e289a4f9$paf010fc8"; } for ($v43dd7d0051 = 0; $v43dd7d0051 < $v7c3bb5ff4b; $v43dd7d0051++) $v327f72fb62 .= ($v43dd7d0051 > 0 ? $v86e289a4f9 : "") . $v7dd61acb7f . addcslashes($v223002cb02[$v43dd7d0051], $v86e289a4f9 . $v7dd61acb7f . "\\") . $v7dd61acb7f; if ($v327f72fb62) { $v327f72fb62 .= $paf010fc8; foreach ($pc2cdfd1b as $pff59654a) if (is_array($pff59654a)) { for ($v43dd7d0051 = 0; $v43dd7d0051 < $v7c3bb5ff4b; $v43dd7d0051++) { $v30de06e58b = isset($pff59654a[ $v223002cb02[$v43dd7d0051] ]) ? $pff59654a[ $v223002cb02[$v43dd7d0051] ] : null; $v327f72fb62 .= ($v43dd7d0051 > 0 ? $v86e289a4f9 : "") . $v7dd61acb7f . addcslashes($v30de06e58b, $v86e289a4f9 . $v7dd61acb7f . "\\") . $v7dd61acb7f; } $v327f72fb62 .= $paf010fc8; } } } } $v9ad1385268 = $v327f72fb62; if ($v28c1cd997a == "die") $v61cbe50bc9 = true; else if ($v28c1cd997a == "stop") $pa2507f6c = true; break; case "call_block": $peebaaf55 = isset($pcf8113cc["block"]) ? trim($pcf8113cc["block"]) : ""; $v93756c94b3 = isset($pcf8113cc["project"]) ? trim($pcf8113cc["project"]) : ""; $v9ad1385268 = $peebaaf55 ? $this->f1bf30be701($peebaaf55, $v93756c94b3) : ""; break; case "call_view": $pa36e00ea = isset($pcf8113cc["view"]) ? trim($pcf8113cc["view"]) : ""; $v93756c94b3 = isset($pcf8113cc["project"]) ? trim($pcf8113cc["project"]) : ""; $v9ad1385268 = $pa36e00ea ? $this->f34a6079b5c($pa36e00ea, $v93756c94b3) : ""; break; case "include_file": $pa32be502 = isset($pcf8113cc["path"]) ? trim($pcf8113cc["path"]) : ""; $pa32be502 = $this->f46854f4feb($pa32be502, $pee4c7870); if ($pa32be502) { $v4616a9abfd = !empty($pcf8113cc["once"]); $v067674f4e4 = 'include' . ($v4616a9abfd ? '_once' : '') . ' "' . addcslashes($pa32be502, '\\"') . '";'; $v9ad1385268 = eval($v067674f4e4); } break; case "draw_graph": if (is_array($pcf8113cc)) { if (array_key_exists("code", $pcf8113cc)) { $v368e4322b2 = array(); $v067674f4e4 = isset($pcf8113cc["code"]) ? $pcf8113cc["code"] : null; $v067674f4e4 = $this->f46854f4feb($v067674f4e4, $pee4c7870, false); $v9ad1385268 = \PHPScriptHandler::parseContent($v067674f4e4, $pee4c7870, $v368e4322b2); if (isset($v368e4322b2[0]) && $v368e4322b2[0] !== false) $v9ad1385268 = $v368e4322b2[0]; } else { $v01cdaad323 = isset($pcf8113cc["include_graph_library"]) ? $pcf8113cc["include_graph_library"] : null; $v607a49cf36 = isset($pcf8113cc["width"]) ? $pcf8113cc["width"] : null; $v3a0455afd7 = isset($pcf8113cc["height"]) ? $pcf8113cc["height"] : null; $pdfb8dd62 = isset($pcf8113cc["labels_variable"]) ? $pcf8113cc["labels_variable"] : null; $v01cdaad323 = $this->f46854f4feb($v01cdaad323, $pee4c7870); $v607a49cf36 = $this->f46854f4feb($v607a49cf36, $pee4c7870); $v3a0455afd7 = $this->f46854f4feb($v3a0455afd7, $pee4c7870); $pdfb8dd62 = $this->f46854f4feb($pdfb8dd62, $pee4c7870); $v6e7400d6c4 = ''; $v260dc30551 = null; if (!empty($pcf8113cc["data_sets"])) { $pb2a355f5 = $pcf8113cc["data_sets"]; if (isset($pb2a355f5["values_variable"])) $pb2a355f5 = array($pb2a355f5); $pef157974 = array( "values_variable" => "data", "item_label" => "label", "background_colors" => "backgroundColor", "border_colors" => "borderColor", "border_width" => "borderWidth" ); foreach ($pb2a355f5 as $pa5d1f26e) { if ($pa5d1f26e) { $v18d503df65 = array(); $v2e62ccf0b7 = array(); foreach ($pa5d1f26e as $pbfa01ed1 => $v67db1bd535) { $pbfa01ed1 = preg_replace("/(^\.+|\.+$)/", "", preg_replace("/\s*/", "", $pbfa01ed1)); if (strpos($pbfa01ed1, ".") !== false) { $v9cd205cadb = explode(".", $pbfa01ed1); $v682fcfa9ea = &$v18d503df65; $peb9a1267 = &$v2e62ccf0b7; for ($v43dd7d0051 = 0, $pc37695cb = count($v9cd205cadb); $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v1d2d80ed32 = $v9cd205cadb[$v43dd7d0051]; if ($v1d2d80ed32 || is_numeric($v1d2d80ed32)) { if ($v43dd7d0051 + 1 == $pc37695cb) $v682fcfa9ea[$v1d2d80ed32] = $v67db1bd535; else { if (!is_array($v682fcfa9ea[$v1d2d80ed32])) { $v682fcfa9ea[$v1d2d80ed32] = array(); $peb9a1267[$v1d2d80ed32] = array(); } $v682fcfa9ea = &$v682fcfa9ea[$v1d2d80ed32]; $peb9a1267 = &$peb9a1267[$v1d2d80ed32]; } } } } else $v18d503df65[$pbfa01ed1] = $v67db1bd535; } $pecd05ed1 = ''; foreach ($v18d503df65 as $pbfa01ed1 => $v67db1bd535) { if (is_array($v67db1bd535) && is_array($v2e62ccf0b7) && array_key_exists($pbfa01ed1, $v2e62ccf0b7)) { $pecd05ed1 .= ($pecd05ed1 ? ",\n                 " : "") . $pbfa01ed1 . ': {'; $pecd05ed1 .= $this->mf6d4b2925c4c($v67db1bd535, $v2e62ccf0b7[$pbfa01ed1], $pee4c7870, "                     "); $pecd05ed1 .= ($pecd05ed1 ? "\n              	   " : "") . '}'; } else { $v67db1bd535 = $this->f46854f4feb($v67db1bd535, $pee4c7870); if ($pbfa01ed1) { $pe1d32923 = !empty($pef157974[$pbfa01ed1]) ? $pef157974[$pbfa01ed1] : $pbfa01ed1; $v0ff021f094 = !empty($v67db1bd535) || is_numeric($v67db1bd535) || !isset($pef157974[$pbfa01ed1]); if ($pbfa01ed1 == "type") { if (!$v260dc30551) $v260dc30551 = $v67db1bd535; $v0ff021f094 = $v0ff021f094 && $v67db1bd535 != $v260dc30551; } else if ($pbfa01ed1 == "border_width") $v0ff021f094 = $v0ff021f094 || is_numeric($v67db1bd535); if ($v0ff021f094) $pecd05ed1 .= ($pecd05ed1 ? ",\n                 " : "") . $pe1d32923 . ': ' . json_encode($v67db1bd535); } } } $v6e7400d6c4 .= '
		     {
		         ' . $pecd05ed1 . '
		     },'; } } } $v93ff269092 = rand(0, 1000); $v9ad1385268 = ''; if ($v01cdaad323 == "cdn_even_if_exists") $v9ad1385268 .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>' . "\n\n"; else if ($v01cdaad323 == "cdn_if_not_exists") $v9ad1385268 .= '<script>
if (typeof Chart != "function")
	document.write(\'<scr\' + \'ipt src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></scr\' + \'ipt>\');
</script>' . "\n\n"; $v9ad1385268 .= '<canvas id="my_chart_' . $v93ff269092 . '"' . (is_numeric($v607a49cf36) ? ' width="' . $v607a49cf36 . '"' : '') . (is_numeric($v3a0455afd7) ? ' height="' . $v3a0455afd7 . '"' : '') . '></canvas>

<script>
var canvas = document.getElementById("my_chart_' . $v93ff269092 . '");
var myChart_' . $v93ff269092 . ' = new Chart(canvas, {
    type: "' . $v260dc30551 . '",
    data: {
        ' . ($pdfb8dd62 ? 'labels: ' . json_encode($pdfb8dd62) . ',' : '') . '
        datasets: [' . $v6e7400d6c4 . '
        ]
    }
});
</script>'; } } break; case "loop": $pe27a6344 = isset($pcf8113cc["records_variable_name"]) ? $pcf8113cc["records_variable_name"] : null; $pe27a6344 = $this->f46854f4feb($pe27a6344, $pee4c7870); $v62e186f8df = !is_array($pe27a6344) ? $pee4c7870[$pe27a6344] : $pe27a6344; if (is_array($v62e186f8df)) { $v83cf462ef8 = isset($pcf8113cc["records_start_index"]) ? $pcf8113cc["records_start_index"] : null; $pf5ee153c = isset($pcf8113cc["records_end_index"]) ? $pcf8113cc["records_end_index"] : null; $pe0489235 = isset($pcf8113cc["array_item_key_variable_name"]) ? $pcf8113cc["array_item_key_variable_name"] : null; $v3f883a2957 = isset($pcf8113cc["array_item_value_variable_name"]) ? $pcf8113cc["array_item_value_variable_name"] : null; $v83cf462ef8 = $this->f46854f4feb($v83cf462ef8, $pee4c7870); $pf5ee153c = $this->f46854f4feb($pf5ee153c, $pee4c7870); $pe0489235 = $this->f46854f4feb($pe0489235, $pee4c7870); $v3f883a2957 = $this->f46854f4feb($v3f883a2957, $pee4c7870); $v83cf462ef8 = is_numeric($v83cf462ef8) ? $v83cf462ef8 : 0; $pf5ee153c = is_numeric($pf5ee153c) ? $pf5ee153c : count($v62e186f8df); $v5cc0d0b311 = isset($pcf8113cc["actions"]) ? $pcf8113cc["actions"] : null; $v9ad1385268 = ''; $v43dd7d0051 = 0; foreach ($v62e186f8df as $pe5c5e2fe => $v956913c90f) { if ($v43dd7d0051 >= $pf5ee153c || $pa2507f6c) break; else if ($v43dd7d0051 >= $v83cf462ef8) { $pee4c7870[ $pe0489235 ] = $pe5c5e2fe; $pee4c7870[ $v3f883a2957 ] = $v956913c90f; $v9ad1385268 .= $this->f67598304bc($v5cc0d0b311, $pee4c7870, $pa2507f6c, $v61cbe50bc9); } ++$v43dd7d0051; } } break; case "group": $v5cc0d0b311 = isset($pcf8113cc["actions"]) ? $pcf8113cc["actions"] : null; $v92d5aae0e2 = $pee4c7870; $v9ad1385268 = $this->f67598304bc($v5cc0d0b311, $v92d5aae0e2, $pa2507f6c, $v61cbe50bc9); $pfc00b2ed = isset($pcf8113cc["group_name"]) ? $pcf8113cc["group_name"] : null; $pfc00b2ed = $this->f46854f4feb($pfc00b2ed, $pee4c7870); if ($pfc00b2ed) { $v188524da88 = array_diff_key($v92d5aae0e2, $pee4c7870); $pee4c7870 = array_intersect_key($v92d5aae0e2, $pee4c7870); $pee4c7870[$pfc00b2ed] = $v188524da88; } else $pee4c7870 = $v92d5aae0e2; break; default: $v9ad1385268 = $this->f46854f4feb($pcf8113cc, $pee4c7870); } } return $v9ad1385268; } private function mf6d4b2925c4c($v18d503df65, $v2e62ccf0b7, $pee4c7870, $pdcf670f6) { $v7d0e1423bc = ""; if ($v18d503df65) foreach ($v18d503df65 as $pbfa01ed1 => $v67db1bd535) { $v7d0e1423bc .= ($v7d0e1423bc ? "," : "") . "\n$pdcf670f6$pbfa01ed1: "; if (is_array($v67db1bd535) && is_array($v2e62ccf0b7) && array_key_exists($pbfa01ed1, $v2e62ccf0b7)) { $v7d0e1423bc .= '{'; $v7d0e1423bc .= $this->mf6d4b2925c4c($v67db1bd535[$pbfa01ed1], $v2e62ccf0b7[$pbfa01ed1], $pee4c7870, $pdcf670f6 . "    "); $v7d0e1423bc .= "\n$pdcf670f6}"; } else { $v67db1bd535 = $this->f46854f4feb($v67db1bd535, $pee4c7870); $v7d0e1423bc .= json_encode($v67db1bd535); } } return $v7d0e1423bc; } private function mcdc2cbc34b3c($pcf8113cc, &$v539082ff30) { $pa32be502 = isset($pcf8113cc["include_file_path"]) ? trim($pcf8113cc["include_file_path"]) : ""; $pa32be502 = $this->f46854f4feb($pa32be502, $v539082ff30); if ($pa32be502) { $v4616a9abfd = !empty($pcf8113cc["include_once"]); if ($v4616a9abfd) include_once $pa32be502; else include $pa32be502; } } private function f46854f4feb($v67db1bd535, &$v539082ff30, $pbd14d28d = true) { if (is_array($v67db1bd535)) foreach ($v67db1bd535 as $pbfa01ed1 => $v342a134247) $v67db1bd535[$pbfa01ed1] = $this->f46854f4feb($v342a134247, $v539082ff30); else if ($v67db1bd535 && is_string($v67db1bd535)) { if ($pbd14d28d && $v67db1bd535 && is_string($v67db1bd535) && strpos($v67db1bd535, '$') !== false) { $v067674f4e4 = '<?= "' . addcslashes($v67db1bd535, '"') . '" ?>'; $v67db1bd535 = \PHPScriptHandler::parseContent($v067674f4e4, $v539082ff30); } if (strpos($v67db1bd535, '#') !== false) { $pfc216969 = new \HtmlFormHandler(); $v67db1bd535 = $pfc216969->getParsedValueFromData($v67db1bd535, $v539082ff30); } if ($pbd14d28d && $v67db1bd535 && is_string($v67db1bd535) && strpos($v67db1bd535, '$') !== false) { $v067674f4e4 = '<?= "' . addcslashes($v67db1bd535, '"') . '" ?>'; $v67db1bd535 = \PHPScriptHandler::parseContent($v067674f4e4, $v539082ff30); } } return $v67db1bd535; } private function me7b206ef8f17($v93dda00907, $pe569ae33, $pa929f3e8, $pe201cb4b) { if (!$pa929f3e8) return true; $v90a8c6325d = false; $pabd24755 = array(); foreach ($pa929f3e8 as $v74b37bf978) if (isset($v74b37bf978["user_type_id"]) && $v74b37bf978["user_type_id"] == \UserUtil::PUBLIC_USER_TYPE_ID) { $v90a8c6325d = true; break; } else $pabd24755[] = $v74b37bf978; if ($v90a8c6325d && !$pe201cb4b) return true; if (!$pe569ae33) return false; if (!$pabd24755) return true; $pa929f3e8 = $pabd24755; $v0a035c60aa = \ObjectUtil::PAGE_OBJECT_TYPE_ID; $v3fab52f440 = $v93dda00907; if (!$v3fab52f440) return false; $v3fab52f440 = str_replace(APP_PATH, "", $v3fab52f440); $v3fab52f440 = \HashCode::getHashCodePositive($v3fab52f440); $pc4223ce1 = $this->getEVC()->getBrokers(); $v983ecfde45 = \UserUtil::getUserTypeActivityObjectsByUserIdAndConditions($pc4223ce1, $pe569ae33, array( "object_type_id" => $v0a035c60aa, "object_id" => $v3fab52f440 ), null); if (!$v983ecfde45) return false; $pb6c3ef6f = false; $v9ad1385268 = true; foreach ($pa929f3e8 as $v74b37bf978) if (isset($v74b37bf978["user_type_id"]) && isset($v74b37bf978["activity_id"]) && is_numeric($v74b37bf978["user_type_id"]) && is_numeric($v74b37bf978["activity_id"])) { if (!$pb6c3ef6f && !$pe201cb4b) $v9ad1385268 = false; $pb6c3ef6f = true; $v1ad68c9c0e = false; foreach ($v983ecfde45 as $pbe30595e) if (isset($pbe30595e["user_type_id"]) && isset($pbe30595e["activity_id"]) && $pbe30595e["user_type_id"] == $v74b37bf978["user_type_id"] && $pbe30595e["activity_id"] == $v74b37bf978["activity_id"]) { $v1ad68c9c0e = true; break; } if ($pe201cb4b && !$v1ad68c9c0e) return false; else if (!$pe201cb4b && $v1ad68c9c0e) return true; } return $v9ad1385268; } private function f1bf30be701($peebaaf55, $v93756c94b3) { $EVC = $this->getEVC(); $pb377e11f = $EVC->getBlockPath($peebaaf55, $v93756c94b3); if (file_exists($pb377e11f)) { $v13e76aba2d = $EVC->getConfigPath("config"); if (file_exists($v13e76aba2d)) include $v13e76aba2d; @include_once $EVC->getModulePath("translator/include_text_translator_handler", $EVC->getCommonProjectName()); $v09d6b9e2f6 = array(); include $pb377e11f; return $EVC->getCMSLayer()->getCMSBlockLayer()->getCurrentBlock(); } return ""; } private function f34a6079b5c($pa36e00ea, $v93756c94b3) { $v08d9602741 = $this->getEVC(); $v190190a02d = $v08d9602741->getViewPath($pa36e00ea, $v93756c94b3); if (file_exists($v190190a02d)) { $v13e76aba2d = $v08d9602741->getConfigPath("config"); if (file_exists($v13e76aba2d)) include $v13e76aba2d; @include_once $v08d9602741->getModulePath("translator/include_text_translator_handler", $v08d9602741->getCommonProjectName()); ob_start(null, 0); include $v190190a02d; $v0eeba1b284 = ob_get_contents(); ob_end_clean(); return $v0eeba1b284; } return ""; } } ?>
