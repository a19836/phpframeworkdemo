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
include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); abstract class ServiceCacheRelatedServicesHandler { const MAXIMUM_ITEMS_PER_FILE = 10000; const RELATED_SERVICES_FOLDER_NAME = "__related"; protected $CacheHandler; abstract public function addServiceToRelatedKeysToDelete($pdcf670f6, $pbfa01ed1, $pe7235a8d, $v3fb9f41470 = false); abstract public function delete($pdcf670f6, $pbfa01ed1, $v3fb9f41470, $v1491940c54, $v91d4d88b89); public function getServiceRuleToDeletePath($pdcf670f6, $v3fb9f41470, $v9a6acca23e, $pd9e207f2) { return $this->getServiceRuleToDeleteDirPath($pdcf670f6, $v3fb9f41470) . $this->getServiceRuleToDeleteRelativePath($v9a6acca23e, $pd9e207f2); } protected function getServiceRuleToDeleteDirPath($pdcf670f6, $v3fb9f41470) { return $this->CacheHandler->getServiceDirPath($pdcf670f6, $v3fb9f41470) . self::RELATED_SERVICES_FOLDER_NAME . "/"; } protected function getServiceRuleToDeleteRelativePath($v9a6acca23e, $pd9e207f2) { $v9a6acca23e = CacheHandlerUtil::getCorrectKeyType($v9a6acca23e); return strtolower($v9a6acca23e) . "/" . md5($pd9e207f2) . "/"; } public function getCacheHandler() {return $this->CacheHandler;} } ?>
