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

namespace WorkFlowTask\programming\callpresentationlayerwebservice; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $v9073377656 = $pb16df866->getFunctionProps($v5faa4b8a01); if ($v9073377656) { $v24b0e52635 = $v9073377656["func_name"]; $v86066462c3 = $v9073377656["func_args"]; if ($v24b0e52635 && strtolower($v24b0e52635) == "call_presentation_layer_web_service") { $v30857f7eca = $v86066462c3[0]["value"]; $v2b61851547 = $v86066462c3[0]["type"]; if ($v2b61851547 == "array") { $pc9b47287 = $pb16df866->getPHPParserEmulative()->parse("<?php\n" . $v30857f7eca . "\n?>"); $v30857f7eca = $pb16df866->getArrayItems($pc9b47287[0]->items); if (is_array($v30857f7eca)) { $pc37695cb = count($v30857f7eca); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pe5a746ff = $v30857f7eca[$v43dd7d0051]; switch (strtolower($pe5a746ff["key"])) { case "presentation_id": $v93756c94b3 = $pe5a746ff["value"]; $v55c04fed81 = $pe5a746ff["value_type"]; break; case "url": $v14e509a0fb = $pe5a746ff["value"]; $pf87c99f0 = $pe5a746ff["value_type"]; break; case "external_vars": if (isset($pe5a746ff["items"])) { $pc5a892eb = $pe5a746ff["items"]; $pf5a42197 = "array"; } else { $pc5a892eb = $pe5a746ff["value"]; $pf5a42197 = $pe5a746ff["value_type"]; } break; case "includes": if (isset($pe5a746ff["items"])) { $pc06f1034 = $pe5a746ff["items"]; $v177769266f = "array"; } else { $pc06f1034 = $pe5a746ff["value"]; $v177769266f = $pe5a746ff["value_type"]; } break; case "includes_once": if (isset($pe5a746ff["items"])) { $v3dd01781cd = $pe5a746ff["items"]; $pbb908a41 = "array"; } else { $v3dd01781cd = $pe5a746ff["value"]; $pbb908a41 = $pe5a746ff["value_type"]; } break; } } } } unset($v9073377656["func_name"]); unset($v9073377656["func_args"]); $v3d778afc51 = array( "project" => $v93756c94b3, "project_type" => self::getConfiguredParsedType($v55c04fed81), "page" => $v14e509a0fb, "page_type" => self::getConfiguredParsedType($pf87c99f0), "external_vars" => $pc5a892eb, "external_vars_type" => self::getConfiguredParsedType($pf5a42197, array("", "string", "variable", "array")), "includes" => $pc06f1034, "includes_type" => self::getConfiguredParsedType($v177769266f, array("", "string", "variable", "array")), "includes_once" => $v3dd01781cd, "includes_once_type" => self::getConfiguredParsedType($pbb908a41, array("", "string", "variable", "array")), "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ), ); $v9073377656 = array_merge($v9073377656, $v3d778afc51); $v9073377656["label"] = "Call $v93756c94b3/$v14e509a0fb"; return $v9073377656; } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pf5a42197 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["external_vars_type"][0]["value"]; if ($pf5a42197 == "array") { $pc5a892eb = $v3c3af72a1c["childs"]["properties"][0]["childs"]["external_vars"]; $pc5a892eb = self::parseArrayItems($pc5a892eb); } else $pc5a892eb = $v3c3af72a1c["childs"]["properties"][0]["childs"]["external_vars"][0]["value"]; $v177769266f = $v3c3af72a1c["childs"]["properties"][0]["childs"]["includes_type"][0]["value"]; if ($v177769266f == "array") { $pc06f1034 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["includes"]; $pc06f1034 = self::parseArrayItems($pc06f1034); } else $pc06f1034 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["includes"][0]["value"]; $pbb908a41 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["includes_once_type"][0]["value"]; if ($pbb908a41 == "array") { $v3dd01781cd = $v3c3af72a1c["childs"]["properties"][0]["childs"]["includes_once"]; $v3dd01781cd = self::parseArrayItems($v3dd01781cd); } else $v3dd01781cd = $v3c3af72a1c["childs"]["properties"][0]["childs"]["includes_once"][0]["value"]; $pef349725 = array( "project" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["project"][0]["value"], "project_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["project_type"][0]["value"], "page" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["page"][0]["value"], "page_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["page_type"][0]["value"], "external_vars" => $pc5a892eb, "external_vars_type" => $pf5a42197, "includes" => $pc06f1034, "includes_type" => $v177769266f, "includes_once" => $v3dd01781cd, "includes_once_type" => $pbb908a41, ); $pef349725 = self::parseResultVariableProperties($v3c3af72a1c, $pef349725); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v067674f4e4 = ""; if ($pef349725["project"] && $pef349725["page"]) { $v1cfba8c105 = self::getPropertiesResultVariableCode($pef349725); $pf5a42197 = $pef349725["external_vars_type"]; if ($pf5a42197 == "array") $pc5a892eb = self::getArrayString($pef349725["external_vars"]); else $pc5a892eb = self::getVariableValueCode($pef349725["external_vars"], $pf5a42197); $v177769266f = $pef349725["includes_type"]; if ($v177769266f == "array") $pc06f1034 = self::getArrayString($pef349725["includes"]); else $pc06f1034 = self::getVariableValueCode($pef349725["includes"], $pc06f1034); $pbb908a41 = $pef349725["includes_once_type"]; if ($pbb908a41 == "array") $v3dd01781cd = self::getArrayString($pef349725["includes_once"]); else $v3dd01781cd = self::getVariableValueCode($pef349725["includes_once"], $v3dd01781cd); $v067674f4e4 = $v54bb17785b . $v1cfba8c105; $v067674f4e4 .= "call_presentation_layer_web_service(array("; $v067674f4e4 .= '"presentation_id" => ' . self::getVariableValueCode($pef349725["project"], $pef349725["project_type"]) . ", "; $v067674f4e4 .= '"url" => ' . self::getVariableValueCode($pef349725["page"], $pef349725["page_type"]) . ", "; $v067674f4e4 .= '"external_vars" => ' . ($pc5a892eb ? $pc5a892eb : "null") . ", "; $v067674f4e4 .= '"includes" => ' . ($pc06f1034 ? $pc06f1034 : "null") . ", "; $v067674f4e4 .= '"includes_once" => ' . ($v3dd01781cd ? $v3dd01781cd : "null"); $v067674f4e4 .= "));\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
