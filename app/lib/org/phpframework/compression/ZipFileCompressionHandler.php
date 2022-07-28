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

include_once get_lib("org.phpframework.compression.IFileCompressionHandler"); include_once get_lib("org.phpframework.compression.ZipHandler"); class ZipFileCompressionHandler implements IFileCompressionHandler { protected $file_pointer = null; protected $file_name = null; protected $tmp_file = null; public function __construct() { } public function open($pf3dc0762) { $this->file_pointer = new ZipArchive(); $this->file_name = basename($pf3dc0762); $this->tmp_file = tmpfile(); if ($this->file_pointer === false || !$this->file_pointer->open($pf3dc0762, ZIPARCHIVE::CREATE) || !$this->file_pointer->addFromString($this->file_name, "") || !$this->tmp_file) throw new Exception("Could not open file! Please check if the '" . $this->file_name . "' file is writeable..."); return true; } public function write($v327f72fb62) { $v8d6672117e = fwrite($this->tmp_file, $v327f72fb62); if ($v8d6672117e === false) throw new Exception("Could not write to file! Please check if you have enough free space..."); return $v8d6672117e; } public function close() { fseek($this->tmp_file, 0); $v6490ea3a15 = ""; while (!feof($this->tmp_file)) $v6490ea3a15 .= fread($this->tmp_file, 8192); fclose($this->tmp_file); $v5c1c342594 = $this->file_pointer->addFromString($this->file_name, $v6490ea3a15); if ($v5c1c342594 === false) throw new Exception("Could not write to file! Please check if you have enough free space..."); return $this->file_pointer->close(); } } ?>
