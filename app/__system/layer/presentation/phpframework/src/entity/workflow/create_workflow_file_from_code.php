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
include_once $EVC->getUtilPath("WorkFlowTasksFileHandler"); include get_lib("org.phpframework.workflow.WorkFlowTaskCodeParser"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $path = $_GET["path"]; $path_extra = $_GET["path_extra"]; $path = str_replace("../", "", $path);$path_extra = str_replace("../", "", $path_extra); $status = false; if (isset($_POST)) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $code = htmlspecialchars_decode( file_get_contents("php://input"), ENT_NOQUOTES); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->addTasksFoldersPath($code_workflow_editor_user_tasks_folders_path); $loaded_tasks_settings_cache_id = $_GET["loaded_tasks_settings_cache_id"]; $loaded_tasks_settings = $WorkFlowTaskHandler->getCachedLoadedTasksSettings($loaded_tasks_settings_cache_id); if ($loaded_tasks_settings) { $allowed_tasks_tag = array(); foreach ($loaded_tasks_settings as $group_id => $group_tasks) foreach ($group_tasks as $task_type => $task_settings) $allowed_tasks_tag[] = $task_settings["tag"]; if ($allowed_tasks_tag) $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks_tag); } $WorkFlowTaskHandler->initWorkFlowTasks(); $WorkFlowTaskCodeParser = new WorkFlowTaskCodeParser($WorkFlowTaskHandler); $xml = $WorkFlowTaskCodeParser->getParsedCodeAsXml($code); $task_file_path = WorkFlowTasksFileHandler::getTaskFilePathByPath($workflow_paths_id, $path, $path_extra); $folder = dirname($task_file_path); if (is_dir($folder) || mkdir($folder, 0775, true)) if (file_put_contents($task_file_path, $xml) > 0) $status = true; } ?>
