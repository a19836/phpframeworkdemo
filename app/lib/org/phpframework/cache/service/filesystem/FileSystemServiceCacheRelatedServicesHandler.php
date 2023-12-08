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
include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); include_once get_lib("org.phpframework.cache.service.ServiceCacheRelatedServicesHandler"); class FileSystemServiceCacheRelatedServicesHandler extends ServiceCacheRelatedServicesHandler { const MAXIMUM_REGISTRATION_ATTEMPTS = 5; const RELATED_SERVICE_REGISTRATION_STATUS_FOLDER_NAME = "__status"; const SERVICE_MAIN_ERROR_FILE_NAME = "__error"; public function __construct($pbc96d822) { $this->CacheHandler = $pbc96d822; } public function addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false) { $v5c1c342594 = false; if ($pbfa01ed1) { $v4778b02820 = $this->getRegistrationKeyStatus($pdcf670f6, $pbfa01ed1, $v3fb9f41470); if ($v4778b02820 <= self::MAXIMUM_REGISTRATION_ATTEMPTS) { if ($v4778b02820) { $this->setRegistrationKeyStatus($pdcf670f6, $pbfa01ed1, $v3fb9f41470); } $v5c1c342594 = true; $pc37695cb = $pe7235a8d ? count($pe7235a8d) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v342a134247 = $pe7235a8d[$v43dd7d0051]; $pc8001b34 = isset($v342a134247["key"]) ? $v342a134247["key"] : null; $v8773b3a63a = isset($v342a134247["type"]) ? $v342a134247["type"] : null; if ($v8773b3a63a == "regexp" || $v8773b3a63a == "regex" || $v8773b3a63a == "start" || $v8773b3a63a == "begin" || $v8773b3a63a == "prefix" || $v8773b3a63a == "middle" || $v8773b3a63a == "end" || $v8773b3a63a == "finish" || $v8773b3a63a == "suffix") { $v17be587282 = $this->getServiceRuleToDeletePath($pdcf670f6, $v3fb9f41470, $v8773b3a63a, $pc8001b34); $v872c4849e0 = $this->getFilePathKey($v17be587282, $pbfa01ed1); $v52755e65e5 = isset($v872c4849e0["file_path"]) ? $v872c4849e0["file_path"] : null; if (!$v52755e65e5) { if (!$this->registerKey($v17be587282, $pbfa01ed1, isset($v872c4849e0["free_file_paths"]) ? $v872c4849e0["free_file_paths"] : null)) { $v5c1c342594 = false; ++$v4778b02820; $this->setRegistrationKeyStatus($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v4778b02820); if ($v4778b02820 >= self::MAXIMUM_REGISTRATION_ATTEMPTS) $this->createServiceMainError($pdcf670f6, $v3fb9f41470); } } } } } } return $v5c1c342594; } public function delete($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $v91d4d88b89) { $v17be587282 = $this->getServiceRuleToDeletePath($pdcf670f6, $v3fb9f41470, $v1491940c54, $v91d4d88b89); return $this->deleteRelatedServicesKeys($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $v17be587282); } protected function deleteRelatedServicesKeys($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $v17be587282) { $v5c1c342594 = true; if ($v17be587282 && is_dir($v17be587282) && ($v89d33f4133 = opendir($v17be587282)) ) { while (($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if ($v7dffdb5a5b != "." && $v7dffdb5a5b != "..") { $pf3dc0762 = $v17be587282 . $v7dffdb5a5b; if (is_dir($pf3dc0762)) { if (!$this->deleteRelatedServicesKeys($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $pf3dc0762 . "/")) { $v5c1c342594 = false; } } else { $v57b4b0200b = @file_get_contents($pf3dc0762); $pfb662071 = unserialize($v57b4b0200b); if (is_array($pfb662071)) { $pecbc50e6 = array_keys($pfb662071); $pc37695cb = count($pecbc50e6); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pbb46311a = $pecbc50e6[$v43dd7d0051]; if (CacheHandlerUtil::checkIfKeyTypeMatchValue($pbb46311a, $pbfa01ed1, $v1491940c54)) { $v47cef7ac50 = $this->CacheHandler->getServicePath($pdcf670f6, $pbb46311a, $v3fb9f41470); if (!$this->CacheHandler->getCacheFileHandler()->setFileValidation($v47cef7ac50, 1)) { $v5c1c342594 = false; } } } } } } } closedir($v89d33f4133); } return $v5c1c342594; } protected static function getFilePathKey($v17be587282, $pbfa01ed1) { $v52755e65e5 = false; $v2c942d8ac7 = array(); if ($v17be587282 && is_dir($v17be587282) && ($v89d33f4133 = opendir($v17be587282)) ) { while (($v7dffdb5a5b = readdir($v89d33f4133)) !== false) { if ($v7dffdb5a5b != "." && $v7dffdb5a5b != "..") { $pf3dc0762 = $v17be587282 . $v7dffdb5a5b; if (is_dir($pf3dc0762)) { $v9ad1385268 = self::getFilePathKey($pf3dc0762 . "/", $pbfa01ed1); $v52755e65e5 = isset($v9ad1385268["file_path"]) ? $v9ad1385268["file_path"] : null; $v2c942d8ac7 = array_merge($v2c942d8ac7, isset($v9ad1385268["free_file_paths"]) ? $v9ad1385268["free_file_paths"] : null); } else { $v57b4b0200b = @file_get_contents($pf3dc0762); $pfb662071 = unserialize($v57b4b0200b); if (is_array($pfb662071) && isset($pfb662071[$pbfa01ed1])) $v52755e65e5 = $pf3dc0762; else if (!$pfb662071 || count($pfb662071) < self::MAXIMUM_ITEMS_PER_FILE) $v2c942d8ac7[] = $pf3dc0762; } if ($v52755e65e5) break; } } closedir($v89d33f4133); } return array("file_path" => $v52755e65e5, "free_file_paths" => $v2c942d8ac7); } protected function registerKey($v17be587282, $pbfa01ed1, $v2c942d8ac7) { $v16c9091332 = false; $pc37695cb = $v2c942d8ac7 ? count($v2c942d8ac7) : 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pa0613321 = $v2c942d8ac7[$v43dd7d0051]; if (file_exists($pa0613321)) { $v57b4b0200b = @file_get_contents($pa0613321); $pfb662071 = unserialize($v57b4b0200b); if (!is_array($pfb662071)) $pfb662071 = array(); if (count($pfb662071) < self::MAXIMUM_ITEMS_PER_FILE) { if ($v9a84a79e2e = fopen($pa0613321, "r+")) { $v07d639e181 = 5; do { $v8d359f8d5a = flock($v9a84a79e2e, LOCK_EX); if (!$v8d359f8d5a) { --$v07d639e181; usleep(round( rand(0, 100) * 1000 )); } } while (!$v8d359f8d5a && $v07d639e181 > 0); if ($v8d359f8d5a) { $v57b4b0200b = @file_get_contents($pa0613321); $pfb662071 = unserialize($v57b4b0200b); if (is_array($pfb662071) && count($pfb662071) < self::MAXIMUM_ITEMS_PER_FILE) { $pfb662071[$pbfa01ed1] = true; $v57b4b0200b = serialize($pfb662071); $pc22b42fd = fopen($pa0613321, "w"); if ($pc22b42fd) { $v5c1c342594 = fwrite($pc22b42fd, $v57b4b0200b); $v5c1c342594 = $v5c1c342594 === false ? false : true; if ($v5c1c342594) { $v16c9091332 = true; } fclose($pc22b42fd); } } flock($v9a84a79e2e, LOCK_UN); } fclose($v9a84a79e2e); } } } if($v16c9091332) { break; } } if (!$v16c9091332) { $pd6e5bd91 = $this->CacheHandler->getCacheFileHandler()->getCacheFolderHandler(); $v9280d62b92 = $pd6e5bd91->getFolderPath($v17be587282, true); $v0e5a9eeca2 = $v9280d62b92 . uniqid(); if ($v9a84a79e2e = fopen($v0e5a9eeca2, "w")) { $pfb662071 = array($pbfa01ed1 => true); $v57b4b0200b = serialize($pfb662071); $v5c1c342594 = fwrite($v9a84a79e2e, $v57b4b0200b); $v5c1c342594 = $v5c1c342594 === false ? false : true; if ($v5c1c342594) { $v16c9091332 = true; } fclose($v9a84a79e2e); } $pd6e5bd91->checkFolderFiles($v9280d62b92); } return $v16c9091332; } protected function createServiceMainError($pdcf670f6, $v3fb9f41470 = false) { $v9bbf151086 = $this->CacheHandler->getServiceDirPath($pdcf670f6, $v3fb9f41470); if($this->CacheHandler->getCacheFileHandler()->exists($v9bbf151086)) { $pf3dc0762 = $v9bbf151086 . self::SERVICE_MAIN_ERROR_FILE_NAME; return $this->CacheHandler->getCacheFileHandler()->write($pf3dc0762, 1); } return true; } protected function setRegistrationKeyStatus($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v857fedb90c = false) { $v7f231a9072 = $this->getRegistrationKeyStatusFilePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); $v7959970a41 = $this->CacheHandler->getCacheFileHandler()->exists($v7f231a9072); $v28c1cd997a = (!$v7959970a41 && $v857fedb90c) || ($v7959970a41 && $this->CacheHandler->getCacheFileHandler()->getContent($v7f231a9072) != $v857fedb90c); if($v28c1cd997a) { if(CacheHandlerUtil::preparePath(dirname($v7f231a9072))) return $this->CacheHandler->getCacheFileHandler()->write($v7f231a9072, $v857fedb90c); return false; } return true; } public function getRegistrationKeyStatus($pdcf670f6, $pbfa01ed1, $v3fb9f41470) { $v7f231a9072 = $this->getRegistrationKeyStatusFilePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); if($this->CacheHandler->getCacheFileHandler()->exists($v7f231a9072)) { $v57b4b0200b = $this->CacheHandler->getCacheFileHandler()->getContent($v7f231a9072); } return $v57b4b0200b; } protected function getRegistrationKeyStatusFilePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470) { $v47cef7ac50 = $this->CacheHandler->getServicePath($pdcf670f6, $pbfa01ed1, $v3fb9f41470); $v17be587282 = dirname($v47cef7ac50); $v250a1176c9 = basename($v47cef7ac50); return $v17be587282 . "/" . self::RELATED_SERVICE_REGISTRATION_STATUS_FOLDER_NAME . "/" . hash("md4", $v250a1176c9); } } ?>
