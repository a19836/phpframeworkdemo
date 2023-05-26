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

include_once get_lib("org.phpframework.memcache.exception.MemcacheException"); include_once get_lib("org.phpframework.memcache.IMemcacheHandler"); include_once get_lib("lib.vendor.memcache.NSMemcache"); class MemcacheHandler implements IMemcacheHandler { private $pf3d2eef6; private $v2068a4d581; public function connect($v244067a7fe = "", $v7e782022ec = "", $v50623da9ca = null) { try{ $this->v2068a4d581 = false; $this->pf3d2eef6 = new NSMemcache(); if ($this->pf3d2eef6) { if (empty($v50623da9ca)) { $v50623da9ca = 1; } $this->v2068a4d581 = $this->pf3d2eef6->connect($v244067a7fe, $v7e782022ec, $v50623da9ca); } if (empty($this->v2068a4d581)) { launch_exception(new MemcacheException(1, null, array($v244067a7fe, $v7e782022ec, $v50623da9ca))); } } catch(Exception $paec2c009) { launch_exception(new MemcacheException(1, $paec2c009, array($v244067a7fe, $v7e782022ec, $v50623da9ca))); } } public function close() { if ($this->v2068a4d581) { $this->pf3d2eef6->close(); } } public function ok() { return $this->v2068a4d581; } public function getConn() { return $this->v2068a4d581 ? $this->pf3d2eef6 : null; } public function get($pbfa01ed1) { if ($this->v2068a4d581 && !empty($pbfa01ed1)) { return $this->pf3d2eef6->get($pbfa01ed1); } return false; } public function nsGet($v4a2fedb8f0, $pbfa01ed1) { if ($this->v2068a4d581 && !empty($v4a2fedb8f0) && !empty($pbfa01ed1)) { return $this->pf3d2eef6->ns_get($v4a2fedb8f0, $pbfa01ed1); } return false; } public function set($pbfa01ed1, $v57b4b0200b, $pea3723af = 0) { if ($this->v2068a4d581 && !empty($pbfa01ed1)) { return $this->pf3d2eef6->set($pbfa01ed1, $v57b4b0200b, MEMCACHE_COMPRESSED, $pea3723af); } return false; } public function nsSet($v4a2fedb8f0, $pbfa01ed1, $v57b4b0200b, $pea3723af = 0) { if ($this->v2068a4d581 && !empty($v4a2fedb8f0) && !empty($pbfa01ed1)) { return $this->pf3d2eef6->ns_set($v4a2fedb8f0, $pbfa01ed1, $v57b4b0200b, MEMCACHE_COMPRESSED, $pea3723af); } return false; } public function nsFlush($v4a2fedb8f0) { if ($this->v2068a4d581 && !empty($v4a2fedb8f0)) { return $this->pf3d2eef6->ns_flush($v4a2fedb8f0); } return false; } public function delete($pbfa01ed1) { if ($this->v2068a4d581 && !empty($pbfa01ed1)) { return $this->pf3d2eef6->delete($pbfa01ed1); } return false; } public function nsDelete($v4a2fedb8f0, $pbfa01ed1, $pea3723af = 0) { if ($this->v2068a4d581 && !empty($v4a2fedb8f0) && !empty($pbfa01ed1)) { return $this->pf3d2eef6->ns_delete($v4a2fedb8f0, $pbfa01ed1, $pea3723af); } return false; } } ?>
