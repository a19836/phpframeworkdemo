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
include_once $EVC->getUtilPath("WorkFlowTasksFileHandler"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $common_project_name = $EVC->getCommonProjectName(); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $sla_tasks_folder_path = $EVC->getViewsPath() . "sequentiallogicalactivity/tasks/"; $WorkFlowTaskHandler->addTasksFolderPath($sla_tasks_folder_path); $WorkFlowTaskHandler->initWorkFlowTasks(); $task_file_path = WorkFlowTasksFileHandler::getTaskFilePathByPath($workflow_paths_id, $_GET["path"], $_GET["path_extra"]); $obj_settings = null; if ($task_file_path && file_exists($task_file_path)) { $loops = $WorkFlowTaskHandler->getLoopsTasksFromFile($task_file_path); $res = $WorkFlowTaskHandler->parseFile($task_file_path, $loops, array("return_obj" => true)); if (isset($res)) { $tasks = convertResultsIntoTasks($res); $actions = convertTasksIntoSettingsActions($tasks); $obj_settings = array("actions" => $actions); if (!empty($loops)) { $t = count($loops); for ($i = 0; $i < $t; $i++) { $loop = $loops[$i]; $is_loop_allowed = $loop[2]; if (!$is_loop_allowed) $obj_settings["error"]["infinit_loop"][] = array("source_task_id" => $loop[0], "target_task_id" => $loop[1]); } } } } function convertResultsIntoTasks($pf72c1d58) { $v1d696dbd12 = array(); foreach ($pf72c1d58 as $v342a134247) { $v7f5911d32d = $v342a134247["code"]; if (isset($v7f5911d32d["inner"])) $v7f5911d32d["inner"] = convertResultsIntoTasks($v7f5911d32d["inner"]); if (isset($v7f5911d32d["next"])) $v7f5911d32d["next"] = convertResultsIntoTasks($v7f5911d32d["next"]); if (!$v7f5911d32d["inner"]) unset($v7f5911d32d["inner"]); if (!$v7f5911d32d["next"]) unset($v7f5911d32d["next"]); $v1d696dbd12[] = $v7f5911d32d; } return $v1d696dbd12; } function convertTasksIntoSettingsActions($v1d696dbd12) { $v55bd236ac1 = array(); foreach ($v1d696dbd12 as $v7f5911d32d) { $v342a134247 = $v7f5911d32d["properties"]; if (isset($v7f5911d32d["inner"])) $v342a134247["action_value"]["actions"] = convertTasksIntoSettingsActions($v7f5911d32d["inner"]); $v55bd236ac1[] = $v342a134247; if (isset($v7f5911d32d["next"])) { $v064fd4340d = convertTasksIntoSettingsActions($v7f5911d32d["next"]); $v55bd236ac1 = array_merge($v55bd236ac1, $v064fd4340d); } } return $v55bd236ac1; } ?>
