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

include_once get_lib("org.phpframework.db.DB"); include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $db_broker = $_GET["db_broker"]; $db_driver = $_GET["db_driver"]; $data = $_POST["obj"]; if ($data) { $path = str_replace("../", "", $path); $PHPVariablesFileHandler = new PHPVariablesFileHandler($user_global_variables_file_path); $PHPVariablesFileHandler->startUserGlobalVariables(); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); if ($item_type != "presentation") $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); else $obj = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); if ($obj) { if (is_a($obj, "DB")) $sql = $obj->convertObjectToSQL($data); else { $broker = $obj->getBroker($db_broker); if (is_a($broker, "IDBBrokerClient") || is_a($broker, "IDataAccessBrokerClient")) $sql = $broker->getFunction("convertObjectToSQL", array($data), array("db_driver" => $db_driver)); else { $layers = WorkFlowBeansFileHandler::getLocalBeanLayersFromBrokers($user_global_variables_file_paths, $user_beans_folder_path, $obj->getBrokers(), true); foreach ($layers as $layer_bean_name => $layer_obj) if (is_a($layer_obj, "DBLayer") || is_a($layer_obj, "DataAccessLayer")) { $sql = $layer_obj->getFunction("convertObjectToSQL", array($data), array("db_driver" => $db_driver)); break; } } } } else $sql = DB::convertObjectToDefaultSQL($data); } ?>
