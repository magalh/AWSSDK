<?php
namespace AWSSDK;

require dirname(__DIR__, 1).'/SDK/aws-autoloader.php';

use \Aws\S3\S3Client;  
use \Aws\Exception\AwsException;
use \Aws\Credentials\Credentials;

final class utils
{

    private $s3Client = null;

    public function __construct (){
        $this->mod = self::get_mod();
        $this->s3Client = null;
        $this->access_secret_key = $this->mod->GetPreference('access_secret_key');
        $this->access_key = $this->mod->GetPreference('access_key');
        $this->errors = array();
    }

    public static function getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    public function validate() {

        $mod = $this->mod;
        $settings = $mod->GetSettingsValues();
        if(empty($settings)) return false;

        try {
            $data = array('access_key','access_secret_key');

            foreach ($data as $key)
            {
                $item = $mod->GetPreference($key);
                if($item=="" or $item==null)
                {
                    $this->errors[] = $mod->lang($key). " ".$mod->lang("required") ;
                }
            }
            
            if(!empty($this->errors)){
                throw new \AWSSDK\Exception($this->errors,"slide-danger",null,true);
            }

            $s3 = self::getS3Client();
            if (is_array($s3) && !$s3['conn']){
                $this->errors[] = $s3['message'];
                throw new \AWSSDK\Exception($this->errors,"slide-danger",null,true);
            }
            $this->s3Client = $s3;

        }
        catch (\AWSSDK\Exception $e) {
            $mod->_DisplayMessage($e->getText(),$e->getType());
            return false;
        }

        $mod->_DisplayMessage($this->lang('msg_vrfy_integrityverified'),"success");
        //$smarty->assign("message",$message);
        return true;
	}
    

    public static function getS3Client(){

        $result = array();
        $result['conn'] = true;

        try {

            $s3Client = new S3Client([
                'credentials' => self::get_credentials(),
                'region' => self::get_mod()->GetPreference('access_region'),
                'version' => 'latest'
                ]);

            $buckets = $s3Client->listBuckets();
        } catch (AwsException $e) {
            $result['conn'] = false;
            $result['message'] = $e->getAwsErrorMessage();
            print_r($result);
            return $result;
        }

        return $s3Client;
    }
    
    public static function get_mod(){
        static $_mod;
        if( !$_mod ) $_mod = \cms_utils::get_module('AWSSDK');
        return $_mod;
    }

    private static function get_credentials(){
        $mod = self::get_mod();
        $credentials = new Credentials($mod->GetPreference('access_key'),$mod->GetPreference('access_secret_key'));
        return $credentials;
    }

    public static function sort_by_key($array, $key) {
        usort($array, function($a, $b) use ($key) {
            return $a[$key] - $b[$key];
        });
        return $array;
    }

    public static function sortByProperty($array, $property) {
        usort($array, function($a, $b) use ($property) {
            return strcmp($a->$property, $b->$property);
        });
        return $array;
    }

}
?>