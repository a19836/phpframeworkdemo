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
namespace WorkFlowTask\programming\debuglog; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $v9073377656 = $pb16df866->getFunctionProps($v5faa4b8a01); if ($v9073377656) { $v24b0e52635 = $v9073377656["func_name"]; $v86066462c3 = $v9073377656["func_args"]; if ($v24b0e52635 && strtolower($v24b0e52635) == "debug_log") { if (count($v86066462c3) <= 2) { $pffa799aa = $v86066462c3[0]["value"]; $pde3978c7 = $v86066462c3[0]["type"]; $v0275e9e86c = $v86066462c3[1]["value"]; $v165682a275 = $v86066462c3[1]["type"]; unset($v9073377656["func_name"]); unset($v9073377656["func_args"]); unset($v9073377656["label"]); $v9073377656["message"] = $pffa799aa; $v9073377656["message_type"] = self::getConfiguredParsedType($pde3978c7); $v9073377656["log_type"] = $v0275e9e86c; $v9073377656["log_type_type"] = self::getConfiguredParsedType($v165682a275); $v9073377656["label"] = "Define date " . self::prepareTaskPropertyValueLabelFromCodeStmt($v9073377656["format"]); $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ); return $v9073377656; } } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "message" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["message"][0]["value"], "message_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["message_type"][0]["value"], "log_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["log_type"][0]["value"], "log_type_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["log_type_type"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $pffa799aa = self::getVariableValueCode($pef349725["message"], $pef349725["message_type"]); $v0275e9e86c = self::getVariableValueCode($pef349725["log_type"], $pef349725["log_type_type"]); $v067674f4e4 = $v54bb17785b . "debug_log($pffa799aa"; $v067674f4e4 .= $v0275e9e86c ? ", $v0275e9e86c" : ""; $v067674f4e4 .= ");\n"; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
