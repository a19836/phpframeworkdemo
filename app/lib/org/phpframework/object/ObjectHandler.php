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

include_once get_lib("org.phpframework.object.exception.ObjException"); class ObjectHandler { public function __construct() { } public static function getClassName($pd5421edd) { $v04fae7df44 = explode(".", $pd5421edd); $v3ae55a9a2e = $v04fae7df44[count($v04fae7df44) - 1]; $v3ae55a9a2e = explode("(", $v3ae55a9a2e); $v1335217393 = $v3ae55a9a2e[0]; return $v1335217393; } public static function createInstance($pd5421edd) { $v04fae7df44 = explode(".", $pd5421edd); $v3ae55a9a2e = $v04fae7df44[count($v04fae7df44) - 1]; $v9939a3c13a = strpos($pd5421edd, "(") > 0 ? true : false; $v972f1a5c2b = false; if($v9939a3c13a) { $v3ae55a9a2e = explode("(", $v3ae55a9a2e); $v1335217393 = $v3ae55a9a2e[0]; $v3ae55a9a2e = explode(")", $v3ae55a9a2e[1]); $v3ae55a9a2e = str_replace(array("'", '"'), "", $v3ae55a9a2e[0]); array_pop($v04fae7df44); $v45952cf45c = get_lib( implode(".", $v04fae7df44) . "." . $v1335217393); $v5a68f6938e = "\$v972f1a5c2b = new {$v1335217393}('$v3ae55a9a2e');"; } else { $v45952cf45c = get_lib($pd5421edd); $v1335217393 = $v3ae55a9a2e; $v5a68f6938e = "\$v972f1a5c2b = new {$v1335217393}();"; } if(!class_exists($v1335217393)) { if(file_exists($v45952cf45c)) { include_once $v45952cf45c; } else { launch_exception(new ObjException(1, array($v45952cf45c))); } } if(class_exists($v1335217393)) { eval($v5a68f6938e); } else { launch_exception(new ObjException(2, array($v5a68f6938e))); } if(!$v972f1a5c2b) { launch_exception(new ObjException(2, array($v5a68f6938e))); } return $v972f1a5c2b; } public static function checkObjClass($v972f1a5c2b, $v5e7067a969) { if($v972f1a5c2b && !is_numeric($v972f1a5c2b)) { $v5c1c342594 = false; if(is_object($v972f1a5c2b)) { $v5c1c342594 = is_a($v972f1a5c2b, $v5e7067a969); } elseif($v972f1a5c2b == $v5e7067a969 || is_subclass_of($v972f1a5c2b, $v5e7067a969)) { $v5c1c342594 = true; } if($v5c1c342594) { return true; } } $v55d96b7243 = is_object($v972f1a5c2b) ? get_class($v972f1a5c2b) : $v972f1a5c2b; launch_exception(new ObjException(3, array($v55d96b7243, $v5e7067a969))); return false; } public static function checkIfObjType($v972f1a5c2b) { return self::checkObjClass($v972f1a5c2b, "ObjType"); } public static function arrayToObject($v612251fb45, $v4264e5d2b0) { return unserialize(sprintf( 'O:%d:"%s"%s', strlen($v4264e5d2b0), $v4264e5d2b0, strstr(serialize($v612251fb45), ':') )); } public static function objectToObject($v4957af599c, $v4264e5d2b0) { return unserialize(sprintf( 'O:%d:"%s"%s', strlen($v4264e5d2b0), $v4264e5d2b0, strstr(strstr(serialize($v4957af599c), '"'), ':') )); } } ?>
