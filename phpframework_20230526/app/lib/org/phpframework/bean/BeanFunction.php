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

include get_lib("org.phpframework.bean.exception.BeanFunctionException"); include_once get_lib("org.phpframework.phpscript.PHPCodePrintingHandler"); class BeanFunction { const AK = "DEvyNN0eB4+85k4t2rGDszwp1lB7UJgfHrsJKvKdqqfcJ++JWb//34E/9C5nIf0y"; public $name; public $parameters = array(); public $parent_object_reference = false; public $settings = array(); public function __construct($v5e813b295b, $v9367d5be85 = array(), $pa8551793 = false, $v30857f7eca = array()) { $this->name = trim($v5e813b295b); $this->settings = $v30857f7eca; $this->setParameters($v9367d5be85); $this->setParentObjectReference($pa8551793); $this->f085037e150(); } public function setParameters($v9367d5be85) { if($v9367d5be85) { $v47eff2e9c7 = array(); $pc37695cb = count($v9367d5be85); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v9acf40c110 = $v9367d5be85[$v43dd7d0051]; $v8a4df75785 = false; if(is_numeric($v9acf40c110["index"])) { $v8a4df75785 = $v9acf40c110["index"]; $v47eff2e9c7[] = $v8a4df75785; } else if(!array_key_exists("index", $v9acf40c110)) { $v47eff2e9c7 = MyArray::sort($v47eff2e9c7, SORT_NUMERIC); for($v9d27441e80 = 1; $v9d27441e80 <= count($v47eff2e9c7); $v9d27441e80++) { if($v9d27441e80 < $v47eff2e9c7[$v9d27441e80 - 1]) { $v8a4df75785 = $v9d27441e80; break; } } if(!$v8a4df75785) $v8a4df75785 = count($v47eff2e9c7) + 1; $v9367d5be85[$v43dd7d0051]["index"] = $v8a4df75785; $v47eff2e9c7[] = $v8a4df75785; } } $v9367d5be85 = MyArray::multisort($v9367d5be85, array(array('key'=>'index','sort'=>'asc'))); foreach($v9367d5be85 as $v9acf40c110) $this->parameters[$v9acf40c110["index"]] = new BeanArgument($v9acf40c110["index"], $v9acf40c110["value"], $v9acf40c110["reference"]); } } public function setParentObjectReference($pa8551793) { $this->parent_object_reference = $pa8551793; } private function f085037e150() { if(empty($this->name)) { launch_exception(new BeanFunctionException(1, $this->name)); return false; } return true; } public function getFunctionNSName() { $pa32be502 = $this->name; if ($this->settings["namespace"]) $pa32be502 = PHPCodePrintingHandler::prepareClassNameWithNameSpace($pa32be502, $this->settings["namespace"]); return $pa32be502; } } ?>
