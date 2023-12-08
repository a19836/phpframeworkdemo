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
namespace WorkFlowTask\programming\setblockparams; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { const MAIN_VARIABLE_NAME = "block_local_variables"; public function __construct() { $this->priority = 3; } public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "expr_assign") { $v9073377656 = $pb16df866->getVariableNameProps($v5faa4b8a01); $v9073377656 = $v9073377656 ? $v9073377656 : array(); if (trim($v9073377656["result_var_name"]) != self::MAIN_VARIABLE_NAME) { return null; } $v52ff2b3770 = $v5faa4b8a01->expr; $v74aa7d558c = strtolower($v52ff2b3770->getType()); $v067674f4e4 = $pb16df866->printCodeExpr($v52ff2b3770); $v067674f4e4 = $pb16df866->getStmtValueAccordingWithType($v067674f4e4, $v74aa7d558c); $v3fb9f41470 = $pb16df866->getStmtType($v52ff2b3770); $v9073377656 = array(); $v9073377656["main_variable_name"] = self::MAIN_VARIABLE_NAME; $v9073377656["value"] = $v067674f4e4; $v9073377656["type"] = self::getConfiguredParsedType($v3fb9f41470); $v9073377656["label"] = "Add param: " . self::prepareTaskPropertyValueLabelFromCodeStmt($v067674f4e4); $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ); return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "main_variable_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["main_variable_name"][0]["value"], "value" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["value"][0]["value"], "type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["type"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $pc4de5ee4 = '$' . ($pef349725["main_variable_name"] ? $pef349725["main_variable_name"] : self::MAIN_VARIABLE_NAME); $v067674f4e4 = $v54bb17785b . $pc4de5ee4 . " = " . self::getVariableValueCode($pef349725["value"], $pef349725["type"]) . ";\n"; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
