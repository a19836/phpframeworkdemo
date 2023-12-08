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
 include_once get_lib("org.phpframework.phpscript.docblock.DocBlockParser"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("CodeResultGuesser"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $module_id = $_GET["module_id"]; $service_id = $_GET["service"]; $db_driver = $_GET["db_driver"]; $path = str_replace(".", "/", $module_id); $path = str_replace("../", "", $path); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "BusinessLogicLayer") && $path) { $layer_path = $obj->getLayerPathSetting(); $class_name = null; $file_path = null; if (($pos = strpos($service_id, ".")) !== false) { $class_name = substr($service_id, 0, $pos); $method_name = substr($service_id, $pos + 1); } else $method_name = $service_id; if ($class_name) $file_path = $layer_path . $path . "/" . $class_name . ".php"; else { $folder_path = $layer_path . $path; if (is_dir($folder_path)) { $files = array_diff(scandir($folder_path), array('..', '.')); foreach ($files as $file_name) { $fp = "$folder_path/$file_name"; if (!is_dir($fp) && strtolower(pathinfo($fp, PATHINFO_EXTENSION)) == "php" && PHPCodePrintingHandler::getFunctionFromFile($fp, $service_id)) { $file_path = $fp; break; } } } } if ($file_path && file_exists($file_path)) { $code = PHPCodePrintingHandler::getFunctionCodeFromFile($file_path, $method_name, $class_name); if ($code) { $db_driver = $db_driver ? $db_driver : $GLOBALS["default_db_driver"]; $CodeResultGuesser = new CodeResultGuesser($obj, $UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $project_url_prefix, $db_driver); $props = $CodeResultGuesser->getCodeResultAttributes($code); } if (!$props) { $get_service_possible_names = array("get", "getall", "gets"); $methods_to_search = array("insert"); $methods_to_search_second = array("update"); $mnl = strtolower($method_name); if ($class_name) { $class_name_parsed = substr($class_name, 0, - strlen("Service")); $cnpl = strtolower($class_name_parsed); $get_service_possible_names[] = "get$cnpl"; $get_service_possible_names[] = "get{$cnpl}s"; $get_service_possible_names[] = "getall{$cnpl}s"; $methods_to_search[] = "insert$class_name_parsed"; $methods_to_search_second[] = "update$class_name_parsed"; } $methods_to_search = array_merge($methods_to_search, $methods_to_search_second); if (in_array($mnl, $get_service_possible_names)) { $bean_objs = $obj->getPHPFrameWork()->getObjects(); $vars = is_array($bean_objs["vars"]) ? array_merge($bean_objs["vars"], $obj->settings) : $obj->settings; $vars["current_business_logic_module_path"] = $file_path; $vars["current_business_logic_module_id"] = $module_id; include_once $file_path; $DocBlockParser = new DocBlockParser(); if ($class_name) { $classes = PHPCodePrintingHandler::getPHPClassesFromFile($file_path); $found_class_data = PHPCodePrintingHandler::searchClassFromPHPClasses($classes, $class_name); if ($found_class_data) { $found_class_name = PHPCodePrintingHandler::prepareClassNameWithNameSpace($found_class_data["name"], $found_class_data["namespace"]); $class_name = $found_class_name; } } foreach ($methods_to_search as $method_to_search) { $method = PHPCodePrintingHandler::getFunctionFromFile($file_path, $method_to_search, $class_name); if ($method) { if ($class_name) $DocBlockParser->ofMethod($class_name, $method_to_search); else $DocBlockParser->ofFunction($service_id); $params = $DocBlockParser->getTagParams(); $is_multiple = preg_match("/\.(gets|getall)/i", $service_id) || preg_match("/s$/i", $service_id); $props = array( "attributes" => array(), "is_multiple" => $is_multiple, ); $t = count($params); $repeated = array(); for ($i = 0; $i < $t; $i++) { $param = $params[$i]; $args = $param->getArgs(); $name = !empty($args["name"]) ? $args["name"] : (isset($args["index"]) ? $args["index"] : $i); if ($name) { if (strpos($name, "[") !== false) { preg_match_all("/^([^\[]*)\[([^\[]*)\]/u", $name, $matches, PREG_PATTERN_ORDER); if ($matches[0]) $name = $matches[2][0]; } if ($name && !in_array($name, $repeated)) { $props["attributes"][] = array( "column" => $name ); $repeated[] = $name; } } } break; } } } } } } $PHPVariablesFileHandler->endUserGlobalVariables(); ?>
