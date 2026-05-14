<h3>What does this module do?</h3>
<p>The AWSSDK module provides the AWS PHP SDK as a shared dependency for other CMS Made Simple modules. It handles credential management and SDK loading so that other modules can use AWS services without bundling their own copy of the SDK.</p>

<h3>How do I use it?</h3>
<p>Once installed, other modules can access AWS services by obtaining credentials from this module:</p>
<pre>
$awssdk = \cms_utils::get_module('AWSSDK');
$credentials = $awssdk->getCredentials();
</pre>
<p>The <code>getCredentials()</code> method returns an <code>Aws\Credentials\Credentials</code> object (for Access Keys or AWS Profile) or <code>null</code> (for IAM Role, letting the SDK auto-detect).</p>

<h3>Authentication Methods</h3>
<p>The module supports three authentication methods:</p>
<ul>
  <li><strong>Access Keys</strong> — Enter your AWS Access Key ID and Secret Access Key directly. Works on any server.</li>
  <li><strong>AWS Profile</strong> — Use a named profile from your <code>~/.aws/credentials</code> file. Specify the profile name (e.g. "default", "production") and optionally a custom credentials file path. Works on any server with the AWS CLI configured.</li>
  <li><strong>IAM Role (EC2/ECS)</strong> — No configuration needed. The SDK automatically obtains credentials from the EC2 instance metadata service or ECS task role. Only use this when running on AWS infrastructure.</li>
</ul>

<h3>Configuration</h3>
<p>Navigate to <em>Extensions &raquo; AWSSDK Module</em> in the admin panel to configure your authentication method. The form will show/hide relevant fields based on your selection.</p>

<h3>Test Connection</h3>
<p>After configuring your credentials, click the <strong>Test Connection</strong> button on the admin page to verify your setup. This will attempt to list S3 buckets in the <code>us-east-1</code> region using your configured credentials. A successful test confirms that:</p>
<ul>
  <li>The credentials file is readable by the web server</li>
  <li>The profile name matches an entry in the credentials file</li>
  <li>The access keys are valid and have S3 list permissions</li>
</ul>

<h3>File Permissions (Linux/WSL)</h3>
<p>When using the AWS Profile method, the web server (e.g. Apache running as <code>www-data</code>) must be able to read your credentials file. If you get a "Cannot read credentials" error, fix permissions with:</p>
<pre>sudo chmod 644 ~/.aws/credentials</pre>
<p><strong>Note:</strong> This makes the file world-readable. For tighter security on shared servers, use group-based access instead:</p>
<pre>sudo usermod -aG your_username www-data
sudo chmod 640 ~/.aws/credentials
sudo systemctl restart apache2</pre>

<h3>For Module Developers</h3>
<p>To use the AWS SDK in your own module:</p>
<pre>
// Get the AWSSDK module instance
$awssdk = \cms_utils::get_module('AWSSDK');
if (!$awssdk) throw new \Exception('AWSSDK module not installed');

// Get credentials (returns Credentials object or null for IAM Role)
$credentials = $awssdk->getCredentials();

// Build your AWS client config
$config = [
    'region'  => 'us-east-1',
    'version' => 'latest',
];
if ($credentials) {
    $config['credentials'] = $credentials;
}

// Create any AWS service client
$s3 = new \Aws\S3\S3Client($config);
</pre>

<h3>Requirements</h3>
<ul>
  <li>CMS Made Simple 2.2.22 or newer</li>
  <li>PHP 7.4 or newer with the zip extension enabled</li>
</ul>

<h3>Support</h3>
<p>For support, please contact <a href="mailto:info@pixelsolutions.biz">Pixel Solutions</a>.</p>
