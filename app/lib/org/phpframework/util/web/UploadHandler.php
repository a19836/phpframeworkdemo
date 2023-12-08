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
include_once get_lib("org.phpframework.util.web.ImageHandler"); class UploadHandler { public static function upload($v6ee393d9fb, $v41a4ab45e3, $v52a39aea36 = null) { $v5c1c342594 = false; if (is_array($v6ee393d9fb) && array_key_exists("name", $v6ee393d9fb) && array_key_exists("tmp_name", $v6ee393d9fb) && array_key_exists("type", $v6ee393d9fb) && array_key_exists("size", $v6ee393d9fb)) { if (!is_array($v6ee393d9fb["name"])) { $v6ee393d9fb["name"] = array($v6ee393d9fb["name"]); $v6ee393d9fb["tmp_name"] = array($v6ee393d9fb["tmp_name"]); $v6ee393d9fb["type"] = array($v6ee393d9fb["type"]); $v6ee393d9fb["size"] = array($v6ee393d9fb["size"]); $v6ee393d9fb["error"] = array(isset($v6ee393d9fb["error"]) ? $v6ee393d9fb["error"] : null); } $v5c1c342594 = true; if (!is_dir($v41a4ab45e3)) $v5c1c342594 = mkdir($v41a4ab45e3, 0755, true); if ($v5c1c342594) { if ($v52a39aea36) { if (!is_array($v52a39aea36)) $v52a39aea36 = array($v52a39aea36); $pc96665cf = new ImageHandler(); } foreach ($v6ee393d9fb["name"] as $pd69fb7d0 => $v5e813b295b) if ($v5e813b295b && $v6ee393d9fb["tmp_name"][$pd69fb7d0]) { $peaf74b17 = $v6ee393d9fb["tmp_name"][$pd69fb7d0]; $v3fb9f41470 = $v6ee393d9fb["type"][$pd69fb7d0]; $v648f89f08c = $v6ee393d9fb["size"][$pd69fb7d0]; $v0f9512fda4 = $v6ee393d9fb["error"][$pd69fb7d0]; if ($v0f9512fda4 == UPLOAD_ERR_OK && is_uploaded_file($peaf74b17)) { $v28f0a097df = true; if ($v52a39aea36) foreach ($v52a39aea36 as $ped7cce7c) switch ($ped7cce7c) { case "image": $v28f0a097df = $pc96665cf->isImageValid($peaf74b17); break; default: $v71dafe3739 = MimeTypeHandler::getFileMimeType($peaf74b17); $v28f0a097df = MimeTypeHandler::checkMimeType($v71dafe3739, $ped7cce7c); } if ($v28f0a097df) { $v5e813b295b = basename($v5e813b295b); if (!move_uploaded_file($peaf74b17, "$v41a4ab45e3/$v5e813b295b")) $v5c1c342594 = false; } else $v5c1c342594 = false; } } } } return $v5c1c342594; } } ?>
