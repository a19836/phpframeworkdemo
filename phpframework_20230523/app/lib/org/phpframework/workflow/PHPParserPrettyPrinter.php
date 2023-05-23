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

 class PHPParserPrettyPrinter extends PhpParser\PrettyPrinter\Standard { public function disableNoIndentToken() { $this->noIndentToken = ""; } protected function pStmts(array $v50d32a6fc4, $v825d0e516b = true) { $v9ad1385268 = parent::pStmts($v50d32a6fc4, false); if ($v825d0e516b) $v9ad1385268 = preg_replace('~\n(?!$|' . $this->noIndentToken . ')~', "\n\t", $v9ad1385268); return $v9ad1385268; } public function pParam(PhpParser\Node\Param $v6694236c2c) { return parent::pParam($v6694236c2c); } public function pArg(PhpParser\Node\Arg $v6694236c2c) { return parent::pArg($v6694236c2c); } public function pConst(PhpParser\Node\Const_ $v6694236c2c) { return parent::pConst($v6694236c2c); } public function pName(PhpParser\Node\Name $v6694236c2c) { return parent::pName($v6694236c2c); } public function prettyPrint(array $v793f92423d, $v5a8476b36f = false) { if ($v5a8476b36f) $v793f92423d = $this->getStmtsWithComments($v793f92423d); return parent::prettyPrint($v793f92423d); } protected function getStmtsWithComments(array $v50d32a6fc4) { foreach ($v50d32a6fc4 as $v43dd7d0051 => &$v6694236c2c) { if (is_array($v6694236c2c)) $v6694236c2c = $this->getStmtsWithComments($v6694236c2c); else if ($v6694236c2c instanceof PhpParser\Node) $v6694236c2c = $this->getStmtWithComments($v6694236c2c); } return $v50d32a6fc4; } protected function getStmtWithComments(PhpParser\Node $v6694236c2c) { foreach ($v6694236c2c->getSubNodeNames() as $v5e813b295b) { $v1fb0cef105 =& $v6694236c2c->$v5e813b295b; if (is_array($v1fb0cef105)) $v1fb0cef105 = $this->getStmtsWithComments($v1fb0cef105); else if ($v1fb0cef105 instanceof PhpParser\Node) $v1fb0cef105 = $this->getStmtWithComments($v1fb0cef105); } $pcc2fe66c = $v6694236c2c->getAttribute("my_comments"); if ($pcc2fe66c) { $v6694236c2c->setAttribute("comments", $pcc2fe66c); $v6694236c2c->setAttribute("my_comments", null); } return $v6694236c2c; } } ?>
