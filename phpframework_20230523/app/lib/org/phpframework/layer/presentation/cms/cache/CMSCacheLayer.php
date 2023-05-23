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

include_once get_lib("org.phpframework.layer.presentation.cms.CMSLayer"); include_once get_lib("org.phpframework.layer.presentation.PresentationLayer"); include_once get_lib("org.phpframework.layer.cache.PresentationCacheLayer"); class CMSCacheLayer { public $CMSLayer; public $settings; protected $PresentationCacheLayer; public function __construct(CMSLayer $v874d5d2d79, $v30857f7eca) { $this->CMSLayer = $v874d5d2d79; $this->settings = $v30857f7eca; } protected function initPresentationCacheLayer($v30857f7eca) { $v9ab35f1f0d = $this->CMSLayer->getEVC()->getPresentationLayer(); $pd3623f40 = new PresentationLayer($v9ab35f1f0d->settings); $this->PresentationCacheLayer = new PresentationCacheLayer($pd3623f40, $v30857f7eca); $pd3623f40->setCacheLayer($this->PresentationCacheLayer); $pd3623f40->setPHPFrameWorkObjName($v9ab35f1f0d->getPHPFrameWorkObjName()); $pc4223ce1 = $v9ab35f1f0d->getBrokers(); foreach ($pc4223ce1 as $v2b2cf4c0eb => $pd922c2f7) $pd3623f40->addBroker($pd922c2f7, $v2b2cf4c0eb); } public function isValid($v20b8676a9f, $v539082ff30, $v5d3813882f = false) { return $this->PresentationCacheLayer->isValid($this->CMSLayer->getEVC()->getPresentationLayer()->getSelectedPresentationId(), $v20b8676a9f, $v539082ff30, $v5d3813882f); } public function get($v20b8676a9f, $v539082ff30, $v5d3813882f = false) { return $this->PresentationCacheLayer->get($this->CMSLayer->getEVC()->getPresentationLayer()->getSelectedPresentationId(), $v20b8676a9f, $v539082ff30, $v5d3813882f); } public function check($v20b8676a9f, $v539082ff30, &$v9ad1385268, $v5d3813882f = false) { return $this->PresentationCacheLayer->check($this->CMSLayer->getEVC()->getPresentationLayer()->getSelectedPresentationId(), $v20b8676a9f, $v539082ff30, $v9ad1385268, $v5d3813882f); } public function deleteSearchedServices($pe4662d7b, $v539082ff30 = array(), $v5d3813882f = false) { return $this->PresentationCacheLayer->deleteSearchedServices($this->CMSLayer->getEVC()->getPresentationLayer()->getSelectedPresentationId(), $pe4662d7b, $v539082ff30, $v5d3813882f); } } ?>
