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

 class S3 { const ACL_PRIVATE = 'private'; const ACL_PUBLIC_READ = 'public-read'; const ACL_PUBLIC_READ_WRITE = 'public-read-write'; public static $useSSL = true; private static $v15c4c03314; private static $v968aa21540; public function __construct($padecfee3 = null, $pd89821dc = null, $v6de6eaafea = true) { if ($padecfee3 !== null && $pd89821dc !== null) self::setAuth($padecfee3, $pd89821dc); self::$useSSL = $v6de6eaafea; } public static function setAuth($padecfee3, $pd89821dc) { self::$v15c4c03314 = $padecfee3; self::$v968aa21540 = $pd89821dc; } public static function listBuckets($v2d06ae5c1d = false) { $pb8a2e3ca = new S3Request('GET', '', ''); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::listBuckets(): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } $pee4c7870 = array(); if (!isset($pb8a2e3ca->body->Buckets)) return $pee4c7870; if ($v2d06ae5c1d) { if (isset($pb8a2e3ca->body->Owner, $pb8a2e3ca->body->Owner->ID, $pb8a2e3ca->body->Owner->DisplayName)) $pee4c7870['owner'] = array( 'id' => (string)$pb8a2e3ca->body->Owner->ID, 'name' => (string)$pb8a2e3ca->body->Owner->ID ); $pee4c7870['buckets'] = array(); foreach ($pb8a2e3ca->body->Buckets->Bucket as $v7aeaf992f5) $pee4c7870['buckets'][] = array( 'name' => (string)$v7aeaf992f5->Name, 'time' => strtotime((string)$v7aeaf992f5->CreationDate) ); } else foreach ($pb8a2e3ca->body->Buckets->Bucket as $v7aeaf992f5) $pee4c7870[] = (string)$v7aeaf992f5->Name; return $pee4c7870; } public static function getBucket($v4907c60569, $pdcf670f6 = null, $v6124659e37 = null, $pb3b953d2 = null, $v19cbfb3aee = null, $v9fa8f76b7b = false) { $pb8a2e3ca = new S3Request('GET', $v4907c60569, ''); if ($pdcf670f6 !== null && $pdcf670f6 !== '') $pb8a2e3ca->setParameter('prefix', $pdcf670f6); if ($v6124659e37 !== null && $v6124659e37 !== '') $pb8a2e3ca->setParameter('marker', $v6124659e37); if ($pb3b953d2 !== null && $pb3b953d2 !== '') $pb8a2e3ca->setParameter('max-keys', $pb3b953d2); if ($v19cbfb3aee !== null && $v19cbfb3aee !== '') $pb8a2e3ca->setParameter('delimiter', $v19cbfb3aee); $v7bd5d88a74 = $pb8a2e3ca->getResponse(); if ($v7bd5d88a74->error === false && $v7bd5d88a74->code !== 200) $v7bd5d88a74->error = array('code' => $v7bd5d88a74->code, 'message' => 'Unexpected HTTP status'); if ($v7bd5d88a74->error !== false) { trigger_error(sprintf("S3::getBucket(): [%s] %s", $v7bd5d88a74->error['code'], $v7bd5d88a74->error['message']), E_USER_WARNING); return false; } $pee4c7870 = array(); $pefd21a40 = null; if (isset($v7bd5d88a74->body, $v7bd5d88a74->body->Contents)) foreach ($v7bd5d88a74->body->Contents as $v9a8b7dc209) { $pee4c7870[(string)$v9a8b7dc209->Key] = array( 'name' => (string)$v9a8b7dc209->Key, 'time' => strtotime((string)$v9a8b7dc209->LastModified), 'size' => (int)$v9a8b7dc209->Size, 'hash' => substr((string)$v9a8b7dc209->ETag, 1, -1) ); $pefd21a40 = (string)$v9a8b7dc209->Key; } if ($v9fa8f76b7b && isset($v7bd5d88a74->body, $v7bd5d88a74->body->CommonPrefixes)) foreach ($v7bd5d88a74->body->CommonPrefixes as $v9a8b7dc209) $pee4c7870[(string)$v9a8b7dc209->Prefix] = array('prefix' => (string)$v9a8b7dc209->Prefix); if (isset($v7bd5d88a74->body, $v7bd5d88a74->body->IsTruncated) && (string)$v7bd5d88a74->body->IsTruncated == 'false') return $pee4c7870; if (isset($v7bd5d88a74->body, $v7bd5d88a74->body->NextMarker)) $pefd21a40 = (string)$v7bd5d88a74->body->NextMarker; if ($pb3b953d2 == null && $pefd21a40 !== null && (string)$v7bd5d88a74->body->IsTruncated == 'true') do { $pb8a2e3ca = new S3Request('GET', $v4907c60569, ''); if ($pdcf670f6 !== null && $pdcf670f6 !== '') $pb8a2e3ca->setParameter('prefix', $pdcf670f6); $pb8a2e3ca->setParameter('marker', $pefd21a40); if ($v19cbfb3aee !== null && $v19cbfb3aee !== '') $pb8a2e3ca->setParameter('delimiter', $v19cbfb3aee); if (($v7bd5d88a74 = $pb8a2e3ca->getResponse(true)) == false || $v7bd5d88a74->code !== 200) break; if (isset($v7bd5d88a74->body, $v7bd5d88a74->body->Contents)) foreach ($v7bd5d88a74->body->Contents as $v9a8b7dc209) { $pee4c7870[(string)$v9a8b7dc209->Key] = array( 'name' => (string)$v9a8b7dc209->Key, 'time' => strtotime((string)$v9a8b7dc209->LastModified), 'size' => (int)$v9a8b7dc209->Size, 'hash' => substr((string)$v9a8b7dc209->ETag, 1, -1) ); $pefd21a40 = (string)$v9a8b7dc209->Key; } if ($v9fa8f76b7b && isset($v7bd5d88a74->body, $v7bd5d88a74->body->CommonPrefixes)) foreach ($v7bd5d88a74->body->CommonPrefixes as $v9a8b7dc209) $pee4c7870[(string)$v9a8b7dc209->Prefix] = array('prefix' => (string)$v9a8b7dc209->Prefix); if (isset($v7bd5d88a74->body, $v7bd5d88a74->body->NextMarker)) $pefd21a40 = (string)$v7bd5d88a74->body->NextMarker; } while ($v7bd5d88a74 !== false && (string)$v7bd5d88a74->body->IsTruncated == 'true'); return $pee4c7870; } public static function putBucket($v4907c60569, $pf9163b61 = self::ACL_PRIVATE, $pae397839 = false) { $pb8a2e3ca = new S3Request('PUT', $v4907c60569, ''); $pb8a2e3ca->setAmzHeader('x-amz-acl', $pf9163b61); if ($pae397839 !== false) { $v30163feac8 = new DOMDocument; $v4fdbff3d85 = $v30163feac8->createElement('CreateBucketConfiguration'); $v6a33288016 = $v30163feac8->createElement('LocationConstraint', strtoupper($pae397839)); $v4fdbff3d85->appendChild($v6a33288016); $v30163feac8->appendChild($v4fdbff3d85); $pb8a2e3ca->data = $v30163feac8->saveXML(); $pb8a2e3ca->size = strlen($pb8a2e3ca->data); $pb8a2e3ca->setHeader('Content-Type', 'application/xml'); } $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::putBucket({$v4907c60569}, {$pf9163b61}, {$pae397839}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return true; } public static function deleteBucket($v4907c60569) { $pb8a2e3ca = new S3Request('DELETE', $v4907c60569); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 204) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::deleteBucket({$v4907c60569}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return true; } public static function inputFile($v7dffdb5a5b, $v8f9c772975 = true) { if (!file_exists($v7dffdb5a5b) || !is_file($v7dffdb5a5b) || !is_readable($v7dffdb5a5b)) { trigger_error('S3::inputFile(): Unable to open input file: '.$v7dffdb5a5b, E_USER_WARNING); return false; } return array('file' => $v7dffdb5a5b, 'size' => filesize($v7dffdb5a5b), 'md5sum' => $v8f9c772975 !== false ? (is_string($v8f9c772975) ? $v8f9c772975 : base64_encode(md5_file($v7dffdb5a5b, true))) : ''); } public static function inputResource(&$v3c238d643b, $pcbec634c, $v8f9c772975 = '') { if (!is_resource($v3c238d643b) || $pcbec634c <= 0) { trigger_error('S3::inputResource(): Invalid resource or buffer size', E_USER_WARNING); return false; } $pa293a3d3 = array('size' => $pcbec634c, 'md5sum' => $v8f9c772975); $pa293a3d3['fp'] =& $v3c238d643b; return $pa293a3d3; } public static function putObject($pa293a3d3, $v4907c60569, $pe6469026, $pf9163b61 = self::ACL_PRIVATE, $v49d6fe165a = array(), $v91e9aef767 = array()) { if ($pa293a3d3 == false) return false; $pb8a2e3ca = new S3Request('PUT', $v4907c60569, $pe6469026); if (is_string($pa293a3d3)) $pa293a3d3 = array( 'data' => $pa293a3d3, 'size' => strlen($pa293a3d3), 'md5sum' => base64_encode(md5($pa293a3d3, true)) ); if (isset($pa293a3d3['fp'])) $pb8a2e3ca->fp =& $pa293a3d3['fp']; elseif (isset($pa293a3d3['file'])) $pb8a2e3ca->fp = @fopen($pa293a3d3['file'], 'rb'); elseif (isset($pa293a3d3['data'])) $pb8a2e3ca->data = $pa293a3d3['data']; if (isset($pa293a3d3['size']) && $pa293a3d3['size'] > -1) $pb8a2e3ca->size = $pa293a3d3['size']; else { if (isset($pa293a3d3['file'])) $pb8a2e3ca->size = filesize($pa293a3d3['file']); elseif (isset($pa293a3d3['data'])) $pb8a2e3ca->size = strlen($pa293a3d3['data']); } if (is_array($v91e9aef767)) foreach ($v91e9aef767 as $pacf2a341 => $v956913c90f) $pb8a2e3ca->setHeader($pacf2a341, $v956913c90f); elseif (is_string($v91e9aef767)) $pa293a3d3['type'] = $v91e9aef767; if (!isset($pa293a3d3['type'])) { if (isset($v91e9aef767['Content-Type'])) $pa293a3d3['type'] =& $v91e9aef767['Content-Type']; elseif (isset($pa293a3d3['file'])) $pa293a3d3['type'] = self::__getMimeType($pa293a3d3['file']); else $pa293a3d3['type'] = 'application/octet-stream'; } if ($pb8a2e3ca->size > 0 && ($pb8a2e3ca->fp !== false || $pb8a2e3ca->data !== false)) { $pb8a2e3ca->setHeader('Content-Type', $pa293a3d3['type']); if (isset($pa293a3d3['md5sum'])) $pb8a2e3ca->setHeader('Content-MD5', $pa293a3d3['md5sum']); $pb8a2e3ca->setAmzHeader('x-amz-acl', $pf9163b61); foreach ($v49d6fe165a as $pacf2a341 => $v956913c90f) $pb8a2e3ca->setAmzHeader('x-amz-meta-'.$pacf2a341, $v956913c90f); $pb8a2e3ca->getResponse(); } else $pb8a2e3ca->response->error = array('code' => 0, 'message' => 'Missing input parameters'); if ($pb8a2e3ca->response->error === false && $pb8a2e3ca->response->code !== 200) $pb8a2e3ca->response->error = array('code' => $pb8a2e3ca->response->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->response->error !== false) { trigger_error(sprintf("S3::putObject(): [%s] %s", $pb8a2e3ca->response->error['code'], $pb8a2e3ca->response->error['message']), E_USER_WARNING); return false; } return true; } public static function putObjectFile($v7dffdb5a5b, $v4907c60569, $pe6469026, $pf9163b61 = self::ACL_PRIVATE, $v49d6fe165a = array(), $pc75c1b12 = null) { return self::putObject(self::inputFile($v7dffdb5a5b), $v4907c60569, $pe6469026, $pf9163b61, $v49d6fe165a, $pc75c1b12); } public static function putObjectString($v70a24a74ac, $v4907c60569, $pe6469026, $pf9163b61 = self::ACL_PRIVATE, $v49d6fe165a = array(), $pc75c1b12 = 'text/plain') { return self::putObject($v70a24a74ac, $v4907c60569, $pe6469026, $pf9163b61, $v49d6fe165a, $pc75c1b12); } public static function getObject($v4907c60569, $pe6469026, $v0c0874fcd2 = false) { $pb8a2e3ca = new S3Request('GET', $v4907c60569, $pe6469026); if ($v0c0874fcd2 !== false) { if (is_resource($v0c0874fcd2)) $pb8a2e3ca->fp =& $v0c0874fcd2; else if (($pb8a2e3ca->fp = @fopen($v0c0874fcd2, 'wb')) !== false) $pb8a2e3ca->file = realpath($v0c0874fcd2); else $pb8a2e3ca->response->error = array('code' => 0, 'message' => 'Unable to open save file for writing: '.$v0c0874fcd2); } if ($pb8a2e3ca->response->error === false) $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->response->error === false && $pb8a2e3ca->response->code !== 200) $pb8a2e3ca->response->error = array('code' => $pb8a2e3ca->response->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->response->error !== false) { trigger_error(sprintf("S3::getObject({$v4907c60569}, {$pe6469026}): [%s] %s", $pb8a2e3ca->response->error['code'], $pb8a2e3ca->response->error['message']), E_USER_WARNING); return false; } return $pb8a2e3ca->response; } public static function getObjectInfo($v4907c60569, $pe6469026, $v956f2b6d0f = true) { $pb8a2e3ca = new S3Request('HEAD', $v4907c60569, $pe6469026); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && ($pb8a2e3ca->code !== 200 && $pb8a2e3ca->code !== 404)) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::getObjectInfo({$v4907c60569}, {$pe6469026}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return $pb8a2e3ca->code == 200 ? $v956f2b6d0f ? $pb8a2e3ca->headers : true : false; } public static function copyObject($pb6ae9e67, $v27d8a0f610, $v4907c60569, $pe6469026, $pf9163b61 = self::ACL_PRIVATE) { $pb8a2e3ca = new S3Request('PUT', $v4907c60569, $pe6469026); $pb8a2e3ca->setHeader('Content-Length', 0); $pb8a2e3ca->setAmzHeader('x-amz-acl', $pf9163b61); $pb8a2e3ca->setAmzHeader('x-amz-copy-source', sprintf('/%s/%s', $pb6ae9e67, $v27d8a0f610)); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::copyObject({$pb6ae9e67}, {$v27d8a0f610}, {$v4907c60569}, {$pe6469026}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return isset($pb8a2e3ca->body->LastModified, $pb8a2e3ca->body->ETag) ? array( 'time' => strtotime((string)$pb8a2e3ca->body->LastModified), 'hash' => substr((string)$pb8a2e3ca->body->ETag, 1, -1) ) : false; } public static function setBucketLogging($v4907c60569, $v73600b2d70, $v0c9b431b23 = null) { if ($v73600b2d70 !== null && ($v2069e5bd12 = self::getAccessControlPolicy($v73600b2d70, '')) !== false) { $v6be1273f95 = false; $pbf8fff77 = false; foreach ($v2069e5bd12['acl'] as $pf9163b61) if ($pf9163b61['type'] == 'Group' && $pf9163b61['uri'] == 'http://acs.amazonaws.com/groups/s3/LogDelivery') { if ($pf9163b61['permission'] == 'WRITE') $v6be1273f95 = true; elseif ($pf9163b61['permission'] == 'READ_ACP') $pbf8fff77 = true; } if (!$v6be1273f95) $v2069e5bd12['acl'][] = array( 'type' => 'Group', 'uri' => 'http://acs.amazonaws.com/groups/s3/LogDelivery', 'permission' => 'WRITE' ); if (!$pbf8fff77) $v2069e5bd12['acl'][] = array( 'type' => 'Group', 'uri' => 'http://acs.amazonaws.com/groups/s3/LogDelivery', 'permission' => 'READ_ACP' ); if (!$pbf8fff77 || !$v6be1273f95) self::setAccessControlPolicy($v73600b2d70, '', $v2069e5bd12); } $v30163feac8 = new DOMDocument; $v3c8464d89e = $v30163feac8->createElement('BucketLoggingStatus'); $v3c8464d89e->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/'); if ($v73600b2d70 !== null) { if ($v0c9b431b23 == null) $v0c9b431b23 = $v4907c60569 . '-'; $pcb7b9c1e = $v30163feac8->createElement('LoggingEnabled'); $pcb7b9c1e->appendChild($v30163feac8->createElement('TargetBucket', $v73600b2d70)); $pcb7b9c1e->appendChild($v30163feac8->createElement('TargetPrefix', $v0c9b431b23)); $v3c8464d89e->appendChild($pcb7b9c1e); } $v30163feac8->appendChild($v3c8464d89e); $pb8a2e3ca = new S3Request('PUT', $v4907c60569, ''); $pb8a2e3ca->setParameter('logging', null); $pb8a2e3ca->data = $v30163feac8->saveXML(); $pb8a2e3ca->size = strlen($pb8a2e3ca->data); $pb8a2e3ca->setHeader('Content-Type', 'application/xml'); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::setBucketLogging({$v4907c60569}, {$pe6469026}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return true; } public static function getBucketLogging($v4907c60569) { $pb8a2e3ca = new S3Request('GET', $v4907c60569, ''); $pb8a2e3ca->setParameter('logging', null); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::getBucketLogging({$v4907c60569}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } if (!isset($pb8a2e3ca->body->LoggingEnabled)) return false; return array( 'targetBucket' => (string)$pb8a2e3ca->body->LoggingEnabled->TargetBucket, 'targetPrefix' => (string)$pb8a2e3ca->body->LoggingEnabled->TargetPrefix, ); } public static function disableBucketLogging($v4907c60569) { return self::setBucketLogging($v4907c60569, null); } public static function getBucketLocation($v4907c60569) { $pb8a2e3ca = new S3Request('GET', $v4907c60569, ''); $pb8a2e3ca->setParameter('location', null); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::getBucketLocation({$v4907c60569}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return (isset($pb8a2e3ca->body[0]) && (string)$pb8a2e3ca->body[0] !== '') ? (string)$pb8a2e3ca->body[0] : 'US'; } public static function setAccessControlPolicy($v4907c60569, $pe6469026 = '', $v2069e5bd12 = array()) { $v30163feac8 = new DOMDocument; $v30163feac8->formatOutput = true; $v9bb9347702 = $v30163feac8->createElement('AccessControlPolicy'); $v23e3ed73b2 = $v30163feac8->createElement('AccessControlList'); $v1bb7dfae3c = $v30163feac8->createElement('Owner'); $v1bb7dfae3c->appendChild($v30163feac8->createElement('ID', $v2069e5bd12['owner']['id'])); $v1bb7dfae3c->appendChild($v30163feac8->createElement('DisplayName', $v2069e5bd12['owner']['name'])); $v9bb9347702->appendChild($v1bb7dfae3c); foreach ($v2069e5bd12['acl'] as $pe23e9199) { $pb1206d82 = $v30163feac8->createElement('Grant'); $v88d026d8ef = $v30163feac8->createElement('Grantee'); $v88d026d8ef->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance'); if (isset($pe23e9199['id'])) { $v88d026d8ef->setAttribute('xsi:type', 'CanonicalUser'); $v88d026d8ef->appendChild($v30163feac8->createElement('ID', $pe23e9199['id'])); } elseif (isset($pe23e9199['email'])) { $v88d026d8ef->setAttribute('xsi:type', 'AmazonCustomerByEmail'); $v88d026d8ef->appendChild($v30163feac8->createElement('EmailAddress', $pe23e9199['email'])); } elseif ($pe23e9199['type'] == 'Group') { $v88d026d8ef->setAttribute('xsi:type', 'Group'); $v88d026d8ef->appendChild($v30163feac8->createElement('URI', $pe23e9199['uri'])); } $pb1206d82->appendChild($v88d026d8ef); $pb1206d82->appendChild($v30163feac8->createElement('Permission', $pe23e9199['permission'])); $v23e3ed73b2->appendChild($pb1206d82); } $v9bb9347702->appendChild($v23e3ed73b2); $v30163feac8->appendChild($v9bb9347702); $pb8a2e3ca = new S3Request('PUT', $v4907c60569, $pe6469026); $pb8a2e3ca->setParameter('acl', null); $pb8a2e3ca->data = $v30163feac8->saveXML(); $pb8a2e3ca->size = strlen($pb8a2e3ca->data); $pb8a2e3ca->setHeader('Content-Type', 'application/xml'); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::setAccessControlPolicy({$v4907c60569}, {$pe6469026}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return true; } public static function getAccessControlPolicy($v4907c60569, $pe6469026 = '') { $pb8a2e3ca = new S3Request('GET', $v4907c60569, $pe6469026); $pb8a2e3ca->setParameter('acl', null); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::getAccessControlPolicy({$v4907c60569}, {$pe6469026}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } $v2069e5bd12 = array(); if (isset($pb8a2e3ca->body->Owner, $pb8a2e3ca->body->Owner->ID, $pb8a2e3ca->body->Owner->DisplayName)) { $v2069e5bd12['owner'] = array( 'id' => (string)$pb8a2e3ca->body->Owner->ID, 'name' => (string)$pb8a2e3ca->body->Owner->DisplayName ); } if (isset($pb8a2e3ca->body->AccessControlList)) { $v2069e5bd12['acl'] = array(); foreach ($pb8a2e3ca->body->AccessControlList->Grant as $pb1206d82) { foreach ($pb1206d82->Grantee as $v88d026d8ef) { if (isset($v88d026d8ef->ID, $v88d026d8ef->DisplayName)) $v2069e5bd12['acl'][] = array( 'type' => 'CanonicalUser', 'id' => (string)$v88d026d8ef->ID, 'name' => (string)$v88d026d8ef->DisplayName, 'permission' => (string)$pb1206d82->Permission ); elseif (isset($v88d026d8ef->EmailAddress)) $v2069e5bd12['acl'][] = array( 'type' => 'AmazonCustomerByEmail', 'email' => (string)$v88d026d8ef->EmailAddress, 'permission' => (string)$pb1206d82->Permission ); elseif (isset($v88d026d8ef->URI)) $v2069e5bd12['acl'][] = array( 'type' => 'Group', 'uri' => (string)$v88d026d8ef->URI, 'permission' => (string)$pb1206d82->Permission ); else continue 1; } } } return $v2069e5bd12; } public static function deleteObject($v4907c60569, $pe6469026) { $pb8a2e3ca = new S3Request('DELETE', $v4907c60569, $pe6469026); $pb8a2e3ca = $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 204) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::deleteObject(): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return true; } public static function getAuthenticatedURL($v4907c60569, $pe6469026, $v922a636397, $v97e0908db2 = false, $ped62aa8f = false) { $v3ac2cc0b43 = time() + $v922a636397; $pe6469026 = str_replace('%2F', '/', rawurlencode($pe6469026)); return sprintf(($ped62aa8f ? 'https' : 'http').'://%s/%s?AWSAccessKeyId=%s&Expires=%u&Signature=%s', $v97e0908db2 ? $v4907c60569 : $v4907c60569.'.s3.amazonaws.com', $pe6469026, self::$v15c4c03314, $v3ac2cc0b43, urlencode(self::__getHash("GET\n\n\n{$v3ac2cc0b43}\n/{$v4907c60569}/{$pe6469026}"))); } public static function createDistribution($v4907c60569, $v8f96a41927 = true, $pb7c47f3a = array(), $v020036c951 = '') { self::$useSSL = true; $pb8a2e3ca = new S3Request('POST', '', '2008-06-30/distribution', 'cloudfront.amazonaws.com'); $pb8a2e3ca->data = self::__getCloudFrontDistributionConfigXML($v4907c60569.'.s3.amazonaws.com', $v8f96a41927, $v020036c951, (string)microtime(true), $pb7c47f3a); $pb8a2e3ca->size = strlen($pb8a2e3ca->data); $pb8a2e3ca->setHeader('Content-Type', 'application/xml'); $pb8a2e3ca = self::__getCloudFrontResponse($pb8a2e3ca); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 201) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::createDistribution({$v4907c60569}, ".(int)$v8f96a41927.", '$v020036c951'): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } elseif ($pb8a2e3ca->body instanceof SimpleXMLElement) return self::__parseCloudFrontDistributionConfig($pb8a2e3ca->body); return false; } public static function getDistribution($pb7582c34) { self::$useSSL = true; $pb8a2e3ca = new S3Request('GET', '', '2008-06-30/distribution/'.$pb7582c34, 'cloudfront.amazonaws.com'); $pb8a2e3ca = self::__getCloudFrontResponse($pb8a2e3ca); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::getDistribution($pb7582c34): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } elseif ($pb8a2e3ca->body instanceof SimpleXMLElement) { $v657bedbb38 = self::__parseCloudFrontDistributionConfig($pb8a2e3ca->body); $v657bedbb38['hash'] = $pb8a2e3ca->headers['hash']; return $v657bedbb38; } return false; } public static function updateDistribution($v657bedbb38) { self::$useSSL = true; $pb8a2e3ca = new S3Request('PUT', '', '2008-06-30/distribution/'.$v657bedbb38['id'].'/config', 'cloudfront.amazonaws.com'); $pb8a2e3ca->data = self::__getCloudFrontDistributionConfigXML($v657bedbb38['origin'], $v657bedbb38['enabled'], $v657bedbb38['comment'], $v657bedbb38['callerReference'], $v657bedbb38['cnames']); $pb8a2e3ca->size = strlen($pb8a2e3ca->data); $pb8a2e3ca->setHeader('If-Match', $v657bedbb38['hash']); $pb8a2e3ca = self::__getCloudFrontResponse($pb8a2e3ca); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::updateDistribution({$v657bedbb38['id']}, ".(int)$v8f96a41927.", '$v020036c951'): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } else { $v657bedbb38 = self::__parseCloudFrontDistributionConfig($pb8a2e3ca->body); $v657bedbb38['hash'] = $pb8a2e3ca->headers['hash']; return $v657bedbb38; } return false; } public static function deleteDistribution($v657bedbb38) { self::$useSSL = true; $pb8a2e3ca = new S3Request('DELETE', '', '2008-06-30/distribution/'.$v657bedbb38['id'], 'cloudfront.amazonaws.com'); $pb8a2e3ca->setHeader('If-Match', $v657bedbb38['hash']); $pb8a2e3ca = self::__getCloudFrontResponse($pb8a2e3ca); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 204) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::deleteDistribution({$v657bedbb38['id']}): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } return true; } public static function listDistributions() { self::$useSSL = true; $pb8a2e3ca = new S3Request('GET', '', '2008-06-30/distribution', 'cloudfront.amazonaws.com'); $pb8a2e3ca = self::__getCloudFrontResponse($pb8a2e3ca); if ($pb8a2e3ca->error === false && $pb8a2e3ca->code !== 200) $pb8a2e3ca->error = array('code' => $pb8a2e3ca->code, 'message' => 'Unexpected HTTP status'); if ($pb8a2e3ca->error !== false) { trigger_error(sprintf("S3::listDistributions(): [%s] %s", $pb8a2e3ca->error['code'], $pb8a2e3ca->error['message']), E_USER_WARNING); return false; } elseif ($pb8a2e3ca->body instanceof SimpleXMLElement && isset($pb8a2e3ca->body->DistributionSummary)) { $pc2cdfd1b = array(); if (isset($pb8a2e3ca->body->Marker, $pb8a2e3ca->body->MaxItems, $pb8a2e3ca->body->IsTruncated)) { } foreach ($pb8a2e3ca->body->DistributionSummary as $v8ff58bff47) { $pc2cdfd1b[(string)$v8ff58bff47->Id] = self::__parseCloudFrontDistributionConfig($v8ff58bff47); } return $pc2cdfd1b; } return array(); } private static function __getCloudFrontDistributionConfigXML($v4907c60569, $v8f96a41927, $v020036c951, $pb73d3583 = '0', $pb7c47f3a = array()) { $v30163feac8 = new DOMDocument('1.0', 'UTF-8'); $v30163feac8->formatOutput = true; $pe1419bab = $v30163feac8->createElement('DistributionConfig'); $pe1419bab->setAttribute('xmlns', 'http://cloudfront.amazonaws.com/doc/2008-06-30/'); $pe1419bab->appendChild($v30163feac8->createElement('Origin', $v4907c60569)); $pe1419bab->appendChild($v30163feac8->createElement('CallerReference', $pb73d3583)); foreach ($pb7c47f3a as $v6567c9fe99) $pe1419bab->appendChild($v30163feac8->createElement('CNAME', $v6567c9fe99)); if ($v020036c951 !== '') $pe1419bab->appendChild($v30163feac8->createElement('Comment', $v020036c951)); $pe1419bab->appendChild($v30163feac8->createElement('Enabled', $v8f96a41927 ? 'true' : 'false')); $v30163feac8->appendChild($pe1419bab); return $v30163feac8->saveXML(); } private static function __parseCloudFrontDistributionConfig(&$v6694236c2c) { $v657bedbb38 = array(); if (isset($v6694236c2c->Id, $v6694236c2c->Status, $v6694236c2c->LastModifiedTime, $v6694236c2c->DomainName)) { $v657bedbb38['id'] = (string)$v6694236c2c->Id; $v657bedbb38['status'] = (string)$v6694236c2c->Status; $v657bedbb38['time'] = strtotime((string)$v6694236c2c->LastModifiedTime); $v657bedbb38['domain'] = (string)$v6694236c2c->DomainName; } if (isset($v6694236c2c->CallerReference)) $v657bedbb38['callerReference'] = (string)$v6694236c2c->CallerReference; if (isset($v6694236c2c->Comment)) $v657bedbb38['comment'] = (string)$v6694236c2c->Comment; if (isset($v6694236c2c->Enabled, $v6694236c2c->Origin)) { $v657bedbb38['origin'] = (string)$v6694236c2c->Origin; $v657bedbb38['enabled'] = (string)$v6694236c2c->Enabled == 'true' ? true : false; } elseif (isset($v6694236c2c->DistributionConfig)) { $v657bedbb38 = array_merge($v657bedbb38, self::__parseCloudFrontDistributionConfig($v6694236c2c->DistributionConfig)); } if (isset($v6694236c2c->CNAME)) { $v657bedbb38['cnames'] = array(); foreach ($v6694236c2c->CNAME as $v6567c9fe99) $v657bedbb38['cnames'][(string)$v6567c9fe99] = (string)$v6567c9fe99; } return $v657bedbb38; } private static function __getCloudFrontResponse(&$pb8a2e3ca) { $pb8a2e3ca->getResponse(); if ($pb8a2e3ca->response->error === false && isset($pb8a2e3ca->response->body) && is_string($pb8a2e3ca->response->body) && substr($pb8a2e3ca->response->body, 0, 5) == '<?xml') { $pb8a2e3ca->response->body = simplexml_load_string($pb8a2e3ca->response->body); if (isset($pb8a2e3ca->response->body->Error, $pb8a2e3ca->response->body->Error->Code, $pb8a2e3ca->response->body->Error->Message)) { $pb8a2e3ca->response->error = array( 'code' => (string)$pb8a2e3ca->response->body->Error->Code, 'message' => (string)$pb8a2e3ca->response->body->Error->Message ); unset($pb8a2e3ca->response->body); } } return $pb8a2e3ca->response; } public static function __getMimeType(&$v7dffdb5a5b) { $v3fb9f41470 = false; if (extension_loaded('fileinfo') && isset($_ENV['MAGIC']) && ($pfd5da3fb = finfo_open(FILEINFO_MIME, $_ENV['MAGIC'])) !== false) { if (($v3fb9f41470 = finfo_file($pfd5da3fb, $v7dffdb5a5b)) !== false) { $v3fb9f41470 = explode(' ', str_replace('; charset=', ';charset=', $v3fb9f41470)); $v3fb9f41470 = array_pop($v3fb9f41470); $v3fb9f41470 = explode(';', $v3fb9f41470); $v3fb9f41470 = trim(array_shift($v3fb9f41470)); } finfo_close($pfd5da3fb); } elseif (function_exists('mime_content_type')) $v3fb9f41470 = trim(mime_content_type($v7dffdb5a5b)); if ($v3fb9f41470 !== false && strlen($v3fb9f41470) > 0) return $v3fb9f41470; static $v3d648f7ed6 = array( 'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png', 'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ico' => 'image/x-icon', 'swf' => 'application/x-shockwave-flash', 'pdf' => 'application/pdf', 'zip' => 'application/zip', 'gz' => 'application/x-gzip', 'tar' => 'application/x-tar', 'bz' => 'application/x-bzip', 'bz2' => 'application/x-bzip2', 'txt' => 'text/plain', 'asc' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html', 'css' => 'text/css', 'js' => 'text/javascript', 'xml' => 'text/xml', 'xsl' => 'application/xsl+xml', 'ogg' => 'application/ogg', 'mp3' => 'audio/mpeg', 'wav' => 'audio/x-wav', 'avi' => 'video/x-msvideo', 'mpg' => 'video/mpeg', 'mpeg' => 'video/mpeg', 'mov' => 'video/quicktime', 'flv' => 'video/x-flv', 'php' => 'text/x-php' ); $v91158153da = strtolower(pathInfo($v7dffdb5a5b, PATHINFO_EXTENSION)); return isset($v3d648f7ed6[$v91158153da]) ? $v3d648f7ed6[$v91158153da] : 'application/octet-stream'; } public static function __getSignature($v70a24a74ac) { return 'AWS '.self::$v15c4c03314.':'.self::__getHash($v70a24a74ac); } private static function __getHash($v70a24a74ac) { return base64_encode(extension_loaded('hash') ? hash_hmac('sha1', $v70a24a74ac, self::$v968aa21540, true) : pack('H*', sha1( (str_pad(self::$v968aa21540, 64, chr(0x00)) ^ (str_repeat(chr(0x5c), 64))) . pack('H*', sha1((str_pad(self::$v968aa21540, 64, chr(0x00)) ^ (str_repeat(chr(0x36), 64))) . $v70a24a74ac))))); } } final class S3Request { private $pe181f2e8, $bucket, $uri, $resource = '', $parameters = array(), $amzHeaders = array(), $headers = array( 'Host' => '', 'Date' => '', 'Content-MD5' => '', 'Content-Type' => '' ); public $fp = false, $size = 0, $data = false, $response; function __construct($pe181f2e8, $v4907c60569 = '', $pe6469026 = '', $pb288c679 = 's3.amazonaws.com') { $this->pe181f2e8 = $pe181f2e8; $this->bucket = strtolower($v4907c60569); $this->uri = $pe6469026 !== '' ? '/'.str_replace('%2F', '/', rawurlencode($pe6469026)) : '/'; if ($this->bucket !== '') { $this->headers['Host'] = $this->bucket.'.'.$pb288c679; $this->resource = '/'.$this->bucket.$this->uri; } else { $this->headers['Host'] = $pb288c679; $this->resource = $this->uri; } $this->headers['Date'] = gmdate('D, d M Y H:i:s T'); $this->response = new STDClass; $this->response->error = false; } public function setParameter($pbfa01ed1, $v67db1bd535) { $this->parameters[$pbfa01ed1] = $v67db1bd535; } public function setHeader($pbfa01ed1, $v67db1bd535) { $this->headers[$pbfa01ed1] = $v67db1bd535; } public function setAmzHeader($pbfa01ed1, $v67db1bd535) { $this->amzHeaders[$pbfa01ed1] = $v67db1bd535; } public function getResponse() { $v9d1744e29c = ''; if (sizeof($this->parameters) > 0) { $v9d1744e29c = substr($this->uri, -1) !== '?' ? '?' : '&'; foreach ($this->parameters as $v847e7d0a83 => $v67db1bd535) if ($v67db1bd535 == null || $v67db1bd535 == '') $v9d1744e29c .= $v847e7d0a83.'&'; else $v9d1744e29c .= $v847e7d0a83.'='.rawurlencode($v67db1bd535).'&'; $v9d1744e29c = substr($v9d1744e29c, 0, -1); $this->uri .= $v9d1744e29c; if (array_key_exists('acl', $this->parameters) || array_key_exists('location', $this->parameters) || array_key_exists('torrent', $this->parameters) || array_key_exists('logging', $this->parameters)) $this->resource .= $v9d1744e29c; } $v6f3a2700dd = ((S3::$useSSL && extension_loaded('openssl')) ? 'https://':'http://').$this->headers['Host'].$this->uri; $v49e3345984 = curl_init(); curl_setopt($v49e3345984, CURLOPT_USERAGENT, 'S3/php'); if (S3::$useSSL) { curl_setopt($v49e3345984, CURLOPT_SSL_VERIFYHOST, 1); curl_setopt($v49e3345984, CURLOPT_SSL_VERIFYPEER, 1); } curl_setopt($v49e3345984, CURLOPT_URL, $v6f3a2700dd); $v15493e4c60 = array(); $v025b01a21d = array(); foreach ($this->amzHeaders as $v6c438ea8cd => $v67db1bd535) if (strlen($v67db1bd535) > 0) $v15493e4c60[] = $v6c438ea8cd.': '.$v67db1bd535; foreach ($this->headers as $v6c438ea8cd => $v67db1bd535) if (strlen($v67db1bd535) > 0) $v15493e4c60[] = $v6c438ea8cd.': '.$v67db1bd535; foreach ($this->amzHeaders as $v6c438ea8cd => $v67db1bd535) if (strlen($v67db1bd535) > 0) $v025b01a21d[] = strtolower($v6c438ea8cd).':'.$v67db1bd535; if (sizeof($v025b01a21d) > 0) { sort($v025b01a21d); $v025b01a21d = "\n".implode("\n", $v025b01a21d); } else $v025b01a21d = ''; $v15493e4c60[] = 'Authorization: ' . S3::__getSignature( $this->headers['Host'] == 'cloudfront.amazonaws.com' ? $this->headers['Date'] : $this->pe181f2e8."\n".$this->headers['Content-MD5']."\n". $this->headers['Content-Type']."\n".$this->headers['Date'].$v025b01a21d."\n".$this->resource ); curl_setopt($v49e3345984, CURLOPT_HTTPHEADER, $v15493e4c60); curl_setopt($v49e3345984, CURLOPT_HEADER, false); curl_setopt($v49e3345984, CURLOPT_RETURNTRANSFER, false); curl_setopt($v49e3345984, CURLOPT_WRITEFUNCTION, array(&$this, '__responseWriteCallback')); curl_setopt($v49e3345984, CURLOPT_HEADERFUNCTION, array(&$this, '__responseHeaderCallback')); curl_setopt($v49e3345984, CURLOPT_FOLLOWLOCATION, true); switch ($this->pe181f2e8) { case 'GET': break; case 'PUT': case 'POST': if ($this->fp !== false) { curl_setopt($v49e3345984, CURLOPT_PUT, true); curl_setopt($v49e3345984, CURLOPT_INFILE, $this->fp); if ($this->size > 0) curl_setopt($v49e3345984, CURLOPT_INFILESIZE, $this->size); } elseif ($this->data !== false) { curl_setopt($v49e3345984, CURLOPT_CUSTOMREQUEST, $this->pe181f2e8); curl_setopt($v49e3345984, CURLOPT_POSTFIELDS, $this->data); if ($this->size > 0) curl_setopt($v49e3345984, CURLOPT_BUFFERSIZE, $this->size); } else curl_setopt($v49e3345984, CURLOPT_CUSTOMREQUEST, $this->pe181f2e8); break; case 'HEAD': curl_setopt($v49e3345984, CURLOPT_CUSTOMREQUEST, 'HEAD'); curl_setopt($v49e3345984, CURLOPT_NOBODY, true); break; case 'DELETE': curl_setopt($v49e3345984, CURLOPT_CUSTOMREQUEST, 'DELETE'); break; default: break; } if (curl_exec($v49e3345984)) $this->response->code = curl_getinfo($v49e3345984, CURLINFO_HTTP_CODE); else $this->response->error = array( 'code' => curl_errno($v49e3345984), 'message' => curl_error($v49e3345984), 'resource' => $this->resource ); @curl_close($v49e3345984); if ($this->response->error === false && isset($this->response->headers['type']) && $this->response->headers['type'] == 'application/xml' && isset($this->response->body)) { $this->response->body = simplexml_load_string($this->response->body); if (!in_array($this->response->code, array(200, 204)) && isset($this->response->body->Code, $this->response->body->Message)) { $this->response->error = array( 'code' => (string)$this->response->body->Code, 'message' => (string)$this->response->body->Message ); if (isset($this->response->body->Resource)) $this->response->error['resource'] = (string)$this->response->body->Resource; unset($this->response->body); } } if ($this->fp !== false && is_resource($this->fp)) fclose($this->fp); return $this->response; } private function __responseWriteCallback(&$v49e3345984, &$v539082ff30) { if ($this->response->code == 200 && $this->fp !== false) return fwrite($this->fp, $v539082ff30); else $this->response->body .= $v539082ff30; return strlen($v539082ff30); } private function __responseHeaderCallback(&$v49e3345984, &$v539082ff30) { if (($v665aaf2175 = strlen($v539082ff30)) <= 2) return $v665aaf2175; if (substr($v539082ff30, 0, 4) == 'HTTP') $this->response->code = (int)substr($v539082ff30, 9, 3); else { list($v6c438ea8cd, $v67db1bd535) = explode(': ', trim($v539082ff30), 2); if ($v6c438ea8cd == 'Last-Modified') $this->response->headers['time'] = strtotime($v67db1bd535); elseif ($v6c438ea8cd == 'Content-Length') $this->response->headers['size'] = (int)$v67db1bd535; elseif ($v6c438ea8cd == 'Content-Type') $this->response->headers['type'] = $v67db1bd535; elseif ($v6c438ea8cd == 'ETag') $this->response->headers['hash'] = $v67db1bd535{0} == '"' ? substr($v67db1bd535, 1, -1) : $v67db1bd535; elseif (preg_match('/^x-amz-meta-.*$/', $v6c438ea8cd)) $this->response->headers[$v6c438ea8cd] = is_numeric($v67db1bd535) ? (int)$v67db1bd535 : $v67db1bd535; } return $v665aaf2175; } } 