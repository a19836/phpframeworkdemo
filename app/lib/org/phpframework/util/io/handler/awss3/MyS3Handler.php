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

include_once get_lib("org.phpframework.util.io.handler.MyIOHandler"); include_once get_lib("lib.vendor.awss3.S3"); class MyS3Handler extends MyIOHandler { public $S3; public function __construct($v614e1f4104, $v253839514f) { $this->S3 = new S3($v614e1f4104, $v253839514f); } public function getType($pf3dc0762) { $v4159504aa3 = $this->getFileTypes(); $v250a1176c9 = basename($pf3dc0762); return $v250a1176c9 == "." ? $v4159504aa3["folder"] : $v4159504aa3[ self::getFileType($v250a1176c9) ]; } public function getACL($v8b27c73d0e) { switch(strtolower($v8b27c73d0e)) { case "p": $pf9163b61 = S3::ACL_PRIVATE; break; case "r": $pf9163b61 = S3::ACL_PUBLIC_READ; break; case "w": $pf9163b61 = S3::ACL_PUBLIC_READ_WRITE; break; default: $pf9163b61 = S3::ACL_PRIVATE; } return $pf9163b61; } public function exists($v4907c60569, $pe6469026) { return $this->S3->getObjectInfo($v4907c60569, $pe6469026, false); } public function getInfo($v4907c60569, $pe6469026) { if($this->exists($v4907c60569, $pe6469026)) { $v3fb9f41470 = $this->getType($pe6469026); $v872c4849e0 = array(); $v872c4849e0["type"] = $v3fb9f41470["id"]; $v872c4849e0["type_desc"] = $v3fb9f41470["desc"]; if($v872c4849e0["type"] == 1) { $v872c4849e0["path"] = $pe6469026; $v872c4849e0["name"] = basename(dirname($pe6469026)); } else { $v872c4849e0["path"] = $pe6469026; $v872c4849e0["name"] = basename($pe6469026); $v872c4849e0["extension"] = $this->getFileExtension($pe6469026); $v872c4849e0["mime_type"] = $this->getFileMimeTypeByExtension($v872c4849e0["extension"]); } return $v872c4849e0; } else return array(); } } ?>
