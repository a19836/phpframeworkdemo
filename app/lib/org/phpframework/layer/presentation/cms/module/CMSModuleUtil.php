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

include_once get_lib("org.phpframework.cache.CacheHandlerUtil"); class CMSModuleUtil { public static function copyFolder($v92dcc541a8, $pa5b0817e) { if ($v92dcc541a8 && $pa5b0817e && is_dir($v92dcc541a8)) { if (!is_dir($pa5b0817e)) @mkdir($pa5b0817e, 0755, true); if (is_dir($pa5b0817e)) { $v5c1c342594 = true; $v6ee393d9fb = scandir($v92dcc541a8); if ($v6ee393d9fb) foreach ($v6ee393d9fb as $v7dffdb5a5b) if ($v7dffdb5a5b != '.' && $v7dffdb5a5b != '..') { if (is_dir("$v92dcc541a8/$v7dffdb5a5b")) { if (!self::copyFolder("$v92dcc541a8/$v7dffdb5a5b", "$pa5b0817e/$v7dffdb5a5b")) $v5c1c342594 = false; } else if (!copy("$v92dcc541a8/$v7dffdb5a5b", "$pa5b0817e/$v7dffdb5a5b")) $v5c1c342594 = false; } return $v5c1c342594; } } } public static function copyFile($v92dcc541a8, $pa5b0817e) { if ($v92dcc541a8 && $pa5b0817e && file_exists($v92dcc541a8)) { if (is_dir($v92dcc541a8)) return self::copyFolder($v92dcc541a8, $pa5b0817e); $v89d33f4133 = dirname($pa5b0817e); if ($v89d33f4133 && !is_dir($v89d33f4133)) @mkdir($v89d33f4133, 0755, true); return is_dir($v89d33f4133) && copy($v92dcc541a8, $pa5b0817e); } } public static function copyFileToLayers($v92dcc541a8, $pa5b0817e, $pc2ed4340, $pd26327f7) { if ($pd26327f7 && $v92dcc541a8) { $v5c1c342594 = true; $pc37695cb = count($pd26327f7); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) if (!self::copyFile($pc2ed4340 . "/$v92dcc541a8", $pd26327f7[$v43dd7d0051] . "/$pa5b0817e")) $v5c1c342594 = false; return $v5c1c342594; } } public static function deleteFiles($v6ee393d9fb, $pae9f0543 = array()) { $v5c1c342594 = true; if ($v6ee393d9fb) { $pc37695cb = count($v6ee393d9fb); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $pa32be502 = $v6ee393d9fb[$v43dd7d0051]; if ($pa32be502 && file_exists($pa32be502)) { if (is_dir($pa32be502)) { if (!self::deleteFolder($pa32be502, $pae9f0543)) $v5c1c342594 = false; } else if (!unlink($pa32be502)) $v5c1c342594 = false; } } } return $v5c1c342594; } public static function deleteFolder($v89d33f4133, $pae9f0543 = array()) { return CacheHandlerUtil::deleteFolder($v89d33f4133, true, $pae9f0543); } public static function deleteFileFromLayers($v92dcc541a8, $pd26327f7, $pae9f0543 = array()) { if ($pd26327f7 && $v92dcc541a8) { $v5c1c342594 = true; $v6ee393d9fb = array(); $pc37695cb = count($pd26327f7); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) $v6ee393d9fb[] = $pd26327f7[$v43dd7d0051] . "/$v92dcc541a8"; return self::deleteFiles($v6ee393d9fb, $pae9f0543); } } } ?>
