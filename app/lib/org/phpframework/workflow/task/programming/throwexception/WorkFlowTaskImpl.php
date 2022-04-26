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

namespace WorkFlowTask\programming\throwexception; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_throw") { $v52ff2b3770 = $v5faa4b8a01->expr; $v9073377656 = $pb16df866->getNewObjectProps($v52ff2b3770); if ($v9073377656) { $v9073377656["exception_type"] = "new"; $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ); return $v9073377656; } else { $v74aa7d558c = strtolower($v52ff2b3770->getType()); $v067674f4e4 = $pb16df866->printCodeExpr($v52ff2b3770); $v067674f4e4 = $pb16df866->getStmtValueAccordingWithType($v067674f4e4, $v74aa7d558c); $v9073377656 = array( "exception_type" => "existent", "exception_var_name" => $v067674f4e4, "exception_var_type" => self::getConfiguredParsedType( $pb16df866->getStmtType($v52ff2b3770) ), "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ), ); return $v9073377656; } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "exception_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["exception_type"][0]["value"], "class_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["class_name"][0]["value"], "class_args" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["class_args"], "exception_var_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["exception_var_name"][0]["value"], "exception_var_type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["exception_var_type"][0]["value"], ); $pef349725 = self::parseResultVariableProperties($v3c3af72a1c, $pef349725); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v067674f4e4 = ""; if ($pef349725["exception_type"] == "new" && trim($pef349725["class_name"])) { $v1cfba8c105 = self::getPropertiesResultVariableCode($pef349725); $v86066462c3 = self::getParametersString($pef349725["class_args"]); $v067674f4e4 = $v54bb17785b . "throw " . ($v1cfba8c105 ? "$v1cfba8c105 = " : "") . "new " . $pef349725["class_name"] . "($v86066462c3);\n"; } else if ($pef349725["exception_type"] == "existent" && trim($pef349725["exception_var_name"])) { $v1cfba8c105 = self::getVariableValueCode($pef349725["exception_var_name"], $pef349725["exception_var_type"]); $v067674f4e4 = $v54bb17785b . "throw $v1cfba8c105;\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
