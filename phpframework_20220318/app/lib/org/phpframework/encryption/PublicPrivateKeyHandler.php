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

 class PublicPrivateKeyHandler { private $v5a67eae740; private $pafb53414; public $error = false; public function __construct($v7aff7fca03 = false) { if ($v7aff7fca03) { $this->v5a67eae740 = 200; $this->pafb53414 = 256; } else { $this->v5a67eae740 = 117; $this->pafb53414 = 172; } } public function encryptString($v70a24a74ac, $pb89c0438, $pfcd66519 = "") { if (file_exists($pb89c0438)) { $v9a84a79e2e = fopen($pb89c0438, "r"); $v99dcd36122 = fread($v9a84a79e2e, 8192); fclose($v9a84a79e2e); return $this->encryptRSA($v70a24a74ac, $v99dcd36122, $pfcd66519); } $this->error = true; } public function decryptString($v70a24a74ac, $v5e59c1bb9a) { if (file_exists($v5e59c1bb9a)) { $v9a84a79e2e = fopen($v5e59c1bb9a, "r"); $pf640cf45 = fread($v9a84a79e2e, 8192); fclose($v9a84a79e2e); return $this->decryptRSA($v70a24a74ac, $pf640cf45); } $this->error = true; } public function encryptRSA($v70a24a74ac, $pdd1513dc, $pfcd66519 = "") { $v0e30ff1cac = ''; $this->error = false; if (!is_resource($pdd1513dc)) $pdd1513dc = openssl_pkey_get_private($pdd1513dc, $pfcd66519); $v70a24a74ac = str_split($v70a24a74ac, $this->v5a67eae740); foreach($v70a24a74ac as $ped13d90f) { $v4ec897b0c7 = ''; $v7dbda1f488 = openssl_private_encrypt($ped13d90f, $v4ec897b0c7, $pdd1513dc, OPENSSL_PKCS1_PADDING); if($v7dbda1f488 === false) { $this->error = true; return false; } $v0e30ff1cac .= $v4ec897b0c7; } return base64_encode($v0e30ff1cac); } public function decryptRSA($v70a24a74ac, $v527dd7860b) { $v84ddd6ebce = ''; $this->error = false; if (!is_resource($v527dd7860b)) $v527dd7860b = openssl_pkey_get_public($v527dd7860b); $v70a24a74ac = str_split(base64_decode($v70a24a74ac), $this->pafb53414); foreach($v70a24a74ac as $ped13d90f) { $v81d57571ad = ''; $v93899e4959 = openssl_public_decrypt($ped13d90f, $v81d57571ad, $v527dd7860b, OPENSSL_PKCS1_PADDING); if($v93899e4959 === false) { $this->error = true; return false; } $v84ddd6ebce .= $v81d57571ad; } return $v84ddd6ebce; } } ?>
