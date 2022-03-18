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

namespace WorkFlowTask\programming\loop; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { protected $is_loop_task = true; public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_for" || $pe83cda0c == "stmt_do" || $pe83cda0c == "stmt_while") { $pcdff30fd = $v5faa4b8a01->init; $v9e394b2939 = $v5faa4b8a01->cond; $v8f51b9ab8b = $v5faa4b8a01->loop; $v75c00de05b = $v5faa4b8a01->stmts; $pcdff30fd = !$pcdff30fd || is_array($pcdff30fd) ? $pcdff30fd : array($pcdff30fd); $v9e394b2939 = !$v9e394b2939 || is_array($v9e394b2939) ? $v9e394b2939 : array($v9e394b2939); $v8f51b9ab8b = !$v8f51b9ab8b || is_array($v8f51b9ab8b) ? $v8f51b9ab8b : array($v8f51b9ab8b); $pbbb7fbfa = array(); if ($pcdff30fd) { $pc37695cb = count($pcdff30fd); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $pcdff30fd[$v43dd7d0051]; $v1cfba8c105 = null; if ($pb16df866->isAssignExpr($v342a134247)) { $v9073377656 = $pb16df866->getVariableNameProps($v342a134247); $v1cfba8c105 = self::getPropertiesResultVariableCode($v9073377656, false); } if ($v1cfba8c105) { $v74aa7d558c = strtolower($v342a134247->expr->getType()); $v67db1bd535 = $pb16df866->printCodeExpr($v342a134247->expr); $v67db1bd535 = $pb16df866->getStmtValueAccordingWithType($v67db1bd535, $v74aa7d558c); $v9073377656 = array( "name" => $v1cfba8c105, "value" => $v67db1bd535, "type" => $pb16df866->getStmtType($v342a134247->expr), ); } else { $v9073377656 = array( "code" => $this->printCodeExpr($v342a134247), ); } $pbbb7fbfa[] = $v9073377656; } } $pab629e6e = array(); if ($v9e394b2939) { $pc37695cb = count($v9e394b2939); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $v9e394b2939[$v43dd7d0051]; $v9073377656 = $pb16df866->getConditions($v342a134247); if (isset($v9073377656)) { $pab629e6e[] = $v9073377656; } } } $v7814e6a657 = array(); if ($v8f51b9ab8b) { $pc37695cb = count($v8f51b9ab8b); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $v8f51b9ab8b[$v43dd7d0051]; $v8773b3a63a = strtolower($v342a134247->getType()); $v1cfba8c105 = null; if ($v8773b3a63a == "expr_postinc" || $v8773b3a63a == "expr_preinc" || $v8773b3a63a == "expr_predec" || $v8773b3a63a == "expr_postdec") { $v9073377656 = $pb16df866->getVariableNameProps($v342a134247); $v1cfba8c105 = self::getPropertiesResultVariableCode($v9073377656, false); } if ($v1cfba8c105) { $v9073377656 = array( "name" => $v1cfba8c105, "inc_or_dec" => $v8773b3a63a == "expr_postinc" || $v8773b3a63a == "expr_preinc" ? "inc" : "dec", ); } else { $v9073377656 = array( "code" => $this->printCodeExpr($v342a134247), ); } $v7814e6a657[] = $v9073377656; } } $pb735b03c = self::createTasksPropertiesFromCodeStmts($v75c00de05b, $pb16df866); $v9073377656 = array( "init" => $pbbb7fbfa, "cond" => $pab629e6e, "inc" => $v7814e6a657, "execute_first_iteration" => $pe83cda0c == "stmt_do" ? 1 : 0, "exits" => array( "start_exit" => array( "color" => "#2a679b", ), self::DEFAULT_EXIT_ID => array( "color" => "#000", ), ), ); $v6939304e91 = array(); $v6939304e91[self::DEFAULT_EXIT_ID][] = array("task_id" => "#next_task#"); if ($pb735b03c) { $v6939304e91["start_exit"][] = array("task_id" => $pb735b03c[0]["id"]); $pb735b03c = $pb16df866->cleanInvalidExitsFromTasks($pb735b03c); $pb735b03c = $pb16df866->stopLoopInnerTasksToBeConnectedToOtherOutsideTasks($pb735b03c); $v1f377b389c = array($pb735b03c); } return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pcdff30fd = $v3c3af72a1c["childs"]["properties"][0]["childs"]["init"]; $v9e394b2939 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["cond"][0]["childs"]["group"][0]; $v2d22d85b1f = $v3c3af72a1c["childs"]["properties"][0]["childs"]["inc"]; $pc22c95e9 = $v3c3af72a1c["childs"]["properties"][0]["childs"]["execute_first_iteration"][0]["value"]; $pbbb7fbfa = array(); $pc37695cb = $pcdff30fd ? count($pcdff30fd) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $pcdff30fd[$v43dd7d0051]["childs"]; if (isset($v342a134247["name"])) { $pbbb7fbfa[] = array( "name" => $v342a134247["name"][0]["value"], "value" => $v342a134247["value"][0]["value"], "type" => $v342a134247["type"][0]["value"], ); } else { $pbbb7fbfa[] = array( "code" => $v342a134247["code"][0]["value"], ); } } $pb90e85ce = array(); $pc37695cb = $v2d22d85b1f ? count($v2d22d85b1f) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $v2d22d85b1f[$v43dd7d0051]["childs"]; if (isset($v342a134247["name"])) { $pb90e85ce[] = array( "name" => $v342a134247["name"][0]["value"], "inc_or_dec" => $v342a134247["inc_or_dec"][0]["value"], ); } else { $pb90e85ce[] = array( "code" => $v342a134247["code"][0]["value"], ); } } $pef349725 = array( "init" => $pbbb7fbfa, "cond" => self::parseGroup($v9e394b2939), "inc" => $pb90e85ce, "execute_first_iteration" => $pc22c95e9, ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $pcdff30fd = $pef349725["init"]; $v9e394b2939 = $pef349725["cond"]; $v2d22d85b1f = $pef349725["inc"]; $pc22c95e9 = $pef349725["execute_first_iteration"]; $v75c838990a = $pc22c95e9 == "1" ? ";\n$v54bb17785b" : ", "; $v84386651c3 = ""; $pc37695cb = $pcdff30fd ? count($pcdff30fd) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $pcdff30fd[$v43dd7d0051]; if (isset($v342a134247["name"])) { $v9a8b7dc209 = self::getVariableValueCode($v342a134247["name"], "variable") . " = " . self::getVariableValueCode($v342a134247["value"], $v342a134247["type"]); } else { $v9a8b7dc209 = trim($v342a134247["code"]); $v9a8b7dc209 = substr($v9a8b7dc209, strlen($v9a8b7dc209) - 1) == ";" ? substr($v9a8b7dc209, 0, strlen($v9a8b7dc209) - 1) : $v9a8b7dc209; } if ($v9a8b7dc209) { $v84386651c3 .= ($v84386651c3 ? $v75c838990a : "") . $v9a8b7dc209; } } $v3035331f56 = self::printGroup($v9e394b2939); $v87d3776619 = $pc22c95e9 == "1" ? ";\n$v54bb17785b\t" : ", "; $pc3e7392a = ""; $pc37695cb = $v2d22d85b1f ? count($v2d22d85b1f) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $v2d22d85b1f[$v43dd7d0051]; if (isset($v342a134247["name"])) { $v9a8b7dc209 = self::getVariableValueCode($v342a134247["name"], "variable") . ($v342a134247["inc_or_dec"] == "decrement" ? "--" : "++"); } else { $v9a8b7dc209 = trim($v342a134247["code"]); $v9a8b7dc209 = substr($v9a8b7dc209, strlen($v9a8b7dc209) - 1) == ";" ? substr($v9a8b7dc209, 0, strlen($v9a8b7dc209) - 1) : $v9a8b7dc209; } if ($v9a8b7dc209) { $pc3e7392a .= ($pc3e7392a ? $v87d3776619 : "") . $v9a8b7dc209; } } $pde6f8fba = array(); if ($v56dcda6d50) $pde6f8fba = is_array($v56dcda6d50) ? $v56dcda6d50 : array($v56dcda6d50); if ($v539082ff30["exits"][self::DEFAULT_EXIT_ID][0]) $pde6f8fba = array_merge($pde6f8fba, $v539082ff30["exits"][self::DEFAULT_EXIT_ID]); $pafaf19a2 = self::printTask($v1d696dbd12, $v539082ff30["exits"]["start_exit"], $pde6f8fba, $v54bb17785b . "\t", $v5d3813882f); if ($pc22c95e9) { $v067674f4e4 = $v84386651c3 ? $v54bb17785b . "$v84386651c3;\n\n" : ""; $v067674f4e4 .= $v54bb17785b . "do {"; $v067674f4e4 .= !$pafaf19a2 && !$pc3e7392a ? "\n\n" : $pafaf19a2; $v067674f4e4 .= $pc3e7392a ? "\n" . $v54bb17785b . "\t$pc3e7392a;\n" : ""; $v067674f4e4 .= !$v54bb17785b && !preg_match("/\s/", substr($v067674f4e4, -1)) ? " " : ""; $v067674f4e4 .= $v54bb17785b . "}\n"; $v067674f4e4 .= $v54bb17785b . "while ($v3035331f56);\n"; } else { if (!$v84386651c3 && !$pc3e7392a) { $v067674f4e4 = $v54bb17785b . "while ($v3035331f56) {"; } else { $v067674f4e4 = $v54bb17785b . "for ($v84386651c3; $v3035331f56; $pc3e7392a) {"; } $v067674f4e4 .= $pafaf19a2 ? $pafaf19a2 : "\n\n"; $v067674f4e4 .= !$v54bb17785b && !preg_match("/\s/", substr($v067674f4e4, -1)) ? " " : ""; $v067674f4e4 .= $v54bb17785b . "}\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID][0], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
