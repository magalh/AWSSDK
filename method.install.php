<?php
#--------------------------------------------------
# See LICENSE for full license information.
#--------------------------------------------------
if (!defined('CMS_VERSION')) exit;

$this->CreatePermission(AWSSDK::MANAGE_PERM, 'Manage AWSSDK');

$utils = new \AWSSDK\utils;
$utils->expandAndRemovePhar();
