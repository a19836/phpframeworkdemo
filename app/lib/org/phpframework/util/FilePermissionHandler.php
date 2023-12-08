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
 */ class FilePermissionHandler { public static function getFilePermissionsInfo($pf3dc0762) { $v2d2046720b = fileperms($pf3dc0762); switch ($v2d2046720b & 0xF000) { case 0xC000: $v872c4849e0 = 's'; break; case 0xA000: $v872c4849e0 = 'l'; break; case 0x8000: $v872c4849e0 = 'r'; break; case 0x6000: $v872c4849e0 = 'b'; break; case 0x4000: $v872c4849e0 = 'd'; break; case 0x2000: $v872c4849e0 = 'c'; break; case 0x1000: $v872c4849e0 = 'p'; break; default: $v872c4849e0 = 'u'; } $v872c4849e0 .= (($v2d2046720b & 0x0100) ? 'r' : '-'); $v872c4849e0 .= (($v2d2046720b & 0x0080) ? 'w' : '-'); $v872c4849e0 .= (($v2d2046720b & 0x0040) ? (($v2d2046720b & 0x0800) ? 's' : 'x' ) : (($v2d2046720b & 0x0800) ? 'S' : '-')); $v872c4849e0 .= (($v2d2046720b & 0x0020) ? 'r' : '-'); $v872c4849e0 .= (($v2d2046720b & 0x0010) ? 'w' : '-'); $v872c4849e0 .= (($v2d2046720b & 0x0008) ? (($v2d2046720b & 0x0400) ? 's' : 'x' ) : (($v2d2046720b & 0x0400) ? 'S' : '-')); $v872c4849e0 .= (($v2d2046720b & 0x0004) ? 'r' : '-'); $v872c4849e0 .= (($v2d2046720b & 0x0002) ? 'w' : '-'); $v872c4849e0 .= (($v2d2046720b & 0x0001) ? (($v2d2046720b & 0x0200) ? 't' : 'x' ) : (($v2d2046720b & 0x0200) ? 'T' : '-')); return $v872c4849e0; } public static function getFileUserOwnerInfo($pf3dc0762) { $v9509165848 = fileowner($pf3dc0762); return $v9509165848 && function_exists("posix_getpwuid") ? posix_getpwuid($v9509165848) : null; } public static function getFileUserGroupInfo($pf3dc0762) { $v001e99a408 = filegroup($pf3dc0762); return $v001e99a408 && function_exists("posix_getgrgid") ? posix_getgrgid($v001e99a408) : null; } } ?>
