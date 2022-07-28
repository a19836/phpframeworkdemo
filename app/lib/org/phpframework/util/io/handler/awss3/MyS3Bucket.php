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

include_once get_lib("org.phpframework.util.io.handler.awss3.MyS3Handler"); class MyS3Bucket extends MyS3Handler { public function __construct($v614e1f4104, $v253839514f) { parent::__construct($v614e1f4104, $v253839514f); } public function create($v4907c60569, $v8b27c73d0e = "p", $pae397839 = false) { $pf9163b61 = $this->getACL($v8b27c73d0e); return $this->S3->putBucket($v4907c60569, $pf9163b61, $pae397839); } public function delete($v4907c60569) { $v5c1c342594 = true; $v6ee393d9fb = $this->getBucketFiles($v4907c60569); foreach($v6ee393d9fb as $pbfa01ed1 => $v67db1bd535) { if(!$this->S3->deleteObject($v4907c60569, $pbfa01ed1)) $v5c1c342594 = false; } return $v5c1c342594 ? $this->S3->deleteBucket($v4907c60569) : false; } public function getBucketFiles($v4907c60569) { return $this->S3->getBucket($v4907c60569); } public function getLocation($v4907c60569) { return $this->S3->getBucketLocation($v4907c60569); } public function getList($v2d06ae5c1d = true) { return $this->S3->listBuckets($v2d06ae5c1d); } } ?>
