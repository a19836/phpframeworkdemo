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

include_once get_lib("org.phpframework.layer.presentation.evc.EVC"); include_once get_lib("org.phpframework.layer.presentation.cms.CMSModuleLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.CMSBlockLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.CMSTemplateLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.CMSJoinPointLayer"); class CMSLayer { private $v08d9602741; private $v34113829f6; private $v0dec668ada; private $pd0cb8ced; private $pae8ae0d2; private $pbc7e2f66; public function __construct(EVC $v08d9602741) { $this->v08d9602741 = $v08d9602741; $this->v34113829f6 = new CMSModuleLayer($this); $this->v0dec668ada = new CMSBlockLayer($this); $this->pd0cb8ced = new CMSTemplateLayer($this); $this->pae8ae0d2 = new CMSJoinPointLayer($this); } public function setCacheLayer($pbc7e2f66) {$this->pbc7e2f66 = $pbc7e2f66;} public function getCacheLayer() {return $this->pbc7e2f66;} public function isCacheActive() {return $this->pbc7e2f66 ? true : false;} public function setEVC($v08d9602741) { $this->v08d9602741 = $v08d9602741; } public function getEVC() { return $this->v08d9602741; } public function setCMSModuleLayer($v34113829f6) { $this->v34113829f6 = $v34113829f6; } public function getCMSModuleLayer() { return $this->v34113829f6; } public function setCMSBlockLayer($v0dec668ada) { $this->v0dec668ada = $v0dec668ada; } public function getCMSBlockLayer() { return $this->v0dec668ada; } public function setCMSTemplateLayer($pd0cb8ced) { $this->pd0cb8ced = $pd0cb8ced; } public function getCMSTemplateLayer() { return $this->pd0cb8ced; } public function setCMSJoinPointLayer($pae8ae0d2) { $this->pae8ae0d2 = $pae8ae0d2; } public function getCMSJoinPointLayer() { return $this->pae8ae0d2; } } ?>
