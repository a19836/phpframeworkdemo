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

include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class SQLMapClientCache extends FileSystemXmlSettingsCacheHandler { protected $cache_root_path; public function cachedXMLElmExists($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedXMLElm($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); return $this->getCache($pf3dc0762); } public function setCachedXMLElm($pf3dc0762, $v539082ff30) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30); } return true; } public function deleteCachedXMLElm($pf3dc0762) { $pf3dc0762 = $this->getCachedFilePath($pf3dc0762); if($pf3dc0762) { return $this->deleteCache($pf3dc0762); } return true; } public function getCachedFilePath($pf3dc0762) { if($this->cache_root_path && $pf3dc0762) { return $this->cache_root_path . hash("md4", $pf3dc0762); } return false; } } ?>
