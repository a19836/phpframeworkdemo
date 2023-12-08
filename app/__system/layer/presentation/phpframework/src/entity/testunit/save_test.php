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
include_once get_lib("org.phpframework.util.MyArray"); include_once $EVC->getUtilPath("WorkFlowTestUnitHandler"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "write"); UserAuthenticationHandler::checkUsersMaxNum($UserAuthenticationHandler); UserAuthenticationHandler::checkActionsMaxNum($UserAuthenticationHandler); $path = $_GET["path"]; $file_modified_time = $_GET["file_modified_time"]; $path = str_replace("../", "", $path); $file_path = TEST_UNIT_PATH . $path; if ($path && file_exists($file_path) && $_POST) { $folder_path = substr($file_path, strlen($file_path) - 1) == "/" ? $file_path : dirname($file_path); if (!is_dir($folder_path)) mkdir($folder_path, 0755, true); $object = $_POST["object"]; MyArray::arrKeysToLowerCase($object, true); $file_was_changed = file_exists($file_path) && $file_modified_time && $file_modified_time < filemtime($file_path); $class_name = pathinfo($path, PATHINFO_FILENAME); if ($file_was_changed) { $old_code = file_exists($file_path) ? file_get_contents($file_path) : ""; $tmp_file_path = tempnam(TMP_PATH, $file_type . "_"); file_put_contents($tmp_file_path, $old_code); $status = WorkFlowTestUnitHandler::saveTestFile($tmp_file_path, $object, $class_name, $object["name"]); $ret = array( "status" => "CHANGED", "old_code" => $old_code, "new_code" => file_exists($tmp_file_path) ? file_get_contents($tmp_file_path) : "", ); unlink($tmp_file_path); } else { $status = WorkFlowTestUnitHandler::saveTestFile($file_path, $object, $class_name, $object["name"]); clearstatcache(true, $file_path); if ($status) $UserAuthenticationHandler->incrementUsedActionsTotal(); $ret = array( "status" => $status, "modified_time" => filemtime($file_path), ); } $status = json_encode($ret); } die($status); ?>
