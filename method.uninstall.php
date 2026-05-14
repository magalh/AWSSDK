<?php
#--------------------------------------------------
# See LICENSE for full license information.
#--------------------------------------------------
if (!defined('CMS_VERSION')) exit;

$this->RemovePermission(AWSSDK::MANAGE_PERM);
$this->RemovePreference();

// Remove the unzipped aws.phar file
$pharFile = __DIR__ . '/lib/aws.phar';
if (is_file($pharFile)) {
    unlink($pharFile);
}
