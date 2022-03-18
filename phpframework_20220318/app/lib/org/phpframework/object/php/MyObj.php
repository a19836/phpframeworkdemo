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

include_once get_lib("org.phpframework.object.ObjType"); class MyObj extends ObjType { public function __construct() { $this->field = false; } public function setData($v539082ff30) { $v5c1c342594 = parent::setData($v539082ff30); if(is_array($this->data)) { foreach($this->data as $pbfa01ed1 => $v67db1bd535) { $v24b0e52635 = "set" . str_replace(" ", "", ucwords(strtolower( str_replace(array("_", "-"), " ", $pbfa01ed1) ))); if(method_exists($this, $v24b0e52635) && $v24b0e52635 != "setData" && $v24b0e52635 != "setField") eval("\$this->{$v24b0e52635}(\$v67db1bd535);"); else $v5c1c342594 = false; } } return $v5c1c342594; } public function getData() { $v9d05685f42 = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "Y", "X", "W", "Z"); $pb2bb35e9 = get_class_methods($this); foreach($pb2bb35e9 as $v603bd47baf) { if(substr($v603bd47baf, 0, 3) == "get" && $v603bd47baf != "getData" && $v603bd47baf != "getField") { $v24b0e52635 = substr($v603bd47baf, 3); $v34f0a629d3 = substr($v24b0e52635, 0, 1); if(in_array($v34f0a629d3, $v9d05685f42)) { $v5e45ec9bb9 = strtolower($v34f0a629d3); for($v43dd7d0051 = 1; $v43dd7d0051 < strlen($v24b0e52635); $v43dd7d0051++) { $pc288256e = $v24b0e52635[$v43dd7d0051]; $v5e45ec9bb9 .= (in_array($pc288256e, $v9d05685f42) ? "_" : "").strtolower($pc288256e); } eval("\$this->data[\"{$v5e45ec9bb9}\"] = \$this->{$v603bd47baf}();"); } } } return parent::getData(); } } ?>
