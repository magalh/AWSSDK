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

namespace AWSSDK;

//require_once dirname(__DIR__, 1).'/lib/SDK/aws-autoloader.php';
 
use \Aws\Exception\AwsException;
use \Aws\Credentials\Credentials;

final class utils
{

    public function __construct (){
        $this->mod = self::get_mod();
        $this->errors = [];
        $this->connected = false;
        $this->debug_level = 0;
    }

    public function connect()
    {
        
        $this->do_debug = $this->debug_level;

/*        if ($this->connected) {
            return true;
        }*/

        set_error_handler(array($this, 'catchWarning'));

        try {

            if (false === $this->isready()) {
                $this->connected = false;
                return false;
            }

            $credentials = self::get_credentials();

            print_r($credentials);


        } catch (AwsException $e) {

            $this->setError(array(
                'error' => "Failed to connect to AWS server",
                'errno' => $e->getAwsErrorCode(),
                'errstr' => $e->getAwsErrorMessage()
            ));

            $this->aws_conn = false;
            return false;
        }

        restore_error_handler();

    }

    public function is_valid()
    {
        if( !$this->isready() ) return false;
        return TRUE;
    }

    private function isready() {

        $mod = $this->mod;
        $settings = $mod->GetSettingsValues();
        if(empty($settings)) return false;

        $data = array('access_key','access_secret_key');

        foreach ($data as $key)
        {
            $item = $mod->GetPreference($key);
            if($item=="" or $item==null)
            {
                $this->setError(array(
                    'error' => "Failed to validate minimum requirements",
                    'errno' => 500,
                    'errstr' => $mod->lang($key). " ".$mod->lang("required")
                ));
            }
        }
        
        if(!empty($this->errors)){
            return false;
        }

        return true;
	}
    
    public static function getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    public static function get_mod(){
        static $_mod;
        if( !$_mod ) $_mod = \cms_utils::get_module('AWSSDK');
        return $_mod;
    }

    public static function get_credentials(){
        $mod = self::get_mod();
        $credentials = new Credentials($mod->GetPreference('access_key'),$mod->GetPreference('access_secret_key'));
        return $credentials;
    }

    protected function setError($error)
    {
        $this->errors[] = $error;
        if ($this->do_debug >= 1) {
            echo '<pre>';
            foreach ($this->errors as $error) {
                print_r($error);
            }
            echo '</pre>';
        }
    }

    /**
     * Get an array of error messages, if any.
     * @return array
     */
    public function getErrors()
    {
        $this->errors_array = [];
        
        if ($this->do_debug >= 1) {
            echo '<pre>';
            foreach ($this->errors as $error) {
                print_r($error);
            }
            echo '</pre>';
        }

        foreach ($this->errors as $error) {
            $this->errors_array[] = $error['errstr'];
        }

        return $this->errors;
    }

    protected function catchWarning($errno, $errstr, $errfile, $errline)
    {
        $this->setError(array(
            'error' => "Connecting to the AWS server raised a PHP warning: ",
            'errno' => $errno,
            'errstr' => $errstr,
            'errfile' => $errfile,
            'errline' => $errline
        ));
    }

}
?>