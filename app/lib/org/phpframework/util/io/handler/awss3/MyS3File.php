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
include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3Handler"); class MyS3File extends MyS3Handler { public function __construct($v614e1f4104, $v253839514f) { parent::__construct($v614e1f4104, $v253839514f); } public function upload($pa293a3d3, $v4907c60569, $pe6469026, $v8b27c73d0e = "p", $v30857f7eca = array()) { $pf9163b61 = $this->getACL($v8b27c73d0e); $v3fb9f41470 = isset($v30857f7eca["type"]) ? strtolower($v30857f7eca["type"]) : ""; $v5c1c342594 = false; switch($v3fb9f41470) { case "string": if(!is_string($pa293a3d3)) { if (is_object($pa293a3d3) && in_array("__toString", get_class_methods($pa293a3d3))) $pa293a3d3 = strval($pa293a3d3->__toString()); else $pa293a3d3 = strval($pa293a3d3); } $v5c1c342594 = $this->S3->putObjectString($pa293a3d3, $v4907c60569, $pe6469026, $pf9163b61); break; case "resource": $v5c1c342594 = $this->S3->putObject($this->S3->inputResource(fopen($pa293a3d3, 'rb'), filesize($pa293a3d3)), $v4907c60569, $pe6469026, $pf9163b61); break; default; $v5c1c342594 = $this->S3->putObject($this->S3->inputFile($pa293a3d3, false), $v4907c60569, $pe6469026, $pf9163b61); } if($v5c1c342594 && $v3fb9f41470 != "string" && file_exists($pa293a3d3)) unlink($pa293a3d3); return $v5c1c342594; } public function create($pa293a3d3, $v4907c60569, $pe6469026, $v8b27c73d0e = "p") { return $this->upload($pa293a3d3, $v4907c60569, $pe6469026, $v8b27c73d0e, array("type" => "string")); } public function get($v4907c60569, $pe6469026, $v902f1c2d84 = false) { if($this->exists($v4907c60569, $pe6469026)) { $v972f1a5c2b = $this->S3->getObject($v4907c60569, $pe6469026, $v902f1c2d84); return $v972f1a5c2b ? $v972f1a5c2b->body : null; } return null; } public function delete($v4907c60569, $pe6469026) { if($this->exists($v4907c60569, $pe6469026)) { return $this->S3->deleteObject($v4907c60569, $pe6469026); } return true; } public function copy($paf30694d, $v610ffcd737, $pb6689abe, $v338d556056, $v8b27c73d0e = "p") { if($paf30694d == $pb6689abe && $v610ffcd737 == $v338d556056) return false; if($this->exists($pb6689abe, $v610ffcd737) && $pb6689abe && $v338d556056) { $pf9163b61 = $this->getACL($v8b27c73d0e); $v5c1c342594 = $this->S3->copyObject($paf30694d, $v610ffcd737, $pb6689abe, $v338d556056, $pf9163b61); return $v5c1c342594 ? true : false; } return false; } } ?>
