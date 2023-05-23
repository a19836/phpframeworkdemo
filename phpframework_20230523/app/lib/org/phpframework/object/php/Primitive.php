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

include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); include_once get_lib("org.phpframework.object.ObjTypeHandler"); class Primitive extends ObjType { private $v3fb9f41470; public $is_primitive = true; public function __construct($v3fb9f41470, $v539082ff30 = false) { $this->setType($v3fb9f41470); if($v539082ff30 !== false) $this->setData($v539082ff30); } public static function getTypes() { $v4159504aa3 = ObjTypeHandler::getDBTypes(); $v4159504aa3["mixed"] = "Mixed"; $v4159504aa3["numeric"] = "Numeric"; $v4159504aa3["bool"] = "Boolean"; $v4159504aa3["array"] = "Array"; $v4159504aa3["object"] = "Object"; $v4159504aa3["string"] = "String"; $v4159504aa3["uuid"] = "UUID"; $v4159504aa3["cidr"] = "CIDR"; $v4159504aa3["inet"] = "INET"; $v4159504aa3["mac addr"] = "Mac Addr"; return $v4159504aa3; } public static function getNumericTypes() { $v4159504aa3 = ObjTypeHandler::getDBNumericTypes(); $v4159504aa3[] = "numeric"; $v4159504aa3[] = "bool"; return $v4159504aa3; } public function getType() {return $this->v3fb9f41470;} public function setType($v3fb9f41470) {$this->v3fb9f41470 = strtolower($v3fb9f41470);} public function setData($v539082ff30) { $v2068a4d581 = false; switch ($this->v3fb9f41470) { case "smallserial": case "intserial": case "bigserial": $v2068a4d581 = is_numeric($v539082ff30) && $v539082ff30 > 0; break; case "numeric": case "bit": case "tinyint": case "smallint": case "int": case "bigint": case "decimal": case "double": case "float": case "money": case "coordinate": $v2068a4d581 = is_numeric($v539082ff30); break; case "bool": case "boolean": $v2068a4d581 = $v539082ff30 === TRUE || $v539082ff30 === false || $v539082ff30 === 0 || $v539082ff30 === 1 || $v539082ff30 === "0" || $v539082ff30 === "1" || ObjTypeHandler::isDBTypeBoolean($v539082ff30); if (!$v2068a4d581) { $v956913c90f = strtolower($v539082ff30); $v2068a4d581 = $v956913c90f === "true" || $v956913c90f === "false"; } break; case "array": $v2068a4d581 = is_array($v539082ff30); break; case "object": $v2068a4d581 = is_object($v539082ff30); break; case "string": case "char": case "varchar": case "mediumtext": case "text": case "longtext": case "blob": case "longblob": $v2068a4d581 = !is_object($v539082ff30) && !is_array($v539082ff30); break; case "date": case "datetime": case "timestamp": case "time": $v2068a4d581 = !is_object($v539082ff30) && !is_array($v539082ff30); break; case "varchar(36)": case "uuid": case "varchar(44)": case "cidr": case "varchar(43)": case "inet": case "varchar(17)": case "mac addr": $v2068a4d581 = !is_object($v539082ff30) && !is_array($v539082ff30); break; case "name": if(preg_match("/[\w]+/iu", $v539082ff30)) $v2068a4d581 = true; break; default: $v2068a4d581 = true; } if($v2068a4d581) { $this->data = $v539082ff30; return true; } launch_exception(new ObjTypeException($this->v3fb9f41470, $v539082ff30)); return false; } } ?>
