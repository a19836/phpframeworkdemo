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

abstract class LayerWebService { protected $PHPFrameWork; protected $settings; protected $url; protected $web_service_validation_string; protected $broker_server_bean_name; public function __construct($v2a9b6f4e3b, $v30857f7eca = false) { $this->PHPFrameWork = $v2a9b6f4e3b; $this->settings = $v30857f7eca; $this->mcdfff12f30fd(); } private function mcdfff12f30fd() { $v6f3a2700dd = $this->settings && isset($this->settings["url"]) ? $this->settings["url"] : false; $v6f3a2700dd = empty($v6f3a2700dd) && isset($_GET["url"]) ? $_GET["url"] : $v6f3a2700dd; $v02a69d4e0f = strstr($v6f3a2700dd, "?", true); $this->url = $v02a69d4e0f ? $v02a69d4e0f : $v6f3a2700dd; $this->url = $this->url && substr($this->url, -1, 1) == "/" ? substr($this->url, 0, -1) : $this->url; $this->md955eb1fc479(); $this->f361757c993(); } private function md955eb1fc479() { unset($_GET["url"]); if (isset($_SERVER["QUERY_STRING"])) $_SERVER["QUERY_STRING"] = preg_replace("/url=([^&]*)([&]?)/u", "", $_SERVER["QUERY_STRING"]); if (isset($_SERVER["REDIRECT_QUERY_STRING"])) $_SERVER["REDIRECT_QUERY_STRING"] = preg_replace("/url=([^&]*)([&]?)/u", "", $_SERVER["REDIRECT_QUERY_STRING"]); if (isset($_SERVER["argv"]) && $_SERVER["argv"]) $_SERVER["argv"][0] = preg_replace("/url=([^&]*)([&]?)/u", "", $_SERVER["argv"][0]); } private function f361757c993() { $pd7a36e35 = isset($this->settings["global_variables"]) ? $this->settings["global_variables"] : null; $pfffd7fa4 = isset($this->settings["request_encryption_key"]) ? $this->settings["request_encryption_key"] : null; if ($pfffd7fa4 && $pd7a36e35) { include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); $pbfa01ed1 = CryptoKeyHandler::hexToBin($pfffd7fa4); $v46db43a407 = CryptoKeyHandler::hexToBin($pd7a36e35); $pd7a36e35 = CryptoKeyHandler::decryptSerializedObject($v46db43a407, $pbfa01ed1); } if ($pd7a36e35) foreach ($pd7a36e35 as $v1cfba8c105 => $pa6209df1) if ($v1cfba8c105) $GLOBALS[$v1cfba8c105] = $pa6209df1; } public function callWebService() { if ($this->web_service_validation_string && $this->url == $this->web_service_validation_string) { echo 1; die(); } $this->PHPFrameWork->loadBeansFile(BEANS_FILE_PATH); set_log_handler_settings(); $pe95619c9 = $this->PHPFrameWork->getObject($this->broker_server_bean_name); return $pe95619c9->callWebService($this->url); } } ?>
