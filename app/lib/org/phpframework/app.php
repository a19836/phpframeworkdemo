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

include get_lib("org.phpframework.exception.ExceptionLogHandler"); include get_lib("org.phpframework.log.LogHandler"); include get_lib("org.phpframework.error.ErrorHandler"); include_once get_lib("org.phpframework.PHPFrameWork"); $GlobalErrorHandler = new ErrorHandler(); $GlobalLogHandler = new LogHandler(); $GlobalLogHandler->setLogLevel($log_level); $GlobalLogHandler->setEchoActive($log_echo_active); $GlobalLogHandler->setRootPath(CMS_PATH); $GlobalExceptionLogHandler = new ExceptionLogHandler($GlobalLogHandler, $die_when_throw_exception); function normalize_windows_path_to_linux($pa32be502) { return DIRECTORY_SEPARATOR != "/" ? str_replace(DIRECTORY_SEPARATOR, "/", $pa32be502) : $pa32be502; } function launch_exception(Exception $v4ace7728e6) { global $GlobalErrorHandler; $GlobalErrorHandler->stop(); if ($v4ace7728e6->file_not_found) echo '<h1 style="text-align:center">File not found.</h1>'; throw $v4ace7728e6; return false; } function set_log_handler_settings() { global $GlobalLogHandler, $PHPFrameWork, $log_level, $log_echo_active, $log_file_path; $v21c0d09111 = $PHPFrameWork->getObject("LogHandler"); if (isset($v21c0d09111) && $v21c0d09111 instanceof ILogHandler) { $GlobalLogHandler = $v21c0d09111; } $v48cc8e20e4 = $PHPFrameWork->getObject("log_vars"); $pb77a7e67 = isset($v48cc8e20e4["log_level"]) ? $v48cc8e20e4["log_level"] : null; if (isset($pb77a7e67) && $pb77a7e67 > 0) { $pb77a7e67 = (int)$pb77a7e67; $GlobalLogHandler->setLogLevel($pb77a7e67); $log_level = $pb77a7e67; } $pcb7e4e12 = isset($v48cc8e20e4["log_echo_active"]) ? $v48cc8e20e4["log_echo_active"] : null; if (isset($pcb7e4e12)) { $pcb7e4e12 = $pcb7e4e12 == "0" || $pcb7e4e12 == "false" ? false : true; $GlobalLogHandler->setEchoActive($pcb7e4e12); $log_echo_active = $pcb7e4e12; } $pbc43098a = isset($v48cc8e20e4["log_file_path"]) ? $v48cc8e20e4["log_file_path"] : null; if (strpos($pbc43098a, "log_file_path") !== false || strpos($pbc43098a, "&lt;") !== false) { echo "<textarea>".print_r($v48cc8e20e4, 1)."</textarea>"; debug_print_backtrace(); die(); } if (isset($pbc43098a) && $pbc43098a) { $GlobalLogHandler->setFilePath($pbc43098a); $log_file_path = $pbc43098a; } $v9da9aa27fa = isset($v48cc8e20e4["log_css"]) ? $v48cc8e20e4["log_css"] : null; if (isset($v9da9aa27fa) && $v9da9aa27fa) { $GlobalLogHandler->setCSS($v9da9aa27fa); } } function debug_log_function($pa051dc1c, $v86066462c3) { $pffa799aa = $pa051dc1c . "(" . LogHandler::getArgsInString($v86066462c3) . ")"; debug_log($pffa799aa); } function debug_log($pffa799aa, $v0275e9e86c = "debug") { global $GlobalLogHandler; $pf438175f = $GlobalLogHandler->getLogLevel(); if ($pf438175f >= 1) { $pe9578251 = $GlobalLogHandler->getEchoActive(); $GlobalLogHandler->setEchoActive(false); switch (strtolower($v0275e9e86c)) { case "exception": if ($pf438175f >= 1) $GlobalLogHandler->setExceptionLog($pffa799aa); break; case "error": if ($pf438175f >= 2) $GlobalLogHandler->setErrorLog($pffa799aa); break; case "info": if ($pf438175f >= 3) $GlobalLogHandler->setInfoLog($pffa799aa); break; default: $GlobalLogHandler->setDebugLog($pffa799aa); } $GlobalLogHandler->setEchoActive($pe9578251); } } function call_presentation_layer_web_service($v30857f7eca = false) { global $PHPFrameWork; $v35e5da9b49 = new PresentationLayerWebService($PHPFrameWork, $v30857f7eca); return $v35e5da9b49->callWebServicePage(); } function call_business_logic_layer_web_service($v30857f7eca = false) { global $PHPFrameWork; $v35e5da9b49 = new BusinessLogicLayerWebService($PHPFrameWork, $v30857f7eca); return $v35e5da9b49->callWebService(); } function call_ibatis_data_access_layer_web_service($v30857f7eca = false) { global $PHPFrameWork; $v35e5da9b49 = new IbatisDataAccessLayerWebService($PHPFrameWork, $v30857f7eca); return $v35e5da9b49->callWebService(); } function call_hibernate_data_access_layer_web_service($v30857f7eca = false) { global $PHPFrameWork; $v35e5da9b49 = new HibernateDataAccessLayerWebService($PHPFrameWork, $v30857f7eca); return $v35e5da9b49->callWebService(); } function call_db_layer_web_service($v30857f7eca = false) { global $PHPFrameWork; $v35e5da9b49 = new DBLayerWebService($PHPFrameWork, $v30857f7eca); return $v35e5da9b49->callWebService(); } ?>
