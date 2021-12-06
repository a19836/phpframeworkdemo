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

namespace DocBlockParser\Annotation; class ParamsAnnotation extends Annotation { public function __construct() { $this->is_input = true; } public function parseArgs($v6da2e4df28, $v86066462c3) { $v020036c951 = "/**\n" . implode("\n", $v86066462c3) . "\n*/"; $pee257eb2 = new \DocBlockParser(); $pee257eb2->ofComment($v020036c951); $v52b4591032 = $pee257eb2->getObjects(); $v6da2e4df28->setIncludedTag("param"); $this->args = !empty($v52b4591032["param"]) ? $v52b4591032["param"] : null; } public function checkMethodAnnotations(&$v5730eacfdc, $pcc2d93a5) { $v5c1c342594 = true; if ($this->args) { $pc37695cb = count($this->args); for ($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v972f1a5c2b = $this->args[$v43dd7d0051]; if (!$v972f1a5c2b->checkMethodAnnotations($v5730eacfdc, $v43dd7d0051)) $v5c1c342594 = false; } } return $v5c1c342594; } } ?>
