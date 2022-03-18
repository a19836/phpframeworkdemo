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

namespace WorkFlowTask\programming\createclass; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_class" || $pe83cda0c == "stmt_interface") { $v067674f4e4 = $pb16df866->printCodeStatement($v5faa4b8a01, true); $v6490ea3a15 = '<?php ' . $v067674f4e4 . ' ?>'; $pa1c3a9c4 = \PHPCodePrintingHandler::getPHPClassesFromString($v6490ea3a15); $v1335217393 = key($pa1c3a9c4); $v9073377656 = $pa1c3a9c4[$v1335217393]; $v9073377656 = $v9073377656 ? $v9073377656 : array(); if ($v9073377656["extends"] && is_array($v9073377656["extends"])) $v9073377656["extends"] = implode(", ", $v9073377656["extends"]); if ($v9073377656["implements"] && is_array($v9073377656["implements"])) $v9073377656["implements"] = implode(", ", $v9073377656["implements"]); $this->f0695d1035c($v9073377656); $v9073377656["properties"] = \PHPCodePrintingHandler::getClassPropertiesFromString($v6490ea3a15, $v1335217393); if (is_array($v9073377656["properties"])) foreach ($v9073377656["properties"] as $pe5c5e2fe => $v956913c90f) { if ($v956913c90f["var_type"] == "string" && ($v956913c90f["value"][0] == '"' || $v956913c90f["value"][0] == "'")) $v9073377656["properties"][$pe5c5e2fe]["value"] = substr($v956913c90f["value"], 1, -1); $this->f0695d1035c($v9073377656["properties"][$pe5c5e2fe]); if ($v956913c90f["const"]) $v9073377656["properties"][$pe5c5e2fe]["type"] = "const"; } if (is_array($v9073377656["methods"])) foreach ($v9073377656["methods"] as $pe5c5e2fe => $v956913c90f) { if (is_array($v956913c90f["arguments"])) { $v86066462c3 = array(); foreach ($v956913c90f["arguments"] as $v792ec600aa => $pe8b6023c) { $pe8b6023c = trim($pe8b6023c); $v79e3beff16 = substr($pe8b6023c, 0, 1); $v97ebe20f21 = $pe8b6023c && ($v79e3beff16 == '"' || $v79e3beff16 == "'") && substr($pe8b6023c, -1) == $v79e3beff16 ? "string" : ""; $pe8b6023c = $v97ebe20f21 == "string" ? substr($pe8b6023c, 1, -1) : $pe8b6023c; $v86066462c3[] = array("name" => $v792ec600aa, "value" => $pe8b6023c, "var_type" => $v97ebe20f21); } $this->f0695d1035c($v9073377656["methods"][$pe5c5e2fe]); $v9073377656["methods"][$pe5c5e2fe]["arguments"] = $v86066462c3; } $v9073377656["methods"][$pe5c5e2fe]["code"] = \PHPCodePrintingHandler::getFunctionCodeFromString($v6490ea3a15, $v956913c90f["name"], $v1335217393); } $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#004480", ), ); return $v9073377656; } } private function f0695d1035c(&$v9073377656) { if ($v9073377656["comments"] || $v9073377656["doc_comments"]) { $v454a8d0d34 = $v9073377656["doc_comments"] ? implode("\n", $v9073377656["doc_comments"]) : ""; $v454a8d0d34 = trim($v454a8d0d34); $v454a8d0d34 = str_replace("\r", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/^\/[*]+\s*/", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/\s*[*]+\/\s*$/", "", $v454a8d0d34); $v454a8d0d34 = preg_replace("/\s*\n\s*[*]*\s*/", "\n", $v454a8d0d34); $v454a8d0d34 = preg_replace("/^\s*[*]*\s*/", "", $v454a8d0d34); $v454a8d0d34 = trim($v454a8d0d34); $pcc2fe66c = is_array($v9073377656["comments"]) ? trim(implode("\n", $v9073377656["comments"])) : ""; $pcc2fe66c .= $v454a8d0d34 ? "\n" . trim($v454a8d0d34) : ""; $pcc2fe66c = str_replace(array("/*", "*/", "//"), "", $pcc2fe66c); $pcc2fe66c = trim($pcc2fe66c); $v9073377656["comments"] = $pcc2fe66c; unset($v9073377656["doc_comments"]); } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["name"][0]["value"], "extends" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["extends"][0]["value"], "implements" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["implements"][0]["value"], "abstract" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["abstract"][0]["value"], "interface" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["interface"][0]["value"], "comments" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["comments"][0]["value"], ); $v02a69d4e0f = \MyXML::complexArrayToBasicArray($v3c3af72a1c["childs"]["properties"][0]["childs"], array("lower_case_keys" => true)); $pef349725["properties"] = $v02a69d4e0f["properties"]; $pef349725["methods"] = $v02a69d4e0f["methods"]; if ($pef349725["properties"]) { $v651d593e1f = array_keys($pef349725["properties"]) !== range(0, count($pef349725["properties"]) - 1); if ($v651d593e1f) $pef349725["properties"] = array($pef349725["properties"]); } if ($pef349725["methods"]) { $v651d593e1f = array_keys($pef349725["methods"]) !== range(0, count($pef349725["methods"]) - 1); if ($v651d593e1f) $pef349725["methods"] = array($pef349725["methods"]); foreach ($pef349725["methods"] as $pd69fb7d0 => $v6cd9d4006f) if ($v6cd9d4006f["arguments"]) { $v651d593e1f = array_keys($v6cd9d4006f["arguments"]) !== range(0, count($v6cd9d4006f["arguments"]) - 1); if ($v651d593e1f) $pef349725["methods"][$pd69fb7d0]["arguments"] = array($v6cd9d4006f["arguments"]); } } return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $v067674f4e4 = ""; $pef349725 = $v539082ff30["properties"]; $v1335217393 = trim($pef349725["name"]); if ($v1335217393) { $v067674f4e4 .= "\n"; $v067674f4e4 .= $v54bb17785b . str_replace("\n", "\n$v54bb17785b", \PHPCodePrintingHandler::getClassString($pef349725)) . " {\n"; if ($pef349725["properties"]) foreach ($pef349725["properties"] as $v1654ac0a73) { $v327f72fb62 = \PHPCodePrintingHandler::getClassPropertyString($v1654ac0a73); if ($v327f72fb62) $v067674f4e4 .= $v54bb17785b . "\t" . str_replace("\n", "\n$v54bb17785b\t", $v327f72fb62) . "\n"; } if ($pef349725["methods"]) foreach ($pef349725["methods"] as $v6cd9d4006f) { if ($v6cd9d4006f["arguments"]) { $v86066462c3 = array(); foreach ($v6cd9d4006f["arguments"] as $pea70e132) if (trim($pea70e132["name"])) $v86066462c3[ $pea70e132["name"] ] = self::getVariableValueCode($pea70e132["value"], $pea70e132["var_type"]); $v6cd9d4006f["arguments"] = $v86066462c3; } $v327f72fb62 = \PHPCodePrintingHandler::getFunctionString($v6cd9d4006f, $v1335217393); if ($v327f72fb62) { $v067674f4e4 .= "\n"; $v067674f4e4 .= $v54bb17785b . str_replace("\n", "\n$v54bb17785b", $v327f72fb62) . " {\n"; $v067674f4e4 .= $v54bb17785b . "\t\t" . str_replace("\n", "\n$v54bb17785b\t\t", $v6cd9d4006f["code"]) . "\n"; $v067674f4e4 .= $v54bb17785b . "\t}\n"; $v067674f4e4 .= "\n"; } } $v067674f4e4 .= !$v54bb17785b && !preg_match("/\s/", substr($v067674f4e4, -1)) ? " " : ""; $v067674f4e4 .= $v54bb17785b . "}\n"; $v067674f4e4 .= "\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
