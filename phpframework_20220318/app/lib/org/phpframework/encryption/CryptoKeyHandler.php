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

use \Defuse\Crypto\Crypto; use \Defuse\Crypto\Exception as Ex; include get_lib("lib.vendor.phpencryption.src.ExceptionHandler"); include get_lib("lib.vendor.phpencryption.src.Crypto"); include get_lib("lib.vendor.phpencryption.src.Exception.CryptoException"); include get_lib("lib.vendor.phpencryption.src.Exception.InvalidCiphertextException"); include get_lib("lib.vendor.phpencryption.src.Exception.CryptoTestFailedException"); include get_lib("lib.vendor.phpencryption.src.Exception.CannotPerformOperationException"); class CryptoKeyHandler { public static function getKey() { try { $pbfa01ed1 = Crypto::createNewRandomKey(); } catch (Ex\CryptoTestFailedException $v020f934c99) { die('Cannot safely create a key'); } catch (Ex\CannotPerformOperationException $v020f934c99) { die('Cannot safely create a key'); } return $pbfa01ed1; } public static function getHexKey() { return self::binToHex(self::getKey()); } public static function encryptText($v39e1347c93, $pbfa01ed1) { if (!isset($v39e1347c93)) { return null; } try { $v8c3792c37f = Crypto::encrypt($v39e1347c93, $pbfa01ed1); } catch (Ex\CryptoTestFailedException $v020f934c99) { die('Cannot safely perform encryption'); } catch (Ex\CannotPerformOperationException $v020f934c99) { die('Cannot safely perform encryption'); } return $v8c3792c37f; } public static function decryptText($v8c3792c37f, $pbfa01ed1) { if (empty($v8c3792c37f)) { return null; } try { $v84ddd6ebce = Crypto::decrypt($v8c3792c37f, $pbfa01ed1); } catch (Ex\InvalidCiphertextException $v020f934c99) { die('DANGER! DANGER! The ciphertext has been tampered with!'); } catch (Ex\CryptoTestFailedException $v020f934c99) { die('Cannot safely perform decryption'); } catch (Ex\CannotPerformOperationException $v020f934c99) { die('Cannot safely perform decryption'); } return $v84ddd6ebce; } public static function encryptJsonObject($v972f1a5c2b, $pbfa01ed1) { if (!isset($v972f1a5c2b)) { return null; } $v39e1347c93 = json_encode($v972f1a5c2b); return self::encryptText($v39e1347c93, $pbfa01ed1); } public static function decryptJsonObject($v8c3792c37f, $pbfa01ed1, $v6d2b56e8a0 = true) { $v84ddd6ebce = self::decryptText($v8c3792c37f, $pbfa01ed1); return isset($v84ddd6ebce) ? json_decode($v84ddd6ebce, $v6d2b56e8a0) : null; } public static function encryptSerializedObject($v972f1a5c2b, $pbfa01ed1) { if (!isset($v972f1a5c2b)) { return null; } $v39e1347c93 = serialize($v972f1a5c2b); return self::encryptText($v39e1347c93, $pbfa01ed1); } public static function decryptSerializedObject($v8c3792c37f, $pbfa01ed1) { $v84ddd6ebce = self::decryptText($v8c3792c37f, $pbfa01ed1); return isset($v84ddd6ebce) ? unserialize($v84ddd6ebce) : null; } public static function binToHex($pbeb3641c) { return Crypto::binToHex($pbeb3641c); } public static function hexToBin($v46ed6319ea) { return Crypto::hexToBin($v46ed6319ea); } } ?>
