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

include_once $EVC->getUtilPath("WorkFlowDBHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $layer_bean_folder_name = $_GET["layer_bean_folder_name"]; $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $popup = $_GET["popup"]; if ($bean_name) { $WorkFlowDBHandler = new WorkFlowDBHandler($user_beans_folder_path, $user_global_variables_file_path); $layer_object_id = LAYER_PATH . "$layer_bean_folder_name/$bean_name"; $UserAuthenticationHandler->checkInnerFilePermissionAuthentication($layer_object_id, "layer", "access"); if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $sql = $_POST["sql"]; if ($sql) { $DBDriver = $WorkFlowDBHandler->getBeanObject($bean_file_name, $bean_name); $status = $DBDriver->setData($sql); } } else { $tasks_file_path = WorkFlowTasksFileHandler::getDBDiagramTaskFilePath($workflow_paths_id, "db_diagram", $bean_name); $sql = $WorkFlowDBHandler->getTaskDBDiagramSql($bean_file_name, $bean_name, $tasks_file_path); } } ?>
