<?php
#--------------------------------------------------
# See LICENSE for full license information.
#--------------------------------------------------
if (!defined('CMS_VERSION')) exit;
if (!$this->CheckPermission(AWSSDK::MANAGE_PERM)) return;

try {
    require_once cms_join_path($this->GetModulePath(), 'lib', 'aws.phar');

    $credentials = $this->getCredentials();

    $s3Config = [
        'version' => 'latest',
        'region'  => 'us-east-1',
    ];

    if ($credentials) {
        $s3Config['credentials'] = $credentials;
    }

    $s3 = new \Aws\S3\S3Client($s3Config);
    $result = $s3->listBuckets();

    $buckets = [];
    foreach ($result['Buckets'] as $bucket) {
        $buckets[] = $bucket['Name'];
    }

    $response = [
        'success' => true,
        'buckets' => $buckets,
        'count'   => count($buckets),
    ];
} catch (\Exception $e) {
    $response = [
        'success' => false,
        'error'   => $e->getMessage(),
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
exit;
