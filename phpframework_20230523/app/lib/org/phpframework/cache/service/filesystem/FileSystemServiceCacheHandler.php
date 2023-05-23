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

include_once get_lib("org.phpframework.cache.service.ServiceCacheHandler"); include get_lib("org.phpframework.cache.service.filesystem.FileSystemServiceCacheFileHandler"); include get_lib("org.phpframework.cache.service.filesystem.FileSystemServiceCacheRelatedServicesHandler"); class FileSystemServiceCacheHandler extends ServiceCacheHandler { private $pf81bcf6b; private $v65c3680fbf; public function __construct($pf195ff39 = false, $pfcc61b33 = false) { $this->pf81bcf6b = new FileSystemServiceCacheFileHandler($this, $pf195ff39, $pfcc61b33); $this->v65c3680fbf = new FileSystemServiceCacheRelatedServicesHandler($this); } public function create($pdcf670f6, $pbfa01ed1, $v9ad1385268, $v3fb9f41470 = false) { $v5c1c342594 = false; if($pbfa01ed1) { $pf3dc0762 = $this->getServicePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); $v57b4b0200b = $this->prepareContentToInsert($v9ad1385268, $v3fb9f41470); $v5c1c342594 = $this->pf81bcf6b->create($pf3dc0762, $v57b4b0200b); } return $v5c1c342594; } public function addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false) { if($pbfa01ed1) { return $this->v65c3680fbf->addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470); } return false; } public function checkServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false) { if($this->v65c3680fbf->getRegistrationKeyStatus($pdcf670f6, $pbfa01ed1, $v3fb9f41470)) { return $this->addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470); } return true; } public function deleteAll($pdcf670f6, $v3fb9f41470 = false) { $v17be587282 = $this->getServiceDirPath($pdcf670f6, $v3fb9f41470); return $this->pf81bcf6b->deleteFolder($v17be587282); } public function delete($pdcf670f6, $pbfa01ed1, $v30857f7eca = array()) { $v3fb9f41470 = $v30857f7eca["cache_type"]; $v1491940c54 = $v30857f7eca["key_type"]; $v91d4d88b89 = $v30857f7eca["original_key"]; $v4a58b2e287 = $v30857f7eca["delete_mode"]; if($v4a58b2e287 == 2) { $pf3dc0762 = $this->getServiceDirPath($pdcf670f6, $v3fb9f41470); $pef4ea73b = $this->pf81bcf6b->search($pf3dc0762, $pbfa01ed1, $v1491940c54); return $this->pf81bcf6b->delete($pef4ea73b); } elseif($v4a58b2e287 == 3) { return $this->v65c3680fbf->delete($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $v91d4d88b89); } else { $pf3dc0762 = $this->getServicePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); $pf3dc0762 = $this->pf81bcf6b->getPath($pf3dc0762); if($pf3dc0762) { $pef4ea73b = array($pf3dc0762); } return $this->pf81bcf6b->delete($pef4ea73b); } } public function get($pdcf670f6, $pbfa01ed1, $v3fb9f41470 = false) { if($pbfa01ed1) { $pf3dc0762 = $this->getServicePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); $pae77d38c = $this->pf81bcf6b->get($pf3dc0762); if($pae77d38c) { return $this->prepareContentFromGet($pae77d38c, $v3fb9f41470); } } return false; } public function isValid($pdcf670f6, $pbfa01ed1, $v492fce9a5d = false, $v3fb9f41470 = false) { if($pbfa01ed1) { if(!$v492fce9a5d) { $v492fce9a5d = $this->default_ttl; } if(is_numeric($v492fce9a5d) && $v492fce9a5d > 0) { $pf3dc0762 = $this->getServicePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); $pf3dc0762 = $this->pf81bcf6b->getPath($pf3dc0762); if($pf3dc0762 && $this->pf81bcf6b->exists($pf3dc0762)) { $v9782b9febe = $this->pf81bcf6b->getFileMTime($pf3dc0762); $v5c1c342594 = $v9782b9febe + $v492fce9a5d < time() ? false : true; if($v5c1c342594) { return $this->pf81bcf6b->isValid($pf3dc0762); } } } } return false; } public function getCacheFileHandler() {return $this->pf81bcf6b;} } ?>
