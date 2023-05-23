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

 include_once $EVC->getUtilPath("CodeResultGuesser"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $class_path = $_GET["class_path"]; $class_name = $_GET["class_name"]; $method = $_GET["method"]; $db_driver = $_GET["db_driver"]; $class_path = str_replace("../", "", $class_path);$path = str_replace("../", "", $path); if ($class_name && $method) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($PEVC) { $PHPVariablesFileHandler = new PHPVariablesFileHandler(array($user_global_variables_file_path, $PEVC->getConfigPath("pre_init_config"))); $PHPVariablesFileHandler->startUserGlobalVariables(); $P = $PEVC->getPresentationLayer(); $layer_path = $P->getLayerPathSetting(); $selected_project_id = $P->getSelectedPresentationId(); $default_extension = "." . $P->getPresentationFileExtension(); $folder_path = $layer_path . $selected_project_id . "/"; if (!$class_path && preg_match("/ResourceUtil$/", $class_name)) $class_path = $PEVC->getUtilPath("resource/$class_name"); else $class_path = $folder_path . $class_path; if (!file_exists($class_path)) $class_path = findsClassPath($folder_path, $class_name); if (file_exists($class_path)) { $code = PHPCodePrintingHandler::getFunctionCodeFromFile($class_path, $method, $class_name); if ($code) { $db_driver = $db_driver ? $db_driver : $GLOBALS["default_db_driver"]; $CodeResultGuesser = new CodeResultGuesser($P, $UserAuthenticationHandler, $user_global_variables_file_path, $user_beans_folder_path, $project_url_prefix, $db_driver); $props = $CodeResultGuesser->getCodeResultAttributes($code); } } $PHPVariablesFileHandler->endUserGlobalVariables(); } } function findsClassPath($pdd397f0a, $v1335217393) { if ($pdd397f0a && is_dir($pdd397f0a)) { $v6ee393d9fb = array_diff(scandir($pdd397f0a), array('..', '.')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pf3dc0762 = $pdd397f0a . $v7dffdb5a5b; if (!is_dir($pf3dc0762) && pathinfo($v7dffdb5a5b, PATHINFO_FILENAME) == $v1335217393) return $pf3dc0762; } foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pf3dc0762 = $pdd397f0a . $v7dffdb5a5b; if (is_dir($pf3dc0762)) { $v50890f6f30 = findsClassPath($pf3dc0762 . "/", $v1335217393); if ($v50890f6f30) return $v50890f6f30; } } } return null; } ?>
