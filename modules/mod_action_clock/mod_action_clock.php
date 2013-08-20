<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/*if(! class_exists('VirtueMartModelProduct'))
{
  require_once(JPATH_ADMINISTRATOR. '/components/com_virtuemart/models/product.php');
}*/
$final=$params->get('final');
$doc=& JFactory::getDocument();
$doc->addScript('/modules/mod_action_clock/js/libs/prefixfree.min.js');
$doc->addScript('/modules/mod_action_clock/js/flipclock.min.js');
$doc->addStyleSheet('/modules/mod_action_clock/css/flipclock.css');
list($d,$m,$Y)=explode('-', $final);
$time=mktime(0,0,0,$m,$d,$Y);
$now=time();
$interval=$time-$now;
require_once(JPATH_SITE.'/modules/mod_action_clock/tmpl/default.php');
?>

