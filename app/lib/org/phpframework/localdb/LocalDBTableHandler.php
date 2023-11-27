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

include_once get_lib("org.phpframework.encryption.CryptoKeyHandler"); class LocalDBTableHandler { private $v4ab372da3a; private $pbdcbd484; public function __construct($v4ab372da3a, $pbdcbd484) { $this->v4ab372da3a = trim($v4ab372da3a); $this->pbdcbd484 = $pbdcbd484; $this->v4ab372da3a .= substr($this->v4ab372da3a, -1) == "/" ? "" : "/"; } public function changeDBTableEncryptionKey($v8c5df8072b, $pf11e1c70) { $pf72c1d58 = $this->getItems($v8c5df8072b); $pa2aa0517 = $this->pbdcbd484; $this->pbdcbd484 = $pf11e1c70; if ($this->writeTableItems($pf72c1d58, $v8c5df8072b)) { return true; } $this->pbdcbd484 = $pa2aa0517; return false; } public function getPKMaxValue($v8c5df8072b, $pe7b34eee, &$pf72c1d58 = null) { $v339f9b50e0 = null; if (empty($pf72c1d58)) { $pf72c1d58 = $this->getItems($v8c5df8072b); } if (is_array($pf72c1d58) && $pe7b34eee) { foreach ($pf72c1d58 as $pd69fb7d0 => $v342a134247) { if (isset($v342a134247[$pe7b34eee]) && $v342a134247[$pe7b34eee] > $v339f9b50e0) { $v339f9b50e0 = $v342a134247[$pe7b34eee]; } } } return $v339f9b50e0; } public function insertItem($v8c5df8072b, $v539082ff30, $pe2f18119, &$pf72c1d58 = null) { if (empty($pf72c1d58)) { $pf72c1d58 = $this->getItems($v8c5df8072b); } if (!is_array($pf72c1d58)) { $pf72c1d58 = array(); } else if (is_array($pe2f18119)) { $paf1bc6f6 = array(); foreach ($pe2f18119 as $v597dd8d456) { if (isset($v539082ff30[$v597dd8d456])) { $paf1bc6f6[$v597dd8d456] = $v539082ff30[$v597dd8d456]; } } $pf51a1d38 = $this->filterItems($pf72c1d58, $paf1bc6f6); if (!empty($pf51a1d38)) { return false; } } $pf72c1d58[] = $v539082ff30; return $this->writeTableItems($pf72c1d58, $v8c5df8072b); } public function updateItem($v8c5df8072b, $v539082ff30, $pe2f18119, &$pf72c1d58 = null) { if (empty($pf72c1d58)) { $pf72c1d58 = $this->getItems($v8c5df8072b); } if (is_array($pf72c1d58) && is_array($pe2f18119)) { $paf1bc6f6 = array(); foreach ($pe2f18119 as $v597dd8d456) { if (isset($v539082ff30[$v597dd8d456])) { $paf1bc6f6[$v597dd8d456] = $v539082ff30[$v597dd8d456]; } } if ($paf1bc6f6) { $v96a6de657b = $this->filterItems($pf72c1d58, $paf1bc6f6); $v7959970a41 = false; foreach ($v96a6de657b as $pd69fb7d0 => $v342a134247) { if (!$v7959970a41) { $pf72c1d58[$pd69fb7d0] = $v539082ff30; $v7959970a41 = true; } else { unset($pf72c1d58[$pd69fb7d0]); } } $pf72c1d58 = array_values($pf72c1d58); if (!$v7959970a41) { $pf72c1d58[] = $v539082ff30; } return $this->writeTableItems($pf72c1d58, $v8c5df8072b); } } return false; } public function deleteItem($v8c5df8072b, $paf1bc6f6, &$pf72c1d58 = null) { if (empty($pf72c1d58)) { $pf72c1d58 = $this->getItems($v8c5df8072b); } if (is_array($pf72c1d58) && is_array($paf1bc6f6) && !empty($paf1bc6f6)) { $v96a6de657b = $this->filterItems($pf72c1d58, $paf1bc6f6); foreach ($v96a6de657b as $pd69fb7d0 => $v342a134247) { unset($pf72c1d58[$pd69fb7d0]); } $pf72c1d58 = array_values($pf72c1d58); return $this->writeTableItems($pf72c1d58, $v8c5df8072b); } return false; } public function getItems($v8c5df8072b) { $pf72c1d58 = $this->readTableItems($v8c5df8072b); return is_array($pf72c1d58) ? $pf72c1d58 : array(); } public function filterItems($pf72c1d58, $paf1bc6f6, $v155e4f6604 = true) { $v2f228af834 = array(); if (is_array($pf72c1d58)) { if (is_array($paf1bc6f6)) { foreach ($pf72c1d58 as $pd69fb7d0 => $v342a134247) { if (is_array($v342a134247)) { $v5c1c342594 = true; foreach ($paf1bc6f6 as $pbfa01ed1 => $v67db1bd535) { $v6248f28bfd = isset($v342a134247[$pbfa01ed1]) ? $v342a134247[$pbfa01ed1] : null; if ($v6248f28bfd != $v67db1bd535) { $v5c1c342594 = false; break; } } if ($v5c1c342594) { $v2f228af834[$pd69fb7d0] = $v342a134247; } } } $v2f228af834 = $v155e4f6604 ? $v2f228af834 : array_values($v2f228af834); } else { $v2f228af834 = $pf72c1d58; } } return $v2f228af834; } public function readTableItems($v8c5df8072b) { $pa32be502 = $this->getTableFilePath($v8c5df8072b); if (file_exists($pa32be502)) { $v6490ea3a15 = file_get_contents($pa32be502); return $v6490ea3a15 ? \CryptoKeyHandler::decryptJsonObject($v6490ea3a15, $this->pbdcbd484) : null; } return null; } public function writeTableItems($pf72c1d58, $v8c5df8072b) { $pa32be502 = $this->getTableFilePath($v8c5df8072b); $pc7de54d5 = dirname($pa32be502); if ($pc7de54d5 && !is_dir($pc7de54d5)) { mkdir($pc7de54d5, 0755, true); } if (is_dir($pc7de54d5)) { $v8c3792c37f = $pf72c1d58 ? \CryptoKeyHandler::encryptJsonObject($pf72c1d58, $this->pbdcbd484) : null; return file_put_contents($pa32be502, $v8c3792c37f) !== false; } return null; } public function getTableFilePath($v8c5df8072b) { return $this->v4ab372da3a . "$v8c5df8072b.tbl"; } } ?>
