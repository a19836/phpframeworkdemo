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
include_once $EVC->getUtilPath("WorkFlowBeansFileHandler"); include_once $EVC->getUtilPath("WorkFlowBusinessLogicHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $_GET["rename_file_with_class"] = true; if ($_POST["object"]) { $bean_name = $_GET["bean_name"]; $bean_file_name = $_GET["bean_file_name"]; $path = $_GET["path"]; $class = $_GET["class"]; $path = str_replace("../", "", $path); $WorkFlowBeansFileHandler = new WorkFlowBeansFileHandler($user_beans_folder_path . $bean_file_name, $user_global_variables_file_path); $obj = $WorkFlowBeansFileHandler->getBeanObject($bean_name); $common_namespace = ""; $common_service_file_path = $obj->settings["business_logic_modules_service_common_file_path"]; if ($common_service_file_path && file_exists($common_service_file_path)) { $common_namespace = PHPCodePrintingHandler::getNamespacesFromFile($common_service_file_path); $common_namespace = $common_namespace[0]; $common_namespace = substr($common_namespace, 0, 1) == "\\" ? substr($common_namespace, 1) : $common_namespace; $common_namespace = substr($common_namespace, -1) == "\\" ? substr($common_namespace, 0, -1) : $common_namespace; } $default_extend = ($common_namespace ? "\\$common_namespace\\" : "") . "CommonService"; WorkFlowBusinessLogicHandler::prepareServiceObjectForsaving($_POST["object"], array( "default_include" => '$vars["business_logic_modules_service_common_file_path"]', "default_extend" => $default_extend, )); if (!WorkFlowBusinessLogicHandler::renameServiceObjectFile($obj->getLayerPathSetting() . $path, $class)) $_GET["rename_file_with_class"] = false; } $do_not_die_on_save = true; include $EVC->getEntityPath("admin/save_file_class"); if ($obj && is_a($obj, "BusinessLogicLayer") && $_POST && $status) CacheHandlerUtil::deleteFolder($obj->getCacheLayer()->getCachedDirPath(), false); die($status); ?>
