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

namespace WorkFlowTask\programming\callfunction; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($v5faa4b8a01, $pb16df866, &$v6939304e91 = null, &$v1f377b389c = null, &$pa41f3d97 = null) { $v9073377656 = $pb16df866->getFunctionProps($v5faa4b8a01); if ($v9073377656) { $v683170574f = $pb16df866->getReservedFunctionNames(); $v24b0e52635 = $v9073377656["func_name"]; if ($v24b0e52635 && !in_array($v24b0e52635, $v683170574f)) { $v9073377656["label"] = "Call $v24b0e52635"; self::joinTaskPropertiesWithIncludeFileTaskPropertiesSibling($v9073377656, $pa41f3d97); $v9073377656["exits"] = array( self::DEFAULT_EXIT_ID => array( "color" => "#426efa", ), ); return $v9073377656; } } } public function parseProperties(&$v7f5911d32d) { $v3c3af72a1c = $v7f5911d32d["raw_data"]; $pef349725 = array( "func_name" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["func_name"][0]["value"], "func_args" => $v3c3af72a1c["childs"]["properties"][0]["childs"]["func_args"], ); $pef349725 = self::parseIncludeFileProperties($v3c3af72a1c, $pef349725); $pef349725 = self::parseResultVariableProperties($v3c3af72a1c, $pef349725); return $pef349725; } public function printCode($v1d696dbd12, $v56dcda6d50, $v54bb17785b = "", $v5d3813882f = null) { $v539082ff30 = $this->data; $pef349725 = $v539082ff30["properties"]; $v1cfba8c105 = self::getPropertiesResultVariableCode($pef349725); $pa051dc1c = trim($pef349725["func_name"]); $v1558758f24 = self::getPropertiesIncludeFileCode($pef349725); $v067674f4e4 = $v1558758f24 ? $v54bb17785b . $v1558758f24 . "\n" : ""; if ($pa051dc1c) { $v86066462c3 = self::getParametersString($pef349725["func_args"]); $v067674f4e4 .= $v54bb17785b . $v1cfba8c105 . "$pa051dc1c($v86066462c3);\n"; } return $v067674f4e4 . self::printTask($v1d696dbd12, $v539082ff30["exits"][self::DEFAULT_EXIT_ID], $v56dcda6d50, $v54bb17785b, $v5d3813882f); } } ?>
