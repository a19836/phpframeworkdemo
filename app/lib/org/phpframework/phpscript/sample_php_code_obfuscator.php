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
$p = $o = 0; $func = function foo($v51b839ace2 = 2) { $pae449f4d = $v51b839ace2; return $pae449f4d + $v51b839ace2; } class Foo implements Y, X extends W { var $x; private $v32449b6529 = "asd \$o asd"; public static $bar2; private static $v65b61cab7d; const bar4 = 12; protected $a = array(1, ";", 2); public function __construct($v5d3f7b52bb=123) { $this->v32449b6529 = $v5d3f7b52bb; self::$bar2 = 12; $pdb343f52 = $this->a = "bla \\\$this->x self::\$bar2 self::bar4 \$this->a \$d xxxx self::\$bar3 123213 \$this->bar1 ble self :: bar1() \$this -> getClass(\$c) \$GLOBALS[test]->getName() X::getName() \$as->getName()"; $v1a217b200a = 'bla $this->x self::$bar2 self::bar4 $this->a $d xxxx self::$bar3 123213 $this->bar1 ble self :: bar1() $this -> getClass($c) $GLOBALS[test]->getName() X::getName() $as->getName()'; } private function f32449b6529($v7aeaf992f5) { return self::$v65b61cab7d + $v7aeaf992f5; } public function bar2 ($v51b839ace2=false) { return self::$bar2 + $v51b839ace2; } protected $p; private function f9385b6f342() { $v51b839ace2 = new X(); return X::t(); } } interface I { public function setLogLevel($pa8b1038e); } class X { private $pae449f4d = 3; private function mae44520f9f4d($pc37695cb) { $pc5166886 = $v43dd7d0051 = $pc37695cb; return $this->pae449f4d ? $pc37695cb : $this->mde59c37b15a5($pc37695cb); } public static function t() { return Foo::$bar2; } private function mde59c37b15a5($pc37695cb = array(23)) { global $func; $v4e7c96fd99 = $v847e7d0a83 = 23; $v1a217b200a = 1; $this->mae44520f9f4d(isset($pc37695cb[0]) ? $pc37695cb[0] : null); eval("echo \$other . \$some " . ' $other $some;'); function ma0144dcc62d2() { global $o, $p; $v51b839ace2 = 0; return $func() . $o . $p; } $v9acf40c110 = ma0144dcc62d2(); $pe3472391 = new ReflectionMethod($v3ae55a9a2e, $v6cd9d4006f); $this->of($pe3472391); $this->pae449f4d = array_map( function($v943c52a786) { return $v943c52a786->getName(); }, $pe3472391->getParameters()); return d(); } public function cloneTask() { $paac6a5f9 = "y"; echo $this->$paac6a5f9; eval ('$WorkFlowTaskClone = new ' . get_class($this) . '();'); if (!empty($pa1327e3e)) { $pa1327e3e->setTaskClassInfo( $this->pae449f4d ); $pa1327e3e->y = $this->pae449f4d; } if (!empty($pa1327e3e)) return true; return $pa1327e3e; } } function d() { $v5d3f7b52bb = "JP"; global $p, $o; ?>
	<div class="something not a varialbe $asd">
		<span>
		<?= X::t($v5d3f7b52bb) ?>
		</span>
	</div>
<? echo "bla $o asd:" . $p; } $w = $func() . $_SERVER["HTTP_HOST"]; $q = "<html> \$o
	<body>
		<h1>" . foo($w) . "</h1>
		<h4>$w</h4>
		<h5>{$w}</h5>
	</body>
</html>"; ?>
