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
 include_once get_lib("org.phpframework.util.io.handler.file.MyFileHandler"); class MyFile extends MyFileHandler { public function __construct($v250a1176c9 = false) { parent::__construct($v250a1176c9); } public function upload($v6eee6903b3, $v7cf76881d7, $pe6871e84 = false) { $v8850c96bea["tmp_file_to_upload"] = $v6eee6903b3; $v3b1b832e46 = $v6eee6903b3['size']; $v250a1176c9 = $v6eee6903b3['name']; $v9078ce28d1 = $v6eee6903b3['tmp_name']; if($v7cf76881d7 && substr($v7cf76881d7, strlen($v7cf76881d7) - 1) != "/") $v7cf76881d7 .= "/"; $pef1545e0 = $v7cf76881d7 . ($pe6871e84 ? $pe6871e84 : $v250a1176c9); $v5c1c342594 = is_uploaded_file($v9078ce28d1) ? move_uploaded_file($v9078ce28d1, $pef1545e0) : copy($v9078ce28d1, $pef1545e0); if($v5c1c342594 && file_exists($v9078ce28d1)) unlink($v9078ce28d1); return $v5c1c342594; } public function create($v57b4b0200b) { if($this->file_name && ($v7dffdb5a5b = fopen($this->file_name,"w"))) { $v5c1c342594 = fputs($v7dffdb5a5b, $v57b4b0200b); $v5c1c342594 = $v5c1c342594 === false ? false : true; fclose($v7dffdb5a5b); } return $v5c1c342594; } public function edit($v57b4b0200b) { if(file_exists($this->file_name)) { return $this->create($v57b4b0200b); } return false; } public function get() { return $this->read(); } public function delete() { if($this->exists()) { return unlink($this->file_name); } return true; } public function copy($v3806ce773c) { if($this->exists() && $v3806ce773c && $this->file_name != $v3806ce773c) { return copy($this->file_name, $v3806ce773c); } return null; } public function read() { if($this->file_name && file_exists($this->file_name)) { $v57b4b0200b = file_get_contents($this->file_name); } return $v57b4b0200b; } public function parse() { $v57b4b0200b = array(); if($this->file_name && file_exists($this->file_name) && $v7dffdb5a5b = fopen($this->file_name,"r")) { while(!feof($v7dffdb5a5b)) $v57b4b0200b[] = fgets($v7dffdb5a5b); fclose($v7dffdb5a5b); } return $v57b4b0200b; } public function readAndWrite($v57b4b0200b) { $pf69d92f1 = $this->read(); $pf69d92f1 .= $v57b4b0200b; return $this->create($pf69d92f1); } public function setFileName($v250a1176c9) { $this->file_name = $v250a1176c9; } } ?>
