<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart');
if(! class_exists('VirtueMartModelProduct'))
{
  require_once(JPATH_ADMINISTRATOR. '/components/com_virtuemart/models/product.php');
}
JTable::addIncludePath(JPATH_ADMINISTRATOR. '/components/com_virtuemart/tables');
$product1ID=$params->get('product1');
$product2ID=$params->get('product2');
$prodModel=new VirtueMartModelProduct();
$prodModel2=new VirtueMartModelProduct();
$product1=$prodModel->getProduct($product1ID);
$prodModel->addImages($product1,1);
if($product2ID!=0){
$product2=$prodModel2->getProduct($product2ID);
$prodModel->addImages($product2,1);}
$newPrice=$params->get('new_price');
$tmpl=$params->get('template');
$classSfx=$params->get('class_sfx');
require_once('tmpl/'.$tmpl);
?>

