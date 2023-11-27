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

include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); class TextTranslator { private $pe90a98de; private $v0b6600bd7b; private $v0501187bdb; private $v2138b0c621; private $pcd6eb013; public function __construct($pe90a98de, $v0b6600bd7b) { $this->pe90a98de = $pe90a98de . "/"; $this->v0b6600bd7b = $v0b6600bd7b; $this->v0501187bdb = array(); $this->v2138b0c621 = array(); $this->pcd6eb013 = array(); } public function getDefaultLang() { return $this->v0b6600bd7b; } private function f530c2a28ba($v0da33fe24a = null) { return $this->pe90a98de . ($v0da33fe24a ? "$v0da33fe24a/" : ""); } private function f0ad1f7487d($v0da33fe24a = null, $v49a0d96ab9 = null) { return $this->f530c2a28ba($v0da33fe24a) . ($v49a0d96ab9 ? $v49a0d96ab9 : $this->v0b6600bd7b) . ".php"; } private function f40a427a129($pf3dc0762) { if (!isset($this->v0501187bdb[$pf3dc0762])) { if (file_exists($pf3dc0762)) { $v6490ea3a15 = file_get_contents($pf3dc0762); $v9e9acf7b8a = $v6490ea3a15 ? unserialize($v6490ea3a15) : null; $this->v0501187bdb[$pf3dc0762] = is_array($v9e9acf7b8a) ? $v9e9acf7b8a : array(); } else $this->v0501187bdb[$pf3dc0762] = array(); } } public function setTranslationsFile($pf7a56ff7, $v0da33fe24a = null, $v49a0d96ab9 = null) { $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); return file_put_contents($pf3dc0762, serialize($pf7a56ff7)) !== false; } public function replaceTextTranslationInFile($v39e1347c93, $v4a170fa071, $v0da33fe24a = null, $v49a0d96ab9 = null) { $this->replaceTextTranslation($v39e1347c93, $v4a170fa071, $v0da33fe24a, $v49a0d96ab9); $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $v0501187bdb = isset($this->v0501187bdb[$pf3dc0762]) ? $this->v0501187bdb[$pf3dc0762] : null; return file_put_contents($pf3dc0762, serialize($v0501187bdb)) !== false; } public function replaceTextTranslation($v39e1347c93, $v4a170fa071, $v0da33fe24a = null, $v49a0d96ab9 = null) { $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $this->f40a427a129($pf3dc0762); $this->v0501187bdb[$pf3dc0762][$v39e1347c93] = $v4a170fa071; return isset($this->v0501187bdb[$pf3dc0762]) ? $this->v0501187bdb[$pf3dc0762] : null; } public function removeTextTranslationInFile($v39e1347c93, $v0da33fe24a = null, $v49a0d96ab9 = null) { $this->removeTextTranslation($v39e1347c93, $v0da33fe24a, $v49a0d96ab9); $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $v0501187bdb = isset($this->v0501187bdb[$pf3dc0762]) ? $this->v0501187bdb[$pf3dc0762] : null; return file_put_contents($pf3dc0762, serialize($v0501187bdb)) !== false; } public function removeTextTranslation($v39e1347c93, $v0da33fe24a = null, $v49a0d96ab9 = null) { $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $this->f40a427a129($pf3dc0762); unset($this->v0501187bdb[$pf3dc0762][$v39e1347c93]); return isset($this->v0501187bdb[$pf3dc0762]) ? $this->v0501187bdb[$pf3dc0762] : null; } public function addTextTranslationsFile($pf3dc0762, $v0da33fe24a = null, $v49a0d96ab9 = null, $pa245524e = false) { if (file_exists($pf3dc0762)) { $v6490ea3a15 = file_get_contents($pf3dc0762); $v9fd1003f1d = $v6490ea3a15 ? unserialize($v6490ea3a15) : null; return $this->addTextTranslations($v9fd1003f1d, $v0da33fe24a, $v49a0d96ab9, $pa245524e); } } public function addTextTranslations($v9fd1003f1d, $v0da33fe24a = null, $v49a0d96ab9 = null, $pa245524e = false) { if ($v9fd1003f1d) { $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $this->f40a427a129($pf3dc0762); if (is_array($v9fd1003f1d)) foreach ($v9fd1003f1d as $v39e1347c93 => $v4a170fa071) if (!$pa245524e || !isset($this->v0501187bdb[$pf3dc0762][$v39e1347c93])) $this->v0501187bdb[$pf3dc0762][$v39e1347c93] = $v4a170fa071; return isset($this->v0501187bdb[$pf3dc0762]) ? $this->v0501187bdb[$pf3dc0762] : null; } } public function getTextTranslation($v39e1347c93, $v0da33fe24a = null, $v49a0d96ab9 = null) { $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $this->f40a427a129($pf3dc0762); return isset($this->v0501187bdb[$pf3dc0762][$v39e1347c93]) ? $this->v0501187bdb[$pf3dc0762][$v39e1347c93] : null; } public function translateText($v39e1347c93, $v0da33fe24a = null, $v49a0d96ab9 = null) { $v4a170fa071 = $this->getTextTranslation($v39e1347c93, $v0da33fe24a, $v49a0d96ab9); return $v4a170fa071 ? $v4a170fa071 : $v39e1347c93; } public function translateTextFromFile($pf3dc0762, $v39e1347c93) { $this->f40a427a129($pf3dc0762); return isset($this->v0501187bdb[$pf3dc0762][$v39e1347c93]) ? $this->v0501187bdb[$pf3dc0762][$v39e1347c93] : null; } public function getTranslations($v0da33fe24a = null, $v49a0d96ab9 = null) { $pf3dc0762 = $this->f0ad1f7487d($v0da33fe24a, $v49a0d96ab9); $this->f40a427a129($pf3dc0762); return isset($this->v0501187bdb[$pf3dc0762]) ? $this->v0501187bdb[$pf3dc0762] : null; } public function insertCategory($v0da33fe24a) { return $v0da33fe24a && (is_dir($this->pe90a98de . $v0da33fe24a) || mkdir($this->pe90a98de . $v0da33fe24a, 0755, true)); } public function updateCategory($v39c9d31943, $v76901ab04b) { if ($v39c9d31943 && $v76901ab04b && is_dir($this->pe90a98de . $v39c9d31943)) return rename($this->pe90a98de . $v39c9d31943, $this->pe90a98de . $v76901ab04b); } public function removeCategory($v0da33fe24a) { if ($v0da33fe24a && is_dir($this->pe90a98de . $v0da33fe24a)) return CacheHandlerUtil::deleteFolder($this->pe90a98de . $v0da33fe24a); } public function categoryExists($v0da33fe24a) { return $v0da33fe24a && is_dir($this->pe90a98de . $v0da33fe24a); } public function getCategories($v0da33fe24a = null, $v49a0d96ab9 = null, $v59c6829ee1 = false) { $v1cbfbb49c5 = md5($v0da33fe24a . "_" . $v49a0d96ab9 . "_" . $v59c6829ee1); if (isset($this->v2138b0c621[$v1cbfbb49c5])) return $this->v2138b0c621[$v1cbfbb49c5]; $pa2dcbd2e = array(); $pa32be502 = $v0da33fe24a ? "$v0da33fe24a/" : ""; $v6ee393d9fb = array_diff(scandir($this->pe90a98de . $pa32be502), array('.', '..')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { if ($v59c6829ee1 && is_dir($this->pe90a98de. $pa32be502 . $v7dffdb5a5b)) { if (!$v49a0d96ab9) $pa2dcbd2e[] = $pa32be502 . $v7dffdb5a5b; $pa2dcbd2e = array_merge($pa2dcbd2e, $this->getCategories($pa32be502 . $v7dffdb5a5b, $v49a0d96ab9, $v59c6829ee1)); } else if (!$v49a0d96ab9 || $v7dffdb5a5b == $v49a0d96ab9 . ".php") $pa2dcbd2e[] = $pa32be502 ? dirname($pa32be502 . $v7dffdb5a5b) : ""; } $pa2dcbd2e = array_unique($pa2dcbd2e); $this->v2138b0c621[$v1cbfbb49c5] = $pa2dcbd2e; return $pa2dcbd2e; } public function getLanguageCategories($v49a0d96ab9 = null, $v59c6829ee1 = false) { return $this->getCategories(null, $v49a0d96ab9, $v59c6829ee1); } public function getTextCategories($v39e1347c93, $v0da33fe24a = null, $v49a0d96ab9 = null, $v59c6829ee1 = false) { $v2a7ea8dd0c = array(); $pa2dcbd2e = $this->getCategories($v0da33fe24a, $v49a0d96ab9, $v59c6829ee1); foreach ($pa2dcbd2e as $v0da33fe24a) { $pb262485f = $v49a0d96ab9 ? array($v49a0d96ab9) : $this->getCategoryLanguages($v0da33fe24a); foreach ($pb262485f as $v49a0d96ab9) { $pc2dd227e = $this->getTranslations($v0da33fe24a, $v49a0d96ab9); if (isset($pc2dd227e[$v39e1347c93])) $v2a7ea8dd0c[] = $v0da33fe24a; } } return $v2a7ea8dd0c; } public function insertLanguage($v49a0d96ab9, $v0da33fe24a = null) { return $v49a0d96ab9 && (file_exists($this->pe90a98de . "$v0da33fe24a/{$v49a0d96ab9}.php") || file_put_contents($this->pe90a98de . "$v0da33fe24a/{$v49a0d96ab9}.php", "") !== false); } public function updateLanguage($pc6b1529d, $v048d42cf6b, $v0da33fe24a = null, $v59c6829ee1 = false) { if ($pc6b1529d && $v048d42cf6b) { $pa2dcbd2e = $this->getCategories($v0da33fe24a, $pc6b1529d, $v59c6829ee1); if ($pa2dcbd2e) { $v5c1c342594 = true; foreach ($pa2dcbd2e as $v0da33fe24a) if (file_exists($this->pe90a98de . "$v0da33fe24a/{$pc6b1529d}.php") && !rename($this->pe90a98de . "$v0da33fe24a/{$pc6b1529d}.php", $this->pe90a98de . "$v0da33fe24a/{$v048d42cf6b}.php")) $v5c1c342594 = false; return $v5c1c342594; } } } public function removeLanguage($v49a0d96ab9, $v0da33fe24a = null, $v59c6829ee1 = false) { if ($v49a0d96ab9) { $pa2dcbd2e = $this->getCategories($v0da33fe24a, $v49a0d96ab9, $v59c6829ee1); $v5c1c342594 = true; foreach ($pa2dcbd2e as $v0da33fe24a) if (file_exists($this->pe90a98de . "$v0da33fe24a/{$v49a0d96ab9}.php") && !unlink($this->pe90a98de . "$v0da33fe24a/{$v49a0d96ab9}.php")) $v5c1c342594 = false; return $v5c1c342594; } } public function languageExists($v49a0d96ab9, $v0da33fe24a = null) { return $v49a0d96ab9 && file_exists($this->pe90a98de . "$v0da33fe24a/{$v49a0d96ab9}.php"); } public function getCategoryLanguages($v0da33fe24a = null, $v59c6829ee1 = false) { $v1cbfbb49c5 = md5($v0da33fe24a . "_" . $v59c6829ee1); if (isset($this->pcd6eb013[$v1cbfbb49c5])) return $this->pcd6eb013[$v1cbfbb49c5]; $pe36e8799 = array(); $pa32be502 = $v0da33fe24a ? "$v0da33fe24a/" : ""; $v6ee393d9fb = array_diff(scandir($this->pe90a98de . $pa32be502), array('.', '..')); foreach ($v6ee393d9fb as $v7dffdb5a5b) { if ($v59c6829ee1 && is_dir($this->pe90a98de . $pa32be502 . $v7dffdb5a5b)) $pe36e8799 = array_merge($pe36e8799, $this->getCategoryLanguages($pa32be502 . $v7dffdb5a5b, $v59c6829ee1)); else if (substr($v7dffdb5a5b, -4) == ".php") $pe36e8799[] = pathinfo($v7dffdb5a5b, PATHINFO_FILENAME); } $pe36e8799 = array_unique($pe36e8799); $this->pcd6eb013[$v1cbfbb49c5] = $pe36e8799; return $pe36e8799; } public function getTextLanguages($v39e1347c93, $v0da33fe24a = null, $v49a0d96ab9 = null, $v59c6829ee1 = false) { $pb70c5fd1 = array(); $pa2dcbd2e = $this->getCategories($v0da33fe24a, $v49a0d96ab9, $v59c6829ee1); foreach ($pa2dcbd2e as $v0da33fe24a) { $pb262485f = $v49a0d96ab9 ? array($v49a0d96ab9) : $this->getCategoryLanguages($v0da33fe24a); foreach ($pb262485f as $v49a0d96ab9) { $pc2dd227e = $this->getTranslations($v0da33fe24a, $v49a0d96ab9); if (isset($pc2dd227e[$v39e1347c93])) $pb70c5fd1[] = $v49a0d96ab9; } } return array_unique($pb70c5fd1); } } ?>
