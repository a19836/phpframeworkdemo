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

@include GLOBAL_SETTINGS_PROPERTIES_FILE_PATH; $default_timezone = !empty($default_timezone) ? $default_timezone : @date_default_timezone_get(); $default_timezone = !empty($default_timezone) ? $default_timezone : "Europe/London"; if (empty($tmp_path)) { $local_installation_name = strstr($_SERVER["SCRIPT_NAME"], "/" . basename(__DIR__) . "/", true); $document_root = ($_SERVER["CONTEXT_DOCUMENT_ROOT"] ? $_SERVER["CONTEXT_DOCUMENT_ROOT"] : (isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] : null) ) . "/"; if ($local_installation_name && is_dir($document_root . $local_installation_name . "/tmp/")) $tmp_path = $document_root . $local_installation_name . "/tmp/"; else if (is_dir($document_root . "/tmp/")) $tmp_path = $document_root . "/tmp/"; else $tmp_path = (sys_get_temp_dir() ? sys_get_temp_dir() : "/tmp") . "/phpframework/"; $tmp_path = preg_replace("/\/\/+/", "/", $tmp_path); } else if (substr($tmp_path, -1) != "/") $tmp_path .= "/"; $die_when_throw_exception = isset($die_when_throw_exception) ? $die_when_throw_exception : true; $log_level = is_numeric($log_level) ? $log_level: 2;$log_echo_active = isset($log_echo_active) ? $log_echo_active : true; $log_file_path = !empty($log_file_path) ? $log_file_path : $tmp_path . "phpframework.log"; date_default_timezone_set($default_timezone); define('CMS_PATH', dirname(str_replace(DIRECTORY_SEPARATOR, "/", __DIR__)) . "/"); define('APP_PATH', CMS_PATH . "app/"); define('VENDOR_PATH', CMS_PATH . "vendor/"); define('OTHER_PATH', CMS_PATH . "other/"); define('LAYER_PATH', APP_PATH . "layer/"); define('LIB_PATH', APP_PATH . "lib/"); define('CONFIG_PATH', APP_PATH . "config/"); define('DAO_PATH', VENDOR_PATH . "dao/"); define('CODE_WORKFLOW_EDITOR_PATH', VENDOR_PATH . "codeworkfloweditor/"); define('CODE_WORKFLOW_EDITOR_TASK_PATH', CODE_WORKFLOW_EDITOR_PATH . "task/"); define('LAYOUT_UI_EDITOR_PATH', VENDOR_PATH . "layoutuieditor/"); define('LAYOUT_UI_EDITOR_WIDGET_PATH', LAYOUT_UI_EDITOR_PATH . "widget/"); define('TEST_UNIT_PATH', VENDOR_PATH . "testunit/"); define('BEAN_PATH', CONFIG_PATH . "bean/"); define('TMP_PATH', $tmp_path); define('CACHE_PATH', TMP_PATH . "cache/"); define('LAYER_CACHE_PATH', CACHE_PATH . "layer/"); define('SYSTEM_PATH', APP_PATH . "__system/"); define('SYSTEM_LAYER_PATH', SYSTEM_PATH . "layer/"); define('SYSTEM_CONFIG_PATH', SYSTEM_PATH . "config/"); define('SYSTEM_BEAN_PATH', SYSTEM_CONFIG_PATH . "bean/"); include GLOBAL_VARIABLES_PROPERTIES_FILE_PATH; include LIB_PATH . "org/phpframework/util/import/lib.php"; include get_lib("org.phpframework.app"); $PHPFrameWork = new PHPFrameWork(); $PHPFrameWork->init(); $PHPFrameWork->setCacheRootPath(LAYER_CACHE_PATH); ?>
