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

include_once get_lib("org.phpframework.broker.client.local.LocalDataAccessBrokerClient"); include_once get_lib("org.phpframework.broker.client.IIbatisDataAccessBrokerClient"); class LocalIbatisDataAccessBrokerClient extends LocalDataAccessBrokerClient implements IIbatisDataAccessBrokerClient { public function callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false) { return $this->getBrokerServer()->callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85, $v5d3813882f); } } ?>
