<?php
if (!defined('CMS_VERSION')) exit;
if (!$this->CheckPermission(AWSSDK::MANAGE_PERM)) return;

if (isset($params['cancel'])) {
    $this->RedirectToAdminTab();
}

if (isset($params['submit'])) {
    $this->SetPreference('auth_method', $params['auth_method']);
    $this->SetPreference('access_key', trim($params['access_key']));
    $this->SetPreference('access_secret_key', trim($params['access_secret_key']));
    $this->SetPreference('aws_profile', trim($params['aws_profile']));

    // Normalize Windows UNC WSL paths to native Linux paths
    $credsFile = trim($params['credentials_file']);
    if ($credsFile && preg_match('#^\\\\\\\\wsl[.$]localhost\\\\[^\\\\]+(.+)$#i', $credsFile, $m)) {
        $credsFile = str_replace('\\', '/', $m[1]);
    }
    $this->SetPreference('credentials_file', $credsFile);

    $this->SetMessage($this->Lang('settings_saved'));
    $this->RedirectToAdminTab();
}

$detected_credentials = null;

if ($this->GetPreference('auth_method') == 'profile') {
    $detected_credentials = $this->_detectProfileCredentials();
}

$smarty = cmsms()->GetSmarty();
$tpl = $smarty->CreateTemplate($this->GetTemplateResource('defaultadmin.tpl'), null, null, $smarty);
$tpl->assign('detected_credentials', $detected_credentials);
$tpl->display();
