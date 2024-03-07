<?php
if (!isset($gCms)) exit;

switch ($oldversion)
{
	case "1.0.0":
	case "1.1.0":
    $utils = new \AWSSDK\utils;
    $utils->expandAndRemovePhar();

}
?>
