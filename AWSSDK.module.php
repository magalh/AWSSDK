<?php
#---------------------------------------------------------------------------------------------------
# Module: AWSSDK
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2023 Magal Hezi, h_magal@hotmail.com
# Licence: GNU General Public License version 3. See http://www.gnu.org/licenses/  
#---------------------------------------------------------------------------------------------------
# CMS Made Simple(TM) is (c) CMS Made Simple Foundation 2004-2020 (info@cmsmadesimple.org)
# Project's homepage is: http://www.cmsmadesimple.org
# Module's homepage is: http://dev.cmsmadesimple.org/projects/AWSSDK
#---------------------------------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify it under the terms of the GNU
# General Public License as published by the Free Software Foundation; either version 3 of the 
# License, or (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple.  You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
# without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
# See the GNU General Public License for more details.
#
# Amazon Web Services, AWS, and the Powered by AWS logo are trademarks of Amazon.com, Inc. or its affiliates
#---------------------------------------------------------------------------------------------------

class AWSSDK extends CMSModule
{
	const MANAGE_PERM = 'manage_AWSSDK';
	
	public function GetName() { return 'AWSSDK'; }
	public function GetVersion() { return '1.2.0'; }
	public function MinimumCMSVersion() { return '2.2.22'; }
	public function GetDependencies() { return []; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
	public function IsPluginModule() { return false; }
	public function HasAdmin() { return true; }
    public function GetAdminSection() { return 'extensions'; }
	public function VisibleToAdminUser() { return $this->CheckPermission(self::MANAGE_PERM); }
    public function GetHeaderHTML() { return $this->_output_header_javascript(); }
	public function GetAuthor() { return 'Pixel Solutions'; }
	public function GetAuthorEmail() { return 'info@pixelsolutions.biz'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function InitializeAdmin() {$this->SetParameters();}
	public function GetHelp() {
		$fn = __DIR__ . '/doc/help.inc';
		return (is_file($fn)) ? file_get_contents($fn) : '';
	}
	public function GetChangeLog() {
		$fn = __DIR__ . '/doc/changelog.inc';
		return (is_file($fn)) ? file_get_contents($fn) : '';
	}


    public function __construct(){
        parent::__construct();

        $smarty = \CmsApp::get_instance()->GetSmarty();
        if( !$smarty ) return;
        $smarty->assign('sdk_mod',$this);
        if ($this->is_developer_mode()){
            $smarty->assign('isdev',true);
        }

        \spl_autoload_register([$this, 'autoload']);
    }

    public function autoload($classname) : bool
    {
        $path = $this->GetModulePath() . '/lib';
        $archivePath = $path.'/aws.phar';

        if (file_exists($archivePath)) {
            require_once $archivePath;
        }

        return TRUE;
    }

	public function is_developer_mode() {
		$config = \cms_config::get_instance();
		if( isset($config['developer_mode']) && $config['developer_mode'] ) {
			return true;
		} else {
			return false;
		}
	}

    public function getUtils()
	{
	  return \AWSSDK\utils::getInstance();
	}

    public function _DisplayMessage($message,$type="alert-danger",$fetch=null)
	{

	  $smarty = cmsms()->GetSmarty();
	  $tpl = $smarty->CreateTemplate($this->GetTemplateResource('message.tpl'),null,null,$smarty);

      if (is_array($message)) {
        $message = implode("<br>",$message);
      }

      switch ($type) {
            case ($type == "alert-info" || $type == "info"):
                $class = "alert alert-info";
                break;
            case ($type == "alert-success" || $type == "success" || $type == 200):
                $class = "alert alert-success";
                break;
            case ($type == "alert-warning" || $type == "warning"):
                $class = "alert alert-warning";
                break;
            case ($type == "alert-danger" || $type == "error" || $type == 500):
                $class = "alert alert-danger";
                audit('', 'AWSSDK Error', substr( $message,0 ,200 ) );
                break;
            case "slide-danger":
                $class = "message pageerrorcontainer";
                audit('', 'AWSSDK Error', substr( $message,0 ,200 ) );
                break;
            case "slide-success":
                $class = "message pagemcontainer";
                break;
        }

        $tpl->assign('errorclass', $class);
        $tpl->assign('message', $message);
	  
        if(isset($fetch)){
            $out = $tpl->fetch();
            return $out;
        } else {
            $tpl->display();
        }
      
	}

    public function get_regions_options(){
        $awsregionnames = file_get_contents(dirname(__FILE__).'/doc/aws-region-names.json');
        return json_decode($awsregionnames,true);
    }

    public function getCredentials(){
        $utils = new \AWSSDK\utils;
        return $utils::get_credentials();
    }

    /**
     * Detect AWS Profile credentials for display in admin panel.
     * @return array|null
     */
    public function _detectProfileCredentials()
    {
        try {
            require_once cms_join_path($this->GetModulePath(), 'lib', 'aws.phar');

            $profile = $this->GetPreference('aws_profile', 'default');

            $credsFile = $this->GetPreference('credentials_file');

            // Normalize Windows UNC WSL paths to native Linux paths
            if ($credsFile && preg_match('#^\\\\\\\\wsl[.$]localhost\\\\[^\\\\]+(.+)$#i', $credsFile, $m)) {
                $credsFile = str_replace('\\', '/', $m[1]);
            }

            if ($credsFile) {
                if (!file_exists($credsFile)) {
                    return ['error' => 'AWS credentials file not found at configured path: ' . $credsFile];
                }
                $provider = \Aws\Credentials\CredentialProvider::ini($profile, $credsFile);
            } else {
                // Use default ~/.aws/credentials (standard AWS SDK behavior)
                $homeDir = getenv('HOME') ?: getenv('USERPROFILE');
                $defaultFile = $homeDir . DIRECTORY_SEPARATOR . '.aws' . DIRECTORY_SEPARATOR . 'credentials';
                if (!file_exists($defaultFile)) {
                    return ['error' => 'No AWS credentials file found. Set the credentials file path in settings, or create: ' . $defaultFile];
                }
                $provider = \Aws\Credentials\CredentialProvider::ini($profile, $defaultFile);
            }

            $creds = $provider()->wait();
            return [
                'access_key' => substr($creds->getAccessKeyId(), 0, 8) . '...',
                'source' => "Profile: {$profile}"
            ];
        } catch (\Exception $e) {
            audit('', 'AWSSDK', 'Credential detection failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function resolve_alias_or_id($txt, int $dflt = NULL)
    {
        $txt = \trim((string)$txt);
        if(!$txt)
        {
        return $dflt;
        }
        $manager = $this->cms->GetHierarchyManager();
        $node    = NULL;
        if(\is_numeric($txt) && (int)$txt > 0)
        {
        $node = $manager->find_by_tag('id', (int)$txt);
        }
        else
        {
        $node = $manager->find_by_tag('alias', $txt);
        }
        if($node)
        {
        return (int)$node->get_tag('id');
        }
        
        return $dflt;
    }



}

?>