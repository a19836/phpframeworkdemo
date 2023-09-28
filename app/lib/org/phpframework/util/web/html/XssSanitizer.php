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

if (file_exists( get_lib("lib.vendor.xsssanitizer.src.Sanitizer") )) { include_once get_lib("lib.vendor.xsssanitizer.src.FilterRunnerTrait"); include_once get_lib("lib.vendor.xsssanitizer.src.FilterInterface"); include_once get_lib("lib.vendor.xsssanitizer.src.AttributeFinder"); include_once get_lib("lib.vendor.xsssanitizer.src.TagFinderInterface"); include_once get_lib("lib.vendor.xsssanitizer.src.TagFinder.ByAttribute"); include_once get_lib("lib.vendor.xsssanitizer.src.TagFinder.ByTag"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeCleaner"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContentCleaner"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.EscapeTags"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.FilterRunner"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.MetaRefresh"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.RemoveAttributes"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.RemoveBlocks"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContent.CompactExplodedWords"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContent.DecodeEntities"); include_once get_lib("lib.vendor.xsssanitizer.src.Filter.AttributeContent.DecodeUtf8"); include_once get_lib("lib.vendor.xsssanitizer.src.Sanitizer"); } class XssSanitizer { public static function sanitizeHtml($pf8ed4912) { if ($pf8ed4912 && class_exists("Phlib\XssSanitizer\Sanitizer")) { $v00c2ba641b = new Phlib\XssSanitizer\Sanitizer(); return $v00c2ba641b->sanitize($pf8ed4912); } return $pf8ed4912; } public static function sanitizeVariable($v847e7d0a83) { if ($v847e7d0a83) { if (is_array($v847e7d0a83) || is_object($v847e7d0a83)) { foreach ($v847e7d0a83 as $pe5c5e2fe => $v956913c90f) $v847e7d0a83[$pe5c5e2fe] = self::sanitizeVariable($v956913c90f); } else if (class_exists("Phlib\XssSanitizer\Sanitizer")) { $v00c2ba641b = new Phlib\XssSanitizer\Sanitizer(); $v847e7d0a83 = $v00c2ba641b->sanitize($v847e7d0a83); } } return $v847e7d0a83; } } ?>
