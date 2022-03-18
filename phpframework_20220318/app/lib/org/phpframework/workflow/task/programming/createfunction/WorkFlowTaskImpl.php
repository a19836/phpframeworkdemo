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

namespace WorkFlowTask\programming\createfunction; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_function") { $v067674f4e4 = $pb16df866->printCodeStatement($v5faa4b8a01, true); $v6490ea3a15 = '<?php ' . $v067674f4e4 . ' ?>'; $pedde3449 = \PHPCodePrintingHandler::getPHPClassesFromString($v6490ea3a15); $v9073377656 = $pedde3449[0]["methods"][0]; $v9073377656 = $v9073377656 ? $v9073377656 : array(); if (is_array($v9073377656["arguments"])) { $v86066462c3 = array(); foreach ($v9073377656["arguments"] as $pe5c5e2fe => $v956913c90f) { $v956913c90f = trim($v956913c90f); $v79e3beff16 = substr($v956913c90f, 0, 1); $v97ebe20f21 = $v956913c90f && ($v79e3beff16 == '"' || $v79e3beff16 == "'") && substr($v956913c90f, -1) == $v79e3beff16 ? "string" : ""; $v956913c90f = $v97ebe20f21 == "string" ? substr($v956913c90f, 1, -1) : $v956913c90f; $v86066462c3[] = array("name" => $pe5c5e2fe, "value" => $v956913c90f, "var_type" => $v97ebe20f21); } $v9073377656["arguments"] = $v86066462c3; } if ($v9073377656["comments"] || $v9073377656["doc_comments"]) { $v454a8d0d34 = $v9073377656["doc_comments"] ? implode("\n", $v9073377656["doc_comments"]) : ""; $v454a8d0d34 = trim($v454a8d0d34); $v454a8d0d34 = str_replace("\r", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/^\/[*]+\s*/", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/\s*[*]+\/\s*$/", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/\s*\n\s*[*]*\s*/", "\n", $v454a8d0d34); $v454a8d0d34 = preg_replace("/^\s*[*]*\s*/", "", $v454a8d0d34); $v454a8d0d34 = trim($v454a8d0d34); $pcc2fe66c = is_array($v9073377656["comments"]) ? trim(implode("\n", $v9073377656["comments"])) : ""; $pcc2fe66c .= $v454a8d0d34 ? "\n" . trim($v454a8d0d34) : ""; $pcc2fe66c = str_replace(array("/*", "*/", "//"), "", $pcc2fe66c); $pcc2fe66c = trim($pcc2fe66c); $v9073377656["comments"] = $pcc2fe66c; unset($v9073377656["doc_comments"]); } $v9073377656["code"] = \PHPCodePrintingHandler::getFunctionCodeFromString($v6490ea3a15, $v9073377656["name"]); $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#6492b5", ), ); return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["name"][0]["value"], "comments" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["comments"][0]["value"], "code" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["code"][0]["value"], ); $v02a69d4e0f = \MyXML::complexArrayToBasicArray($v3c3af72a1c["childs"]["properties"][0]["childs"], array("lower_case_keys" => true)); $pef349725["arguments"] = $v02a69d4e0f["arguments"]; if ($pef349725["arguments"]) { $v651d593e1f = array_keys($pef349725["arguments"]) !== range(0, count($pef349725["arguments"]) - 1); if ($v651d593e1f) $pef349725["arguments"] = array($pef349725["arguments"]); } return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; if ($pef349725["arguments"]) { $v86066462c3 = array(); foreach ($pef349725["arguments"] as $pea70e132) if (trim($pea70e132["name"])) $v86066462c3[ $pea70e132["name"] ] = self::getVariableValueCode($pea70e132["value"], $pea70e132["var_type"]); $pef349725["arguments"] = $v86066462c3; } $v067674f4e4 = "\n"; $v067674f4e4 .= $v54bb17785b . str_replace("\n", "\n$v54bb17785b", \PHPCodePrintingHandler::getFunctionString($pef349725)) . " {\n"; $v067674f4e4 .= $v54bb17785b . "\t" . str_replace("\n", "\n$v54bb17785b\t", $pef349725["code"]) . "\n"; $v067674f4e4 .= $v54bb17785b . "}\n"; $v067674f4e4 .= "\n"; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
