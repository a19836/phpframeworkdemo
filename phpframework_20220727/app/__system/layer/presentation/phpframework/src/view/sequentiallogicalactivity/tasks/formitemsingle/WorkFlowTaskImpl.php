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

namespace WorkFlowTask\programming\slaitemsingle; include_once get_lib("org.phpframework.workflow.WorkFlowTask"); class WorkFlowTaskImpl extends \WorkFlowTask { public function createTaskPropertiesFromCodeStmt($stmt, $WorkFlowTaskCodeParser, &$exits = null, &$inner_tasks = null) { return null; } public function parseProperties(&$task) { $raw_data = $task["raw_data"]; $properties = $raw_data["childs"]["properties"][0]["childs"]; $properties = \MyXML::complexArrayToBasicArray($properties, array("lower_case_keys" => true)); return $properties["properties"]; } public function printCode($tasks, $stop_task_id, $prefix_tab = "", $options = null) { $data = $this->data; $properties = $data["properties"]; $next_task = self::printTask($tasks, $data["exits"][self::DEFAULT_EXIT_ID][0], $stop_task_id, $prefix_tab, $options); return array( "properties" => $properties, "next" => $next_task, ); } } ?>
