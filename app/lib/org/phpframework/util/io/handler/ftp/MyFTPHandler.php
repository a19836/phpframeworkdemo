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
 */ include_once get_lib("org.phpframework.util.io.handler.MyIOHandler"); class MyFTPHandler extends MyIOHandler { public $file_name; public $settings; private $pf20b991b; private $v1966635b8b; private $v37c0ca7c25; public function __construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v250a1176c9 = false, $v30857f7eca = array()) { $this->file_name = $v250a1176c9; $this->settings = $v30857f7eca; $this->v37c0ca7c25 = isset($v30857f7eca["passive_mode"]) ? $v30857f7eca["passive_mode"] : null; $this->connect($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, $this->v37c0ca7c25); } public function connect($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v37c0ca7c25 = false) { $v244067a7fe = $this->f5acbf528f9($v244067a7fe); if(is_numeric($v7e782022ec)) $this->pf20b991b = ftp_connect($v244067a7fe, $v7e782022ec) or die("Couldn't connect to $v244067a7fe:$v7e782022ec"); else $this->pf20b991b = ftp_connect($v244067a7fe) or die("Couldn't connect to $v244067a7fe"); $this->v1966635b8b = false; if (@ftp_login($this->pf20b991b, $pd97bc935, $v8a9d082c74)) { $this->setPassiveMode($v37c0ca7c25); $this->v1966635b8b = true; } } public function setPassiveMode($v5c1c342594) { return ftp_pasv($this->pf20b991b, $v5c1c342594); } public function close() { return $this->pf20b991b ? ftp_close($this->pf20b991b) : true; } public function isConnected() { return $this->v1966635b8b; } private function f5acbf528f9($v244067a7fe) { $v8a4df75785 = strpos($v244067a7fe, "ftp://"); return is_numeric($v8a4df75785) && $v8a4df75785 == 0 ? substr($v244067a7fe, 6) : $v244067a7fe; } public function getType($pf3dc0762) { } public function rename($pe6871e84) { } public function exists() { } public function getInfo() { } } ?>
