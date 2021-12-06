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

include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSModuleCacheLayer"); include_once get_lib("org.phpframework.layer.presentation.cms.cache.CMSBlockCacheLayer"); class MultipleCMSCacheLayer { private $pdd3510b1; private $v9857cd3214; public function __construct($v874d5d2d79, $v30857f7eca) { $this->pdd3510b1 = new CMSModuleCacheLayer($v874d5d2d79, $v30857f7eca); $this->v9857cd3214 = new CMSBlockCacheLayer($v874d5d2d79, $v30857f7eca); } public function setCMSModuleCacheLayer($pdd3510b1) {$this->pdd3510b1 = $pdd3510b1;} public function getCMSModuleCacheLayer() {return $this->pdd3510b1;} public function setCMSBlockCacheLayer($v9857cd3214) {$this->v9857cd3214 = $v9857cd3214;} public function getCMSBlockCacheLayer() {return $this->v9857cd3214;} } ?>
