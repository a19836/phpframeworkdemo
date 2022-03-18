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

namespace WorkFlowTask\programming\getdbdriver; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $v9073377656 = $pb16df866->getObjectMethodProps($v5faa4b8a01); if ($v9073377656) { $v603bd47baf = $v9073377656["method_name"]; if ($v603bd47baf == "getBroker" && empty($v9073377656["method_static"])) { $v86066462c3 = $v9073377656["method_args"]; $v872f5b4dbb = $v86066462c3[0]["value"]; $v1c379098f6 = $v86066462c3[0]["type"]; unset($v9073377656["method_name"]); unset($v9073377656["method_args"]); unset($v9073377656["method_static"]); $v9073377656["db_driver"] = $v872f5b4dbb; $v9073377656["db_driver_type"] = self::getConfiguredParsedType($v1c379098f6); $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#a6abac", ), ); return $v9073377656; } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "method_obj" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["method_obj"][0]["value"], "db_driver" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["db_driver"][0]["value"], "db_driver_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["db_driver_type"][0]["value"], ); $pef349725 = self::parseResultVariableProperties($v3c3af72a1c, $pef349725); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v067674f4e4 = ""; if ($pef349725["db_driver"]) { $v1cfba8c105 = self::getPropertiesResultVariableCode($pef349725); $pb537c4d4 = $pef349725["method_obj"]; if ($pb537c4d4) { $v0ee64549f2 = strpos($pb537c4d4, "::"); $pd6f5fc01 = strpos($pb537c4d4, "->"); $pb537c4d4 = substr($pb537c4d4, 0, 1) != '$' && (!$v0ee64549f2 || ($pd6f5fc01 && $v0ee64549f2 > $pd6f5fc01)) ? '$' . $pb537c4d4 : $pb537c4d4; $pb537c4d4 .= "->"; } $v067674f4e4 = $v54bb17785b . $v1cfba8c105; $v067674f4e4 .= $pb537c4d4 . "getBroker("; $v067674f4e4 .= self::getVariableValueCode($pef349725["db_driver"], $pef349725["db_driver_type"]); $v067674f4e4 .= ");\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
