<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission($this::MANAGE_PERM) ) return;

use \AWSSDK\aws_sdk_utils;

$utils = new aws_sdk_utils();
$error = 0;
$ready = 0;
$message = '';

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

if(!$utils->is_valid()){
    //$utils->do_debug = 1;
    $utils->getErrors();
    //print_r($utils->errors_array);
    //$this->ShowErrors($utils->errors_array);
    $message = $this->_DisplayMessage($utils->errors_array,"alert-danger",true);
}

$tpl = $smarty->CreateTemplate( $this->GetTemplateResource('defaultadmin.tpl'), null, null, $smarty );
$tpl->assign('access_region_list',$this->get_regions_options());
$tpl->assign('message',$message);
$tpl->display();

?>