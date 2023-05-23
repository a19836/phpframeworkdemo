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

include_once get_lib("org.phpframework.cache.xmlsettings.XmlSettingsCacheHandler"); include_once get_lib("org.phpframework.mongodb.IMongoDBHandler"); class MongoDBXmlSettingsCacheHandler extends XmlSettingsCacheHandler { private $pfeae7c9a; public function setMongoDBHandler(IMongoDBHandler $pfeae7c9a) {$this->pfeae7c9a = $pfeae7c9a;} public function getMongoDBHandler() {return $this->pfeae7c9a;} public function getCache($pf3dc0762) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName($pf3dc0762); $pbfa01ed1 = $this->getFileKey($pf3dc0762); $v539082ff30 = $this->pfeae7c9a->get($ped24d60a, $pbfa01ed1); if (empty($v539082ff30["expire"]) || $v539082ff30["expire"] >= time()) { $v57b4b0200b = $v539082ff30["content"]; if (!empty($v57b4b0200b)) { $pfb662071 = unserialize($v57b4b0200b); return is_array($pfb662071) ? $pfb662071 : false; } } } return false; } public function setCache($pf3dc0762, $v539082ff30) { if (!empty($this->pfeae7c9a) && is_array($v539082ff30)) { $ped24d60a = $this->getCollectionName($pf3dc0762); $pbfa01ed1 = $this->getFileKey($pf3dc0762); $v587d93a3a7 = $this->getCache($pf3dc0762); $v65a396e40d = is_array($v587d93a3a7) ? array_merge($v587d93a3a7, $v539082ff30) : $v539082ff30; $v57b4b0200b = serialize($v65a396e40d); return $this->pfeae7c9a->set($ped24d60a, $pbfa01ed1, array( "content" => $v57b4b0200b, "expire" => $this->cache_ttl ? $this->cache_ttl + time() : 0; )); } return false; } public function isCacheValid($pf3dc0762) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName($pf3dc0762); $pbfa01ed1 = $this->getFileKey($pf3dc0762); $v539082ff30 = $this->pfeae7c9a->get($ped24d60a, $pbfa01ed1); return empty($v539082ff30["expire"]) || $v539082ff30["expire"] >= time(); } return false; } public function deleteCache($pf3dc0762) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName($pf3dc0762); $pbfa01ed1 = $this->getFileKey($pf3dc0762); return $this->pfeae7c9a->delete($ped24d60a, $pbfa01ed1) !== false; } return false; } protected function getCollectionName($pf3dc0762) { return CacheHandlerUtil::getFilePathKey( dirname($pf3dc0762) ); } protected function getFileKey($pf3dc0762) { return CacheHandlerUtil::getFilePathKey( basename($pf3dc0762) ); } } ?>
