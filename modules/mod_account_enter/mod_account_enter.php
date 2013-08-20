<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
/*if(! class_exists('VirtueMartModelProduct'))
{
  require_once(JPATH_ADMINISTRATOR. '/components/com_virtuemart/models/product.php');
}*/
jimport( 'joomla.application.module.helper' );
$module = &JModuleHelper::getModule( 'login','Вход на сайт');

$params = new JParameter($module->params);

require_once(JPATH_SITE.'/modules/mod_login/helper.php');
$type	= modLoginHelper::getType();
$return	= modLoginHelper::getReturnURL($params, $type);

$user = JFactory::getUser();
if($user->guest == 1)
	require_once(JPATH_SITE.'/modules/mod_account_enter/tmpl/enter.php');
else
	require_once(JPATH_SITE.'/modules/mod_account_enter/tmpl/out.php');
	

?>

