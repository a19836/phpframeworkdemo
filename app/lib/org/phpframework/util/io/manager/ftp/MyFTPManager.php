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
include_once get_lib("org.phpframework.util.io.handler.ftp.MyFTPFolder"); include_once get_lib("org.phpframework.util.io.handler.ftp.MyFTPFile"); include_once get_lib("org.phpframework.util.io.manager.MyIOManager"); class MyFTPManager extends MyIOManager { private $pf626e2c0; private $v3823cc6c49; public function __construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v30857f7eca = array()) { $this->pf626e2c0 = new MyFTPFolder($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, "", $v30857f7eca); $this->v3823cc6c49 = new MyFTPFile($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, "", $v30857f7eca); } public function connect($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v37c0ca7c25 = false) { $this->v3823cc6c49->connect($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, $v37c0ca7c25); } public function close() { return $this->v3823cc6c49->close(); } public function isConnected() { return $this->v3823cc6c49->isConnected(); } public function add($v3fb9f41470, $v17be587282, $v5e813b295b, $v30857f7eca = array()) { } public function edit($v17be587282, $v5e813b295b, $v30857f7eca = array()) { } public function delete($v3fb9f41470, $v17be587282, $v5e813b295b) { } public function copy($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()) { } public function move($v3fb9f41470, $pc941b4ab, $v23d7f19208, $v525288e856, $v30857f7eca = array()) { } public function rename($v17be587282, $v0c4b06ddf7, $pe6871e84, $v30857f7eca = array()) { } public function getFile($v17be587282, $v5e813b295b) { } public function getFileInfo($v17be587282, $v5e813b295b) { } public function getFiles($v17be587282) { $v6ee393d9fb = array(); $v9ad1385268 = array(); $v9ad1385268["files"] = $this->prepareFiles($v6ee393d9fb); $v9ad1385268["is_truncate"] = $v4170eebbfd; $v9ad1385268["last_marker"] = $v4170eebbfd ? $pacc2b83d : ""; return $v9ad1385268; } public function getFilesCount($v17be587282) { } public function upload($v6eee6903b3, $v17be587282, $pe6871e84, $v30857f7eca = array()) { } public function exists($v17be587282, $v5e813b295b) { } public function getMyIOHandler() { return $this->v3823cc6c49; } } ?>
