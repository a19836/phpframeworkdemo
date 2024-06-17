<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
 include_once get_lib("org.phpframework.encryption.PublicPrivateKeyHandler"); class CSRFValidator { public static $REQUEST_RESTRICTED_TO_SAME_REFERER_HOST = true; public static $REQUEST_RESTRICTED_TO_SAME_REMOTE_ADDR = false; public static $COOKIES_EXTRA_FLAGS = array("SameSite" => "Strict", "httponly" => true); public static $CLIENT_IP_VARIABLE_NAME = "dad90ad76sad23"; public static $CLIENT_IP_CYPHER_POSITION = 11; public static $CLIENT_IP_CYPHER_LENGTH = 14; public static $CLIENT_IP_ENCRYPTION_KEY = "-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCyToww/fIvA8Va
EbP8LeDfCgrsahgZ0X489MuflMYoGj5EmWtchH4IjlAZzuXon1DP8UQ74iMNPARu
TfkhButdMUhLtEFAAJxcUyPam73zy+lppr1t6Hin4BQKhWsdyLWCnfsMI+fs2MHh
XjYsnu2Ia/77BZtfoZiSQNQ0XNyB/uvPubFDRK3Z38BT1AGBIf3JCC/w45iS91nj
JavAKAdYf2kvr150ei8LfDY/3zJ+R8GyCGwQI44zO3rtZifZX6R1kXLBuPrnpgDl
5djPpic+rJqo3ILg3b+NQUP/tYVEQjrP/nUHwet2i5CYzxvJDNIOknw0f7rpo8uu
HsoFuT1XAgMBAAECggEAMYcX8dPYHa8Sdn5MXFPyDoIfnqOppiJGym/Ez8Lnd+Qy
P6PN6pjy2TWOklyiCAeYzunZZjjeO6LcKDeIZ+AgKHaz+jNLnJeO1yZQ4zw3eyy8
3RfvrkPQn/DiIDoHEvLZWDrBrRGcLnHXCN6+dY5/tFErNlbMXbfpRVa0mwbgSUsr
DYNkR3r92YaJrFOHQmCOYS8FY/9agp2CTKtlpG+K+Rixq3mY5FXCPYK1A+jujDmK
1RzclNBPvTySXnoyyLBEtAI+P2ROpgNONLmT8hs1nQGXcQ4gItTqSIBiW3LH6Hh8
2I8EuAhlPcshre2+TKVHFbSyqXTzlqP1UrGSDYDHQQKBgQDj0DqwUeMjXWbav2NE
/xknh2lB58WDuNdigBi5qRyr1lUsuJMuAATv80TwFlNWsnuFYO9d8yj1pTDdtjYp
uKdbdoIVANyHg2gcdQAV8bUvPIU8P/Rg30og9rhZdS26YzGcGyPpQ3Ifag+2HQlo
RNDed4BfsjRokOG1onn0DVynfwKBgQDIXj7LsE71lOFBHebevP0R27rZvh+71a5E
+7FkZSkt0scjfL7c82FMGVPe2RRYmgOZqB+ADg9J5MbWNiq+Wrp1mYqGZm3jWv0z
P647Tjscrmgbmiky7KLneEpTDvyF7O2rWSp2ETEQ3qI5a7+t25k4V/WzAeC16tlu
UqYQF0KWKQKBgQC4j2vciJrBfdvkAAWGUjyov5VQpVpo2ojz7d8aGp11wVCDyIzE
SZO2aZlCAHRH2pUje2Kw9FwMlmW+WO4MYuKCwMGmDmqbBqSD2W3WWVl2CUvPgeiT
ypIdnoO/RaVkSRRZ6crwIYoFVUGhQmjqpkWo1ZuU66R1ylpxck3moCSeNQKBgHWm
8VSFODf3rbSgrDnJ2wercDH+839F30heSjFbPSzNAWWTEDeJKW6XyKmn6cyE0uxc
zfJRTyTikuahc8PGXopDGBYG+ytu+BIpqFLmgss6laLviJWAYb9s4KeYuyqgjoX4
m3gsbBUtxS/WVvztXzC4ZWsxBROMzRN8sEnufojRAoGAexxixH47AGj8Ltq4LN1W
bMm8k5OB8uiSU2Hqhkh3cgczlbODZr2ho0eXrEcBwOxWI9O+nq5jvX2hoPaQ+Wfn
a/RWd49ZOIBUPEWVH8VBfFtffGbasn4r4/uR8FvddQ9U1DJTGpyTFMbVPpan7+RZ
DXW+pzUWrmScbhHynvHdKQo=
-----END PRIVATE KEY-----"; public static $CLIENT_IP_ENCRYPTION_PASSPHRASE = "ASD87yum9D9Safggh8SA7998"; public static function validateRequest() { $pb990eabb = !self::$REQUEST_RESTRICTED_TO_SAME_REFERER_HOST || self::isSameReferer(); $v1c0abfeb75 = !self::$REQUEST_RESTRICTED_TO_SAME_REMOTE_ADDR || self::isSameIP(); $v5c1c342594 = $pb990eabb && $v1c0abfeb75; if (self::$REQUEST_RESTRICTED_TO_SAME_REMOTE_ADDR) self::setClientIPCookie(); return $v5c1c342594; } public static function isSameReferer() { $v06ba5136db = $_SERVER["HTTP_REFERER"] ? strtolower(parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST)) : null; $v0e7ba85a30 = explode(":", $_SERVER["HTTP_HOST"]); $v0e7ba85a30 = $v0e7ba85a30[0]; return $v06ba5136db && $v06ba5136db == strtolower($v0e7ba85a30); } public static function isSameIP() { $v74da805c30 = self::getClientIP(); $v3f35744a2f = $_COOKIE[self::$CLIENT_IP_VARIABLE_NAME]; return $v3f35744a2f && $v3f35744a2f == $v74da805c30; } public static function setClientIPCookie() { $v74da805c30 = self::getClientIP(); return CookieHandler::setSafeCookie(self::$CLIENT_IP_VARIABLE_NAME, $v74da805c30, 0, "/", self::$COOKIES_EXTRA_FLAGS); } public static function getClientIP() { $v74da805c30 = $_SERVER["REMOTE_ADDR"]; if (self::$CLIENT_IP_ENCRYPTION_KEY) { $v2564410bfb = new PublicPrivateKeyHandler(true); $pe4669b28 = $v2564410bfb->encryptRSA($v74da805c30, self::$CLIENT_IP_ENCRYPTION_KEY, self::$CLIENT_IP_ENCRYPTION_PASSPHRASE); $v74da805c30 = substr($pe4669b28, self::$CLIENT_IP_CYPHER_POSITION, self::$CLIENT_IP_CYPHER_LENGTH); } return $v74da805c30; } } ?>
