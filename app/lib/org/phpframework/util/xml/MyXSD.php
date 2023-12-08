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
include get_lib("org.phpframework.util.xml.exception.MyXSDException"); class MyXSD { public static function validate($pae77d38c, $v538cb1a1f7) { if(!file_exists($v538cb1a1f7)) { launch_exception(new MyXSDException(2, $v538cb1a1f7)); return false; } if ($pae77d38c) { $v8383ac73dd = libxml_use_internal_errors(); libxml_use_internal_errors(true); $v4a81532cb9 = new DOMDocument(); $v4a81532cb9->loadXML($pae77d38c); $v8a29987473 = false; if(!$v4a81532cb9->schemaValidate($v538cb1a1f7)) { $v8a29987473 = self::f45091699ef(); } libxml_use_internal_errors($v8383ac73dd); return $v8a29987473 === false ? true : $v8a29987473; } return false; } private static function f45091699ef() { $v655402c226 = array(); $v8a29987473 = libxml_get_errors(); foreach ($v8a29987473 as $v0f9512fda4) { $v655402c226[] = self::f9721a0e5ce($v0f9512fda4); } libxml_clear_errors(); return $v655402c226; } private static function f9721a0e5ce($v0f9512fda4) { $v05b1c40a78 = ""; switch ($v0f9512fda4->level) { case LIBXML_ERR_WARNING: $v05b1c40a78 .= "<b>Warning $v0f9512fda4->code</b>: "; break; case LIBXML_ERR_ERROR: $v05b1c40a78 .= "<b>Error $v0f9512fda4->code</b>: "; break; case LIBXML_ERR_FATAL: $v05b1c40a78 .= "<b>Fatal Error $v0f9512fda4->code</b>: "; break; } $v05b1c40a78 .= trim($v0f9512fda4->message); $v05b1c40a78 .= " on line <b>$v0f9512fda4->line</b>\n"; return $v05b1c40a78; } } ?>
