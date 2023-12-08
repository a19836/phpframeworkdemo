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
include_once get_lib("org.phpframework.cache.xmlsettings.filesystem.FileSystemXmlSettingsCacheHandler"); class HibernateModelCache extends FileSystemXmlSettingsCacheHandler { const CACHE_DIR_NAME = "__system/hibernate/sql/"; private $pd8192d9d; public function __construct() { } public static function getCachedSQLName($pdcf670f6, $v9367d5be85) { $v9994512d98 = is_array($v9367d5be85) ? array_keys($v9367d5be85) : array(); sort($v9994512d98); $v9994512d98 = serialize($v9994512d98); return "{$pdcf670f6}_".hash("md4", $v9994512d98).".sql"; } public function cachedSQLExists($v250a1176c9) { $pf3dc0762 = $this->getCachedSQLPath($v250a1176c9); if($pf3dc0762 && $this->isCacheSQLValid($pf3dc0762)) { return $this->getCachedSQL($v250a1176c9) ? true : false; } return false; } public function getCachedSQL($v250a1176c9) { $pf3dc0762 = $this->getCachedSQLPath($v250a1176c9); if($pf3dc0762 && file_exists($pf3dc0762)) { return file_get_contents($pf3dc0762); } return false; } public function setCachedSQL($v250a1176c9, $v539082ff30) { $pf3dc0762 = $this->getCachedSQLPath($v250a1176c9); if($pf3dc0762) { if(($v7dffdb5a5b = fopen($pf3dc0762, "w"))) { $v5c1c342594 = fputs($v7dffdb5a5b, $v539082ff30); fclose($v7dffdb5a5b); return $v5c1c342594 === false ? false : true; } } return false; } public function initCacheDirPath($v17be587282) { if(!$this->pd8192d9d) { if($v17be587282) { CacheHandlerUtil::configureFolderPath($v17be587282); $v17be587282 .= self::CACHE_DIR_NAME; if(CacheHandlerUtil::preparePath($v17be587282)) { CacheHandlerUtil::configureFolderPath($v17be587282); $this->pd8192d9d = $v17be587282; } } } } public function getCachedSQLPath($pf3dc0762) { if($this->pd8192d9d && $pf3dc0762) { return $this->pd8192d9d . $pf3dc0762; } return false; } public function isCacheSQLValid($pf3dc0762) { if($pf3dc0762 && file_exists($pf3dc0762)) return filemtime($pf3dc0762) + $this->cache_ttl < time() ? false : true; return false; } } ?>
