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
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $containers = array( "layer_presentations" => array("presentation"), "layer_bls" => array("businesslogic"), "layer_dals" => array("dataaccess"), "layer_dbs" => array("db"), "layer_drivers" => array("dbdriver"), ); $tasks_order_by_tag = array("presentation", "businesslogic", "dataaccess", "db", "dbdriver"); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskFolders(array("layer/")); $WorkFlowTaskHandler->setTasksContainers($containers); $workflow_path_id = "layer"; $diagram_already_exists = file_exists($workflow_paths_id[ $workflow_path_id ]); if (!$diagram_already_exists) { $content = file_get_contents($EVC->getPresentationLayer()->getSelectedPresentationSetting("presentation_webroot_path") . "/assets/default_layers_workflow_with_db.xml"); $content = str_replace("\$db_type", "mysql", $content); $content = str_replace("\$db_encoding", "utf8", $content); $content = str_replace("\$driver_label", "mysql", $content); $content = str_replace(array("\$db_extension", "\$db_host", "\$db_port", "\$db_name", "\$db_username", "\$db_password", "\$db_odbc_data_source", "\$db_odbc_driver", "\$db_extra_dsn"), "", $content); file_put_contents($workflow_paths_id[ $workflow_path_id ], $content); } ?>
