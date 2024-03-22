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
include_once get_lib("org.phpframework.workflow.WorkFlowTaskHandler"); include_once get_lib("org.phpframework.workflow.WorkFlowTaskCodeParser"); $UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $method = $_POST["method"]; if ($method) { $code = "<?php " . htmlspecialchars_decode($method) . " ?>"; $allowed_tasks = array("callbusinesslogic", "callibatisquery", "callhibernatemethod", "getquerydata", "setquerydata", "callhibernateobject", "callfunction", "callobjectmethod", "restconnector", "soapconnector"); $WorkFlowTaskHandler = new WorkFlowTaskHandler($webroot_cache_folder_path, $webroot_cache_folder_url); $WorkFlowTaskHandler->setCacheRootPath(LAYER_CACHE_PATH); $WorkFlowTaskHandler->setAllowedTaskTags($allowed_tasks); $WorkFlowTaskHandler->initWorkFlowTasks(); $WorkFlowTaskCodeParser = new WorkFlowTaskCodeParser($WorkFlowTaskHandler); $arr = $WorkFlowTaskCodeParser->getParsedCodeAsArray($code); $arr = $arr["task"][0]["childs"]; $tag = $arr["tag"][0]["value"]; if (in_array($tag, $allowed_tasks)) { $properties = $arr["properties"][0]["childs"]; $properties = MyXML::complexArrayToBasicArray($properties, array("lower_case_keys" => true)); foreach ($properties as $k => $v) { if (is_array($v)) { $is_assoc = array_keys($v) !== range(0, count($v) - 1); if ($is_assoc) { $properties[$k] = array($v); } } } } } $obj = array("brokers" => $properties, "brokers_layer_type" => $tag); ?>
