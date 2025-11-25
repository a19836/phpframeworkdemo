<?php
/*
 * Copyright (c) 2025 Bloxtor (http://bloxtor.com) and Joao Pinto (http://jplpinto.com)
 * 
 * Multi-licensed: BSD 3-Clause | Apache 2.0 | GNU LGPL v3 | HLNC License (http://bloxtor.com/LICENSE_HLNC.md)
 * Choose one license that best fits your needs.
 *
 * Original Bloxtor Repo: https://github.com/a19836/bloxtor
 *
 * YOU ARE NOT AUTHORIZED TO MODIFY OR REMOVE ANY PART OF THIS NOTICE!
 */
 if (!defined("MAX_OVERFLOW_VALUE")) define("MAX_OVERFLOW_VALUE", "2147483647"); if (!defined("MIN_OVERFLOW_VALUE")) define("MIN_OVERFLOW_VALUE", "-2147483648"); class HashCode { public static function getHashCode($v327f72fb62){ $v258de04f2e = 0; for ($v43dd7d0051 = 0; $v43dd7d0051 < strlen($v327f72fb62); $v43dd7d0051++){ $v258de04f2e = self::getBigInt(bcadd(bcmul(31, $v258de04f2e), ord($v327f72fb62[$v43dd7d0051]))); } return $v258de04f2e; } public static function getHashCodePositive($v327f72fb62){ $v258de04f2e = self::getHashCode($v327f72fb62); if($v258de04f2e < 0) { $v2d1fae9fb8 = bcsub(0, $v258de04f2e); $v258de04f2e = bcadd(MAX_OVERFLOW_VALUE, bcsub($v2d1fae9fb8, 1)); } return $v258de04f2e; } public static function getBigInt($v58ac916504) { $v9ad1385268 = $v58ac916504; $v339f9b50e0 = MAX_OVERFLOW_VALUE; $v9c75c2f068 = MIN_OVERFLOW_VALUE; $v0911c6122e = bcsub($v339f9b50e0, $v9c75c2f068); $pa02b1e9a = bccomp($v58ac916504, $v9c75c2f068); if($pa02b1e9a == -1) { $v9ad1385268 = bcsub(0, $v9ad1385268); } if(bccomp($v9ad1385268, $v339f9b50e0) == 1) { $v1b08a89324 = bcdiv($v9ad1385268, $v0911c6122e); $v7a1b9c07b3 = bcmod($v9ad1385268, $v0911c6122e); if($v1b08a89324 == 0) { $v7a1b9c07b3 = bcsub($v9ad1385268, $v339f9b50e0); } $v9ad1385268 = bcadd($v9c75c2f068, bcsub($v7a1b9c07b3,1)); if($pa02b1e9a == -1) { $v9ad1385268 = bcsub(0, $v9ad1385268); } } return $v9ad1385268; } } ?>
