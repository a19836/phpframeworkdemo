<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
 include __DIR__ . "/OpenSSLCipherHandler.php"; $salt = "some string here. whatever you want!!!"; $text = "some message to be encrypted"; echo "\n**** SIMPLE TEXT TO ENCRYPT ****"; $cipher_text = OpenSSLCipherHandler::encryptText($text, $salt); $decrypted_text = OpenSSLCipherHandler::decryptText($cipher_text, $salt); echo "
salt: $salt
text: $text
cipher_text: $cipher_text
decrypted_text: $decrypted_text
"; echo "\n**** ARRAY TO ENCRYPT ****"; $var = array( "text1" => "some message 1 to be encrypted", "text2" => "some text 2 to be encrypted", ); $cipher_var = OpenSSLCipherHandler::encryptVariable($var, $salt); $decrypted_var = OpenSSLCipherHandler::decryptVariable($cipher_var, $salt); echo "\nsalt: $salt"; echo "\nvar:";print_r($var); echo "\ncipher_var:";print_r($cipher_var); echo "\ndecrypted_var:";print_r($decrypted_var); ?>
