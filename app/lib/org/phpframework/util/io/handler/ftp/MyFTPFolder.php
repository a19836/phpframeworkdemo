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
 include_once get_lib("org.phpframework.util.io.handler.ftp.MyFTPHandler"); class MyFTPFolder extends MyFTPHandler { public $invalid_files; public function __construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec = false, $v250a1176c9 = false, $v30857f7eca = array()) { parent::__construct($v244067a7fe, $pd97bc935, $v8a9d082c74, $v7e782022ec, $v250a1176c9, $v30857f7eca); $this->f7b354b22de(); $this->invalid_files = $this->getInvalidFiles(); if(isset($v30857f7eca["invalid_files"]) && is_array($v30857f7eca["invalid_files"])) $this->invalid_files = array_merge($this->invalid_files, $v30857f7eca["invalid_files"]); } public function create() { } public function getFiles() { } public function getFilesRecursevly() { } public function getFilesCount() { } public function delete() { } public function copy($v3806ce773c) { } private function f7b354b22de() { } private function f085037e150($v250a1176c9) { $v250a1176c9 = basename($v250a1176c9); return array_search($v250a1176c9, $this->invalid_files) === false ? true : false; } public function setFileName($v250a1176c9) { $this->file_name = $v250a1176c9; $this->f7b354b22de(); } } ?>
