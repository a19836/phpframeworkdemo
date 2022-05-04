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

namespace WorkFlowTask\programming\_if; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_if") { $v352910ba5a = $v5faa4b8a01->cond; $peb3eca83 = $v5faa4b8a01->stmts; $v0b55c7afa2 = $v5faa4b8a01->else->stmts; $pe9decd86 = $v5faa4b8a01->elseifs; $v5bd7564855 = $pb16df866->getConditions($v352910ba5a); if (!isset($v5bd7564855)) { return null; } $v3ae1d9a1fd = self::createTasksPropertiesFromCodeStmts($peb3eca83, $pb16df866); $v9073377656 = $v5bd7564855; $v9073377656["exits"] = array( "true" => array( "color" => "#00c000", "label" => "True", ), "false" => array( "color" => "#c00000", "label" => "False", ), ); $pa47792b3 = self::createTasksPropertiesFromCodeStmts($v0b55c7afa2, $pb16df866); $v6939304e91 = array(); $v1f377b389c = array(); if ($v3ae1d9a1fd && $v3ae1d9a1fd[0]["id"]) { $v6939304e91["true"][] = array("task_id" => $v3ae1d9a1fd[0]["id"]); $pb16df866->addNextTaskToUndefinedTaskExits($v3ae1d9a1fd[count($v3ae1d9a1fd) - 1]); $v1f377b389c[] = $v3ae1d9a1fd; } else { $v6939304e91["true"][] = array("task_id" => "#next_task#"); } $v43c45891e5 = null; if ($pa47792b3) { $v43c45891e5 = $pa47792b3[0]["id"]; $pb16df866->addNextTaskToUndefinedTaskExits($pa47792b3[count($pa47792b3) - 1]); $v1f377b389c[] = $pa47792b3; } $pa255672d = $this->getTaskClassInfo(); $pa255672d["obj"] = $this; if ($pe9decd86) for ($v43dd7d0051 = count($pe9decd86) - 1; $v43dd7d0051 >= 0; --$v43dd7d0051) { $v0c095d1764 = $pe9decd86[$v43dd7d0051]; $pe68af5c5 = $v0c095d1764->cond; $pdf5db68c = $v0c095d1764->stmts; $v49d84fa542 = $pb16df866->getConditions($pe68af5c5); if (!isset($v49d84fa542)) { return null; } $v90d4c3d9fa = self::createTasksPropertiesFromCodeStmts($pdf5db68c, $pb16df866); $pfc2cea11 = $v49d84fa542; $pfc2cea11["exits"] = array( "true" => array( "color" => "#00c000", "label" => "True", ), "false" => array( "color" => "#c00000", "label" => "False", ), ); $pbc043ca9 = array(); if ($v90d4c3d9fa && $v90d4c3d9fa[0]["id"]) { $pbc043ca9["true"][] = array("task_id" => $v90d4c3d9fa[0]["id"]); } else { $pbc043ca9["true"][] = array("task_id" => "#next_task#"); } if ($v43c45891e5) { $pbc043ca9["false"][] = array("task_id" => $v43c45891e5); } else { $pbc043ca9["false"][] = array("task_id" => "#next_task#"); } $pc2d66c7c = $pb16df866->createXMLTask($pa255672d, $pfc2cea11, $pbc043ca9); if ($pc2d66c7c) { $v43c45891e5 = $pc2d66c7c["id"]; $v1f377b389c[] = array($pc2d66c7c); } else { return null; } if ($v90d4c3d9fa) { $pb16df866->addNextTaskToUndefinedTaskExits($v90d4c3d9fa[count($v90d4c3d9fa) - 1]); $v1f377b389c[] = $v90d4c3d9fa; } } if ($v352910ba5a) $v9073377656["label"] = "If " . $pb16df866->printCodeStatement($v352910ba5a); if ($v43c45891e5) { $v6939304e91["false"][] = array("task_id" => $v43c45891e5); } else { $v6939304e91["false"][] = array("task_id" => "#next_task#"); } return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pfe401b9e = $v3c3af72a1c["childs"]["properties"][0]["childs"]["group"][0]; $pef349725 = self::parseGroup($pfe401b9e); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v9547cc3e66 = self::getCommonTaskExitIdFromTaskPaths($v1d696dbd12, $v539082ff30["id"]); $pde6f8fba = array(); if ($v56dcda6d50) $pde6f8fba = is_array($v56dcda6d50) ? $v56dcda6d50 : array($v56dcda6d50); if ($v9547cc3e66) $pde6f8fba[] = $v9547cc3e66; $v18949fd80e = self::printTask($v1d696dbd12, $v539082ff30["exits"]["true"], $pde6f8fba, $v54bb17785b . "\t", $v5d3813882f); $v9cf1c7968d = self::printTask($v1d696dbd12, $v539082ff30["exits"]["false"], $pde6f8fba, $v54bb17785b . "\t", $v5d3813882f); $v18949fd80e = $v18949fd80e ? $v18949fd80e : "\n\n"; $v9cf1c7968d = $v9cf1c7968d ? $v9cf1c7968d : "\n\n"; $v067674f4e4 = $v54bb17785b . "if (" . self::printGroup($pef349725) . ") {"; $v067674f4e4 .= $v18949fd80e; $v067674f4e4 .= !$v54bb17785b && !preg_match("/\s/", substr($v067674f4e4, -1)) ? " " : ""; $v067674f4e4 .= $v54bb17785b . " }\n"; $v9cf1c7968d = trim($v9cf1c7968d); if (!empty($v9cf1c7968d)) { $v067674f4e4 .= $v54bb17785b . "else {"; $v067674f4e4 .= $v9cf1c7968d; $v067674f4e4 .= "\n$v54bb17785b}\n"; } return $v067674f4e4 . ($v9547cc3e66 ? self::printTask($v1d696dbd12, $v9547cc3e66, $v56dcda6d50, $v54bb17785b, $v5d3813882f) : ''); } } ?>
