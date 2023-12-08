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
 */ include_once get_lib("org.phpframework.util.io.handler.MyIOHandler"); class MyFileHandler extends MyIOHandler { public $file_name; public function __construct($v250a1176c9 = false) { $this->file_name = $v250a1176c9; } public function getType($pf3dc0762) { $v4159504aa3 = $this->getFileTypes(); $v250a1176c9 = basename($pf3dc0762); return is_dir($pf3dc0762) ? $v4159504aa3["folder"] : $v4159504aa3[ self::getFileType($v250a1176c9) ]; } public function rename($pe6871e84) { if($this->file_name) { $v0e5a9eeca2 = dirname($this->file_name); $v0e5a9eeca2 .= $v0e5a9eeca2 ? "/" . $pe6871e84 : $pe6871e84; return rename($this->file_name, $v0e5a9eeca2); } return false; } public function exists() { return $this->file_name && file_exists($this->file_name); } public function getInfo() { $v872c4849e0 = array(); if($this->exists()) { $v3fb9f41470 = $this->getType($this->file_name); $v872c4849e0 = array(); $v872c4849e0["path"] = $this->file_name; $v872c4849e0["name"] = basename($this->file_name); $v872c4849e0["type"] = $v3fb9f41470["id"]; $v872c4849e0["type_desc"] = $v3fb9f41470["desc"]; if($v872c4849e0["type"] != 1) { $v6bfcc44e7b = pathinfo($this->file_name, PATHINFO_EXTENSION); $v872c4849e0["extension"] = $v6bfcc44e7b; $v872c4849e0["mime_type"] = $this->getFileMimeTypeByExtension($v872c4849e0["extension"]); } } return $v872c4849e0; } } ?>
