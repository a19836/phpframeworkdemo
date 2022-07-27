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

class MyXMLArrayItem { private $v539082ff30; public function __construct($v539082ff30) { $this->v539082ff30 = is_array($v539082ff30) ? $v539082ff30 : array(); } public function getChildNodes($v7bb23e0d9e = "", $paf1bc6f6 = false, $pec2e10b1 = true) { $pc4222023 = $this->getChilds(); $pa694ba99 = new MyXMLArray($pc4222023); return $pa694ba99->getNodes($v7bb23e0d9e, $paf1bc6f6, $pec2e10b1); } public function getData() { return $this->v539082ff30; } public function getName() { return $this->v539082ff30["name"]; } public function getValue() { return $this->v539082ff30["value"]; } public function getChilds() { return is_array($this->v539082ff30["childs"]) ? $this->v539082ff30["childs"] : array(); } public function getChildsCount() { $pc4222023 = $this->getChilds(); $v9994512d98 = array_keys($pc4222023); return count($v9994512d98); } public function attributeExists($v5e813b295b) { return isset($this->v539082ff30["@"][$v5e813b295b]); } public function getAttribute($v5e813b295b) { return isset($this->v539082ff30["@"][$v5e813b295b]) ? $this->v539082ff30["@"][$v5e813b295b] : false; } public function getAttributes() { return is_array($this->v539082ff30["@"]) ? $this->v539082ff30["@"] : array(); } public function getAttributesName() { $ped0a6251 = $this->getAttributes(); return array_keys($ped0a6251); } public function getAttributesCount() { $ped0a6251 = $this->getAttributesName(); return count($ped0a6251); } public function checkAttributes($paf1bc6f6 = false) { if($paf1bc6f6) { $pfb35386f = is_array($paf1bc6f6) && count($paf1bc6f6) ? array_keys($paf1bc6f6) : false; if($pfb35386f) { if($this->getAttributesCount()) { $pc37695cb = count($pfb35386f); for($v43dd7d0051 = 0; $v43dd7d0051 < $pc37695cb; $v43dd7d0051++) { $v5e45ec9bb9 = $pfb35386f[$v43dd7d0051]; if(!$this->attributeExists($v5e45ec9bb9) || $this->getAttribute($v5e45ec9bb9) != $paf1bc6f6[$v5e45ec9bb9]) { return false; } } return true; } return false; } } return true; } } ?>
