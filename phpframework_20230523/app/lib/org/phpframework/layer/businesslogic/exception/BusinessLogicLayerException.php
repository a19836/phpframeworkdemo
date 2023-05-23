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

class BusinessLogicLayerException extends Exception { public $problem; public function __construct($v6de691233b, $v67db1bd535) { switch($v6de691233b) { case 1: $this->problem = "Business Logic service function is not register: '{$v67db1bd535}'!"; break; case 2: $this->problem = "Business Logic service function doesn't exists: '{$v67db1bd535}'!"; break; case 3: $this->problem = "Business Logic service constructor doesn't exists: '{$v67db1bd535}'!"; break; case 4: $this->problem = "Business Logic services file doesn't exists: '{$v67db1bd535}'!"; break; case 5: $this->problem = "Business Logic service input annotation error in module '" . $v67db1bd535[0] . "' for '" . $v67db1bd535[1] . "' function: " . self::f084a809dc1($v67db1bd535[2]); break; case 6: $this->problem = "Business Logic service output annotation error in module '" . $v67db1bd535[0] . "' for '" . $v67db1bd535[1] . "' function: " . self::f0d7580db1a($v67db1bd535[2]); break; case 7: $this->problem = "'" . $v67db1bd535[0] . "' class cannot be repeated in Business Logic Layer. You cannot have 2 classes with the same name. Please add a namespace to one of them. Error in file '" . $v67db1bd535[1] . "'"; break; } } private static function f084a809dc1($v8a29987473) { $v655402c226 = ""; if (is_array($v8a29987473)) { foreach ($v8a29987473 as $v67ccb03f4c => $pc368d727) { $v67ccb03f4c = is_numeric($v67ccb03f4c) ? "Param $v67ccb03f4c" : ucfirst($v67ccb03f4c) . " Param"; $v655402c226 .= "<br>- $v67ccb03f4c:<br>&nbsp;&nbsp;&nbsp;+ " . implode("<br>&nbsp;&nbsp;&nbsp;+ ", $pc368d727); } } return $v655402c226; } private static function f0d7580db1a($v8a29987473) { return "<br>- Return value:<br>&nbsp;&nbsp;&nbsp;+ " . implode("<br>&nbsp;&nbsp;&nbsp;+ ", $v8a29987473); } } ?>
