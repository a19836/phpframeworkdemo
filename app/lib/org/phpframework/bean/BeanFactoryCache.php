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
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class BeanFactoryCache extends FileSystemXmlSettingsCacheHandler { private $v17d19962dd = "__system/beans/"; private $pd8192d9d; private $pace9d67e = false; public function __construct() { } public function cachedFileExists($pf3dc0762) { $pf3dc0762 = $this->getCacheFilePath($pf3dc0762); if($pf3dc0762 && $this->isCacheValid($pf3dc0762)) { $pfb662071 = $this->getCache($pf3dc0762); return $pfb662071 ? true : false; } return false; } public function getCachedFile($pf3dc0762) { $pf3dc0762 = $this->getCacheFilePath($pf3dc0762); return $this->getCache($pf3dc0762); } public function setCachedFile($pf3dc0762, $v539082ff30, $pf25d75af = false) { $pf3dc0762 = $this->getCacheFilePath($pf3dc0762); if($pf3dc0762) { return $this->setCache($pf3dc0762, $v539082ff30, $pf25d75af); } return true; } public function initCacheDirPath($v17be587282) { if(!$this->pd8192d9d) { if($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $v17be587282 .= $this->v17d19962dd; if(CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->pd8192d9d = $v17be587282; $this->pace9d67e = true; } } } else { $this->pace9d67e = true; } } public function getCacheFilePath($pf3dc0762) { if($this->pd8192d9d && $pf3dc0762) { return $this->pd8192d9d . hash("md4", $pf3dc0762); } return false; } public function isActive() { return $this->pace9d67e; } } ?>
