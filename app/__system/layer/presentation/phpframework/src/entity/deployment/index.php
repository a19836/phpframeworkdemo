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
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskFolders(array("deployment/", "layer/")); $containers = array( "layer_presentations" => array("presentation"), "layer_bls" => array("businesslogic"), "layer_dals" => array("dataaccess"), "layer_dbs" => array("db"), "layer_drivers" => array("dbdriver"), ); $SubWorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $SubWorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $SubWorkFlowTaskHandler->setAllowedTaskFolders(array("layer/")); $SubWorkFlowTaskHandler->setTasksContainers($containers); $workflow_path_id = "deployment"; $layer_brokers_settings = WorkFlowTestUnitHandler::getAllLayersBrokersSettings($user_global_variables_file_path, $user_beans_folder_path); $db_brokers = $layer_brokers_settings["db_brokers"]; $ibatis_brokers = $layer_brokers_settings["ibatis_brokers"]; $hibernate_brokers = $layer_brokers_settings["hibernate_brokers"]; $data_access_brokers = $layer_brokers_settings["data_access_brokers"]; $business_logic_brokers = $layer_brokers_settings["business_logic_brokers"]; $presentation_brokers = $layer_brokers_settings["presentation_brokers"]; $beans_folders_name = array( "dao" => substr(DAO_PATH, strlen(CMS_PATH)), "lib" => substr(LIB_PATH, strlen(CMS_PATH)), "vendor" => substr(VENDOR_PATH, strlen(CMS_PATH)), "test_unit" => substr(TEST_UNIT_PATH, strlen(CMS_PATH)), ); $relative_layers_path = substr(LAYER_PATH, strlen(CMS_PATH)); foreach ($layer_brokers_settings as $k => $layer_brokers) if ($k == "data_access_brokers" || $k == "business_logic_brokers" || $k == "presentation_brokers") { $t = count($layer_brokers); for ($i = 0; $i < $t; $i++) { $l = $layer_brokers[$i]; $bean_file_name = $l[1]; $bean_name = $l[2]; $bean_folder_name = WorkFlowBeansFileHandler::getLayerBeanFolderName($user_beans_folder_path . $bean_file_name, $bean_name, $user_global_variables_file_path); if ($bean_folder_name) $beans_folders_name[$bean_name] = $relative_layers_path . $bean_folder_name . "/"; } } $method = PHPCodePrintingHandler::getFunctionCodeFromFile($EVC->getUtilPath("CMSObfuscatePHPFilesHandler"), "getDefaultFilesSettings", "CMSObfuscatePHPFilesHandler"); $show_php_obfuscation_option = !empty($method[0]); $method = PHPCodePrintingHandler::getFunctionCodeFromFile($EVC->getUtilPath("CMSObfuscateJSFilesHandler"), "getDefaultFilesSettings", "CMSObfuscateJSFilesHandler"); $show_js_obfuscation_option = !empty($method[0]); $li = $EVC->getPresentationLayer()->getPHPFrameWork()->gLI(); $projects_max_expiration_date = $li["ped"]; $sysadmin_max_expiration_date = $li["sed"]; $projects_max_num = $li["pmn"]; $users_max_num = $li["umn"]; $end_users_max_num = $li["eumn"]; $actions_max_num = $li["amn"]; $allowed_paths = $li["ap"]; $allowed_domains = $li["ad"]; $check_allowed_domains_port = $li["cadp"]; $allowed_sysadmin_migration = $li["asm"]; if ($projects_max_num > 0) $projects_max_num--; ?>
