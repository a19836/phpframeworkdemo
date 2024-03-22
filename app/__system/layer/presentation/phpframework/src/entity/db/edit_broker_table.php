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
include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $db_broker = $_GET["db_broker"]; $db_driver = $_GET["db_driver"]; $db_table = $_GET["db_table"]; $path = str_replace("../", "", $path);$ok = false; if ($bean_name && $bean_file_name && $db_driver) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "Layer")) { $broker_obj = $obj->getBroker($db_broker); if (!$db_broker && $broker_obj) { $brokers = $obj->getBrokers(); if ($brokers) foreach($brokers as $broker_name => $broker) if ($broker == $broker_obj) { $db_broker = $broker_name; break; } } if ($db_broker && $broker_obj) { $broker_name = WorkFlowBeansFileHandler::getBrokersLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, array($db_broker => $broker_obj), $db_driver, $found_broker_obj, $found_broker_props); if ($broker_name && $found_broker_props && $found_broker_obj) { $db_layer_props = WorkFlowBeansFileHandler::getLocalBeanLayerFromBroker($global_variables_file_path, $beans_folder_path, $found_broker_obj); $db_layer_bean_name = $db_layer_props[0]; $db_layer_bean_file_name = $db_layer_props[1]; $db_layer_obj = $db_layer_props[2]; if ($db_layer_obj) { $db_layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerObjFolderName($db_layer_obj); $db_driver_props = WorkFlowBeansFileHandler::getLayerDBDriverProps($user_global_variables_file_path, $user_beans_folder_path, $db_layer_obj, $db_driver); if ($db_driver_props) { $ok = true; $_GET["layer_bean_folder_name"] = $db_layer_bean_folder_name; $_GET["bean_name"] = $db_driver_props[2]; $_GET["bean_file_name"] = $db_driver_props[1]; $_GET["table"] = $_GET["table"] ? $_GET["table"] : $db_table; unset($_GET["path"]); unset($_GET["db_broker"]); unset($_GET["db_driver"]); unset($_GET["db_type"]); unset($_GET["db_table"]); } } } } } if ($ok) { if ($_POST) $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); include $EVC->getEntityPath("db/edit_table"); } else $error_message = "Invalid DB Broker or Driver"; } else $error_message = "Invalid params"; ?>
