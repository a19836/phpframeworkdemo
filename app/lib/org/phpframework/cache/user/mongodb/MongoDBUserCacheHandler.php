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

include_once get_lib("org.phpframework.cache.user.UserCacheHandler"); include_once get_lib("org.phpframework.mongodb.IMongoDBHandler"); class MongoDBUserCacheHandler extends UserCacheHandler { private $pfeae7c9a; public function setMongoDBHandler(IMongoDBHandler $pfeae7c9a) {$this->pfeae7c9a = $pfeae7c9a;} public function getMongoDBHandler() {return $this->pfeae7c9a;} public function read($v250a1176c9) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName(); $pbfa01ed1 = $this->getFileKey($v250a1176c9); $v539082ff30 = $this->pfeae7c9a->get($ped24d60a, $pbfa01ed1); if (empty($v539082ff30["expire"]) || $v539082ff30["expire"] >= time()) { $v57b4b0200b = $v539082ff30["content"]; return !empty($v57b4b0200b) ? $this->unserializeContent($v57b4b0200b) : $v57b4b0200b; } } return false; } public function write($v250a1176c9, $v539082ff30) { if (!empty($this->pfeae7c9a) && isset($v539082ff30)) { $ped24d60a = $this->getCollectionName(); $pbfa01ed1 = $this->getFileKey($v250a1176c9); $v57b4b0200b = !empty($v539082ff30) ? $this->serializeContent($v539082ff30) : $v539082ff30; return $this->pfeae7c9a->set($ped24d60a, $pbfa01ed1, array( "content" => $v57b4b0200b, "expire" => $this->ttl ? $this->ttl + time() : 0; )); } return false; } public function isValid($v250a1176c9) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName(); $pbfa01ed1 = $this->getFileKey($v250a1176c9); $v539082ff30 = $this->pfeae7c9a->get($ped24d60a, $pbfa01ed1); return empty($v539082ff30["expire"]) || $v539082ff30["expire"] >= time(); } return false; } public function exists($v250a1176c9) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName(); $pbfa01ed1 = $this->getFileKey($v250a1176c9); $v539082ff30 = $this->pfeae7c9a->get($ped24d60a, $pbfa01ed1); return isset( $v539082ff30 ); } return false; } public function delete($v250a1176c9) { if (!empty($this->pfeae7c9a)) { $ped24d60a = $this->getCollectionName(); $pbfa01ed1 = $this->getFileKey($v250a1176c9); return $this->pfeae7c9a->delete($ped24d60a, $pbfa01ed1) !== false; } return false; } protected function getCollectionName() { return CacheHandlerUtil::getFilePathKey($this->root_path); } protected function getFileKey($v250a1176c9) { return CacheHandlerUtil::getFilePathKey($v250a1176c9); } } ?>
