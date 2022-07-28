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

include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $broker = $_GET["broker"]; $item_type = $_GET["item_type"]; $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); if ($obj && is_a($obj, "Layer")) { if ($item_type == "db" || $item_type == "ibatis" || $item_type == "hibernate") $db_drivers = WorkFlowBeansFileHandler::getLayerDBDrivers($user_global_variables_file_path, $user_beans_folder_path, $obj, true); else { $b = $obj->getBroker($broker); $db_drivers = $broker ? WorkFlowBeansFileHandler::getBrokersDBDrivers($user_global_variables_file_path, $user_beans_folder_path, array($broker => $b), true) : array(); } if ($db_drivers) foreach ($db_drivers as $db_driver_name => $db_driver_props) { if ($item_type == "db" || $item_type == "ibatis" || $item_type == "hibernate") $found_broker_name = WorkFlowBeansFileHandler::getLayerLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, $obj, $db_driver_name, $found_broker_obj, $found_broker_props); else $found_broker_name = WorkFlowBeansFileHandler::getBrokersLocalDBBrokerNameForChildBrokerDBDriver($user_global_variables_file_path, $user_beans_folder_path, array($broker => $b), $db_driver_name, $found_broker_obj, $found_broker_props); if ($found_broker_props) { $layer_props = WorkFlowBeansFileHandler::getLocalBeanLayerFromBroker($user_global_variables_file_path, $user_beans_folder_path, $found_broker_obj); $layer_obj = $layer_props[2]; $layer_object_id = LAYER_PATH . WorkFlowBeansFileHandler::getLayerObjFolderName($layer_obj) . "/" . $db_driver_props[2]; if (!$UserAuthenticationHandler->isInnerFilePermissionAllowed($layer_object_id, "layer", "access")) unset($db_drivers[$db_driver_name]); } } } ?>
