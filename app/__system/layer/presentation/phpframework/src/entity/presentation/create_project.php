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
include_once $EVC->getUtilPath("CMSPresentationLayerHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $popup = $_GET["popup"]; $creation_step = $_GET["creation_step"]; $on_success_js_func = $_GET["on_success_js_func"]; $path = str_replace("../", "", $path); if (!$creation_step || !$_POST) { if (is_numeric($creation_step) && $creation_step != 0) { $refresh_page_without_creation_step = true; $creation_step = 0; } else { $creation_step = 0; include_once $EVC->getEntityPath("presentation/edit_project_details"); if ($_POST && $status) { $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerObjFolderName( $PEVC->getPresentationLayer() ); $new_filter_by_layout = "$layer_bean_folder_name/$path"; } } } else if ($_POST) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $on_success_js_func = $_POST["on_success_js_func"]; $on_success_js_func_opts = $_POST["on_success_js_func_opts"]; $msg = $_POST["msg"]; if ($creation_step == 3) { $step = $_POST["step"]; include_once $EVC->getEntityPath("admin/install_program"); $is_last_step_successfull = $_POST && $step >= 3 && !$errors && !$error_message && !$next_step_html; if ($is_last_step_successfull) { $index_changed = false; if ($PEVC && $program_name) { $index_path = $PEVC->getEntityPath("index"); $code = "<?php\n\$url = \"\${project_url_prefix}$program_name\";\nheader(\"Location: \$url\");\necho \"<script>document.location='\$url';</script>\";\ndie();\n?>"; $index_changed = file_put_contents($index_path, $code) !== false; } if (!$index_changed) $error_message = "The index.php in the root of this project was not pointed to the installed program. Please do this manually..."; } } else if ($creation_step == 2) { $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $PEVC = $WorkFlowBeansFileHandler->getEVCBeanObject($bean_name, $path); $layer_bean_folder_name = WorkFlowBeansFileHandler::getLayerObjFolderName( $PEVC->getPresentationLayer() ); $filter_by_layout = "$layer_bean_folder_name/" . $PEVC->getPresentationLayer()->getSelectedPresentationId(); } else if ($creation_step == 1) { } } ?>
