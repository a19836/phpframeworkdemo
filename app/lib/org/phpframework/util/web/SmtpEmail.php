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

include_once get_lib("lib.vendor.phpmailer.PHPMailerAutoload"); class SmtpEmail { private $v641a8eff1b; private $pb38119eb; private $v2063d7a628; private $pa913859c; private $pe1b79626; private $v062ac71672; private $v53e6fbb1f7; public function __construct($v641a8eff1b, $pb38119eb, $v2063d7a628, $pa913859c, $pe1b79626, $v062ac71672 = 'utf-8') { $this->v641a8eff1b = $v641a8eff1b; $this->pb38119eb = $pb38119eb; $this->v2063d7a628 = $v2063d7a628; $this->pa913859c = $pa913859c; $this->pe1b79626 = $pe1b79626; $this->v062ac71672 = $v062ac71672; } public function send($v769a9c3807, $v4446fb8633, $pa04f7e11, $v94a62ba63a, $pe6c1d5bd, $v568e7467ac, $pe4c7aeda, $pffa799aa, $v8b809255dd = 0) { $this->mdc9f9cb49924($v769a9c3807, $v4446fb8633); $this->mdc9f9cb49924($pa04f7e11, $v94a62ba63a); $this->mdc9f9cb49924($pe6c1d5bd, $v568e7467ac); $this->v53e6fbb1f7 = new PHPMailer; $this->v53e6fbb1f7->isSMTP(); $this->v53e6fbb1f7->CharSet = $this->v062ac71672; $this->v53e6fbb1f7->SMTPDebug = $v8b809255dd; $this->v53e6fbb1f7->Host = $this->v641a8eff1b; $this->v53e6fbb1f7->Port = $this->pb38119eb; $this->v53e6fbb1f7->SMTPSecure = $this->pe1b79626 ? $this->pe1b79626 : null; $this->v53e6fbb1f7->SMTPAuth = $this->v2063d7a628 ? true : false; $this->v53e6fbb1f7->Username = $this->v2063d7a628; $this->v53e6fbb1f7->Password = $this->pa913859c; $this->v53e6fbb1f7->setFrom($v769a9c3807, $v4446fb8633); $this->v53e6fbb1f7->addReplyTo($pa04f7e11, $v94a62ba63a); $this->v53e6fbb1f7->addAddress($pe6c1d5bd, $v568e7467ac); $this->v53e6fbb1f7->Subject = $pe4c7aeda; $this->v53e6fbb1f7->msgHTML( nl2br($pffa799aa) ); $this->v53e6fbb1f7->AltBody = strip_tags($pffa799aa); return $this->v53e6fbb1f7->send() ? true : false; } public function getPHPMailer() { return $this->v53e6fbb1f7; } public function getErrorInfo() { return $this->v53e6fbb1f7->ErrorInfo; } private function mdc9f9cb49924(&$v9239e028dd, &$v5e813b295b) { $pea98bd8b = strpos($v9239e028dd, "<"); if ($pea98bd8b !== false) { $v1fdc6f29da = strpos($v9239e028dd, ">", $pea98bd8b); $v1fdc6f29da = $v1fdc6f29da === false ? strlen($v9239e028dd) : $v1fdc6f29da; $v5e813b295b = trim($v5e813b295b); $v5e813b295b = empty($v5e813b295b) ? trim(substr($v9239e028dd, 0, $pea98bd8b)) : $v5e813b295b; $v9239e028dd = trim(substr($v9239e028dd, $pea98bd8b + 1, $v1fdc6f29da - $pea98bd8b - 1)); } } } ?>
