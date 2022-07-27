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

include_once $EVC->getUtilPath("PHPVariablesFileHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $popup = $_GET["popup"]; $deployment = $_GET["deployment"]; if (isset($_POST["data"])) { $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); $content = '<?php
//[GENERAL SETTINGS]
$default_timezone = "' . $_POST["data"]["default_timezone"] . '";

//[EXCEPTION SETTINGS]
$die_when_throw_exception = ' . $_POST["data"]["die_when_throw_exception"] . ';

//[LOG SETTINGS]
$log_level = ' . $_POST["data"]["log_level"] . ';
$log_echo_active = ' . $_POST["data"]["log_echo_active"] . ';
$log_file_path = "' . $_POST["data"]["log_file_path"] . '";

//[TMP SETTINGS]
$tmp_path = "' . $_POST["data"]["tmp_path"] . '";
?>'; if (file_put_contents($user_global_settings_file_path, $content)) { $status_message = "Settings saved successfully"; } else { $error_message = "There was an error trying to save settings. Please try again..."; } } $vars = PHPVariablesFileHandler::getVarsFromFileContent($user_global_settings_file_path); $vars["default_timezone"] = $vars["default_timezone"] ? $vars["default_timezone"] : $GLOBALS["default_timezone"]; $vars["die_when_throw_exception"] = $vars["die_when_throw_exception"] ? $vars["die_when_throw_exception"] : ($GLOBALS["die_when_throw_exception"] ? "true" : "false"); $vars["log_level"] = is_numeric($vars["log_level"]) ? $vars["log_level"] : $GLOBALS["log_level"]; $vars["log_echo_active"] = $vars["log_echo_active"] ? $vars["log_echo_active"] : ($GLOBALS["log_echo_active"] ? "true" : "false"); $vars["log_file_path"] = $vars["log_file_path"] ? $vars["log_file_path"] : $GLOBALS["log_file_path"]; $vars["tmp_path"] = $vars["tmp_path"] ? $vars["tmp_path"] : $GLOBALS["tmp_path"]; ?>
