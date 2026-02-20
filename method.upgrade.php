<?php
if (!defined('CMS_VERSION')) exit;

switch ($oldversion)
{
	case "1.0.0":
	case "1.1.0":
	case "1.1.1":
    $utils = new \AWSSDK\utils;
    $utils->expandAndRemovePhar();
}
?>
