<?php
/*
 * Copyright (c) 2024 Bloxtor - http://bloxtor.com
 * 
 * Please note that this code belongs to the Bloxtor framework and must comply with the Bloxtor license.
 * If you do not accept these provisions, or if the Bloxtor License is not present or cannot be found, you are not entitled to use this code and must stop and delete it immediately.
 */
$UserAuthenticationHandler->checkPresentationFileAuthentication($entity_path, "access"); $user_user_types = $UserAuthenticationHandler->getAllUserUserTypes(); $user_types = $UserAuthenticationHandler->getAvailableUserTypes(); $user_types = is_array($user_types) ? array_flip($user_types) : array(); $users = $UserAuthenticationHandler->getAvailableUsers(); $users = is_array($users) ? array_flip($users) : array(); ?>
