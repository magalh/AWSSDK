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
	
	public function GetVersion() { return '1.0.0'; }
	public function GetFriendlyName() { return $this->Lang('friendlyname'); }
	public function GetAdminDescription() { return $this->Lang('admindescription'); }
	public function IsPluginModule() { return false; }
	public function HasAdmin() { return TRUE; }
	public function VisibleToAdminUser() { return $this->CheckPermission(self::MANAGE_PERM); }
    public function GetHeaderHTML() { return $this->_output_header_javascript(); }
	public function GetAuthor() { return 'Magal Hezi'; }
	public function GetAuthorEmail() { return 'h_magal@hotmail.com'; }
	public function UninstallPreMessage() { return $this->Lang('ask_uninstall'); }
	public function GetAdminSection() { return 'extentions'; }
	public function InitializeAdmin() {$this->SetParameters();}
	public function GetHelp() { return @file_get_contents(__DIR__.'/README.md'); }
	public function GetChangeLog() { return @file_get_contents(__DIR__.'/doc/changelog.inc'); }

	public function is_developer_mode() {
		$config = \cms_config::get_instance();
		if( isset($config['developer_mode']) && $config['developer_mode'] ) {
			return true;
		} else {
			return false;
		}
	}

    protected function _output_header_javascript()
    {
        $out = '';
        $urlpath = $this->GetModuleURLPath();
        $fmt = '<link rel="stylesheet" type="text/css" href="%s/%s"/>';
        $cssfiles = array('css/style.css');
        foreach( $cssfiles as $one ) {
            $out .= sprintf($fmt,$urlpath,$one);
        }

        return $out;
    }

    final public function GetSettingsValues()
    {
        $prefix = $this->GetName().'_mapi_pref_';
        $list = cms_siteprefs::list_by_prefix($prefix);
        if( !$list || !count($list) ) return [];
        $out = [];
        foreach( $list as $prefname ) {
            $tmp = cms_siteprefs::get($prefname);
            if( !$tmp ) continue;
            $out[substr($prefname, strlen($prefix))] = $tmp;
        }

        if( count($out) ) return $out;

    }
    
    final public function GetOptionValue($key, $default = '')
    {
        $value = $this->GetPreference($key);
        if(isset($value) && $value !== '') {
            return $value;
        } else {
            return $default;
        }
        
    }
    
    final public function SetOptionValue($key, $value) : void
    {
      $this->SetPreference($key,$value);
    }

    public function getUtils()
	{
	  return \AWSSDK\aws_sdk_utils::getInstance();
	}

    public function getHelpers()
	{
	  return \AWSSDK\helpers::getInstance();
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
        $utils = new \AWSSDK\aws_sdk_utils;
        return $utils::get_credentials();
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

    public function encodefilename($filename) {
        return base64_encode(sha1($this->config['dbpassword'].__FILE__.$filename).'|'.$filename);
    }

    public function decodefilename($encodedfilename) {
        list($sig,$filename) = explode('|',base64_decode($encodedfilename),2);
        if( sha1($this->config['dbpassword'].__FILE__.$filename) == $sig ) return $filename;
    }

}

?>