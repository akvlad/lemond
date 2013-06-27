<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
if(! class_exists('VirtueMartModelProduct'))
{
  require_once(JPATH_ADMINISTRATOR. '/components/com_virtuemart/models/product.php');
}
$product1ID=$params->get('product1');
$product2ID=$params->get('product2');
$prodModel=new VirtueMartModelProduct();
$product1=$prodModel->getProduct($product1ID);
$product2=$prodModel->getProduct($product2ID);
$tmpl=$params->get('template');
require_once('tmpl/'.$tmpl.'.php');
?>

