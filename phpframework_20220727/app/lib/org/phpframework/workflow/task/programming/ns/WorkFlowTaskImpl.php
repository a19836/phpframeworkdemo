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

namespace WorkFlowTask\programming\ns; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null) { $pe83cda0c = strtolower($v5faa4b8a01->getType()); if ($pe83cda0c == "stmt_namespace") { $v67db1bd535 = $pb16df866->printCodeNodeName($v5faa4b8a01); $v75c00de05b = $v5faa4b8a01->stmts; $pb735b03c = self::createTasksPropertiesFromCodeStmts($v75c00de05b, $pb16df866); $v9073377656 = array( "value" => $v67db1bd535, "label" => "Namespace: $v67db1bd535", "exits" => array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ) ); $v6939304e91 = array(); if ($pb735b03c) { $v6939304e91[self::DEFAULT_EXIT_ID][] = array("task_id" => $pb735b03c[0]["id"]); $pb16df866->addNextTaskToUndefinedTaskExits($pb735b03c[count($pb735b03c) - 1]); $v1f377b389c = array($pb735b03c); } else $v6939304e91[self::DEFAULT_EXIT_ID][] = array("task_id" => "#next_task#"); return $v9073377656; } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "value" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["value"][0]["value"], ); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v067674f4e4 = "namespace " . $pef349725["value"] . ";"; return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
