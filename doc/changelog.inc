<ul>
  <li>VERSION: 1.2.0 DATED: 14-MAY-2026
    <ul>
      <li>Added "Test Connection" button — lists S3 buckets in us-east-1 to verify credentials</li>
      <li>Added automatic Windows UNC-to-Linux path normalization for credentials file</li>
      <li>Fixed credentials file fallback to use ~/.aws/credentials instead of CMSMS root path</li>
      <li>Added 3 authentication methods: Access Keys, AWS Profile, and IAM Role (EC2/ECS)</li>
      <li>AWS Profile name is now a saved preference (no longer relies on AWS_PROFILE env var)</li>
      <li>Fixed GetAdminSection typo ("extentions" → "extensions")</li>
      <li>Added GetName(), MinimumCMSVersion(), GetDependencies() metadata methods</li>
      <li>Removed debug print_r() statements, replaced with error_log()</li>
      <li>Removed @ error suppression, using proper precondition checks</li>
      <li>Added audit() logging to all catch blocks</li>
      <li>On uninstall: now removes the extracted aws.phar file</li>
      <li>On upgrade: always re-extracts aws.phar from zip to ensure latest version</li>
      <li>Added doc/LICENSE, doc/help.inc, assets/ with module icons and banners</li>
    </ul>
  </li>
  <li>VERSION: 1.1.2 DATED: 20-DECEMBER-2023
    <p>Added IAM Role / AWS Profile authentication support</p>
  </li>
  <li>VERSION: 1.1.0 DATED: 16-NOVEMBER-2023
    <p>Removed admin dashboard</p>
  </li>
  <li>VERSION: 1.0.0 DATED: 10-OCTOBER-2023
    <p>First version of the plugin.</p>
  </li>
</ul>
