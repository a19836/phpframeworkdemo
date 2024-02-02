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
include_once get_lib("org.phpframework.cache.user.UserCacheHandler"); class FileSystemUserCacheHandler extends UserCacheHandler { public function read($v250a1176c9) { if($this->isValid($v250a1176c9)) { $pf3dc0762 = $this->root_path . $v250a1176c9; $this->prepareFilePath($pf3dc0762); $v57b4b0200b = @file_get_contents($pf3dc0762); return !empty($v57b4b0200b) ? $this->unserializeContent($v57b4b0200b) : $v57b4b0200b; } return false; } public function write($v250a1176c9, $v539082ff30) { $pf3dc0762 = $this->root_path . $v250a1176c9; $this->prepareFilePath($pf3dc0762); if($v250a1176c9 && CacheHandlerUtil::preparePath(dirname($pf3dc0762)) && isset($v539082ff30)) { $v57b4b0200b = $this->serializeContent($v539082ff30); return file_put_contents($pf3dc0762, $v57b4b0200b) !== false; } return false; } public function isValid($v250a1176c9) { $pf3dc0762 = $this->root_path . $v250a1176c9; $this->prepareFilePath($pf3dc0762); if($this->root_path && $v250a1176c9 && file_exists($pf3dc0762)) return filemtime($pf3dc0762) + $this->ttl < time() ? false : true; return false; } public function exists($v250a1176c9) { $pf3dc0762 = $this->root_path . $v250a1176c9; $this->prepareFilePath($pf3dc0762); return $this->root_path && $v250a1176c9 && file_exists($pf3dc0762); } public function delete($v250a1176c9, $pf0d6eaba = null) { $pf3dc0762 = $this->root_path . $v250a1176c9; $this->prepareFilePath($pf3dc0762); if($this->root_path && $v250a1176c9) { $pf0d6eaba = CacheHandlerUtil::getCorrectKeyType($pf0d6eaba); if (!$pf0d6eaba && file_exists($pf3dc0762)) return unlink($pf3dc0762); else if ($pf0d6eaba) { $pdd397f0a = dirname($pf3dc0762); $v5c1c342594 = true; if (is_dir($pdd397f0a)) { $v6ee393d9fb = array_diff(scandir($pdd397f0a), array('..', '.')); if ($v6ee393d9fb) { $v508f94d3fe = basename($v250a1176c9); foreach ($v6ee393d9fb as $v7dffdb5a5b) { $pc8581934 = pathinfo($v7dffdb5a5b, PATHINFO_FILENAME); if (CacheHandlerUtil::checkIfKeyTypeMatchValue($pc8581934, $v508f94d3fe, $pf0d6eaba) && !unlink("$pdd397f0a/$v7dffdb5a5b")) $v5c1c342594 = false; } } } return $v5c1c342594; } } return false; } } ?>
