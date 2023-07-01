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

include_once get_lib("org.phpframework.util.web.SendEmail"); include_once get_lib("org.phpframework.util.web.SmtpEmail"); class SendEmailHandler { public static function sendEmail($v30857f7eca) { $v565a00eace = $v30857f7eca["mail_boundary"]; $v8d1f503e9f = $v30857f7eca["encoding"] ? $v30857f7eca["encoding"] : 'UTF8'; $v769a9c3807 = $v30857f7eca["from_email"]; $v332bb23aaf = $v30857f7eca["reply_email"]; $pe6c1d5bd = $v30857f7eca["to_email"]; $pe4c7aeda = $v30857f7eca["subject"]; $pffa799aa = $v30857f7eca["message"]; $pc0dcc212 = $v30857f7eca["extra_headers"]; if ($v332bb23aaf && (!$pc0dcc212 || stripos($pc0dcc212, "Reply-To") === false)) $pc0dcc212 = trim($pc0dcc212) . "\r\nReply-To: $v332bb23aaf"; $v9f496527ac = new SendEmail($v769a9c3807, $v565a00eace, $v8d1f503e9f); $v5c1c342594 = $v9f496527ac->send($pe6c1d5bd, $pe4c7aeda, $pffa799aa, $pc0dcc212); return $v5c1c342594; } public static function sendSMTPEmail($v30857f7eca) { $v641a8eff1b = $v30857f7eca["smtp_host"]; $pb38119eb = $v30857f7eca["smtp_port"]; $v2063d7a628 = $v30857f7eca["smtp_user"]; $pa913859c = $v30857f7eca["smtp_pass"]; $pe1b79626 = $v30857f7eca["smtp_secure"]; $v062ac71672 = $v30857f7eca["smtp_encoding"] ? $v30857f7eca["smtp_encoding"] : 'utf-8'; $v769a9c3807 = $v30857f7eca["from_email"]; $v332bb23aaf = $v30857f7eca["reply_email"]; $pe6c1d5bd = $v30857f7eca["to_email"]; $pe4c7aeda = $v30857f7eca["subject"]; $pffa799aa = $v30857f7eca["message"]; $v8b809255dd = $v30857f7eca["debug"] ? $v30857f7eca["debug"] : 0; $v04a474b3f6 = new SmtpEmail($v641a8eff1b, $pb38119eb, $v2063d7a628, $pa913859c, $pe1b79626, $v062ac71672); $v5c1c342594 = $v04a474b3f6->send($v769a9c3807, null, $v332bb23aaf, null, $pe6c1d5bd, null, $pe4c7aeda, $pffa799aa, $v8b809255dd); return $v5c1c342594; } } ?>
