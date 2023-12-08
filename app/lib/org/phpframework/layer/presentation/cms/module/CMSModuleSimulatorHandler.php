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
include_once get_lib("org.phpframework.layer.presentation.cms.module.ICMSModuleSimulatorHandler"); abstract class CMSModuleSimulatorHandler implements ICMSModuleSimulatorHandler { private $v7bc2e7898b; public function setCMSModuleHandler($v7bc2e7898b) { $this->v7bc2e7898b = $v7bc2e7898b; } public function getCMSModuleHandler() { return $this->v7bc2e7898b; } public static function getCMSModuleSimulatorHandlerImplFilePath($v4a650a2b36) { return "$v4a650a2b36/CMSModuleSimulatorHandlerImpl.php"; } public function simulate(&$v30857f7eca = false, &$v881367f1c2 = false) { return ""; } public function simulateEditFormFields(&$v30857f7eca = false, &$v881367f1c2 = false) { $v182f7d984b = $v30857f7eca; $v881367f1c2 = array( "elements" => array() ); if ($v182f7d984b && !empty($v182f7d984b["fields"]) && is_array($v182f7d984b["fields"])) foreach ($v182f7d984b["fields"] as $pe5c5e2fe => $v02a69d4e0f) $v881367f1c2["elements"][".module_edit .form_fields > .form_field.$pe5c5e2fe > label"] = "fields/$pe5c5e2fe/field/label/value"; return $this->getCMSModuleHandler()->execute($v182f7d984b); } public function simulateListFormFields(&$v30857f7eca = false, &$v881367f1c2 = false) { $v182f7d984b = $v30857f7eca; $v881367f1c2 = array( "elements" => array() ); if ($v182f7d984b && !empty($v182f7d984b["fields"]) && is_array($v182f7d984b["fields"])) foreach ($v182f7d984b["fields"] as $pe5c5e2fe => $v02a69d4e0f) $v881367f1c2["elements"][".module_list .list_items > .list_container > table.list_table > thead > tr > th.list_column.$pe5c5e2fe > label"] = "fields/$pe5c5e2fe/field/label/value"; return $this->getCMSModuleHandler()->execute($v182f7d984b); } } ?>
