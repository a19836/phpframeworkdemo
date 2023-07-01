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

namespace WorkFlowTask\programming\sendemail; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $v9073377656 = $pb16df866->getObjectMethodProps($v5faa4b8a01); if ($v9073377656 && $v9073377656["method_name"] && $v9073377656["method_static"] && $v9073377656["method_obj"] == "SendEmailHandler") { $v603bd47baf = $v9073377656["method_name"]; if ($v603bd47baf == "sendEmail" || $v603bd47baf == "sendSMTPEmail") { $v86066462c3 = $v9073377656["method_args"]; $v9073377656["method"] = $v9073377656["method_obj"] . "::$v603bd47baf"; $v30857f7eca = $v86066462c3[0]["value"]; $v2b61851547 = $v86066462c3[0]["type"]; if ($v2b61851547 == "array") { $v8ba0fb6d41 = $pb16df866->getPHPParserEmulative()->parse("<?php\n" . $v30857f7eca . "\n?>"); $v30857f7eca = $pb16df866->getArrayItems($v8ba0fb6d41[0]->items); } $v9073377656["settings"] = $v30857f7eca; $v9073377656["settings_type"] = self::getConfiguredParsedType($v2b61851547, array("", "string", "variable", "array")); unset($v9073377656["method_name"]); unset($v9073377656["method_obj"]); unset($v9073377656["method_args"]); unset($v9073377656["method_static"]); $v9073377656["label"] = $v603bd47baf; $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ); return $v9073377656; } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $v2b61851547 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["settings_type"][0]["value"]; if ($v2b61851547 == "array") { $v30857f7eca = $v3c3af72a1c["childs"]["properties"][0]["childs"]["settings"]; $v30857f7eca = self::parseArrayItems($v30857f7eca); } else { $v30857f7eca = $v3c3af72a1c["childs"]["properties"][0]["childs"]["settings"][0]["value"]; } $pef349725 = array( "method" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["method"][0]["value"], "settings" => $v30857f7eca, "settings_type" => $v2b61851547, ); $pef349725 = self::parseResultVariableProperties($v3c3af72a1c, $pef349725); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v1cfba8c105 = self::getPropertiesResultVariableCode($pef349725); $v6cd9d4006f = $pef349725["method"]; $v067674f4e4 = ""; if ($v6cd9d4006f) { $v2b61851547 = $pef349725["settings_type"]; if ($v2b61851547 == "array") $v30857f7eca = self::getArrayString($pef349725["settings"]); else $v30857f7eca = self::getVariableValueCode($pef349725["settings"], $v2b61851547); $v067674f4e4 = $v54bb17785b . $v1cfba8c105 . "$v6cd9d4006f("; $v067674f4e4 .= $v30857f7eca ? $v30857f7eca : "null"; $v067674f4e4 .= ");\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
