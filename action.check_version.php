<?php
if (!defined('CMS_VERSION')) exit;
if (!$this->CheckPermission(AWSSDK::MANAGE_PERM)) return;

try {
    $version = \Aws\Sdk::VERSION;
    echo "<div class='alert alert-info'>";
    echo "<strong>AWS SDK Version:</strong> {$version}";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>";
    echo "Could not determine SDK version: " . $e->getMessage();
    echo "</div>";
}

echo "<div class='alert alert-warning'>";
echo "<strong>Latest Version:</strong> Check <a href='https://github.com/aws/aws-sdk-php/releases' target='_blank'>AWS SDK PHP Releases</a>";
echo "</div>";
?>
