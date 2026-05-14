<?php
#--------------------------------------------------
# See LICENSE for full license information.
#--------------------------------------------------
if (!defined('CMS_VERSION')) exit;

// Always overwrite the aws.phar file on upgrade
$utils = new \AWSSDK\utils;
$utils->expandAndRemovePhar();
