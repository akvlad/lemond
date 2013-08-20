<?php
// No direct access
defined( '_JEXEC' ) or die();

$version = new JVersion;

$joomla = $version->getShortVersion();

if(substr($joomla, 0, 3) >= '3.0') {
    include('toggler30.php');
} else {
    include('toggler25.php');
}