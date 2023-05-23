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

interface IIbatisDataAccessBrokerServer { public function callQuerySQL($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false); public function callQuery($pc8b88eb4, $v3fb9f41470, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callSelectSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callSelect($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callInsertSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callInsert($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callUpdateSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callUpdate($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callDeleteSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callDelete($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); public function callProcedureSQL($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false); public function callProcedure($pc8b88eb4, $v95eeadc9e9, $v9367d5be85 = false, $v5d3813882f = false); } ?>
