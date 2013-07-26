<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
defined('JPATH_VM_ADMINISTRATOR') or define('JPATH_VM_ADMINISTRATOR', JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_virtuemart');
if(! class_exists('VirtueMartModelProduct'))
{
  require_once(JPATH_ADMINISTRATOR. '/components/com_virtuemart/models/product.php');
}
if(!class_exists('shopFunctionsF'))
{
    require_once JPATH_SITE.'/components/com_virtuemart/helpers/shopfunctionsf.php';
}
JTable::addIncludePath(JPATH_ADMINISTRATOR. '/components/com_virtuemart/tables');
$tmpl=$params->get('template');
$actprodModel=new VirtueMartModelProduct();
if($tmpl=='day_merchant.php'){
$query='SELECT pcpa.product1, pcpa.product2, pcpa.virtuemart_product_id as action_prod FROM #__virtuemart_product_customfields as pc
    LEFT JOIN #__virtuemart_customs as c ON pc.virtuemart_custom_id=c.virtuemart_custom_id 
    LEFT JOIN #__virtuemart_products as p ON pc.virtuemart_product_id=p.virtuemart_product_id
    LEFT JOIN #__virtuemart_product_custom_plg_action as pcpa ON pcpa.virtuemart_custom_id=c.virtuemart_custom_id 
    AND pcpa.virtuemart_product_id=p.virtuemart_product_id
    WHERE c.custom_element="action" AND 
    ((NOW() BETWEEN p.product_available_date AND p.product_expire_date) OR p.product_expire_date="0000-00-00 00:00:00")';
$db=  JFactory::getDbo();
$db->setQuery($query);
$res=$db->loadObject();
$product1ID=$res->product1;
$product2ID=$res->product2;
$actionProdID=$res->action_prod;
$actionProd=$actprodModel->getProduct($actionProdID, $front = TRUE, $withCalc = TRUE, $onlyPublished = TRUE, $quantity = 1,$renderPlugins=false);
$newPrice=$actionProd->prices['salesPrice'];
}
else {
    $product1ID=$params->get('product1');
    $product2ID=$params->get('product2');
    $newPrice=$params->get('new_price');
}
$prodModel=new VirtueMartModelProduct();
$prodModel2=new VirtueMartModelProduct();
$product1=$prodModel->getProduct($product1ID, $front = TRUE, $withCalc = TRUE, $onlyPublished = TRUE, $quantity = 1,$renderPlugins=true);
$prodModel->addImages($product1,1);
if($product2ID!=0){
$product2=$prodModel2->getProduct($product2ID, $front = TRUE, $withCalc = TRUE, $onlyPublished = TRUE, $quantity = 1,$renderPlugins=true);
$prodModel->addImages($product2,1);}
$doc=&JFactory::getDocument();
$classSfx=$params->get('class_sfx');
$moduleclass_sfx=$params->get('moduleclass_sfx');
require_once('tmpl/'.$tmpl);
?>

