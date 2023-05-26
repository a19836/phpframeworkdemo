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

class OpenSSLCipherHandler { public static function encryptText($v39e1347c93, $pbfa01ed1) { if (strlen($v39e1347c93)) { $pf1932edc = "AES-128-CBC"; $v140849ec17 = openssl_cipher_iv_length($pf1932edc); $v94331c7891 = openssl_random_pseudo_bytes($v140849ec17); $v83b6d58d9a = openssl_encrypt($v39e1347c93, $pf1932edc, $pbfa01ed1, $v5d3813882f = OPENSSL_RAW_DATA, $v94331c7891); $v917befa8a2 = hash_hmac('sha256', $v83b6d58d9a, $pbfa01ed1, $v160ad04537 = true); $v8c3792c37f = base64_encode( $v94331c7891 . $v917befa8a2 . $v83b6d58d9a ); return $v8c3792c37f; } return $v39e1347c93; } public static function encryptVariable($v847e7d0a83, $pbfa01ed1) { if ($v847e7d0a83) { if (is_array($v847e7d0a83) || is_object($v847e7d0a83)) { foreach ($v847e7d0a83 as $pe5c5e2fe => $v956913c90f) $v847e7d0a83[$pe5c5e2fe] = self::encryptVariable($v956913c90f, $pbfa01ed1); } else $v847e7d0a83 = self::encryptText($v847e7d0a83, $pbfa01ed1); } return $v847e7d0a83; } public static function encryptArray($pfb662071, $pbfa01ed1) { return self::encryptVariable($pfb662071, $pbfa01ed1); } public static function decryptText($v8c3792c37f, $pbfa01ed1) { if (strlen($v8c3792c37f)) { $pf1932edc = "AES-128-CBC"; $v9a8b7dc209 = base64_decode($v8c3792c37f); $v140849ec17 = openssl_cipher_iv_length($pf1932edc); $v94331c7891 = substr($v9a8b7dc209, 0, $v140849ec17); $v917befa8a2 = substr($v9a8b7dc209, $v140849ec17, $v498407192a = 32); $v83b6d58d9a = substr($v9a8b7dc209, $v140849ec17 + $v498407192a); $pca24981a = openssl_decrypt($v83b6d58d9a, $pf1932edc, $pbfa01ed1, $v5d3813882f = OPENSSL_RAW_DATA, $v94331c7891); $pd2ad6bfa = hash_hmac('sha256', $v83b6d58d9a, $pbfa01ed1, $v160ad04537 = true); if (hash_equals($v917befa8a2, $pd2ad6bfa)) return $pca24981a; } } public static function decryptVariable($v847e7d0a83, $pbfa01ed1) { if ($v847e7d0a83) { if (is_array($v847e7d0a83) || is_object($v847e7d0a83)) { foreach ($v847e7d0a83 as $pe5c5e2fe => $v956913c90f) $v847e7d0a83[$pe5c5e2fe] = self::decryptVariable($v956913c90f, $pbfa01ed1); } else $v847e7d0a83 = self::decryptText($v847e7d0a83, $pbfa01ed1); } return $v847e7d0a83; } public static function decryptArray($pfb662071, $pbfa01ed1) { return self::decryptVariable($pfb662071, $pbfa01ed1); } } ?>
