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

include_once get_lib("org.phpframework.object.ObjTypeHandler"); include_once get_lib("org.phpframework.phpscript.docblock.DocBlockParser"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $service_id = $_GET["service"]; $path = str_replace("../", "", $path); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "BusinessLogicLayer")) { $layer_path = $obj->getLayerPathSetting(); $file_path = $layer_path . $path; if ($path && file_exists($file_path)) { $bean_objs = $obj->getPHPFrameWork()->getObjects(); $vars = is_array($bean_objs["vars"]) ? array_merge($bean_objs["vars"], $obj->settings) : $obj->settings; $vars["current_business_logic_module_path"] = $file_path; $vars["current_business_logic_module_id"] = substr($path, 0, strlen($path) - 4); include_once $file_path; $DocBlockParser = new DocBlockParser(); if (($pos = strpos($service_id, ".")) !== false) { $class_name = substr($service_id, 0, $pos); $method_name = substr($service_id, $pos + 1); $classes = PHPCodePrintingHandler::getPHPClassesFromFile($file_path); $found_class_data = PHPCodePrintingHandler::searchClassFromPHPClasses($classes, $class_name); if ($found_class_data) { $found_class_name = PHPCodePrintingHandler::prepareClassNameWithNameSpace($found_class_data["name"], $found_class_data["namespace"]); $class_name = $found_class_name; } $DocBlockParser->ofMethod($class_name, $method_name); } else $DocBlockParser->ofFunction($service_id); $params = $DocBlockParser->getTagParams(); $numeric_types = array_merge(ObjTypeHandler::getPHPNumericTypes(), ObjTypeHandler::getDBNumericTypes()); $props = array(); $t = count($params); for ($i = 0; $i < $t; $i++) { $param = $params[$i]; $args = $param->getArgs(); $name = !empty($args["name"]) ? $args["name"] : (isset($args["index"]) ? $args["index"] : $i); $type = $args["type"]; if ($name) { if (strpos($name, "[") !== false) { preg_match_all("/^([^\[]*)\[([^\[]*)\]/u", $name, $matches, PREG_PATTERN_ORDER); if ($matches[0]) $name = $matches[2][0]; } if ($name && !isset($props[$name])) $props[$name] = $type && !in_array($type, $numeric_types) ? "string" : ""; } } } } $PHPVariablesFileHandler->endUserGlobalVariables(); ?>
