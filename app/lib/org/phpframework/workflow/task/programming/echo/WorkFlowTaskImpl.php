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

namespace WorkFlowTask\programming\_echo; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_echo") { $v4747c6905c = $v5faa4b8a01->exprs; $v067674f4e4 = ""; $v3fb9f41470 = ""; if ($v4747c6905c) { if (count($v4747c6905c) == 1) { $v52ff2b3770 = $v4747c6905c[0]; $v74aa7d558c = strtolower($v52ff2b3770->getType()); if (!$pb16df866->isAssignExpr($v52ff2b3770) && ($v74aa7d558c == "expr_funccall" || $v74aa7d558c == "expr_methodcall" || $v74aa7d558c == "expr_staticcall" || $v74aa7d558c == "expr_new" || $v74aa7d558c == "expr_array")) { return null; } $v067674f4e4 = $pb16df866->printCodeExpr($v52ff2b3770); $v067674f4e4 = $pb16df866->getStmtValueAccordingWithType($v067674f4e4, $v74aa7d558c); $v3fb9f41470 = $pb16df866->getStmtType($v52ff2b3770); } else { $pc37695cb = count($v4747c6905c); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v067674f4e4 .= ($v43dd7d0051 > 0 ? ", " : "") . $pb16df866->printCodeExpr($v4747c6905c[$v43dd7d0051]); } } } $v9073377656 = array( "value" => $v067674f4e4, "type" => self::getConfiguredParsedType($v3fb9f41470), "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#dca80a", ), ), ); return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "value" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["value"][0]["value"], "type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["type"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v67db1bd535 = self::getVariableValueCode($pef349725["value"], $pef349725["type"]); $v067674f4e4 = $v67db1bd535 ? $v54bb17785b . "echo $v67db1bd535;\n" : ""; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
