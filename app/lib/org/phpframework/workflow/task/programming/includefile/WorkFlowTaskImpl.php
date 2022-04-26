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

namespace WorkFlowTask\programming\includefile; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "expr_include") { $v52ff2b3770 = $v5faa4b8a01->expr; $v74aa7d558c = strtolower($v52ff2b3770->getType()); $pf3dc0762 = $pb16df866->printCodeExpr($v5faa4b8a01->expr); $pf3dc0762 = $pb16df866->getStmtValueAccordingWithType($pf3dc0762, $v74aa7d558c); if ($pf3dc0762) { $v3fb9f41470 = $pb16df866->getStmtType($v52ff2b3770); return array( "file_path" => $pf3dc0762, "type" => self::getConfiguredParsedType($v3fb9f41470, array("", "string")), "once" => $v5faa4b8a01->type == 2 || $v5faa4b8a01->type == 4, "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ), ); } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "file_path" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["file_path"][0]["value"], "type" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["type"][0]["value"], "once" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["once"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v1cfba8c105 = self::getVariableValueCode($pef349725["file_path"], $pef349725["type"]); $v067674f4e4 = $v1cfba8c105 ? $v54bb17785b . "include" . ($pef349725["once"] ? "_once" : "") . " $v1cfba8c105;\n" : ""; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
