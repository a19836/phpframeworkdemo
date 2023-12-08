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
include_once get_lib("org.phpframework.cache.xmlsettings.XmlSettingsCacheHandler"); class FileSystemXmlSettingsCacheHandler extends XmlSettingsCacheHandler { public function getCache($pf3dc0762) { $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists($pf3dc0762)) { $v57b4b0200b = @file_get_contents($pf3dc0762); $pfb662071 = unserialize($v57b4b0200b); return is_array($pfb662071) ? $pfb662071 : false; } return false; } public function setCache($pf3dc0762, $v539082ff30, $pf25d75af = false) { $pdc9a8b3f = $pf3dc0762; $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists(dirname($pf3dc0762))) { if(is_array($v539082ff30)) { if (!$pf25d75af) { $v587d93a3a7 = $this->getCache($pdc9a8b3f); $v65a396e40d = is_array($v587d93a3a7) ? array_merge($v587d93a3a7, $v539082ff30) : $v539082ff30; } else $v65a396e40d = $v539082ff30; if(($v7dffdb5a5b = fopen($pf3dc0762, "w"))) { $v57b4b0200b = serialize($v65a396e40d); $v5c1c342594 = fputs($v7dffdb5a5b, $v57b4b0200b); fclose($v7dffdb5a5b); return $v5c1c342594 === false ? false : true; } } } return false; } public function isCacheValid($pf3dc0762) { $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists($pf3dc0762)) return filemtime($pf3dc0762) + $this->cache_ttl < time() ? false : true; return false; } public function deleteCache($pf3dc0762) { $this->prepareFilePath($pf3dc0762); if($pf3dc0762 && file_exists($pf3dc0762)) return unlink($pf3dc0762); return false; } } ?>
