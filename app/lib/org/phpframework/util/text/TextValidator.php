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

class TextValidator { public static function isBinary($v67db1bd535) { return preg_match('~[^\x20-\x7E\t\r\n]~', $v67db1bd535); } public static function isEmail($v67db1bd535) { if(preg_match("/^([a-z0-9\+\-\_\.]+)(\@)([a-z0-9\-\_\.]+)\.([a-z]{2,10})$/i", $v67db1bd535)) return strpos($v67db1bd535, "..") === false; return false; } public static function isDomain($v67db1bd535) { if(preg_match("/^([a-z0-9-_]+\.)*[a-z0-9][a-z0-9-_]+\.[a-z]{2,}$/i", $v67db1bd535)) return strpos($v67db1bd535, "..") === false; return false; } public static function isPhone($v67db1bd535) { return preg_match("/^([\+]*)([0-9\- \)\(]*)$/i", $v67db1bd535); } public static function isNumber($v67db1bd535) { return is_numeric($v67db1bd535); } public static function isDecimal($v67db1bd535) { return preg_match("/^-?([0-9]+|[0-9]+\.[0-9]+)$/", $v67db1bd535); } public static function isSmallInt($v67db1bd535) { return preg_match("/^[0,1]{1}$/", $v67db1bd535); } public static function isDate($v67db1bd535) { return preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})$/", $v67db1bd535); } public static function isDateTime($v67db1bd535) { return preg_match("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})(([ T]{1})([0-9]{1,2}):([0-9]{1,2})(:([0-9]{1,2}))?)?$/", $v67db1bd535); } public static function isTime($v67db1bd535) { return preg_match("/^([0-9]{1,2}):([0-9]{1,2})(:([0-9]{1,2}))?$/", $v67db1bd535); } public static function isIPAddress($v67db1bd535) { return preg_match("/^((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])$/", $v67db1bd535); } public static function isFileName($v67db1bd535) { return preg_match("/^[\w\-\+\.]+$/u", $v67db1bd535); } public static function checkMinLength($v67db1bd535, $v0911c6122e) { return is_numeric($v0911c6122e) && strlen("$v67db1bd535") >= $v0911c6122e; } public static function checkMaxLength($v67db1bd535, $v0911c6122e) { return is_numeric($v0911c6122e) && strlen("$v67db1bd535") <= $v0911c6122e; } public static function checkMinValue($v67db1bd535, $v9c75c2f068) { return is_numeric($v67db1bd535) && is_numeric($v9c75c2f068) && $v67db1bd535 >= $v9c75c2f068; } public static function checkMaxValue($v67db1bd535, $v339f9b50e0) { return is_numeric($v67db1bd535) && is_numeric($v339f9b50e0) && $v67db1bd535 <= $v339f9b50e0; } public static function checkMinWords($v67db1bd535, $v9c75c2f068) { return is_numeric($v9c75c2f068) && str_word_count($v67db1bd535) >= $v9c75c2f068; } public static function checkMaxWords($v67db1bd535, $v339f9b50e0) { return is_numeric($v339f9b50e0) && str_word_count($v67db1bd535) <= $v339f9b50e0; } public static function checkMinDate($v67db1bd535, $v9c75c2f068) { $v956913c90f = strtotime($v67db1bd535); $v6107abf109 = strtotime($v9c75c2f068); return self::checkMinValue($v956913c90f, $v6107abf109); } public static function checkMaxDate($v67db1bd535, $v339f9b50e0) { $v956913c90f = strtotime($v67db1bd535); $v6107abf109 = strtotime($v339f9b50e0); return self::checkMaxValue($v956913c90f, $v6107abf109); } } ?>
