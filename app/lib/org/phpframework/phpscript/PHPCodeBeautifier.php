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
include_once get_lib("lib.vendor.phpcf.phpcf-src.src.init"); class PHPCodeBeautifier { private $v86ffe95514; private $v261c7a42d4; private $v0f9512fda4; private $v5c1c342594; public function __construct() { $this->v86ffe95514 = false; $this->v261c7a42d4 = null; $this->v0f9512fda4 = null; $this->v5c1c342594 = false; } public function wasFormatted() { return $this->v86ffe95514; } public function getIssues() { return $this->v261c7a42d4; } public function getError() { return $this->v0f9512fda4; } public function getStatus() { return $this->v5c1c342594; } public function beautifyCode($v067674f4e4) { $v9b8a410170 = new \Phpcf\Options(); $v9b8a410170->setTabSequence("\t"); $pc2cca5a3 = new \Phpcf\Formatter($v9b8a410170); $v67a0ec3cdc = $pc2cca5a3->format($v067674f4e4); $pf4e3c708 = $v67a0ec3cdc->getContent(); $this->v86ffe95514 = $v67a0ec3cdc->wasFormatted(); $this->v261c7a42d4 = $v67a0ec3cdc->getIssues(); $this->v0f9512fda4 = $v67a0ec3cdc->getError(); $this->v5c1c342594 = $this->v86ffe95514 && empty($this->v261c7a42d4) && empty($this->v0f9512fda4); if ($this->v5c1c342594) { if (substr(trim($v067674f4e4), -2) == "?>" && substr(trim($pf4e3c708), -2) != "?>") $pf4e3c708 = trim($pf4e3c708) . "\n?>"; return $pf4e3c708; } return $v067674f4e4; } } ?>
