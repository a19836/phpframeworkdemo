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

include_once get_lib("org.phpframework.object.ObjType"); include_once get_lib("org.phpframework.object.exception.ObjTypeException"); include_once get_lib("org.phpframework.db.DB"); class DBPrimitive extends ObjType { private $v3fb9f41470; public $is_primitive = true; public function __construct($v3fb9f41470, $v539082ff30 = false) { $this->setType($v3fb9f41470); $this->field = false; if($v539082ff30 !== false) $this->setData($v539082ff30); } public static function getTypes() { return DB::getAllColumnTypes(); } public static function getNumericTypes() { return DB::getAllColumnNumericTypes(); } public static function getDateTypes() { return DB::getAllColumnDateTypes(); } public static function getTextTypes() { return DB::getAllColumnTextTypes(); } public static function getBlobTypes() { return DB::getAllColumnBlobTypes(); } public static function getBooleanTypeAvailableValues() { return array_keys( DB::getAllBooleanTypeAvailableValues() ); } public static function getCurrentTimestampAvailableValues() { return DB::getAllCurrentTimestampAvailableValues(); } public function getType() {return $this->v3fb9f41470;} public function setType($v3fb9f41470) {$this->v3fb9f41470 = strtolower($v3fb9f41470);} public function setData($v539082ff30) { $v2068a4d581 = false; switch ($this->v3fb9f41470) { case "smallserial": case "serial": case "bigserial": $v2068a4d581 = is_numeric($v539082ff30) && $v539082ff30 > 0; break; case "bit": case "tinyint": case "smallint": case "int": case "bigint": case "decimal": case "double": case "float": case "money": case "coordinate": case "time": $v2068a4d581 = is_numeric($v539082ff30); break; case "boolean": $v2068a4d581 = $v539082ff30 === TRUE || $v539082ff30 === false || $v539082ff30 === 0 || $v539082ff30 === 1 || $v539082ff30 === "0" || $v539082ff30 === "1" || in_array($v539082ff30, self::getBooleanTypeAvailableValues()); if (!$v2068a4d581) { $v956913c90f = strtolower($v539082ff30); $v2068a4d581 = $v956913c90f === "true" || $v956913c90f === "false"; } break; case "char": case "varchar": case "mediumtext": case "text": case "longtext": case "blob": case "longblob": $v2068a4d581 = !is_object($v539082ff30) && !is_array($v539082ff30); break; case "date": case "datetime": case "timestamp": $v2068a4d581 = !is_object($v539082ff30) && !is_array($v539082ff30); break; case "varchar(36)": case "varchar(44)": case "varchar(43)": case "varchar(17)": $v2068a4d581 = !is_object($v539082ff30) && !is_array($v539082ff30); break; default: $v2068a4d581 = true; } if($v2068a4d581) { $this->data = $v539082ff30; return true; } launch_exception(new ObjTypeException($this->v3fb9f41470, $v539082ff30)); return false; } } ?>
