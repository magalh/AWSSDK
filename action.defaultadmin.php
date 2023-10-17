<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission($this::MANAGE_PERM) ) return;

$utils = new \AWSSDK\utils;
$helpers = new \AWSSDK\helpers;
$error = 0;
$ready = 0;


if($utils->validate()){
	$ready = 1;
} else {
	$ready = 0;
}

if( isset($params['submit']) ) {

    $settings_input = $params['settings_input'];
    if(count($settings_input) > 0)
    {
        foreach($settings_input as $key => $value) 
        {
            $this->SetOptionValue($key, $value);
        }
    }

}

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('admin_settings_tab.tpl'), null, null, $smarty );
$awsregionnames = file_get_contents(dirname(__FILE__).'/doc/aws-region-names.json');
$tpl->assign('access_region_list',json_decode($awsregionnames,true));
$tpl->display();

?>