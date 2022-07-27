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

include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); include_once get_lib("org.phpframework.cache.user.IUserCacheHandler"); abstract class UserCacheHandler implements IUserCacheHandler { protected $root_path; protected $ttl; protected $serialize; const DEFAULT_TTL = 30758400; public function config($v492fce9a5d = false, $pc0eabaff = true) { $this->ttl = $v492fce9a5d ? $v492fce9a5d : self::DEFAULT_TTL; $this->serialize = $pc0eabaff; } public function setRootPath($v4ab372da3a) { CacheHandlerUtil::configureFolderPath($v4ab372da3a); $this->root_path = $v4ab372da3a; } public function getRootPath() {return $this->root_path;} public function serializeContent($pae77d38c) { return $this->serialize ? serialize($pae77d38c) : $pae77d38c; } public function unserializeContent($pae77d38c) { return $this->serialize ? unserialize($pae77d38c) : $pae77d38c; } protected function prepareFilePath(&$pf3dc0762) { $pf3dc0762 = CacheHandlerUtil::getCacheFilePath($pf3dc0762); } } ?>
